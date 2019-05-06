<?php

class ToolsController extends CommonController {
    
    public function domainAction(){
        $str = "api.208996.com api.4666fh.com api.9991fh.com api.7666fh.com api.689fh.com api.3338fh.com api.208997.com api.2999fh.com api.3777fh.com api.004fh.com api.3999fh.com api.3555fh.com api.6663fh.com api.8886fh.com api.5559fh.com api.2777fh.com api.8555fh.com api.7333fh.com api.9666fh.com api.7778fh.com api.4777fh.com api.0008fh.com api.6777fh.com api.9777fh.com api.5999fh.com";
        //声明mysql master
        $DbMaster = new \Db\Master\Master();
        $tid = 1;
        $tab = '`hall_domain`';
        $domain_r = explode(' ',$str);
        foreach ($domain_r as $_v) {
            $domain = ltrim($_v,'api.');
            $sql = "insert into {$tab} set tid={$tid},domain='{$domain}'";
            $DbMaster::pdo() -> query($sql);
        }
    }
    
    public function domaintestAction(){
        $str = "api.208996.com api.4666fh.com api.9991fh.com api.7666fh.com api.689fh.com api.3338fh.com api.208997.com api.2999fh.com api.3777fh.com api.004fh.com api.3999fh.com api.3555fh.com api.6663fh.com api.8886fh.com api.5559fh.com api.2777fh.com api.8555fh.com api.7333fh.com api.9666fh.com api.7778fh.com api.4777fh.com api.0008fh.com api.6777fh.com api.9777fh.com api.5999fh.com";
//        https://api.208996.com/user/exists?user_name=cat222
        $domain_r = explode(' ',$str);
        foreach ($domain_r as $_v) {
            echo 'https://'.$_v.'/user/exists?user_name=cat222',PHP_EOL;
        }
    }
    
}
