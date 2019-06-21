# yaf-socket
结合Workerman,Yaf的MVC形式socket服务
***
## yaf_gateway简介
1. 此源码结合了Workerman的Gateway框架和Yaf框架，实现了基于MVC结构的socket服务。
2. Gateway和yaf都只修改了入口文件，并未改变两个框架的核心部分，可以按照两个框架的官方文档使用所有功能，不必担心拓展和升级问题。
3. Gateway官方文档：http://doc2.workerman.net
4. Yaf官方文档：http://www.laruence.com/manual/ 
5. PHP官方的Yaf文档：https://www.php.net/manual/zh/book.yaf.php

## 一.配置环境

1. ### php环境
    1. php版本：7.0及以上，推荐版本7.2(5.5,5.6版本理论上也可以，可能需要修改部分bug)
    1. yaf扩展：2.0以上，推荐3.0以上(2.0只支持到php5.5,5.6)
    1. php.ini配置：
        1. 错误等级,推荐E_ALL & ~E_NOTICE
        1. 加入yaf配置
        ```
        [yaf]
        extension=yaf.so
        yaf.use_namespace=1
        yaf.cache_config=1
        yaf.environ=dev     #(dev,local,product根据部署环境填写)
        ```

1. ### linux环境
    1. Cenos：7.0以上，理论上linux版本没影响

## 二.运行环境
1. ### 配置
    1. 集成了local，lan，dev，product四个环境，对应php.ini中的yaf.environ配置，具体请参考yaf-socket\conf\dev\application.ini和yaf-socket\conf\dev\gateway\client.php
    2. application.ini为yaf的配置，client.php为Gateway的配置，详细请查看官方文档，示例中开启了一个tcp，一个text，两个websocket进程。


1. ### 集成
    1. library中集成了redis，file，log等操作类，为本源码使用的功能插件，自行结合yaf开发，不足之处可以自行修改
    2. 集成了composer，安装了Gateway，这是必须的，想要使用其他插件请自行添加。

1. ### 运行
    1. socket入口文件为yaf-socket\start.php，socket启动命令为php start.php start
    1. MVC形式yaf入口文件为yaf_gateway\gateway\client\Events.php，socket服务连接上以后，client通过发送json字符串，传递route参数从而实现类似yaf web请求的MVC，比如route为client/test/index时会进入到 yaf_gateway\application\modules\Client\controllers\Test.php中的indexAction()方法，向所有用户发送消息。
    

