<?php

namespace websocket;

use function foo\func;

include 'Config.php';
include 'CommonFun.php';
include 'MemoryCache.php';
include 'Db.php';

date_default_timezone_set('Asia/Shanghai');

$clientsTable = new \swoole_table(1024);
$clientsTable->column('fd', \swoole_table::TYPE_INT);
$clientsTable->column('info', \swoole_table::TYPE_STRING, 1000);
$clientsTable->create();

MemoryCache::init();

$server = new \swoole_websocket_server(WEBSOCKET_HOST, WEBSOCKET_PORT, SWOOLE_PROCESS);
$server->set([
    'buffer_output_size'       => 64 * 1024 * 1024, // 发送输出缓存区内存尺寸，必须为数字
    'worker_num'               => 8,                // 启动的worker进程数
    'max_request'              => 50,               // worker进程的最大任务数
    'task_worker_num'          => 10,               // task进程的数量
    'task_max_request'         => 10,               // task进程的最大任务数
    'heartbeat_check_interval' => 30,
    'heartbeat_idle_time'      => 600,
    'dispatch_mode'            => 1,                // 轮询模式
    'log_file'                 => __DIR__ . '/swoole.log'
]);

$server->on('open', function ($server, $request) {
    $fd = $request->fd;
    _Log("[OPN] {$fd}");
    global $clientsTable;

    $fdInfo = $server->connection_info($fd);
    if (!$clientsTable->exist($fd)) {
        $clientsTable->set($fd, ['fd' => $fd, 'info' => json_encode($fdInfo)]);
    }
});

// 监听 WebSocket 消息事件
$server->on('message', function ($ws, $frame) {
    _Log(sprintf("[MSG] %s", $frame->data));
});

// 监听 WebSocket 连接关闭事件
$server->on('close', function ($ws, $fd) {
    _Log("[CLS] {$fd}");
});

$server->on('workerstart', function ($server, $id) {
    _Log(sprintf("[INF] @@@ %s worker start $id", $server->taskworker ? 'task' : '', $id));

    if ($id == 0) {
        $server->tick(1000, function ($timerId) use ($server) {
            $sql       = "select g.id, g.title, g.price, g.created_at, g.category_id, f.path as `file_path`, c.name as `category_name`, cp.name as `category_parent_name` from `goods` g join `files` f on g.pic_id = f.id join `categories` c on g.category_id = c.id join `categories` cp on c.parent_id = cp.id where g.`status` = 1 order by g.`updated_at` desc limit 0,4";
            $goods     = Db::querySql($sql);
            $goodsHash = md5(serialize($goods));
            $lastHash  = MemoryCache::get("lastGoodsHash");
            if ($lastHash != $goodsHash) {
                MemoryCache::set("lastGoodsHash", $goodsHash);
                for ($i = 0; $i < count($goods); $i++) {
                    $g                       = $goods[$i];
                    $g['full_category_name'] = $g['category_parent_name'] . '/' . $g['category_name'];
                    $g['pic_url']            = FILE_DOMAIN . $g['file_path'];
                    $goods[$i]               = $g;
                }

                $data = [
                    'type' => 'broadcast',
                    'data' => $goods
                ];
                $server->task($data);
            }
        });

        $server->tick(1000, function ($timerId) use ($server) {
            $now = date("Y-m-d H:i:s", time());
            $sql = "update `goods` as g join `orders` as o on o.goods_id = g.id set g.sell_status = 1, o.status = 4 where o.will_timeout_at <= '{$now}' and o.status = 1";
            Db::querySql($sql);
        });
    }
});

$server->on('task', function ($server, $task_id, $src_worker_id, $data) {
    _Log("[INF] Server started.");

    global $clientsTable;
    $taskType = $data['type'];
    if ($taskType == 'broadcast') {
        foreach ($clientsTable as $row) {
            $fd = $row['fd'];
            if ($server->isEstablished($fd)) {
                $server->push($fd, json_encode($data['data']));
            } else {
                $clientsTable->del($fd);
            }
        }
    }

    $server->finish("$taskType -> OK");
});

$server->on('finish', function ($server, $task_id, $data) {
    _Log("[INF] AsyncTask[$task_id] Finish: $data.");
});

_Log("[INF] Starting server...");

$server->start();