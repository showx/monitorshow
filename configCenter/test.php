<?php
/**
 * 配置测试文件
 */
$tmp = Yaconf::get("www.mysql.user.sss");
$test = Yaconf::has("test.testarr.123.4");
var_dump($tmp);
var_dump($test);
