<?php
/*
 * 2019-01-03 cat
 * 测试脚本
 */
use \GatewayWorker\Lib\Gateway;

class TestController extends CommonController {
    
    public function indexAction(){
        // 向所有人发送 
        $params = $this -> getRequest() -> getParams();
        Gateway::sendToAll("this is yaf {$params['client_id']} said {$params['data']}\r\n");
    }
    
}
