#!/usr/bin/python
# -*- coding: utf-8 -*-


import httplib
import urllib
import psutil
import json
import socket
import ConfigParser
import os




conf = ConfigParser.ConfigParser()
conf.read('/show/monitorshow/client/client_config.ini')
server_id = conf.get('info','server_id')
server_name = conf.get('info','server_name')
server_url = conf.get('info','server_url')
server_path = conf.get('info','server_path')
secret = conf.get('sign','secret')

hostname = socket.gethostname()
ip = socket.gethostbyname(hostname)
server_name = server_name + '(' + ip + ')'


# cpu
cpu = psutil.cpu_percent()
mem = psutil.virtual_memory()
memory = mem.percent
#不考虑多盘的情况
disk = psutil.disk_usage('/');
diskused = disk.percent

qps_nums = []
search_command = 'netstat -nat |grep "ESTABLISHED"| awk "{print $4}"|awk -F: "{print $2}"|grep "80"|wc -l'
num_connects = os.popen(search_command).read()
qps_nums.append(num_connects)
print qps_nums
print cpu,memory,diskused

headers = {"Content-type": "application/x-www-form-urlencoded","Accept": "text/plain"}
params = urllib.urlencode({'server_id': server_id,'server_name':server_name, 'cpu': cpu, 'memory': memory,'disk':diskused,'qps':num_connects,'secret':'shengsheng$'});

try:
	conn = httplib.HTTPConnection(server_url);
	conn.request("POST", server_path,params,headers);
	r1 = conn.getresponse();
	data1 = r1.read();
	#输出结果
	print data1;
except:
	print "传送失败"
conn.close();



