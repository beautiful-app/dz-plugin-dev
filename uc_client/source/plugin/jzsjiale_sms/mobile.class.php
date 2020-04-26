<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class mobileplugin_jzsjiale_sms {
   
    function common() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
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
        
        
        $userinfo = array();
        $istiaozhuan = false;
        
        
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $userinfo =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_uid($_G['uid']);
        
            if(empty($userinfo['mobile'])){
                $istiaozhuan = true;
            }
            //20170805 add end
        }else{
            $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_uid($_G['uid']);
        
            if(!$userinfo){
                $istiaozhuan = true;
            }
        }
  
        $groupid = $_G['groupid'];
        $basescript = $_G['basescript'];
        $curm = CURMODULE;
        
        $g_notqzuidbai = array();
        if(!empty($_config['g_notqzuidbai'])){
            $g_notqzuidbai = explode(",",$_config['g_notqzuidbai']);
        }
        
        if ($_G['uid'] && $_config['g_mqiangzhibangding'] && !in_array($_G['uid'],$g_notqzuidbai) && in_array($groupid, (array) unserialize($_config['g_qiangzhigroups'])) && $istiaozhuan && ($basescript == "portal" || $basescript == "forum" || $basescript == "group" )){
             
            if($_config['g_monlyfatie']){
                if(($basescript == "forum" && $curm == "post") || ($basescript == "group" && $curm == "post")){
                    if($_GET['action'] == "reply" && $_GET['infloat'] == "yes"){
                        showmessage('<a href="plugin.php?id=jzsjiale_sms&#58;mobile&act=bangding&qiangzhibangding=yes"><span style="color&#58;red;">'.$_config['g_mqiangzhitip'].'</span></a>', 'plugin.php?id=jzsjiale_sms:mobile&act=bangding&qiangzhibangding=yes', array(), array('alert' => 'right', 'showdialog' => 1, 'locationtime' => true));
                    }elseif($_GET['action'] == "newthread" && $_GET['infloat'] == "yes"){
                        showmessage('<a href="plugin.php?id=jzsjiale_sms&#58;mobile&act=bangding&qiangzhibangding=yes"><span style="color&#58;red;">'.$_config['g_mqiangzhitip'].'</span></a>', 'plugin.php?id=jzsjiale_sms:mobile&act=bangding&qiangzhibangding=yes', array(), array('alert' => 'right', 'showdialog' => 1, 'locationtime' => true));
                    }else{
                        //showmessage($_config['g_mqiangzhitip'], 'plugin.php?id=jzsjiale_sms:mobile&act=bangding&qiangzhibangding=yes', 'succeed');
                        //showmessage('<script type="text/javascript" reload="1">alert("'.$_config['g_mqiangzhitip'].'");window.location.href=\'plugin.php?id=jzsjiale_sms:mobile&act=bangding&qiangzhibangding=yes\';</script>', '', array(), array('msgtype'	=> 3),1);
                        if(defined('IN_MOBILE_API')){
                            showmessage($_config['g_mqiangzhitip'], 'plugin.php?id=jzsjiale_sms:mobile&act=bangding&qiangzhibangding=yes', 'succeed');
                        }else{
                            showmessage('<script type="text/javascript" reload="1">alert("'.$_config['g_mqiangzhitip'].'");window.location.href="plugin.php?id=jzsjiale_sms:mobile&act=bangding&qiangzhibangding=yes";</script>', '', array(), array('msgtype'	=> 3),1);
                        }
                        
                    }
                }
                if($basescript == "portal" && $curm == "portalcp"){
                    if($_GET['ac'] == "article" || $_GET['ac'] == "comment"){
                        showmessage($_config['g_mqiangzhitip'], 'plugin.php?id=jzsjiale_sms:mobile&act=bangding&qiangzhibangding=yes', 'succeed');
                    }
                    
                }
                if($basescript == "forum" && $curm == "collection"){
                    showmessage($_config['g_mqiangzhitip'], 'plugin.php?id=jzsjiale_sms:mobile&act=bangding&qiangzhibangding=yes', 'succeed');
                }
            }else{
                showmessage($_config['g_mqiangzhitip'], 'plugin.php?id=jzsjiale_sms:mobile&act=bangding&qiangzhibangding=yes', 'succeed');
            }
            
        }elseif ($_G['uid'] && $_config['g_mqiangzhibangding'] && !in_array($_G['uid'],$g_notqzuidbai) && in_array($groupid, (array) unserialize($_config['g_qiangzhigroups'])) && $istiaozhuan && $basescript == "plugin" && $_config['g_isopenxiguawsq'] && $curm == "xigua_wsq" && $_GET['a'] == "newthread"){
            showmessage($_config['g_mqiangzhitip'], 'plugin.php?id=jzsjiale_sms:mobile&act=bangding&qiangzhibangding=yes', 'succeed');
        }elseif ($_G['uid'] && $_config['g_mqiangzhibangding'] && !in_array($_G['uid'],$g_notqzuidbai) && in_array($groupid, (array) unserialize($_config['g_qiangzhigroups'])) && $istiaozhuan && $basescript == "plugin" && $_config['g_isopendsuqdm'] && $curm == "dsu_paulsign" && $_GET['operation'] == "qiandao"){
            showmessage($_config['g_mqiangzhitip'], 'plugin.php?id=jzsjiale_sms:mobile&act=bangding&qiangzhibangding=yes', 'succeed');
        }elseif ($_G['uid'] && $_config['g_mqiangzhibangding'] && !in_array($_G['uid'],$g_notqzuidbai) && in_array($groupid, (array) unserialize($_config['g_qiangzhigroups'])) && $istiaozhuan && $_config['g_isopenmdxxqz'] && $basescript == "home" && $curm == "spacecp" && $_GET['ac'] == "pm" && $_GET['op'] == "send" && ($_GET['pmsubmit'] == "yes" || $_GET['pmsubmit'] == "true")){
            showmessage($_config['g_mqiangzhitip'], 'plugin.php?id=jzsjiale_sms:mobile&act=bangding&qiangzhibangding=yes', 'succeed'); 
        }elseif ($_G['uid'] && $_config['g_mqiangzhibangding'] && !in_array($_G['uid'],$g_notqzuidbai) && in_array($groupid, (array) unserialize($_config['g_qiangzhigroups'])) && $istiaozhuan && $_config['g_isopenkjhdqz'] && $basescript == "home" && ($curm == "spacecp" || $curm == "space" || $curm == "follow") && in_array($_GET['ac'],array("profile","avatar","usergroup","privacy","blog","comment","click","doing","follow","share"))){
            showmessage($_config['g_mqiangzhitip'], 'plugin.php?id=jzsjiale_sms:mobile&act=bangding&qiangzhibangding=yes', 'succeed');
        }
    }
    
    function global_footer_mobile() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
        if (!$_G['uid']){
            return;
        }
        
        $basescript = $_G['basescript'];
        $curm = CURMODULE;
        
        if($basescript != "home" || $curm != "space" || empty($_GET[mycenter])){
            return;
        }
    
        if (!$_config['g_openmobilebangding']){
            return;
        }
        
        
        if($_config['g_openmobilebangdingbtn']) {
            $g_mstyle = $_config['g_mstyle'];
            if (empty($g_mstyle)){
                $g_mstyle = 'v1';
            }
            $g_mstyle = $g_mstyle.'/';
    
            include_once template('jzsjiale_sms:'.$g_mstyle.'bangdingbtn');
            return $bangdingbtn;
        }
    
    }
}

class mobileplugin_jzsjiale_sms_member extends mobileplugin_jzsjiale_sms
{
    function register()
    {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
      
        $formhash =  addslashes($_GET['formhash'])? addslashes($_GET['formhash']):'';
        
        
        if(!$_config['g_openmobileregister']){
            return;
        }
        
        if (!$_config['g_mregqzmobile']) {
            //if($formhash == FORMHASH || $_GET['regsubmit'] == yes || $_GET['regsubmit'] == true){
            if (submitcheck('regsubmit', 0, $seccodecheck, $secqaacheck)) {
                showmessage('Illegal registration', 'plugin.php?id=jzsjiale_sms:mobile&act=register', 'succeed');
            }
            
            //member.php?mod=$_G['setting']['regname']&mobile=2&phoneregister=no
            // && $_GET["phoneregister"] != "no"
            if((isset($_GET["mobile"]) || $this->is_mobile())) {
                $url ="plugin.php?id=jzsjiale_sms:mobile&act=register";
                header("Location: $url");
                die(0);
            
            }
        }else{
            define('NOROBOT', TRUE);
            
            require_once('class/class_member.php');
            $_G['setting']['sendregisterurl']=1;
            
            $mobilecolor = !empty($_config['g_mobilecolor'])?$_config['g_mobilecolor']:"#4eabe8";
            $mlogowidth = !empty($_config['g_mlogowidth'])?$_config['g_mlogowidth']:"3rem";
            $mlogoheight = !empty($_config['g_mlogoheight'])?$_config['g_mlogoheight']:"1rem";
            
            $g_mstyle = $_config['g_mstyle'];
            if (empty($g_mstyle)){
                $g_mstyle = 'v1';
            }
            $g_mstyle = $g_mstyle.'/';
            
            $ctl_obj = new  mobile_register_ctl();
            $ctl_obj->setting = $_G['setting'];
            $ctl_obj->mobilecolor = $mobilecolor;
            $ctl_obj->mlogowidth = $mlogowidth;
            $ctl_obj->mlogoheight = $mlogoheight;
            $ctl_obj->template = 'jzsjiale_sms:'.$g_mstyle.'alone/register';
            $ctl_obj->on_register();
            exit;
        }
        
    }
    
    function logging()
    {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
        if($_G['setting']['bbclosed']){
            return;
        }
        
        if(!$_config['g_openmobilelogin']){
            return;
        }
      
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false && $_config['g_wxautologin']){
            return;
        }
        
        if((isset($_GET["mobile"]) || $this->is_mobile()) && $_config['g_mmorenphonelogin'] && isset($_GET["action"]) && $_GET["action"]=="login" && $_GET["phonelogin"] != "no" && $_GET["loginsubmit"] != "yes" && $_GET["inajax"] != "yes" && $_GET["inajax"] != "1" && $_GET["inajax"] != "true" && $_G['group']['allowvisit']) {
            $url ="plugin.php?id=jzsjiale_sms:mobile&act=login";
            header("Location: $url");
            die(0);
        }elseif((isset($_GET["mobile"]) || $this->is_mobile()) && $_config['g_mmorenphonelogin'] && isset($_GET["action"]) && $_GET["action"]=="login" && $_GET["phonelogin"] == "no" && $_GET["loginsubmit"] != "yes" && $_GET["inajax"] != "yes" && $_GET["inajax"] != "1" && $_GET["inajax"] != "true" && $_G['group']['allowvisit'] && $_config['g_isopenqitalogin'] && $_config['g_mloginusernameplugin']){
            $url ="plugin.php?id=jzsjiale_sms:mobile&act=usernamelogin";
            header("Location: $url");
            die(0);
        }elseif((isset($_GET["mobile"]) || $this->is_mobile()) && !$_config['g_mmorenphonelogin'] && $_config['g_mmorenpluginlogin'] && isset($_GET["action"]) && $_GET["action"]=="login" && $_GET["phonelogin"] != "yes" && $_GET["loginsubmit"] != "yes" && $_GET["inajax"] != "yes" && $_GET["inajax"] != "1" && $_GET["inajax"] != "true" && $_G['group']['allowvisit'] && $_config['g_mloginusernameplugin']){
            $url ="plugin.php?id=jzsjiale_sms:mobile&act=usernamelogin";
            header("Location: $url");
            die(0);
        }elseif((isset($_GET["mobile"]) || $this->is_mobile()) && !$_config['g_mmorenphonelogin'] && $_config['g_mmorenpluginlogin'] && isset($_GET["action"]) && $_GET["action"]=="login" && $_GET["phonelogin"] != "no" && $_GET["loginsubmit"] != "yes" && $_GET["inajax"] != "yes" && $_GET["inajax"] != "1" && $_GET["inajax"] != "true" && $_G['group']['allowvisit'] && $_config['g_mloginusernameplugin']){
            $url ="plugin.php?id=jzsjiale_sms:mobile&act=login";
            header("Location: $url");
            die(0);
        }else{
            return;
        }
    }
    
    
    function logging_bottom_mobile() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
        if (!$_config['g_openmobilelogin']){
            return "";
        }
        if (!$_config['g_isopenmobileloginbtn']){
            return "";
        }
        
        $g_mstyle = $_config['g_mstyle'];
        if (empty($g_mstyle)){
            $g_mstyle = 'v1';
        }
        $g_mstyle = $g_mstyle.'/';
        include_once template('jzsjiale_sms:'.$g_mstyle.'loginbtn');
        return $loginbtn;
    }
    
    function is_mobile() {
        if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
            $is_mobile = false;
        } elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
            $is_mobile = true;
        } else {
            $is_mobile = false;
        }
    
        return $is_mobile;
    }
    
   
}
?>