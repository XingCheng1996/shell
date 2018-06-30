#!/bin/bash
##Date 2017/11/30
##create by xingcheng
##function nosql_install.sh

DIR=/opt/xingcheng/shell/
PHP_DIR=/usr/local/php-7.0.22/



redis_install(){
#最好制作成rpm安装方式。
cd /usr/local/src
wget http://download.redis.io/releases/redis-4.0.8.tar.gz
tar zvxf redis-4.0.8.tar.gz
cd redis-4.0.8
make -j4
make PREFIX=/usr/local/redis  install
mkdir /usr/local/redis/etc &&  mkdir /usr/local/redis/var
cp "$DIR"install_service/redis.conf /usr/local/redis/etc/redis.conf
cp "$DIR"install_service/redis /etc/init.d/redis
useradd -s /sbin/nologin redis && chmod 777 /usr/local/redis/var && chmod 755 /etc/init.d/redis
chkconfig --add redis
chkconfig redis on
service redis start
}

mongodb_install(){
cd "$DIR"install_service
(cat << EOF
[mongodb-org-3.6]
name=MongoDB Repository
baseurl=https://repo.mongodb.org/yum/redhat/$releasever/mongodb-org/3.6/x86_64/
gpgcheck=1
enabled=1
gpgkey=https://www.mongodb.org/static/pgp/server-3.6.asc
EOF
) > /etc/yum.repos.d/mongodb-3.6.repo

yum install -y mongodb-org
(cat << EOF
security:
  authorization: enabled
EOF
) >> /etc/mongod.conf
sed -i s/27017/27018/g /etc/mongod.conf
}

#php安装mongodb扩展
cd $PHP_DIR
./bin/pecl install mongodb
echo 'extension=mongodb.so' >> "$PHP_DIR"etc/php.ini
./sbin/php-fpm  -t  && /etc/init.d/php_7.0.22-fpm reload

############################################################
read -p " Do you want to install mongodb:Y/N " MONGODBCONFIRM
if [ "$PHALCONCONFIRM" = "Y" ] || [ "$PHALCONCONFIRM" = "y" ];then
    mongodb_install
else
echo "=================== install the next thing =============="
fi

read -p " Do you want to install redis:Y/N " REDISCONFIRM
if [ "$PHALCONCONFIRM" = "Y" ] || [ "$PHALCONCONFIRM" = "y" ];then
    redis_install
else
echo "=================== install the next thing =============="
fi
