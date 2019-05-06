<?php

//是否全局启动
if(defined('APP_PATH')){
    $global_start = 1;
}else{
    define('APP_PATH',dirname(dirname(__DIR__)));
    // 自动加载类
    require APP_PATH.'/vendor/autoload.php';
    $global_start = 0;
}

//当前实例名
$str = strrchr(__DIR__,'/');
if($str){
    $appname = ltrim($str,'/');
}else{  //如果是windows
    $appname = ltrim(strrchr(__DIR__,'\\'),'\\');
}

$allconfig = require(APP_PATH.'/conf/'.ini_get('yaf.environ').'/gateway/'.$appname.'.php');      //获取当前实例配置

