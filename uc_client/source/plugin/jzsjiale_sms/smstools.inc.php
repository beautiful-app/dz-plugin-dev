<?php
if (! defined('IN_DISCUZ')) {
    exit('Access Denied');
}


class SMSTools
{

    public $accesskeyid;
    
    public $accesskeysecret;
    
    public function __construct($accesskeyid = "",$accesskeysecret = ""){
        $this->accesskeyid = $accesskeyid;
        $this->accesskeysecret = $accesskeysecret ;
    }
    
    public function smssend($code,$expire,$type=0,$uid,$phoneNumbers = "",$signName = "",$templateCode = "",$templateParam = "",$ip = "")
    {
        if(empty($phoneNumbers) || empty($signName) || empty($templateCode) || empty($templateParam)){
            return;
        }
        loadcache('plugin');
        global $_G;
        $webbianma = $_G['charset'];
        
        
        $isok = false;

        $retdata = "error";
        
        
        //mobile heimingdan jiance start
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        //haoduan heimingdan start
        if(!empty($_config['g_haoduanheimingdan'])){
            $g_haoduanheimingdan = $_config['g_haoduanheimingdan'];
            $g_haoduanheimingdan = explode(",",$g_haoduanheimingdan);
            foreach ($g_haoduanheimingdan as $hdhmd){
                if(!empty($hdhmd)){
                    $hdhmdlen = strlen($hdhmd);
                    $mobilesub = substr($phoneNumbers,0,$hdhmdlen);
                    if($mobilesub == $hdhmd){
                        $isok = false;
                        $retdata = "error";
                    
                        $smslist_heimingdan = array('dateline'=>TIMESTAMP);
                        $smslist_heimingdan['uid'] = $uid;
                        $smslist_heimingdan['phone'] = $phoneNumbers;
                        $smslist_heimingdan['seccode'] = $code;
                        $smslist_heimingdan['ip'] = $ip;
                        $smslist_heimingdan['msg'] = "(".$hdhmd.") hao duan hei ming dan !!!";
                        $smslist_heimingdan['type'] = $type;//type:0ceshi 1zhuce 2shenfenyanzheng 3denglu 4xiugaimima
                        $smslist_heimingdan['status'] = 0;
                        C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->insert($smslist_heimingdan,true);
                    
                        return $retdata;
                    }
                }
                
            }
            
        }
        //haoduan heimingdan end
        //shoujihao heimingdan start
        if(!empty($_config['g_mobileheimingdan'])){
            $g_mobileheimingdan = $_config['g_mobileheimingdan'];
            $g_mobileheimingdan = explode(",",$g_mobileheimingdan);
            if(in_array($phoneNumbers,$g_mobileheimingdan)){
                $isok = false;
                $retdata = "error";
            
                $smslist_heimingdan = array('dateline'=>TIMESTAMP);
                $smslist_heimingdan['uid'] = $uid;
                $smslist_heimingdan['phone'] = $phoneNumbers;
                $smslist_heimingdan['seccode'] = $code;
                $smslist_heimingdan['ip'] = $ip;
                $smslist_heimingdan['msg'] = "hei ming dan !!!";
                $smslist_heimingdan['type'] = $type;//type:0ceshi 1zhuce 2shenfenyanzheng 3denglu 4xiugaimima
                $smslist_heimingdan['status'] = 0;
                C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->insert($smslist_heimingdan,true);
            
                return $retdata;
            }
        }
        //shoujihao heimingdan end
        
        //mobile heimingdan jiance end
        
        
        //echo "==phoneNumbers:=".$phoneNumbers."---signName--".$signName.'==templateCode=='.$templateCode.'==templateParam=='.$templateParam;
        //exit;
      
        
        $g_smsapi = $_config['g_smsapi'];
        if(empty($g_smsapi)){
            $g_smsapi = "aliyun";
        }
        $ret = "";
        if($g_smsapi == "aliyun"){
            //aliyun sms send start
            require_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/SMS.php';
            $sms = new SMS();
            $sms->__construct($this->accesskeyid, $this->accesskeysecret);
            
            
            if($_config['g_smsdebug']){
                //$ret = '{"Message":"\u6ca1\u6709\u8bbf\u95ee\u6743\u9650","RequestId":"43C8D5C3-0EC7-4390-AC42-D12316AFB630","Code":"isv.BUSINESS_LIMIT_CONTROL"}';
                $ret = '{"Message":"OK","RequestId":"962AE9DB-EAF7-4098-A4FF-5F5F1B4F39B1","BizId":"108711042472^1111710451471","Code":"OK"}';
                
            }else{
                $ret = $sms->smssend($phoneNumbers,$signName,$templateCode,$templateParam);
            }
            
            //sms send end
            
            
            //$ret = '{"Message":"\u6ca1\u6709\u8bbf\u95ee\u6743\u9650","RequestId":"43C8D5C3-0EC7-4390-AC42-D12316AFB630","Code":"isv.BUSINESS_LIMIT_CONTROL"}';
            //$ret = '{"Message":"OK","RequestId":"962AE9DB-EAF7-4098-A4FF-5F5F1B4F39B1","BizId":"108711042472^1111710451471","Code":"OK"}';
            //echo "====".$ret;EXIT;
            $retinfo = json_decode($ret);
            
            //echo "===".$retinfo->Code;
            if($retinfo != null && $retinfo->Code == 'OK'){
                $retdata = "success";
                $isok = true;
            }elseif($retinfo != null && $retinfo->Code != 'OK'){
                $retdata = $retinfo->Code;
                $isok = false;
            }else{
                $retdata = "error";
                $isok = false;
            }
            
        }elseif($g_smsapi == "dayu"){
            //dayu sms send start
            require_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/DAYUSMS.php';
            $dayusms = new DAYUSMS();
            $dayusms->__construct($this->accesskeyid, $this->accesskeysecret);
            
            if($_config['g_smsdebug']){
                //$ret = '{"code":"15","msg":"Remote service error","sub_code":"isv.BUSINESS_LIMIT_CONTROL","sub_msg":"\u89e6\u53d1\u5206\u949f\u7ea7\u6d41\u63a7Permits:1","request_id":"uu88g17ys4q"}';
                $ret = '{"result":{"err_code":"0","model":"153817909549214052^0","msg":"OK","success":"true"},"request_id":"ntvny3xgvs1a"}';
                
            }else{
                $ret = $dayusms->smssend($phoneNumbers,$signName,$templateCode,$templateParam);
            }
            
            
            //sms send end
            
            
            //$ret = '{"code":"15","msg":"Remote service error","sub_code":"isv.BUSINESS_LIMIT_CONTROL","sub_msg":"\u89e6\u53d1\u5206\u949f\u7ea7\u6d41\u63a7Permits:1","request_id":"uu88g17ys4q"}';
            //$ret = '{"result":{"err_code":"0","model":"153817909549214052^0","msg":"OK","success":"true"},"request_id":"ntvny3xgvs1a"}';
            //echo "====".$ret;EXIT;
            $retinfo = json_decode($ret);
            $retinfo = $retinfo->result;
            
            if($retinfo != null && $retinfo->success == true){
                $retdata = "success";
                $isok = true;
            }elseif(strpos($ret, "sub_code") !== false && strpos($ret, "sub_msg") !== false){
                $retinfo = json_decode($ret);
                $retdata = $retinfo->sub_code;
                $isok = false;
            }else{
                $retdata = "error";
                $isok = false;
            }
            
        }
            
            
        if($isok){
            $smscode = array('dateline'=>TIMESTAMP);
            $smscode['uid'] = $uid;
            $smscode['phone'] = $phoneNumbers;
            $smscode['seccode'] = $code;
            $smscode['expire'] = $expire;//guoqishijian
             
            if(C::t('#jzsjiale_sms#jzsjiale_sms_code')->insert($smscode,true)){
                $isok = true;
            }else{
                $isok = false;
            }
        }
        
        
        //if($isok){
            $smslist = array('dateline'=>TIMESTAMP);
            $smslist['uid'] = $uid;
            $smslist['phone'] = $phoneNumbers;
            $smslist['seccode'] = $code;
            $smslist['ip'] = $ip;
            $smslist['msg'] = $ret;
            $smslist['type'] = $type;//type:0ceshi 1zhuce 2shenfenyanzheng 3denglu 4xiugaimima
            $smslist['status'] = ($retdata == "success")?1:0;
            
            if(C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->insert($smslist,true)){
                $isok = true;
            }else{
                $isok = false;
            }
        //}
      
        
        return $retdata;
    }
}