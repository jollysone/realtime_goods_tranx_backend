<?php

namespace App\Helpers;


class CacheKey
{
    /**
     * 图片验证码
     * @param string $key
     * @return string
     */
    static public function captcha(string $key): string
    {
        return 'captcha' . $key;
    }

    /**
     * 登录Token
     * @param string $token
     * @return string
     */
    static public function token(string $token): string
    {
        return 'token' . $token;
    }
}