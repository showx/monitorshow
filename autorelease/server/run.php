<?php
/**
 * 同步git服务器
 * 采用gitlab webhook的方式同步
 * php -S 0.0.0.0:9100 -t /show/monitorshow/autorelease/server
 * Author:show
 */
$gitToken = $_SERVER['X-Gitlab-Token'];
$project = $_GET['project'];
$config = include_once "config.php";
if(isset($config[$project]))
{
    if($config[$project]['gitToken'] == $gitToken)
    {
        //ok的情况下执行
        echo 'test';
    }else{
        echo 'token error';
    }
}else{
    echo 'server error!';
}
