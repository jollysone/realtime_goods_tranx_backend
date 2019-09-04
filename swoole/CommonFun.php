<?php

/**
 * 数字转字符串
 * @param double $num 数字
 * @return mixed
 */
function NumToStr($num)
{
    if (!$num) {
        return $num;
    }

    if ($num == 0) {
        return 0;
    }

    $num = round($num, 8);
    $min = 0.0001;

    if ($num <= $min) {
        $times = 0;

        while ($num <= $min) {
            $num *= 10;
            $times++;

            if (10 < $times) {
                break;
            }
        }

        $arr    = explode('.', $num);
        $arr[1] = str_repeat('0', $times) . $arr[1];
        return $arr[0] . '.' . $arr[1] . '';
    }

    return ($num * 1) . '';
}

/**
 * 输出日志
 * @param $args
 */
function _Log(...$args)
{
    $prefix = date("d H:i:s", time()) . ' | ';
    foreach ($args as $value) {
        if (is_string($value)) {
            echo $prefix . $value . PHP_EOL;
        } else {
            echo $prefix . PHP_EOL;
            var_dump($value);
        }
    }
}