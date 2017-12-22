#!/bin/bash
##Date 2017/11/30
##create by xingcheng
##function 安装php拓展

phalcon_install(){
cd /usr/local/src
git clone --depth=1 git://github.com/phalcon/cphalcon.git  
cd  cphalcon/build/php7/64bits && make clean #可能会报错，忽略 
/usr/local/php-7.0.23/bin/phpize --clean  
/usr/local/php-7.0.23/bin/phpize
./configure --with-php-config=/usr/local/php-7.0.23/bin/php-config   
make && [ $(echo $?) -eq 0 ] && make install
echo 'extension=phalcon.so' >> /usr/local/php-7.0.23/etc/php.ini 
/etc/init.d/php_7.0.23-fpm restart  
}


read -p " Do you want to install phalcon:Y/N " PHALCONCONFIRM
if [ "$PHALCONCONFIRM" = "Y" ] || [ "$PHALCONCONFIRM" = "y" ];then
    phalcon_install
else
echo "=================== install the next thing =============="
fi

