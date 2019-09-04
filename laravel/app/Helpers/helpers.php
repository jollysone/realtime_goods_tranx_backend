<?php

namespace App;

use App\Helpers\CacheKey;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Mews\Captcha\Facades\Captcha;

/**
 * 成功响应
 *
 * @param  string|array|object $data
 * @param  int                 $status
 * @param  array               $headers
 * @param  int                 $options
 * @return \Illuminate\Http\JsonResponse
 */
function success($data = null, int $status = 200, array $headers = [], $options = 0): JsonResponse
{
    return response()->json([
        'success' => true,
        'data'    => $data
    ], $status, $headers, $options);
}

/**
 * 失败响应
 * @param array  $errorCode
 * @param string $errors
 * @param int    $status
 * @param array  $headers
 * @param int    $options
 * @return JsonResponse
 */
function failure(array $errorCode, $errors = '', int $status = 200, array $headers = [], int $options = 0): JsonResponse
{
    $result = [
        'success'    => false,
        'error_code' => $errorCode[0],
        'message'    => $errorCode[1]
    ];

    if ($errors) {
        if (is_string($errors)) {
            $result['message'] = $errors;
        } else if (is_array($errors)) {
            $result['extra'] = $errors;
        }
    }

    return response()->json($result, $status, $headers, $options);
}

/**
 * 生成随机字符串
 *
 * @param int $length
 * @return string
 */
function randomStr(int $length = 32): string
{
    if ($length <= 32) {
        return substr(md5(time() . mt_rand() . mt_rand() . mt_rand()), 0, $length);
    }

    $repeat = ceil($length / 32.0);
    $str    = '';
    while ($repeat-- > 0) {
        $str .= randomStr(32);
    }
    return substr($str, 0, $length);
}

/**
 * 验证图片验证码
 * @param string $key
 * @param string $value
 * @return bool
 */
function checkCaptcha(string $key, string $value): bool
{
    $captcha = Cache::pull(CacheKey::captcha($key));
    if (!$captcha) {
        return false;
    }
    return Captcha::check_api($value, $key);
}

/**
 * 获得客户端 IP
 * @return string
 */
function getClientIP(): string
{
    $ip = '';
    try {
        if ($_SERVER["REMOTE_ADDR"])
            $ip = $_SERVER["REMOTE_ADDR"];
        else if (getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
    } catch (\Exception $e) {

    }

    return $ip;
}

/**
 * 获取字符串参数，默认''
 * @param Request $request
 * @param string  $key
 * @param         $default
 * @return mixed
 */
function getInputOrDefault(Request $request, string $key, $default = '')
{
    $val = $request->input($key, $default);
    return $val == $default ? $default : $val;
}

/**
 * 获取非 NULL 的值
 * @param        $value
 * @param string $default
 * @return string
 */
function getDefaultIfNull($value, $default = '')
{
    return $value === null ? $default : $value;
}

/**
 * @param $builder Builder
 * @return mixed
 */
function getWithPage(Builder $builder)
{
    $request   = request();
    $pageIndex = $request->page_index;
    $pageSize  = $request->page_size;

    return $builder->skip($pageSize * ($pageIndex - 1))->take($pageSize)->get();
}

/**
 * 获取分页接口成功返回数据结构
 * @param $request
 * @param $total
 * @param $list
 * @return array
 */
function getPaginationSuccessData($request, $total, $list)
{
    return [
        'page_index' => $request->page_index,
        'page_size'  => $request->page_size,
        'total'      => $total,
        'list'       => $list
    ];
}

/**
 * GET HTTP
 * @param     $url
 * @param int $timeout
 * @return bool|string
 */
function getUrl($url, $timeout = 3)
{
    $opts    = [
        "http" => [
            "method"  => "GET",
            "timeout" => $timeout
        ],
    ];
    $context = stream_context_create($opts);
    $res     = file_get_contents($url, false, $context);
    return $res;
}

/**
 * CURL 请求 URL
 * @param       $url
 * @param array $getData
 * @param array $postData
 * @param int   $timeout
 * @return mixed
 */
function curlUrl($url, $getData = [], $postData = [], $timeout = 5)
{
    $headers = [
        "Content-Type" . ":" . "application/x-www-form-urlencoded; charset=UTF-8"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);                // 超时时间（秒）
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      // 是否返回结果
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    // 不验证HTTPS证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);    // 不验证HTTPS证书
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $getURL = '';
    foreach ($getData as $key => $data) {
        $getURL .= sprintf('%s%s=%s', $getURL == '' ? '' : '&', $key, $data);
    }

    $postStr = '';
    foreach ($postData as $key => $data) {
        $postStr .= sprintf('%s%s=%s', $postStr == '' ? '' : '&', $key, $data);
    }

    curl_setopt($ch, CURLOPT_URL, $url . $getURL);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postStr);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

/**
 * Carbon 转换字符串
 * @param Carbon $carbon
 * @return null|string
 */
function carbonToDateTimeString($carbon)
{
    if ($carbon) {
        return $carbon->toDateTimeString();
    }
    return null;
}

/**
 * 粗略的将数组转换为XML文本
 * @param $arr
 * @return string
 */
function arr2Xml($arr)
{
    $XML = '<xml>';
    foreach ($arr as $key => $value) {
        $XML .= sprintf('<%s><![CDATA[%s]]></%s>', $key, $value, $key);
    }
    $XML .= '</xml>';
    return $XML;
}

function strStartWith($str, $needle)
{
    return strpos($str, $needle) === 0;
}

function subStringUtf8($str, $len, $postfix = true)
{
    if ($postfix && mb_strlen($str, 'utf-8') > $len) {
        $postfix = '...';
    } else {
        $postfix = '';
    }
    return mb_substr($str, 0, $len, 'utf-8') . $postfix;
}

