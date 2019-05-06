<?php
/*
 * 2019-03-26 cat
 * Gateway的client配置
 */
return array(
    //tcp协议
    'tcp_gateway' => array(
        'ip' => '0.0.0.0',      //允许的ip
        'port' => 8280,       //运行的端口
        'count' => 1,      //gateway进程数
        'lanIp' => '10.70.116.251',     // 内网通讯地址默认本机ip，分布式部署时使用内网ip
        'startPort' => 4000,        // 内部通讯起始端口
        'registerAddress' => '',    // 服务注册地址,不填会取127.0.01，部署在一起可不填,可以不写端口号，会调用register中的端口
        'pingInterval' => 200,       // 心跳间隔
        'pingNotResponseLimit' => 3,    // 心跳丢失次数
    ),
    //websocket协议
    'websocket_gateway' => array(
        'ip' => '0.0.0.0',
        'port' => 8282,
        'count' => 2,
        'lanIp' => '10.70.116.251',
        'startPort' => 4100,
        'registerAddress' => '',
        'pingInterval' => 20,
        'pingNotResponseLimit' => 3,
    ),
    //text协议
    'text_gateway' => array(
        'ip' => '0.0.0.0',
        'port' => 8284,
        'count' => 1,
        'lanIp' => '10.70.116.251',
        'startPort' => 4200,
        'registerAddress' => '',
        'pingInterval' => 20,
        'pingNotResponseLimit' => 3,
    ),
    //注册服务端
    'register' => array(
        'ip' => '0.0.0.0',
        'port' => 1234
    ),
    //业务逻辑进程
    'businessworker' => array(
        'count' => 4,
        'registerAddress' => '',    // 服务注册地址,不填会取127.0.01，部署在一起可不填,可以不写端口号，会调用register中的端口
    ),
    
);

