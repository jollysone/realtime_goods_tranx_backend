<?php
$isLocalEnv = (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'be.socket-shop.lab');
return [
    'is_local_env'         => $isLocalEnv,

    // Token 长度
    'token_length'         => intval(env('TOKEN_LENGTH', 64)),

    // Token 过期时间（分钟）
    'token_expire_minutes' => intval(env('TOKEN_EXPIRE_MINUTES', 1440 * 7)),

    'max_page_size' => 100,

    // 图片验证码过期时间（分钟）
    'captcha_expire_minutes' => 5,

    'file' => [
        'domain'                   => $isLocalEnv ? 'http://files.socket-shop.lab/' : 'http://files.socket-shop.demo.qizhit.com/',
        'root_path'                => storage_path() . '/app/public/',
        'thumb_trigger_max_length' => 500,
        'thumb_trigger_size'       => 1024 * 200,
        'thumb_quality'            => 80,
        'pic_extensions'           => ['jpg', 'jpeg', 'png'],
        'max_size'                 => 1024 * 1024 * 2,
    ],
];
