<?php
if (! defined('IN_DISCUZ')) {
    exit('Access Denied');
}

include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/dayusdk/top/TopClient.php';
include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/dayusdk/top/ResultSet.php';
include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/dayusdk/top/TopLogger.php';
include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/dayusdk/top/RequestCheckUtil.php';
include_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/dayusdk/top/request/AlibabaAliqinFcSmsNumSendRequest.php';


class DAYUSMS
{

    public $accesskeyid;
    
    public $accesskeysecret;
    
    public function __construct($accesskeyid = "",$accesskeysecret = ""){
        $this->accesskeyid = $accesskeyid;
        $this->accesskeysecret = $accesskeysecret ;
    }
    
    
    public function smssend($phoneNumbers = "",$signName = "",$templateCode = "",$templateParam = "")
    {
        if(empty($phoneNumbers) || empty($signName) || empty($templateCode) || empty($templateParam)){
            return;
        }

        date_default_timezone_set('Asia/Shanghai');
    
        try {
            
            $c = new TopClient;
            $c->appkey = $this->accesskeyid;
            $c->secretKey = $this->accesskeysecret;
            $req = new AlibabaAliqinFcSmsNumSendRequest;
            //$req->setExtend("123456");
            $req->setSmsType("normal");
            $req->setSmsFreeSignName($signName);
            $req->setSmsParam($templateParam);
            $req->setRecNum($phoneNumbers);
            $req->setSmsTemplateCode($templateCode);
            $resp = $c->execute($req);
            
            return json_encode($resp);
        }catch (Exception $e) {   
          return  $e->getMessage(); 
        } 
        
    }
}