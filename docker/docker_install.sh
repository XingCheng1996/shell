#!/bin/bash
##Date 2018/5/16
##create by xingcheng
##function 安装指定版本docker拓展

DIR=/opt/xingcheng/shell/
yum install -y yum-utils device-mapper-persistent-data lvm2

docker_install(){

yum-config-manager --add-repo http://mirrors.aliyun.com/docker-ce/linux/centos/docker-ce.repo
yum makecache fast
yum -y install docker-ce-17.06.1.ce
service docker start
service enable docker
}


read -p " Do you want to install phalcon:Y/N " PHALCONCONFIRM
if [ "$PHALCONCONFIRM" = "Y" ] || [ "$PHALCONCONFIRM" = "y" ];then
    docker_install
else
echo "=================== install the next thing =============="
fi

