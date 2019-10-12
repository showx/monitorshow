#!/bin/bash
# git 发布代码
# author:show
# gitlab webhook发布
# 禁止提交任何缓存文件，避免冲突

echo 'pull project:'$1
gitBasePath='/show/gitBase/'
cd $gitBasePath$1
git pull

message=$(git log --pretty=format:"%s" -1)
isRedeploy=false
#检测到RELEASE，即发布项目
[[ $message =~ "[RELEASE]" ]] && isRedeploy=true
echo "是否可发布版本："$isRedeploy;

#一定要传git项目名
if [[ $isRedeploy = true && $1 ]]
then
    #判断$2, 有可能$1 与实际环境名不一样
    if [[ -n $2 ]];then
        webAppPath='/webwww/www/'$2'/'
    else
        webAppPath='/webwww/www/'$1'/'
    fi
    gitBasePath='/show/gitBase/'$1'/'
    echo "git:".$gitBasePath;
    echo "web:".$webAppPath;
    cp -r -f -v $gitBasePath* $webAppPath
    echo "success";
else
    echo "fail";
fi;