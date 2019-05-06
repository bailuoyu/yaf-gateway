<?php 
/*
 * 2019-03-26 cat
 * 启动Gateway Register
 */
use \Workerman\Worker;
use \GatewayWorker\Register;

require 'common.php';

if(empty($allconfig['register'])){   //如果配置不存在则什么也不执行
}else{
    $congig = $allconfig['register'];
    // register 必须是text协议
    $register = new Register('text://'.$congig['ip'].':'.$congig['port']);
    // register名称
    $register->name = $appname.'_register';
    
    // 如果不是在根目录启动，则运行runAll方法
    if(!$global_start){
        Worker::runAll();
    }
}


