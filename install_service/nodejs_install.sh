#!/bin/bash
##Date 2018/3/15
##create by xingcheng
##function nosql_install.sh

DIR=/opt/xingcheng/shell/

nodejs_install(){
cd /usr/local/src/
wget https://nodejs.org/dist/v8.10.0/node-v8.10.0.tar.gz
tar zvxf node-v8.10.0.tar.gz && cd node-v8.10.0
./configure --prefix=/usr/local/node/0.10.24
make
make install
touch /etc/profile.d/nodejs.sh
(cat << EOF
#set for nodejs
export NODE_HOME=/usr/local/node/0.10.24
export PATH=$NODE_HOME/bin:$PATH
EOF
) > /etc/profile.d/nodejs.sh
source /etc/profile
#echo $(node -v)
}


############################################################
read -p " Do you want to install nodejs:Y/N " PHALCONCONFIRM
if [ "$PHALCONCONFIRM" = "Y" ] || [ "$PHALCONCONFIRM" = "y" ];then
    nodejs_install
else
echo "=================== install the next thing =============="
fi

