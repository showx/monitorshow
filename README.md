# monitorshow
服务器监控系统
1. client/ 客户端上报信息
2. server/ 服务器统计信息
3. configCenter/ 生成项目配置文件
	 php项目专用，使用yaconf加载,不是任何环境都有composer,故提交vender文件夹
4.autorelease/ :git hook自动发布系统。
   /show/gitBase/  git提交的项目文件。
   /webwww/www/    线上项目地址。 /webwww/www,可自行修改
   采用gitlab的webhook
## 使用路径
/show/monitorshow/

### [configCenter]
1. php扩展: yaconf
2. nohup nohup php client.php > /show/configclient.log & (客户端),接收配置方
3. php server.php 发送端
4. configCenter/configfiles 需要配置的文件(php数组)
 
### [autorelease]
首先要确保开启gitlab的webhook功能
/home/git/gitlab/config/gitlab.yml
里的注释要去掉才生效
webhook_timeout: 10

1. 配置gitlab的webhook地址
2. 配置好项目config.php
3. /show/gitBase 拉取工程 
4. nohup php -S 0.0.0.0:9100 -t /show/monitorshow/autorelease/server &

## [monitor]
简单服务器监控,采用滴滴报警

### crondjob任务
* * * * * /show/monitorshow/client/client.py > /dev/null 2>&1 &

### 支持库
1. python 2.7
2. yum install gcc python-devel
3. yum install python-setuptools
4. easy_install psutil

## 测试通过
1. centos 7
2. python 2.7
3. Php 7.0+ (bcmath扩展)