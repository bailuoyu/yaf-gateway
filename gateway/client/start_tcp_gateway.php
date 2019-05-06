<?php 
/*
 * 2019-03-26 cat
 * 启动Gateway GatewayWorker
 */
use \Workerman\Worker;
use \GatewayWorker\Gateway;

require 'common.php';

if(empty($allconfig['tcp_gateway'])){   //如果配置不存在则什么也不执行
}else{
    $congig = $allconfig['tcp_gateway'];
    
    // gateway 进程，这里使用tcp协议
    $gateway = new Gateway('tcp://'.$congig['ip'].':'.$congig['port']);
    // gateway名称，status方便查看
    $gateway->name = $appname.'_tcp_gateway';
    // gateway进程数
    $gateway->count = $congig['count'];
    // 本机ip，分布式部署时使用内网ip
    $gateway->lanIp = empty($congig['lanIp'])?'127.0.0.1':$congig['lanIp'];
    // 内部通讯起始端口，假如$gateway->count=4，起始端口为4000
    // 则一般会使用4000 4001 4002 4003 4个端口作为内部通讯端口 
    $gateway->startPort = $congig['startPort'];
    // 服务注册地址
//    $gateway->registerAddress = empty($congig['registerAddress'])?'127.0.0.1:'.$allconfig['register']['port']:$congig['registerAddress'];
    if(empty($congig['registerAddress'])){    //无法确认地址取本地
        $gateway->registerAddress = '127.0.0.1:'.$allconfig['register']['port'];
    }else{
        if(strstr($congig['registerAddress'],':')){
            $gateway->registerAddress = $congig['registerAddress'];
        }else{
            $gateway->registerAddress = $congig['registerAddress'].':'.$allconfig['register']['port'];
        }
    }

    // 心跳间隔
    $gateway->pingInterval = empty($congig['pingInterval'])?55:$congig['pingInterval'];
    //心跳丢失次数
    $gateway->pingNotResponseLimit = empty($congig['pingNotResponseLimit'])?1:$congig['pingNotResponseLimit'];
    // 心跳数据
    $gateway->pingData = '';

    // 如果不是在根目录启动，则运行runAll方法
    if(!$global_start){
        Worker::runAll();
    }
    
}


