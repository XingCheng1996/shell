#!/bin/bash
#一键安装python3
############################################################
#定义变量
PWD=$(pwd)
DIR_1=/opt
############################################################
#安装包下载

#wget -c https://www.python.org/ftp/python/3.6.4/Python-3.6.4.tgz
#wget -c https://pypi.python.org/packages/11/b6/abcb525026a4be042b486df43905d6893fb04f05aac21c32c638e939e447/pip-9.0.1.tar.gz#md5=35f01da33009719497f01a4ba69d63c9
#wget -c https://pypi.python.org/packages/69/56/f0f52281b5175e3d9ca8623dadbc3b684e66350ea9e0006736194b265e99/setuptools-38.2.4.zip#md5=e8e05d4f8162c9341e1089c80f742f64

yum install -y zlib zlib-devel
###########################################################
python3_install(){
cd $PWD
#tar zvxf Python-3.6.4.tgz
cd Python-3.6.4
./configure --enable-shared --prefix=/usr/local/python3.6.4
make && make install
#[ $(echo $?) -eq 0 ] | (echo "error" && exit)
yum install -y python-pip
}
##########################################################
#安装
setuptools_install(){
unzip setuptools-38.2.4.zip
cd setuptools-38.2.4
python setup.py install
}
#########################################################
#安装ipython
pip_install(){
cd $PWD
tar zvxf pip-9.0.1.tar.gz
cd pip-9.0.1
python setup.py install

}



##########################################################
#开始安装
python3_install
echo '/usr/local/python3.6.4/lib/' >> /etc/ld.so.conf && ldconfig
/usr/local/python3.6.4/bin/python3 -m venv --without-pip $DIR_1/ENV3.6.4
source $DIR_1/ENV3.6.4/bin/activate
setuptools_install
pip_install
