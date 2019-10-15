<?php
/**
 * 同步git服务器
 * 采用gitlab webhook的方式同步
 * php -S 0.0.0.0:9100 -t /show/monitorshow/autorelease/server
 * Author:show
 */
$gitEvent = $_SERVER['HTTP_X_GITLAB_EVENT'];
$gitToken = $_SERVER['HTTP_X_GITLAB_TOKEN'];
$project = $_GET['project'];
$config = include_once "config.php";
if(isset($config[$project]))
{
    if($config[$project]['gitToken'] == $gitToken)
    {
        //ok的情况下执行
        echo 'ok';
        //记录一下
        $time = time();
        $pro_argv1 = $config[$project]['gitName'];
        $pro_argv2 = $config[$project]['webName'];
        $shell = "bash /show/monitorshow/autorelease/gitsync.sh {$pro_argv1} {$pro_argv2}";
        system($shell, $status);
        $log = "project:{$project} status:{$status} success time:{$time} \r\n";
        echo $log;
        file_put_contents("/show/monitorshow/autorelease/server/record.log",$log,FILE_APPEND|LOCK_EX);
    }else{
        echo 'token error';
        $log = "project:{$project} error time:{$time} \r\n";
        echo $log;
        file_put_contents("/show/monitorshow/autorelease/server/record.log",$log,FILE_APPEND|LOCK_EX);
    }
}else{
    echo 'server error!';
}
