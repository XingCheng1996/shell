#!/bin/bash
##function
##create by xingcheng
##2018/5/2


jdk_install(){
DIR=/usr/local/src
jdk_version=jdk1.7.0_79

cd $DIR
if [ ! -e jdk-7u79-linux-i586.tar.gz ]
then
    wget http://download.oracle.com/otn-pub/java/jdk/7u79-b15/jdk-7u79-linux-i586.tar.gz
fi
tar zvxf jdk-7u79-linux-i586.tar.gz
mv $jdk_version /usr/local/

(cat << EOF
JAVA_HOME=/usr/local/$jdk_version
JAVA_BIN=/usr/local/$jdk_version/bin
JRE_HOME=/usr/local/$jdk_version/jre
PATH=$PATH:/usr/local/$jdk_version:/usr/local/$jdk_version/bin:/usr/local/$jdk_version/jre/bin
CLASSPATH=/usr/local/$jdk_version/jre/lib:/usr/local/$jdk_version/lib:/usr/local/$jdk_version/jre/lib/charsets.jar
export  JAVA_HOME  JAVA_BIN JRE_HOME  PATH  CLASSPATH
EOF
) >> /etc/profile.d/java_"$jdk_version".sh
source /etc/profile
echo $(java -version)

}


tomcat_install(){
jdk_install
wget http://www.aminglinux.com/bbs/data/attachment/forum/apache-tomcat-7.0.14.tar.gz
tar zvxf apache-tomcat-7.0.14.tar.gz
mv apache-tomcat-7.0.14 /usr/local/tomcat
cp -p /usr/local/tomcat/bin/catalina.sh /etc/init.d/tomcat
sed '2i # chkconfig: 112 63 37\n# description: tomcat server init script\n# Source Function Library\n. /etc/init.d/functions\n\nJAVA_HOME=/usr/local/jdk1.7.0_79/\nCATALINA_HOME=/usr/local/tomcat\n'

chmod 755 /etc/init.d/tomcat
chkconfig --add tomcat
chkconfig tomcat on
/etc/init.d/tomcat start


}

############################################################
read -p " Do you want to install tomcat:Y/N " TOMCATCONCONFIRM
if [ "$TOMCATCONCONFIRM" = "Y" ] || [ "$TOMCATCONCONFIRM" = "y" ];then
    tomcat_install
else
echo "=================== install the next thing =============="
fi
