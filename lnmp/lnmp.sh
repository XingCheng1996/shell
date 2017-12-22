#!/bin/bash
##Dtae  20170921
##Create by xingcheng
##function 一键安装LNMP Nginx 1.13.5 Mysql5.7.19 Php7.0.23

# SET THE PATH
PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:~/bin
export PATH
DIR=/usr/local/src

# Check if user is root
if [ $(id -u) != "0" ]; then
    echo "Error: You must be root to run this script, please use root to install lnmp"
    exit 1
fi

function InitInstall()
{
        #REMOVE THE RPMS 
        rpm -qa |  grep http || echo " no httpd " && yum -y remove http* >/dev/null && echo "http ok"
        rpm -qa |  grep nginx || echo " no nginx " && yum -y remove nginx* >/dev/null && echo "nginx ok"
        rpm -qa |  grep php || echo " no php " && yum -y remove php* >/dev/null && echo "php ok"
        rpm -qa |  grep mysql || echo " no mysql " && yum -y remove mysql* >/dev/null && echo "mysql ok"
        
        #Disable SeLinux
        if [ -s /etc/selinux/config ]; then
        sed -i 's/SELINUX=enforcing/SELINUX=disabled/g' /etc/selinux/config
        fi

	yum -y install gcc pcre pcre-devel gcc-c++ autoconf libxml2 libxml2-devel zlib zlib-devel glibc libjpeg libjpeg-devel libpng libpng-devel glibc-devel glib2 glib2-devel ncurses ncurses-devel curl curl-devel e2fsprogs e2fsprogs-devel openssl openssl-devel openldap openldap-devel  openldap-clients openldap-servers make cmake freetype-devel libmcrypt libmcrypt-devel libxslt-devel 
}


function DownLoad()
{
cd $DIR
wget -c http://cn2.php.net/distributions/php-7.0.23.tar.gz
wget -c http://nginx.org/download/nginx-1.13.5.tar.gz
wget -c https://dev.mysql.com/get/Downloads/MySQL-5.7/mysql-5.7.19.tar.gz

echo "==================download ok==============================="

}


function InstallNginx()
{
cd $DIR
tar -zvxf nginx-1.13.5.tar.gz
cd nginx-1.13.5
useradd nobody -s /sbin/nologin
./configure --prefix=/usr/local/nginx --with-http_realip_module --with-http_sub_module --with-http_gzip_static_module --with-http_stub_status_module --with-pcre --with-http_ssl_module --with-file-aio
[ $(echo $?) -eq 0 ] && make
[ $(echo $?) -eq 0 ] && make install
[ $(echo $?) -eq 0 ] && echo "nginx安装成功 "|| (echo "nginx安装失败" && sleep 2 && exit)
cp /usr/local/src/lnmp_install/nginx /etc/init.d/nginx
chmod +x /etc/init.d/nginx
chkconfig --add nginx 
chkconfig nginx on
/etc/init.d/nginx start
echo "=============================== install nginx ok ====================="
}

function InstallMysql(){
cd $DIR
tar zvxf mysql-5.7.19.tar.gz
useradd mysql -s /sbin/nologin
cd mysql-5.7.19
cmake -DCMAKE_INSTALL_PREFIX=/usr/local/mysql -DMYSQL_DATADIR=/data/mysql -DMYSQL_USER=mysql -DSYSCONFDIR=/usr/local/mysql -DWITH_MYISAM_STORAGE_ENGINE=1 -DWITH_INNOBASE_STORAGE_ENGINE=1 -DWITH_DEBUG=0 -DWITH_MEMORY_STORAGE_ENGINE=1 -DWITH_READLINE=1 -DWITH_EMBEDDED_SERVER=1 -DMYSQL_UNIX_ADDR=/data/mysql/mysql.sock -DMYSQL_TCP_PORT=3306 -DENABLED_LOCAL_INFILE=1 -DWITH_PARTITION_STORAGE_ENGINE=1 -DEXTRA_CHARSETS=all -DDEFAULT_CHARSET=utf8 -DDEFAULT_COLLATION=utf8_general_ci -DDOWNLOAD_BOOST=1 -DWITH_BOOST=/usr/local/boost
[ $(echo $?) -eq 0 ] && make
[ $(echo $?) -eq 0 ] && make install
[ $(echo $?) -eq 0 ] && echo "数据库安装成功" || (echo "数据库安装失败" && sleep 2 && exit)
mkdir -p /data/mysql
chown -R mysql:mysql /data/mysql
cp /usr/local/src/lnmp_install/my.cnf /etc/my.cnf
echo "export PATH=$PATH:/usr/local/mysql/bin/" >> /root/.bashrc
source /root/.bashrc
/usr/local/mysql/bin/mysqld --initialize-insecure --user=mysql --basedir=/usr/local/mysql --datadir=/data/mysql
[ $(echo $?) -eq 0 ] && echo "数据库初始化成功" || (echo "数据库初始化失败" && sleep 2)
cp /usr/local/src/lnmp_install/mysqld /etc/init.d/mysqld
chmod 755 /etc/init.d/mysqld
chkconfig --add mysqld
/etc/init.d/mysqld start
echo "=================================mysql install ok =================="
}

function InstallPhp()
{
cd $DIR
tar zvxf php-7.0.23.tar.gz
cd php-7.0.23
./configure --prefix=/usr/local/php-7.0.23 --with-config-file-path=/usr/local/php-7.0.23/etc --with-config-file-scan-dir=/usr/local/php-7.0.23/etc/php.d --with-pdo-mysql=/usr/local/mysql --with-mysql-sock=/data/mysql/mysql.sock --with-curl --with-freetype-dir --with-gd --with-gettext --with-iconv-dir --with-kerberos --with-libdir=lib64 --with-libxml-dir --with-mysqli --with-openssl --with-pcre-regex --with-pdo-mysql --with-pdo-sqlite --with-pear --with-png-dir --with-xmlrpc --with-xsl --with-zlib --with-pdo-mysql --enable-fpm --with-fpm-user=php-fpm --with-fpm-group=php-fpm --enable-bcmath --enable-libxml --enable-inline-optimization --enable-gd-native-ttf --enable-mbregex --enable-mbstring --enable-opcache --enable-pcntl --enable-shmop --enable-soap --enable-sockets --enable-sysvsem  --enable-xml --enable-zip --enable-mysqlnd --with-jpeg-dir --enable-exif --with-mcrypt --enable-maintainer-zts
[ $(echo $?) -eq 0 ] && make
[ $(echo $?) -eq 0 ] && make install
[ $(echo $?) -eq 0 ] && echo "php安装成功" || (echo "php安装失败" && sleep 2 && exit)
cp /usr/local/src/lnmp_install/php.ini /usr/local/php-7.0.23/lib/php.ini
cp /usr/local/src/lnmp_install/php-fpm.conf /usr/local/php-7.0.23/etc/php-fpm.conf
cp /usr/local/src/lnmp_install/php_7.0.23-fpm /etc/init.d/php_7.0.23-fpm
cp /usr/local/php-7.0.23/lib/php.ini /usr/local/php-7.0.23/etc/ && mv /usr/local/php-7.0.23/lib/php.ini /usr/local/php-7.0.23/lib/php.ini.bak
useradd php-fpm -s /sbin/nologin
chmod +x /etc/init.d/php_7.0.23-fpm
chkconfig --add php_7.0.23-fpm
/etc/init.d/php_7.0.23-fpm start
echo "============================= install php ok =========================="
}


InitInstall
DownLoad
read -p " Do you want to install nginx:Y/N " NGINXCONFIRM
if [ "$NGINXCONFIRM" = "Y" ] || [ "$NGINXCONFIRM" = "y" ];then
        InstallNginx
else 
echo "================== install the next thing============"
fi

read -p " Do you want to install mysql: Y/N " MYSQLCONFIRM
if [ "$MYSQLCONFIRM" = "Y" ] || [ "$MYSQLCONFIRM" = "y" ];then
        InstallMysql
else
echo "=================== install the next thing =============="
fi


read -p " Do you want to install the php: Y/N" PHPCONFIRM
if [ "$PHPCONFIRM" = "Y" ] || [ "$PHPCONFIRM" = "y" ];then
        InstallPhp
fi
