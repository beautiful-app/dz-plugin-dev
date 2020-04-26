<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
loadcache('plugin');
global $_G, $lang;
class plugin_jzsjiale_sms {

    public function global_header() {
        global $_G;
    
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
        $g_pccss = "";
        if (!empty($_config['g_pccss'])){
            $g_pccss = $_config['g_pccss'];
        }
        
        if ($_config['g_isopenjs']){
            include_once template('jzsjiale_sms:jsbuchong');
            return $g_pccss.$jsbuchong;
        }else{
            return $g_pccss."";
        }
        
        
    }
    
    public function global_footer() {
        global $_G;
    
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
        if ($_config['g_isopenweijs']){
            include_once template('jzsjiale_sms:jsbuchong');
            return $jsbuchong;
        }else{
            return "";
        }
    
    
    }
    
    function global_login_extra() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
        if (!$_config['g_openpclogin']){
            if ($_config['g_openpczhaohui'] && !$_config['g_isopenjs'] && !$_config['g_isopenweijs']){
                include_once template('jzsjiale_sms:jsbuchong');
                return $jsbuchong;
            }else{
                return "";
            }
            
        }
        include_once template('jzsjiale_sms:loginbtn');
        return $loginbtn;
    }
    
    function logging_method() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
        if (!$_config['g_openpclogin']){
            return "";
        }//f r om ww w.m oqu8. co m
        
        if($_config['g_pcloginphonetab']){
            return "";
        }
        include_once template('jzsjiale_sms:loginbtn2');
        return $loginbtn2;
    }
    
    function register_logging_method() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
        if (!$_config['g_openpclogin']){
            return "";
        }
        include_once template('jzsjiale_sms:loginbtn2');
        return $loginbtn2;
    }
    public function global_usernav_extra1()
    {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
        if (!$_config['g_openpcbangding']){
            return "";
        }
        
        if (!$_G['uid']){
            return "";
        }
       
        if (!$_config['g_isopenbangdingbtn']){
            return "";
        }
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $phonebangding =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_uid($_G["uid"]);
            if (empty($phonebangding['mobile'])) {
                $extra1str = "<span class='pipe'>|</span><a class='phonebind_btn' href='".$_G['siteurl']."home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms:home'><img src='".$_G['siteurl']."source/plugin/jzsjiale_sms/static/images/sjbd.png' align='absmiddle' style='border-radius:2px;width:98px;height:20px;margin-top: -3px;margin-right: 3px;'/></a>";
                return $extra1str;
            }
            //20170805 add end
        }else{
            $phonebangding = C::t("#jzsjiale_sms#jzsjiale_sms_user")->fetch_by_uid($_G["uid"]);
            if (empty($phonebangding)) {
                $extra1str = "<span class='pipe'>|</span><a class='phonebind_btn' href='".$_G['siteurl']."home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms:home'><img src='".$_G['siteurl']."source/plugin/jzsjiale_sms/static/images/sjbd.png' align='absmiddle' style='border-radius:2px;width:98px;height:20px;margin-top: -3px;margin-right: 3px;'/></a>";
                return $extra1str;
            }
        }
        
        
        return "";
    }
    
    function common() {
     
        global $_G;
        
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        
        
        $jzsjiale_sms_seccode = daddslashes(getcookie('jzsjiale_sms_seccode'));
        $jzsjiale_sms_phone = daddslashes(getcookie('jzsjiale_sms_phone'));
        
        $userinfo = array();
        $istiaozhuan = false;
        
        $isregok = false;
        if($_config['g_tongyiuser']){
            //20170805 add start
            $userinfo =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_uid($_G['uid']);
           
            if(empty($userinfo['mobile'])){
                $istiaozhuan = true;
            }
            if ($_G['uid'] && $jzsjiale_sms_seccode && $jzsjiale_sms_phone && empty($userinfo['mobile'])) {
            
                $isregok = true;
            }
            //20170805 add end
        }else{
            $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_uid($_G['uid']);
            
            if(!$userinfo){
                $istiaozhuan = true;
            }
            if ($_G['uid'] && $jzsjiale_sms_seccode && $jzsjiale_sms_phone && !$userinfo) {
            
                $isregok = true;
            }
        }
        
        if($isregok){
            $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($jzsjiale_sms_phone,$jzsjiale_sms_seccode);
            
            if(!empty($codeinfo)){
                
                //weibaochitongbuxianshanchu
                C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($_G['uid']);
                
                $data = array(
                    'uid' => $_G['uid'],
                    'username' => $_G['username'],
                    'phone' => $codeinfo['phone'],
                    'dateline' => TIMESTAMP
                );
            
                C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data,true);
            
                C::t('common_member_profile')->update($_G['uid'], array('mobile'=> $codeinfo['phone']));
            
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($jzsjiale_sms_phone,$jzsjiale_sms_seccode);
            
                
                //verify start
                if($_config['g_isopenautoverify'] && $_config['g_mobileverify']){
                    $verifyuid = $_G['uid'];
                    $memberautoverify = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$verifyuid);
                    if(empty($memberautoverify)){
                        C::t('common_member_verify')->insert(array('uid' => $verifyuid,'verify'.$_config['g_mobileverify'] => 1));
                    }else{
                        C::t('common_member_verify')->update($verifyuid, array('verify'.$_config['g_mobileverify'] => 1));
                    }
                    
                }
                
                
                //verify end
                
                
                $istiaozhuan = false;
                
                dsetcookie('jzsjiale_sms_phone');
                dsetcookie('jzsjiale_sms_seccode');
            }
        }
      
        
        //20171208 update lang cache start
        if($_config['g_isopenyuyan']){
            loadcache('pluginlanguage_template');
            if(!$_G['cache']['pluginlanguage_template']['jzsjiale_sms']['diylang']){
                $db_smstmplang = C::t('#jzsjiale_sms#jzsjiale_sms_tpllang')->getall();
        
                foreach ($db_smstmplang as $stllang){
        
                    $_G['cache']['pluginlanguage_template']['jzsjiale_sms'][$stllang['enname']]=$stllang['cnname'];
                }
        
                $_G['cache']['pluginlanguage_template']['jzsjiale_sms']['diylang']=1;
                savecache('pluginlanguage_template', $_G['cache']['pluginlanguage_template']);
        
            }
        }
        //20171208 update lang cache end
    
        
        //qiangzhibangding
        $groupid = $_G['groupid'];
        $basescript = $_G['basescript'];
        $curm = CURMODULE;
        //echo "===".$basescript."----".$curm;
        $g_notqzuidbai = array();
        if(!empty($_config['g_notqzuidbai'])){
            $g_notqzuidbai = explode(",",$_config['g_notqzuidbai']);
        }
        
        if ($_G['uid'] && $_config['g_pcqiangzhibangding'] && !in_array($_G['uid'],$g_notqzuidbai) && in_array($groupid, (array) unserialize($_config['g_qiangzhigroups'])) && $istiaozhuan && ($basescript == "portal" || $basescript == "forum" || $basescript == "group" )){
            
            if($_config['g_pconlyfatie']){
                if(($basescript == "forum" && $curm == "post") || ($basescript == "group" && $curm == "post")){
                    if($_GET['action'] == "reply" && $_GET['infloat'] == "yes"){
                        showmessage('<a href="home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms&#58;home"><span style="color&#58;red;">'.$_config['g_pcqiangzhitip'].'</span></a>', 'home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms:home', array(), array('alert' => 'right', 'showdialog' => 1, 'locationtime' => true));
                    }elseif($_GET['action'] == "newthread" && $_GET['infloat'] == "yes"){
                        showmessage('<a href="home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms&#58;home"><span style="color&#58;red;">'.$_config['g_pcqiangzhitip'].'</span></a>', 'home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms:home', array(), array('alert' => 'right', 'showdialog' => 1, 'locationtime' => true));
                    }else{
                        showmessage($_config['g_pcqiangzhitip'], 'home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms:home', 'succeed');
                    }
                }
                if($basescript == "portal" && $curm == "portalcp"){
                    if($_GET['ac'] == "article" || $_GET['ac'] == "comment"){
                        showmessage($_config['g_pcqiangzhitip'], 'home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms:home', 'succeed');
                    }
                    
                }
                if($basescript == "forum" && $curm == "collection"){
                    if($_GET['inajax']){
                        showmessage('<script type="text/javascript" reload="1">alert("'.$_config['g_pcqiangzhitip'].'");window.location.href="home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms:home";</script>', '', array(), array('msgtype'	=> 3),1);
                    }else{
                        showmessage($_config['g_pcqiangzhitip'], 'home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms:home', 'succeed');
                    }
                }
            }else{
                showmessage($_config['g_pcqiangzhitip'], 'home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms:home', 'succeed');
            }
            
            if($_config['g_pcpingfenjiance']){
                if(($basescript == "forum" && $curm == "misc") || ($basescript == "group" && $curm == "misc")){
                    showmessage('<a href="home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms&#58;home"><span style="color&#58;red;">'.$_config['g_pcqiangzhitip'].'</span></a>', 'home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms:home', array(), array('alert' => 'right', 'showdialog' => 1, 'locationtime' => true));
                }
            }
            
       
        }elseif($_G['uid'] && $_config['g_pcqiangzhibangding'] && !in_array($_G['uid'],$g_notqzuidbai) && in_array($groupid, (array) unserialize($_config['g_qiangzhigroups'])) && $istiaozhuan && $basescript == "plugin" && $_config['g_isopendsuqdpc'] && $curm == "dsu_paulsign" && $_GET['operation'] == "qiandao"){
            showmessage('<a href="home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms&#58;home"><span style="color&#58;red;">'.$_config['g_pcqiangzhitip'].'</span></a>');
        
        }elseif($_G['uid'] && $_config['g_pcqiangzhibangding'] && !in_array($_G['uid'],$g_notqzuidbai) && in_array($groupid, (array) unserialize($_config['g_qiangzhigroups'])) && $istiaozhuan && $_config['g_isopenpcdxxqz'] && $basescript == "home" && $curm == "spacecp" && $_GET['ac'] == "pm" && $_GET['op'] == "send" && ($_GET['pmsubmit'] == "yes" || $_GET['pmsubmit'] == "true")){
            
            if($_GET['inajax']){
                showmessage('<script type="text/javascript" reload="1">alert("'.$_config['g_pcqiangzhitip'].'");window.location.href="home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms:home";</script>', '', array(), array('msgtype'	=> 3),1);
            }else{
                showmessage($_config['g_pcqiangzhitip'], 'home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms:home', 'succeed');
            }
            
        }elseif($_G['uid'] && $_config['g_pcqiangzhibangding'] && !in_array($_G['uid'],$g_notqzuidbai) && in_array($groupid, (array) unserialize($_config['g_qiangzhigroups'])) && $istiaozhuan && $_config['g_isopenkjhdqz'] && $basescript == "home" && ($curm == "spacecp" || $curm == "space" || $curm == "follow") && in_array($_GET['ac'],array("profile","avatar","usergroup","privacy","blog","comment","click","doing","follow","share"))){
           
            if($_GET['inajax']){
                showmessage('<script type="text/javascript" reload="1">alert("'.$_config['g_pcqiangzhitip'].'");window.location.href="home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms:home";</script>', '', array(), array('msgtype'	=> 3),1);
            }else{
                showmessage($_config['g_pcqiangzhitip'], 'home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms:home', 'succeed');
            }
            
        }
        
    }
    

}

class plugin_jzsjiale_sms_forum extends plugin_jzsjiale_sms
{
    public function viewthread_fastpost_content() {
        global $_G;
    
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
        $groupid = $_G['groupid'];
        if ($_config['g_isopenhuitietip'] && $_G['uid'] && in_array($groupid, (array) unserialize($_config['g_qiangzhigroups']))){
            if($_config['g_tongyiuser']){
                //20171010 add start
                $phonebangding =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_uid($_G["uid"]);
                if (empty($phonebangding['mobile'])) {
                    return $_config['g_huitieshangfangtip'];
                }
                //20171010 add end
            }else{
                $phonebangding = C::t("#jzsjiale_sms#jzsjiale_sms_user")->fetch_by_uid($_G["uid"]);
                if (empty($phonebangding)) {
                    return $_config['g_huitieshangfangtip'];
                }
            }
        }
        
    }
}
class plugin_jzsjiale_sms_member extends plugin_jzsjiale_sms
{
    
    function register_input_output() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
     
        
        if (!$_config['g_openpcregister']) {
            return;
        }
        
        if ($_config['g_pcregphonetab'] && $_config['g_pcregqzmobile']) {
            return;
        }
        
        if (!$_config['g_pcregemailphone']) {
            return;
        }
        include_once template('jzsjiale_sms:register');
        return $register;
    }
    
    //20171108 add
    function register_bottom_output() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
         
    
        if (!$_config['g_openpcregister']) {
            return;
        }
        
        if ($_config['g_pcregphonetab'] && $_config['g_pcregqzmobile']) {
            return;
        }
        
        include_once template('jzsjiale_sms:registertop');
        return $registertop;
    }
    
    function register()
    {
        
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        if($_G['uid']){
            return;
        }
        
        if (!$_config['g_openpcregister']) {
            return;
        }
      
        
        if (!$_config['g_pcregqzmobile']) {
            
            if (!$_config['g_pcregemailphone']) {
                return;
            }
            
            if (submitcheck('regsubmit', 0, $seccodecheck, $secqaacheck)) {
                $phone = addslashes($_GET['phone_reg']);
                $seccode = addslashes($_GET['seccode_reg']);
        
                /*
                 if (!$phone || !$seccode) {
                 showmessage(lang('plugin/jzsjiale_sms', 'paramerror'));
                 }
                */
                if(empty($phone)){
                    showmessage(lang('plugin/jzsjiale_sms', 'phonenull'));
                }
                 
                if (!preg_match("/^1[123456789]{1}\d{9}$/", $phone)) {
                    showmessage(lang('plugin/jzsjiale_sms', 'bind_phone_error'));
                }
            
                if(empty($seccode)){
                    showmessage(lang('plugin/jzsjiale_sms', 'seccodenull'));
                }
            
                $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($phone,$seccode);
                if ($codeinfo) {
                    if ((TIMESTAMP - $codeinfo[dateline]) > $_config['g_youxiaoqi']) {
                        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                        //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
                        showmessage(lang('plugin/jzsjiale_sms', 'err_seccodeguoqi'));
                    }
                } else {
                    showmessage(lang('plugin/jzsjiale_sms', 'err_seccodeerror'));
                }
            
            
                if($_config['g_tongyiuser']){
                    //20170805 add start
                    $phoneuser =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
                    //20170805 add end
                }else{
                    $phoneuser = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
                }
            
            
                if(!empty($phoneuser)){
                    showmessage(lang('plugin/jzsjiale_sms', 'phonecunzai'));
                }
            
            
            
                dsetcookie('jzsjiale_sms_seccode', $seccode, 600);
                dsetcookie('jzsjiale_sms_phone', $phone, 600);
                 
            }
        }else{
            
            if (!$_config['g_pcregphonetab']) {
                return;
            }
            
            define('NOROBOT', TRUE);
            
            require_once('class/class_member.php');
            $_G['setting']['sendregisterurl']=1;
            
            $ctl_obj = new  mobile_register_ctl();
            $ctl_obj->setting = $_G['setting'];
            $ctl_obj->template = 'jzsjiale_sms:alone/register';
            $ctl_obj->on_register();
            exit;
        }
        
        
    }
    
    function logging() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];

        if($_G['uid']){
            return;
        }
        
        if($_G['setting']['bbclosed']){
            return;
        }
        
        if($_GET['action'] == "logout"){
            return;
        }
        
        if($_config['g_openpczhaohui'] && !$_G['uid'] && empty($_GET['popup']) && isset($_GET['viewlostpw']) && !$_GET['byemail'] && $_GET['loginsubmit'] != 'yes' && $_GET['loginsubmit'] != 'true' && $_GET['lssubmit'] != 'yes' && empty($_GET['auth'])){
            include template('jzsjiale_sms:lostpw');
            exit;
        }elseif($_config['g_openpczhaohui'] && !$_G['uid'] && !empty($_GET['popup']) && $_GET['popup']=='no' && isset($_GET['viewlostpw']) && !$_GET['byemail'] && $_GET['loginsubmit'] != 'yes' && $_GET['loginsubmit'] != 'true' && $_GET['lssubmit'] != 'yes' && empty($_GET['auth'])){
            include template('jzsjiale_sms:lostpw2');
            exit;
        }elseif(!$_config['g_phonemimalogin'] && $_config['g_openpclogin'] && !$_G['uid'] && !empty($_GET['phonelogin']) && $_GET['phonelogin'] == "yes" && empty($_GET['infloat']) && $_GET['loginsubmit'] != 'yes' && $_GET['loginsubmit'] != 'true' && $_GET['lssubmit'] != 'yes' && empty($_GET['auth']) && !isset($_GET['viewlostpw'])){
            include template('jzsjiale_sms:login2');
            exit;
        }elseif(!$_config['g_phonemimalogin'] && $_config['g_openpclogin'] && !$_G['uid'] && $_config['g_pcmorenphonelogin'] && $_GET['phonelogin'] != "no" && empty($_GET['infloat']) && $_GET['loginsubmit'] != 'yes' && $_GET['loginsubmit'] != 'true' && $_GET['lssubmit'] != 'yes' && empty($_GET['auth']) && !isset($_GET['viewlostpw'])){
            include template('jzsjiale_sms:login2');
            exit;
        }elseif(!$_config['g_phonemimalogin'] && $_config['g_openpclogin'] && !$_G['uid'] && $_config['g_pcmorenphonelogin'] && $_GET['phonelogin'] != "no" && !empty($_GET['infloat']) && $_GET['loginsubmit'] != 'yes' && $_GET['loginsubmit'] != 'true' && $_GET['lssubmit'] != 'yes' && empty($_GET['auth']) && !isset($_GET['viewlostpw'])){
            include template('jzsjiale_sms:login');
            exit;
        }elseif($_config['g_phonemimalogin'] && $_config['g_openpclogin'] && !$_G['uid'] && !empty($_GET['phonelogin']) && $_GET['phonelogin'] == "yes" && empty($_GET['infloat']) && $_GET['loginsubmit'] != 'yes' && $_GET['loginsubmit'] != 'true' && $_GET['lssubmit'] != 'yes' && empty($_GET['auth']) && !isset($_GET['viewlostpw'])){
            include template('jzsjiale_sms:login2mima');
            exit;
        }elseif($_config['g_phonemimalogin'] && $_config['g_openpclogin'] && !$_G['uid'] && $_config['g_pcmorenphonelogin'] && $_GET['phonelogin'] != "no" && empty($_GET['infloat']) && $_GET['loginsubmit'] != 'yes' && $_GET['loginsubmit'] != 'true' && $_GET['lssubmit'] != 'yes' && empty($_GET['auth']) && !isset($_GET['viewlostpw'])){
            include template('jzsjiale_sms:login2mima');
            exit;
        }elseif($_config['g_phonemimalogin'] && $_config['g_openpclogin'] && !$_G['uid'] && $_config['g_pcmorenphonelogin'] && $_GET['phonelogin'] != "no" && !empty($_GET['infloat']) && $_GET['loginsubmit'] != 'yes' && $_GET['loginsubmit'] != 'true' && $_GET['lssubmit'] != 'yes' && empty($_GET['auth']) && !isset($_GET['viewlostpw'])){
            include template('jzsjiale_sms:loginmima');
            exit;
        }else{
            return;
        }
    }
    
    
    //20171130 add
    function logging_top() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
         
        if ($_config['g_openpclogin'] && $_config['g_pcloginphonetab']) {
            include_once template('jzsjiale_sms:logintop');
            return $logintop;
        }else{
            return "";
        }
        
    }
   
}

class plugin_jzsjiale_sms_home extends plugin_jzsjiale_sms {

    function spacecp_profile_bottom_output() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];

        if (!$_G['uid']){
            return;
        }
        
        if (!$_config['g_openpcbangding']){
            return;
        }
        

        if($_config['g_openpcbangding']) {
            include_once template('jzsjiale_sms:profilemobile');
            return $profilemobile;
        }

    }

}
?>