# monitorshow
服务器监控系统
1. client/ 客户端上报信息
2. server/ 服务器统计信息

## 使用路径
1. /show/monitorshow/
2. * * * * * /show/monitorshow/client/client.py > /dev/null 2>&1 &

## 测试通过
1. centos 7
2. python 2.7

## 支持库
1. python 2.7
2. yum install gcc python-devel
3. yum install python-setuptools
4. easy_install psutil