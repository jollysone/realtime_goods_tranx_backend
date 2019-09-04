<?php

namespace App\Models;

use function App\getClientIP;
use App\Helpers\ErrorCode;
use function App\randomStr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    const TYPE_GOODS   = 'goods';

    use SoftDeletes;

    public    $incrementing = false;
    protected $hidden       = ['ai', 'deleted_at'];

    static public function getAllTypes()
    {
        return [self::TYPE_GOODS];
    }

    public function save(array $options = [])
    {
        if (!$this->exists) {
            $this->id = randomStr(32);
        }

        return parent::save($options);
    }

    public function getUrlAttribute(): string
    {
        if (empty($this->path)) {
            return '';
        }

        return config('api.file.domain') . $this->path;
    }

    public function getThumbUrlAttribute(): string
    {
        if (empty($this->thumb_path)) {
            return '';
        }

        return config('api.file.domain') . $this->thumb_path;
    }

    /**
     * 实例化 Pic 对象
     * @param string $path 相对地址
     * @param bool   $withThumb
     * @param string $remark
     * @return File
     */
    static function constructModel($path, $withThumb = true, $remark = '')
    {
        $basePath = config('api.file.root_path');
        $fullPath = $basePath . $path;

        $model                 = new File();
        $model->ip             = getClientIP();
        $model->remark         = $remark;
        $model->uploaded_by_id = request()->user->id;

        if (file_exists($fullPath)) {
            $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            $size      = filesize($fullPath);

            $model->path      = $path;
            $model->size      = $size;
            $model->extension = $extension;

            if ($withThumb) {
                $res = self::resize($fullPath, '', config('api.file.thumb_trigger_max_length'), config('api.file.thumb_trigger_size'), config('api.file.thumb_quality'));

                if ($res) {
                    $model->thumb_path = str_replace($basePath, '', $res['targetPath']);
                    $model->thumb_size = $res['targetSize'];
                } else {
                    $model->thumb_path = $model->path;
                    $model->thumb_size = $model->size;
                }
            }
        }

        return $model;
    }

    /**
     * @param     $paramName
     * @param     $type
     * @param     $key
     * @param int $index
     * @return array
     */
    static public function saveUploadedFile($paramName, $type, $key, $index = -1)
    {
        $request = request();

        if (is_object($paramName)) {
            $file = $paramName;
        } else {
            if (!$request->hasFile($paramName)) {
                return ['code' => ErrorCode::FORM_VALIDATE_FAILED, 'msg' => $paramName . ' 文件未上传'];
            }

            $file = $request->file($paramName);
            if ($index >= 0) {
                $file = $file[$index];
            }
        }

        if (!$file->isValid()) {
            return ['code' => ErrorCode::FORM_VALIDATE_FAILED, 'msg' => $paramName . ' 文件不可用'];
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $fileSize  = $file->getSize();

        if ($fileSize > config('api.file.max_size')) {
            $kb = config('api.file.max_size') / 1024;
            return ['code' => ErrorCode::FILE_SIZE_INVALID, 'msg' => sprintf("%s 文件大小超过 %skb 限制", $paramName, $kb)];
        }

        switch ($type) {
            case self::TYPE_GOODS:
                {
                    if (!in_array($extension, config('api.file.pic_extensions'))) {
                        return ['code' => ErrorCode::FILE_FORMAT_INVALID, 'msg' => sprintf("%s 图片不支持 %s 格式", $paramName, $extension)];
                    }
                    break;
                }
            default:
                return ['code' => ErrorCode::OPERATION_INVALID, 'msg' => ''];
                break;
        }

        $filePath = '';
        switch ($type) {
            case self::TYPE_GOODS:
                {
                    $filePath .= $type . DIRECTORY_SEPARATOR . $key . DIRECTORY_SEPARATOR;
                    break;
                }
            default:
                return ['code' => ErrorCode::OPERATION_INVALID, 'msg' => ''];
                break;
        }

        $basePath = config('api.file.root_path');
        if (!file_exists($basePath . $filePath)) {
            @mkdir($basePath . $filePath, 0777, true);
        }

        $fileName = $type . '_' . randomStr(16) . '.' . $extension;
        $filePath .= $fileName;

        if (@move_uploaded_file($file->getRealPath(), $basePath . $filePath)) {
            return ['code' => ErrorCode::OK, 'path' => $filePath, 'ext' => $extension, 'size' => $fileSize];
        }

        return ['code' => ErrorCode::UNKNOWN_ERROR, 'msg' => $paramName . ' 文件保存失败'];
    }

    /**
     * 压缩图片
     * @param string $sourcePath 原图绝对地址
     * @param string $targetPath 缩略图绝对地址，默认为原图文件名后添加 _thumb
     * @param int    $triggerMaxLength
     * @param int    $triggerSize
     * @param int    $quality
     * @return array|bool
     */
    static public function resize($sourcePath, $targetPath = '', $triggerMaxLength = 1000, $triggerSize = 500, $quality = 80)
    {
        $timeStart = explode(' ', microtime());

        if (!file_exists($sourcePath) || !is_file($sourcePath)) {
            return false;
        }

        if (empty($targetPath)) {
            $pathParts  = pathinfo($sourcePath);
            $targetPath = sprintf('%s/%s_thumb.%s', $pathParts['dirname'], $pathParts['filename'], $pathParts['extension']);
        }

        $targetDirName = pathinfo($targetPath, PATHINFO_DIRNAME);
        if (!file_exists($targetDirName)) {
            @mkdir($targetDirName, 0777, true);
        }

        $sourceSize = filesize($sourcePath);
        $sourceInfo = @getimagesize($sourcePath);
        if (!$sourceInfo) {
            return false;
        }

        $sourceWidth  = $sourceInfo['0'];
        $sourceHeight = $sourceInfo['1'];
        $sourceMime   = $sourceInfo['mime'];

        $sourceImage = null;
        switch ($sourceMime) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            default:
                return false;
                break;
        }

        $targetWidth  = 0;
        $targetHeight = 0;

        if ($sourceWidth >= $sourceHeight && $sourceWidth > $triggerMaxLength) {
            $targetWidth  = $triggerMaxLength;
            $targetHeight = 1.0 * $triggerMaxLength / $sourceWidth * $sourceHeight;
        } else if ($sourceHeight >= $sourceWidth && $sourceHeight > $triggerMaxLength) {
            $targetHeight = $triggerMaxLength;
            $targetWidth  = 1.0 * $triggerMaxLength / $sourceHeight * $sourceWidth;
        } else if ($sourceSize > $triggerSize * 1024) {
            $targetWidth  = $sourceWidth;
            $targetHeight = $sourceHeight;
        }

        if ($targetWidth > 0 && $targetHeight > 0) {
            $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);

            // 缩放
            imagecopyresampled($targetImage, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);
            imagejpeg($targetImage, $targetPath, $quality);
            imagedestroy($sourceImage);
            imagedestroy($targetImage);

            $timeEnd = explode(' ', microtime());

            return [
                'time'       => $timeEnd[0] + $timeEnd[1] - $timeStart[0] - $timeStart[1],
                'sourceSize' => $sourceSize,
                'targetSize' => filesize($targetPath),
                'sourcePath' => $sourcePath,
                'targetPath' => $targetPath,
            ];
        } else {
            return false;
        }
    }
}
