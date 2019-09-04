<?php

$env = isset($argv[1]) ? strtolower($argv[1]) : 'dev';

/** 数据库配置 **/
define('HOST', '127.0.0.1'); // 数据库主机
define('USERNAME', 'socket_shop'); // 数据库账户
define('DATABASE', 'socket_shop'); // 选择数据库
define('PASSWORD', '94i7546GX5ZR7Xxp'); // 数据库密码

/** WebSocket配置 **/
define('WEBSOCKET_HOST', '0.0.0.0'); // WebSocketServer 主机
define('WEBSOCKET_PORT', '8123'); // WebSocketServer 端口

/** 其他 **/
define('FILE_DOMAIN', $env == 'dev' ? 'http://files.socket-shop.lab/' : 'http://files.socket-shop.demo.qizhit.com/'); // 文件服务器域名