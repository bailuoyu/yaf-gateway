<?php
/*
 * 2019-03-26 cat
 * 启动回调
 */

use \GatewayWorker\Lib\Gateway;
use \Workerman\Lib\Timer;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events{
    
    /**
     * 当businessWorker进程启动时触发。每个进程生命周期内都只会触发一次。
     * 不要在onWorkerStart内执行长时间阻塞或者耗时的操作，这样会导致BusinessWorker无法及时与Gateway建立连接
     */
    public static function onWorkerStart(){
        //设置定时任务
        Timer::add(20, function(){
            echo date('Y-m-d H:i:s'),PHP_EOL;
//        });
        },[],false);      //false只执行一次
    }

    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id){
        // 向当前client_id发送数据 
        Gateway::sendToClient($client_id, "Hello $client_id\r\n");
        // 向所有人发送
        Gateway::sendToAll("$client_id login\r\n");
        /*
         * 连接建立后90秒内必须绑定Uid，否则踢下线
         */
        $_SESSION['auth_timer_id'] = Timer::add(90, function($client_id){
            $Uid = Gateway::getUidByClientId($client_id);
            if($Uid){
            }else{
                Gateway::closeClient($client_id,'未能绑定Uid，您已被下线');
            }
        }, array($client_id), false);
    }
    
    /**
     * 当客户端连接上gateway完成websocket握手时触发的回调函数。
     * 注意：此回调只有gateway为websocket协议并且gateway没有设置onWebSocketConnect时才有效。
     */
    public static function onWebSocketConnect($client_id, $data){
        
    }
    
    /**
     * 当客户端发来消息时触发
     * @param int $client_id 连接id
     * @param mixed $message 具体消息
     */
    public static function onMessage($client_id,$message){
        $message = trim($message);
        if($message=='ping'){   //如果是心跳
            return Gateway::sendToClient($client_id,'ok');
        }
        $arr = json_decode($message,true);
        if(!$arr){
            return self::result($client_id,40000,$message,'非法的数据格式');
        }elseif(empty($arr['route'])){
            return self::result($client_id,40001,$message,'缺少route参数');
        }
        $route = trim($arr['route']);
        if($route=='ping'){
            return self::result($client_id,10000,'ok','success');
        }
        $params = array(
            'client_id' => $client_id,
            'data' => empty($arr['data'])?array():$arr['data']
        );
        self::yaf($arr['route'],$params);
    }
   
    /**
     * 当用户断开连接时触发
     * @param int $client_id 连接id
     */
    public static function onClose($client_id){
       // 向所有人发送 
       GateWay::sendToAll("$client_id logout\r\n");
    }
    
    /**
     * 当businessWorker进程退出时触发。每个进程生命周期内都只会触发一次。
     * 可以在这里为每一个businessWorker进程做一些清理工作，例如保存一些重要数据等。
     */
    public static function onWorkerStop($businessWorker){
       echo "WorkerStop\n";
    }
   
    /**
     * 运行yaf
     */
    public static function yaf($route,$params=array()){

        require  APP_PATH.'/vendor/autoload.php';
        //加载框架的配置文件
        $app = new Yaf\Application(APP_PATH.'/conf/'.ini_get('yaf.environ').'/application.ini');     //载入cli的配置

        //加载cli的bootstrap配置内容
        $app -> bootstrap();
        
        //当前实例名
        $str = strrchr(__DIR__,'/');
        if($str){
            $module = ltrim($str,'/');
        }else{  //如果是windows
            $module = ltrim(strrchr(__DIR__,'\\'),'\\');;
        }
        
        $uri_r = explode('/',$route);
        if($uri_r[1]){
        }elseif($uri_r[0]){
            array_unshift($uri_r,'index');
        }else{
            if(empty($params['client_id'])){
                echo 'uri error!-'.$route,PHP_EOL;
                return false;
            }else{
                return self::result($params['client_id'],40002,$route,'uri error!');
            }
        }
        $controller = $uri_r[0];
        $action = $uri_r[1];
        
        //改造请求
        $Request = new Yaf\Request\Simple('CLI',$module,$controller,$action,$params);

        //启动
        $app -> getDispatcher() -> dispatch($Request);
    }
    
    /**
     * 返回json格式的结果
     */
    public static function result($client_id,$code,$data=array(),$msg=''){
        $result = array(
            'code' => $code,
            'msg'  => $msg,
            'time' => time(),
            'data' => $data
        );
        $json_str = json_encode($result,JSON_UNESCAPED_UNICODE);
        Gateway::sendToClient($client_id,$json_str);
    }
    
}
