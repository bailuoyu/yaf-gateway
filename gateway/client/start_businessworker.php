<?php 
/*
 * 2019-03-26 cat
 * 启动Gateway BusinessWorker
 */
use \Workerman\Worker;
use \GatewayWorker\BusinessWorker;

require 'common.php';

if(empty($allconfig['businessworker'])){   //如果配置不存在则什么也不执行
}else{
    // bussinessWorker 进程
    $worker = new BusinessWorker();
    // worker名称
    $worker->name = $appname.'_businessworker';
    // bussinessWorker进程数量
    $worker->count = 4;
    // 服务注册地址
//    $worker->registerAddress = empty($congig['registerAddress'])?'127.0.0.1:'.$allconfig['register']['port']:$congig['registerAddress'];
    if(empty($congig['registerAddress'])){    //无法确认地址取本地
        $worker->registerAddress = '127.0.0.1:'.$allconfig['register']['port'];
    }else{
        if(strstr($congig['registerAddress'],':')){
            $worker->registerAddress = $congig['registerAddress'];
        }else{
            $worker->registerAddress = $congig['registerAddress'].':'.$allconfig['register']['port'];
        }
    }

    // 如果不是在根目录启动，则运行runAll方法
    if(!$global_start){
        Worker::runAll();
    }
}

