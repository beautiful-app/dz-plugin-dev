<?php


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$act = $_GET['act'];
loadcache('plugin');
global $_G, $lang;


require_once DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/smstools.inc.php';
require_once DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/utils.class.php';
$utils = new Utils();

if($act=='sendtest'){
    if(submitcheck('submit')){

        $setting = $_GET['setting'];
        $dsp = array();
        $dsp['phone'] = daddslashes(trim($setting['phone']));
         
        if(empty($dsp['phone'])){
            cpmsg('jzsjiale_sms:smssendphone_null', '', 'error');
        }

        if(!$utils->isMobile($dsp['phone'])){
            cpmsg('jzsjiale_sms:smssendphoneerror_null', '', 'error');
        }
        
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        $g_accesskeyid = $_config['g_accesskeyid'];
        $g_accesskeysecret = $_config['g_accesskeysecret'];
        $webbianma = $_G['charset'];
        $g_xiane = $_config['g_xiane'];
        $g_isopenhtmlspecialchars = !empty($_config['g_isopenhtmlspecialchars'])?true:false;
        
        if(empty($g_accesskeyid) || empty($g_accesskeysecret)){
            cpmsg('jzsjiale_sms:noappkey', '', 'error');
        }
        
        $phonesendcount = C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->count_by_phone_day($dsp['phone']);
 
        if($phonesendcount >= $g_xiane){
            cpmsg('jzsjiale_sms:smsxiane', '', 'error');
        }
        
        $code = $utils->generate_code();
        
        if(empty($code) || $code == null){
            cpmsg('jzsjiale_sms:generatecodeerror', '', 'error');
        }
        
        $g_yanzhengid = $_config['g_yanzhengid'];
        $g_yanzhengsign = $_config['g_yanzhengsign'];
        
        if(empty($g_yanzhengid)){
            cpmsg('jzsjiale_sms:noyanzhengid', '', 'error');
        }
        if(empty($g_yanzhengsign)){
            cpmsg('jzsjiale_sms:noyanzhengsign', '', 'error');
        }
        
        
        $sms_param_array = array();
        $sms_param_array['code']=(string)$code;
        
        if($_config['g_openyanzhengproduct']){
            $g_product = $_config['g_product'];
            $sms_param_array['product']=!empty($g_product)?$g_product:'';
            $sms_param_array['product'] = $utils->getbianma($sms_param_array['product'],$webbianma,$g_isopenhtmlspecialchars);
        }
        
        $sms_param = json_encode($sms_param_array);
      
        
        $g_yanzhengsign=$utils->getbianma($g_yanzhengsign,$webbianma,$g_isopenhtmlspecialchars);
        
        //quoqishijian
        $g_youxiaoqi = $_config['g_youxiaoqi'];
        if(empty($g_youxiaoqi)){
            $g_youxiaoqi = 600;
        }
        //echo "====".date('Y-m-d H:i:s',strtotime("+".$g_youxiaoqi." second"));exit;
        $expire = strtotime("+".$g_youxiaoqi." second");
       
        $uid = $_G['uid'];
        $smstools = new SMSTools();
        $smstools->__construct($g_accesskeyid, $g_accesskeysecret);
        $retdata = $smstools->smssend($code,$expire,0,$uid,$dsp['phone'],$g_yanzhengsign,$g_yanzhengid,$sms_param);
        
        switch ($retdata){
            case 'success':
                cpmsg('jzsjiale_sms:smssuccess', '', 'success');
                break;
            case 'error':
                cpmsg('jzsjiale_sms:smserror', '', 'error');
                break;
            case 'isv.BUSINESS_LIMIT_CONTROL':
                cpmsg('jzsjiale_sms:smsBUSINESS_LIMIT_CONTROL', '', 'error');
                break;
            case 'isv.MOBILE_NUMBER_ILLEGAL':
                cpmsg('jzsjiale_sms:isvMOBILE_NUMBER_ILLEGAL', '', 'error');
                break;
            case 'isv.SMS_TEMPLATE_ILLEGAL':
                cpmsg('jzsjiale_sms:isvSMS_TEMPLATE_ILLEGAL', '', 'error');
                break;
            case 'isv.SMS_SIGNATURE_ILLEGAL':
                cpmsg('jzsjiale_sms:isvSMS_SIGNATURE_ILLEGAL', '', 'error');
                break;
            case 'isv.AMOUNT_NOT_ENOUGH':
                cpmsg('jzsjiale_sms:isvAMOUNT_NOT_ENOUGH', '', 'error');
                break;
            case 'isv.OUT_OF_SERVICE':
                cpmsg('jzsjiale_sms:isvOUT_OF_SERVICE', '', 'error');
                break;
            case 'isv.INVALID_PARAMETERS':
                cpmsg('jzsjiale_sms:isvINVALID_PARAMETERS', '', 'error');
                break;
            case 'isp.RAM_PERMISSION_DENY':
                cpmsg('jzsjiale_sms:ispRAM_PERMISSION_DENY', '', 'error');
                break;
            case 'SignatureDoesNotMatch':
                cpmsg('jzsjiale_sms:SignatureDoesNotMatch', '', 'error');
                break;
            case 'InvalidTimeStamp.Expired':
                cpmsg('jzsjiale_sms:InvalidTimeStampExpired', '', 'error');
                break;
            default:
                cpmsg('jzsjiale_sms:smserror', '', 'error');
                break;
        }
        exit();
    }

}

/////////tip start

echo '<div class="colorbox"><h4>'.plang('aboutsmssend').'</h4>'.
'<table cellspacing="0" cellpadding="3"><tr>'.
'<td valign="top">'.plang('smssenddescription').'</td></tr></table>'.
'<div style="width:95%" align="right">'.plang('copyright').'</div></div>';

/////////tip end

showformheader('plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=smssend&act=sendtest', 'enctype');
showtableheader(plang('smssendtitle'), '');
showsetting(plang('smssendphone'),'setting[phone]','','text','','',plang('smssendphone_msg'));

showsubmit('submit',plang('fasong'));
showtablefooter();
showformfooter();


echo '<br/>'.plang('smsapitest').'<br/>';
echo '<br/><a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=smssend&smsapitest=smsapitestsms" style="color:red;">'.plang('smsapitestsms').'</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=smssend&smsapitest=smsapitestdy" style="color:red;">'.plang('smsapitestdy').'</a>';
echo '<br/>';
if(!empty($_GET['smsapitest'])){
    if($_GET['smsapitest'] == "smsapitestsms"){
        $ip = 'dysmsapi.aliyuncs.com';
        $pingret = ping($ip);
        foreach($pingret as $pingk => $pingv){
            echo $pingv."<br/>";
        }
        if($pingret['error']=='timeout'){
            echo plang('smsapitesterror')."<br/>";
        }else{
            echo plang('smsapitestok')."<br/>";
        }
    }elseif($_GET['smsapitest'] == "smsapitestdy"){
        $ip = 'gw.api.taobao.com';
        $pingret = ping($ip);
        foreach($pingret as $pingk => $pingv){
            echo $pingv."<br/>";
        }
        if($pingret['error']=='timeout'){
            echo plang('smsapitesterror')."<br/>";
        }else{
            echo plang('smsapitestok')."<br/>";
        }
    }
}


function ping($ip,$times=4)
{
    $info = array();
    if(!is_numeric($times) ||  $times-4<0)
    {
        $times = 4;
    }
    if (PATH_SEPARATOR==':' || DIRECTORY_SEPARATOR=='/')//linux
    {
        exec("ping $ip -c $times",$info);
        if (count($info) < 9)
        {
            $info['error']='timeout';
        }
    }
    else //windows
    {
        exec("ping $ip -n $times",$info);
        if (count($info) < 10)
        {
            $info['error']='timeout';
        }
    }
    return $info;
}

function plang($str) {
    return lang('plugin/jzsjiale_sms', $str);
}
?>