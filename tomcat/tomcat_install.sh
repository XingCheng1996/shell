#!/bin/bash
##function
##create by xingcheng
##2018/5/2

DIR=/usr/local/src
jdk_version=jdk1.7.0_79

cd 
wget http://download.oracle.com/otn-pub/java/jdk/7u79-b15/jdk-7u79-linux-i586.tar.gz

tar zvxf jdk-7u79-linux-i586.tar.gz\?AuthParam\=1470114075_68f5c25eebb5d534cfd8ed8ca4d77145

mv $jdk_version /usr/local/

(cat << EOF
JAVA_HOME=/usr/local/$jdk_version
JAVA_BIN=/usr/local/$jdk_version/bin
JRE_HOME=/usr/local/$jdk_version/jre
PATH=$jdk_versionPATH:/usr/local//bin:/usr/local//jre/bin
CLASSPATH=/usr/local//jre/lib:/usr/local//lib:/usr/local//jre/lib/charsets.jar
) >> /etc/profile.d/java.sh

export  JAVA_HOME  JAVA_BIN JRE_HOME  PATH  CLASSPATH


source /etc/profile
java -version
java version "1.7.0_79"
Java(TM) SE Runtime Environment (build 1.7.0_79-b15)
