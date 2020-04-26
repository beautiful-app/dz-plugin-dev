<?php

if (!defined('IN_DISCUZ') || !defined('IN_APPBYME')) {
    exit('Access Denied');
}
class sendSms {

    public function sendCode($mobile, $code, $act){

        $ret = 0;

        switch ($act) {

            case 'register':

                if(file_exists(DISCUZ_ROOT.'./source/plugin/jzsjiale_smsapi/smsapi.inc.php')){
                    @include_once DISCUZ_ROOT.'./source/plugin/jzsjiale_smsapi/smsapi.inc.php';
                    
                    $smsapi = new SMSApi();
                    
                    $ret = $smsapi->smssend($mobile,$code,1,0);
                 }

                break;

             case 'getpwd':
                 if(file_exists(DISCUZ_ROOT.'./source/plugin/jzsjiale_smsapi/smsapi.inc.php')){
                     @include_once DISCUZ_ROOT.'./source/plugin/jzsjiale_smsapi/smsapi.inc.php';
                 
                     $smsapi = new SMSApi();
                 
                     $ret = $smsapi->smssend($mobile,$code,4,0);
                 }

                break;           

            default:
                if(file_exists(DISCUZ_ROOT.'./source/plugin/jzsjiale_smsapi/smsapi.inc.php')){
                    @include_once DISCUZ_ROOT.'./source/plugin/jzsjiale_smsapi/smsapi.inc.php';
                     
                    $smsapi = new SMSApi();
                     
                    $ret = $smsapi->smssend($mobile,$code,2,0);
                }
                break;

        }//f ro m w w w.m oq u8. co m

        $return = array();
        $return['rs'] = $ret > 0 ? '1' : '0';
        $return['msg'] = 'nomsg';

        return $return;
    }
}
?>