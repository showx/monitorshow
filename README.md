# monitorshow
服务器监控系统
1. monitor/client/ 客户端上报信息
2. monitor/server/ 服务器统计信息(根据个人调整，暂不公开)
3. configCenter/ 生成项目配置文件
	 php项目专用，使用yaconf加载,不是任何环境都有composer,故提交vender文件夹
4. autorelease/ :git hook自动发布系统。
   /show/gitBase/  git提交的项目文件。
   /webwww/www/    线上项目地址。 /webwww/www,可自行修改
   采用gitlab的webhook
5. monitor_third 第三方监控文件
6. one 一键获取服务器情况

## 使用路径
/show/monitorshow/

### [configCenter]
1. php扩展: yaconf
2. configCenter/configfiles 需要配置的文件(php数组)
3. php server.php 发送端

 
### [autorelease]
首先要确保开启gitlab的webhook功能
/home/git/gitlab/config/gitlab.yml
里的注释要去掉才生效
webhook_timeout: 10

1. 配置gitlab的webhook地址
2. 配置好项目config.php
3. /show/gitBase 拉取工程 

## [monitor]
简单服务器监控,采用滴滴报警

### crondjob任务
* * * * * /show/monitorshow/client/client.py > /dev/null 2>&1 &

### 支持库
1. python 2.7
2. yum install gcc python-devel
3. yum install python-setuptools
4. easy_install psutil
5. easy_install supervisor
   
### supervisor
不使用nohup,使用supervisord比较靠谱，当然使用nohup也可以,更新完配置得重启一下,以下是常用命令
1. supervisord -c /show/monitorshow/daemon/supervisord/supervisord.conf
2. supervisorctl reload (supervisorctl -c /show/monitorshow/daemon/supervisord/supervisord.conf reload)
3. supervisorctl status (supervisorctl -c /show/monitorshow/daemon/supervisord/supervisord.conf status)
4. supervisorctl shutdown
5. supervisorctl -c /show/monitorshow/daemon/supervisord/supervisord.conf reread


## 测试通过
1. centos 7
2. python 2.7
3. Php 7.0+ (bcmath扩展)