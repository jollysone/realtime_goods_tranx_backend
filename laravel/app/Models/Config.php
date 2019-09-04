<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    const KEY_SERVICE_TEL = 'service_tel';

    /**
     * 从配置表读取 int 记录
     * @param string $key
     * @param int    $defaultValue
     * @param string $defaultRemark
     * @return int
     */
    static public function getInt(string $key, int $defaultValue = 0, string $defaultRemark = ''): int
    {
        return intval(self::getString($key, $defaultValue, $defaultRemark));
    }

    /**
     * 从配置表读取 float 记录
     * @param string $key
     * @param float  $defaultValue
     * @param string $defaultRemark
     * @return float
     */
    static public function getFloat(string $key, float $defaultValue = 0, string $defaultRemark = ''): float
    {
        return floatval(self::getString($key, $defaultValue, $defaultRemark));
    }

    /**
     * 从配置表读取 string 记录
     * @param string $key
     * @param string $defaultValue
     * @param string $defaultRemark
     * @return mixed|null|string
     */
    static public function getString(string $key, string $defaultValue = '', string $defaultRemark = '')
    {
        if (empty($key)) {
            return null;
        }

        $config = self::where('key', $key)->first();
        if (!$config) {
            $config         = new Config();
            $config->key    = $key;
            $config->value  = $defaultValue;
            $config->remark = $defaultRemark;
            $config->save();
        }
        return $config->value;
    }

    /**
     * 从配置表读取 array 记录
     * @param string $key
     * @param array  $defaultValue
     * @param string $defaultRemark
     * @return mixed
     */
    static public function getArray(string $key, array $defaultValue = [], string $defaultRemark = '')
    {
        if (is_array($defaultValue)) {
            $defaultValue = json_encode($defaultValue);
        }
        return json_decode(self::getString($key, $defaultValue, $defaultRemark), true);
    }

    /**
     * 存储数据到配置表
     * @param string $key
     * @param        $value
     * @param string $remark
     * @return bool
     */
    static public function setValue(string $key, $value, string $remark = ''): bool
    {
        if (empty($key)) {
            return false;
        }

        if (is_array($value)) {
            $value = json_encode($value);
        }

        $config = self::where('key', $key)->first();
        if (!$config) {
            $config         = new Config();
            $config->key    = $key;
            $config->remark = $remark;
        }

        $config->value = $value;
        return $config->save();
    }
}
