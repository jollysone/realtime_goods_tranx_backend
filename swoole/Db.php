<?php

/**
 * By:JollySon
 *
 * 数据库相关的 curl 操作
 *
 */

namespace websocket;

class Db
{

    private static $times;

    public function __construct()
    {
        error_reporting(E_ALL & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Shanghai');
    }

    /**
     * @param null
     * @return $conn | false 成功返回连接，失败返回 flase
     * 连接数据库，并且设置 utf-8 格式
     */
    private static function connect()
    {
        $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
        if ($conn) {
            mysqli_query($conn, "set names 'utf8'");
            return $conn;
        }
        return false;
    }

    /**
     * @param $conn $conn:一个数据库连接
     * @return null
     *              关闭一个数据库连接
     */
    private static function close($conn)
    {
        mysqli_close($conn);
    }

    /**
     * @param $input $input:一个输入的字符串
     * @return string
     *               防止 sql 注入
     */
    public static function safeSQL($input)
    {
        return str_replace('\'', '\'\'', str_replace('\\', '\\\\', $input));
    }

    /**
     * @param string $sql | $conn
     * @return array
     *                    执行 sql 语句
     */
    public static function querySql($sql, $conn = null)
    {
        $disconnect = false;
        // 如果连接了数据库，执行 sql，否则进行连接数据库
        if ($conn) {
            //$res = mysqli_query($conn, $sql);
        } else {
            $conn       = self::connect();
            $disconnect = true;
            //$res        = mysqli_query($conn, $sql);
        }

        $timeStart = microtime(true);
        $res = mysqli_query($conn, $sql);
        $timeEnd = microtime(true);
        // 执行 sql 后的结果放在 $res 数组里面，执行 sql 失败打印错误信息
        if ($res) {
            if ($res === true) {
                $res = mysqli_affected_rows($conn);
            } else {
                while ($row = mysqli_fetch_assoc($res)) {
                    $rows[] = $row;
                }
                $res = isset($rows) ? $rows : [];
            }
        } else {
            _Log(sprintf("[ERR] DB %s: %s", mysqli_errno($conn), mysqli_error($conn)));
        }
        // 执行 sql 后关闭数据库连接
        if ($disconnect) {
            self::close($conn);
        }
        //$timeEnd = microtime(true);
        $timeDiff = $timeEnd - $timeStart;
        if($timeDiff > 1){
            _Log(sprintf("慢查询(%.3f s) %s", $timeEnd - $timeStart, $sql));
        }
        return $res;
    }

}