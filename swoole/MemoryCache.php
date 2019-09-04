<?php

namespace websocket;


class MemoryCache
{
    private static $table;

    public static function init()
    {
        self::$table = new \swoole_table(pow(2, 10));
        self::$table->column('data', \swoole_table::TYPE_STRING, 1024 * 1024);
        self::$table->create();
    }

    /**
     * 保存数据
     * @param     $key  string
     * @param     $data mixed
     * @param int $expire
     */
    public static function set($key, $data, $expire = 0)
    {
        if ($expire != 0) {
            $expire = time() + $expire;
        }
        self::$table->set($key, ['data' => serialize([
            'data'      => $data,
            'expire_at' => $expire
        ])]);
    }

    /**
     * 读取数据
     * @param      $key     string
     * @param      $default mixed
     * @return mixed|null
     */
    public static function get($key, $default = null)
    {
        $row = self::$table->get($key);

        if (false == $row) {
            return $default;
        }

        $row = unserialize($row['data']);

        if ($row['expire_at'] == 0 || time() < $row['expire_at']) {
            return $row['data'];
        }

        self::$table->del($key);
        return $default;
    }

    /**
     * 删除数据
     * @param $key string
     * @return mixed
     */
    public static function del($key)
    {
        return self::$table->del($key);
    }
}