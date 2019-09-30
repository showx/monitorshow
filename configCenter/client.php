<?php
/**
 * 消费者-更新config文件
 * author:show
 */
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
require_once __DIR__ . '/cls_format.php';
$config = require_once __DIR__.'/config.php';
$save_dir = $config['config_dir'];
$connection = new AMQPStreamConnection($config['mq_server'], $config['mq_port'], $config['mq_username'], $config['mq_password']);
$channel = $connection->channel();

$channel->exchange_declare('configs', 'fanout', false, false, false);

list($queue_name, ,) = $channel->queue_declare("", false, false, true, false);

$channel->queue_bind($queue_name, 'configs');

echo ' [*] Waiting for logs.', "\n";
//json传输格式
$arr_format = new cls_format();
$callback = function($msg) use($arr_format,$config) {
  $data = $msg->body;
  if($data)
  { 
    echo ' [x] ', $data, "\n";
    //获取到数据的保存
    $filecontent = json_decode($data,true);
    $filename = $filecontent['filename'];
    $content = $arr_format->darr($filecontent['content']);
    file_put_contents($config['config_dir'].'/'.$filename.".ini",$content);
  }
};

$channel->basic_consume($queue_name, '', false, true, false, false, $callback);
while(count($channel->callbacks)) {
    $channel->wait();
}
$channel->close();
$connection->close();