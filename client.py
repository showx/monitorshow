#!/usr/bin/python
# -*- coding: utf-8 -*-


import httplib
import urllib
import psutil
import json
import socket
import ConfigParser

conf = ConfigParser.ConfigParser()
conf.read('client_config.ini')
server_id = conf.get('info','server_id')
server_name = conf.get('info','server_name')
server_url = conf.get('info','server_url')
server_path = conf.get('info','server_path')
secret = conf.get('sign','secret')

hostname = socket.gethostname()
ip = socket.gethostbyname(hostname)
print ip

# cpu
cpu = psutil.cpu_percent()
mem = psutil.virtual_memory()
memory = mem.percent
#不考虑多盘的情况
disk = psutil.disk_usage('/');
diskused = disk.percent
print cpu,memory,diskused

headers = {"Content-type": "application/x-www-form-urlencoded","Accept": "text/plain"}
params = urllib.urlencode({'server_id': server_id,'server_name':server_name, 'cpu': cpu, 'memory': memory,'disk':diskused,'secret':'shengsheng$'});

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



