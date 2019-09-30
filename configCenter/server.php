<?php
/**
 * 生产者-传送更新配置
 * Author:show
 */
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/cls_format.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$config = require_once __DIR__.'/config.php';
$connection = new AMQPStreamConnection($config['mq_server'], $config['mq_port'], $config['mq_username'], $config['mq_password']);
$channel = $connection->channel();

$channel->exchange_declare('configs', 'fanout', false, false, false);

$dir = dirname(__FILE__)."/configfiles";
$config_dir = scandir($dir);
$arr_format = new cls_format();
foreach($config_dir as $key=>$filename)
{
    if($filename == '..' || $filename == '.')
    {
        continue;
    }
    $path = $dir."/".$filename;
    $info = pathinfo($path);
    if($info['extension'] != "php" )
    {
        continue;
    }
    $name = $info['filename'];
    $filecontent = require_once($path);
    $result = [
        'filename' => $name,
        'content' => $filecontent,
    ];
    $data = json_encode($result);
    $msg = new AMQPMessage($data);
    $channel->basic_publish($msg, 'configs');
    echo " [x] Sent ", $data, "\n";
}
$channel->close();
$connection->close();