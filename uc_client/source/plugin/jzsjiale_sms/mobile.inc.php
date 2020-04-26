<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$act = addslashes($_GET['act']);
$formhash =  addslashes($_GET['formhash'])? addslashes($_GET['formhash']):'';
loadcache('plugin');
global $_G, $lang;

if ($act == 'register') {
    
    if ($formhash == FORMHASH && $_GET['phoneregistersubmit']) {
        $phone = addslashes($_GET['phone']);
        $seccode = addslashes($_GET['seccode']);
        $username = addslashes($_GET['username']);
        $password = addslashes($_GET['password']);
        
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        
        if(!$_config['g_openmobileregister']){
            echo json_encode(array('code' => -1,'data' => 'notopenmobile'));
            exit;
        }
        
        if ($_config['g_mregqzmobile']) {
            echo json_encode(array('code' => -1,'data' => 'registererror'));
            exit;
        }
        
        if(empty($phone)){
            echo json_encode(array('code' => -1,'data' => 'phonenull'));
            exit;
        }
        
        if (!preg_match("/^1[123456789]{1}\d{9}$/", $phone)) {
            echo json_encode(array('code' => -1,'data' => 'bind_phone_error'));
            exit;
        }
        
        if(empty($seccode)){
            echo json_encode(array('code' => -1,'data' => 'seccodenull'));
            exit;
        }
        
        
        if(!$_config['g_mregphoneusername']){
            if($_config['g_phoneisusername']){
                $username = $phone;
            }else{
                $username = $_config['g_mregphoneqianzhui'].get_rand_username(8);
            }
        }else{
            if(empty($username)){
                echo json_encode(array('code' => -1,'data' => 'usernamenull'));
                exit;
            }
        }
        
        
        
        if(empty($password)){
            echo json_encode(array('code' => -1,'data' => 'passwordnull'));
            exit;
        }
        
        
        if($_config['g_isopenmemail']){
            
            $email = addslashes($_GET['email']);
            if(empty($email)){
                echo json_encode(array('code' => -1,'data' => 'emailnull'));
                exit;
            }
            
            if (!preg_match("/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/", $email)) {
                echo json_encode(array('code' => -1,'data' => 'emailerr'));
                exit;
            }
        }
        
        
        
        $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($phone,$seccode);
        if ($codeinfo) {
            if ((TIMESTAMP - $codeinfo[dateline]) > $_config['g_youxiaoqi']) {
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
                echo json_encode(array('code' => -1,'data' => 'err_seccodeguoqi'));
                exit;
            }
        } else {
            echo json_encode(array('code' => -1,'data' => 'err_seccodeerror'));
            exit;
        }
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $phoneuser =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
            //20170805 add end
        }else{
            $phoneuser = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
        }
        
        if(!empty($phoneuser)){
            echo json_encode(array('code' => -1,'data' => 'phonecunzai'));
            exit;
        }
        
        $username = iconv('UTF-8', CHARSET.'//ignore', urldecode($username));
        
        if(!$_config['g_isopenmemail']){
            $email = "reg_".substr($phone,0,3).time().substr($phone,7,4)."@null.null";
        }
        
        
        $user = C::t('common_member')->fetch_by_username($username);
        if($user){
            if(!$_config['g_mregphoneusername']){
                $username = $_config['g_mregphoneqianzhui'].get_rand_username(8);
            
                $user = C::t('common_member')->fetch_by_username($username);
                if($user){
                    echo json_encode(array('code' => -1,'data' => 'usernamecunzai'));
                    exit;
                }
            }else{
                echo json_encode(array('code' => -1,'data' => 'usernamecunzai'));
                exit;
            }
            
        }
        
        $profile = array (
            "mobile" => $phone,
        );
        require_once DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/uc.inc.php';
        
        $isreturnuid = 0;
        $isopenmemail = 0;
        if($_config['g_isopenmemail']){
            $isreturnuid = 1;
            $isopenmemail = 1;
        }
        if(!$_config['g_discuzf']){
            $uid = UC::regist($username,$password,$email,$profile,$isreturnuid,$isopenmemail);
        }else{
            $uid = UC::regist_new($username,$password,$email,$codeinfo['phone'],$profile,$isreturnuid,$isopenmemail);
        }
        
        if (!is_numeric($uid)) {
            if($uid == "username_len_invalid"){
                echo json_encode(array('code' => -1,'data' => 'username_len_invalid_error'));
                exit;
            }elseif($uid == "password_len_invalid"){
                echo json_encode(array('code' => -1,'data' => 'password_len_invalid_error'));
                exit;
            }else{
                echo json_encode(array('code' => -1,'data' => 'registererror'));
                exit;
            }
            
        }else{
            if ($uid<=0) {
                switch ($uid) {
                    case -4:echo json_encode(array('code' => -1,'data' => 'registeremailillegal')); exit(); break;
                    case -5:echo json_encode(array('code' => -1,'data' => 'registeremailillegal')); exit(); break;
                    case -6:echo json_encode(array('code' => -1,'data' => 'registeremailduplicate')); exit(); break;
                    default: echo json_encode(array('code' => -1,'data' => 'registererror')); exit(); break;
                };
            }
        }
        
        
        $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_uid($uid);
         
        
        if ($uid && $phone && $seccode && !$userinfo) {
        
            //weibaochitongbuxianshanchu
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($_G['uid']);
            
            $data = array(
                'uid' => $_G['uid'],
                'username' => $_G['username'],
                'phone' => $codeinfo['phone'],
                'dateline' => TIMESTAMP
            );
            
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data, true);
            
            C::t('common_member_profile')->update($_G['uid'], array('mobile'=> $phone));
            
            C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
            
            
            
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
            
            
            //send email
            if($_config['g_isopenmemail']){
                
                if($_G['setting']['regverify'] == 1 || $_G['setting']['regverify'] == "1"){
                    
                    if($_G[uid] && $email) {
                        $hash = authcode("$_G[uid]\t$email\t$_G[timestamp]", 'ENCODE', md5(substr(md5($_G['config']['security']['authkey']), 0, 16)));
                        $verifyurl = $_G['siteurl'].'home.php?mod=misc&amp;ac=emailcheck&amp;hash='.urlencode($hash);
                        $mailsubject = lang('email', 'email_verify_subject');
                        $mailmessage = lang('email', 'email_verify_message', array(
                            'username' => $_G['member']['username'],
                            'bbname' => $_G['setting']['bbname'],
                            'siteurl' => $_G['siteurl'],
                            'url' => $verifyurl
                        ));
                    
                        if(!function_exists('sendmail')) {
                            include libfile('function/mail');
                        }
                        if(!sendmail($email, $mailsubject, $mailmessage)) {
                            echo json_encode(array(
                                'code' => 200,
                                'data' => 'registersuccesssendemailerror'
                            ));
                            exit();
                        }else{
                            echo json_encode(array(
                                'code' => 200,
                                'data' => 'registersuccessemailyanzheng'
                            ));
                            exit();
                        }
                    }
                    
                
                }
            }
            
            
            //xiaoxi start
            $welcomemsg = $_G['setting']['welcomemsg'];
            $welcomemsgtitle = $_G['setting']['welcomemsgtitle'];
            $welcomemsgtxt = $_G['setting']['welcomemsgtxt'];
            
            if($welcomemsg && !empty($welcomemsgtxt)) {
                $welcomemsgtitle = replacesitevar($welcomemsgtitle);
                $welcomemsgtxt = replacesitevar($welcomemsgtxt);
                if($welcomemsg == 1) {
                    $welcomemsgtxt = nl2br(str_replace(':', '&#58;', $welcomemsgtxt));
                    notification_add($uid, 'system', $welcomemsgtxt, array('from_id' => 0, 'from_idtype' => 'welcomemsg'), 1);
                } elseif($welcomemsg == 2) {
                    sendmail_cron($email, $welcomemsgtitle, $welcomemsgtxt);
                } elseif($welcomemsg == 3) {
                    sendmail_cron($email, $welcomemsgtitle, $welcomemsgtxt);
                    $welcomemsgtxt = nl2br(str_replace(':', '&#58;', $welcomemsgtxt));
                    notification_add($uid, 'system', $welcomemsgtxt, array('from_id' => 0, 'from_idtype' => 'welcomemsg'), 1);
                }
            }
            
            //xiaoxi end
            
            
            echo json_encode(array(
                'code' => 200,
                'data' => 'registersuccess'
            ));
            exit();
        
        }else{
            C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
            echo json_encode(array(
                'code' => 200,
                'data' => 'registersuccess_phoneerror'
            ));
            exit();
        }
        
    }else{
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        $mobilecolor = !empty($_config['g_mobilecolor'])?$_config['g_mobilecolor']:"#4eabe8";
        $mlogowidth = !empty($_config['g_mlogowidth'])?$_config['g_mlogowidth']:"3rem";
        $mlogoheight = !empty($_config['g_mlogoheight'])?$_config['g_mlogoheight']:"1rem";
        
        if ($_config['g_mregqzmobile']) {
            $url ="member.php?mod=".$_G['setting']['regname'];
            header("Location: $url");
            die(0);
        }
        if(!$_G['uid'] && $_config['g_openmobileregister']){
            
            $g_mstyle = $_config['g_mstyle'];
            if (empty($g_mstyle)){
                $g_mstyle = 'v1';
            }
            $g_mstyle = $g_mstyle.'/';
            include template('jzsjiale_sms:'.$g_mstyle.'register');
        }else{
            dheader("Location: home.php?mod=space&do=profile&uid=".$_G['uid']);
            dexit();
        }
        
    }
}elseif ($act == 'phonemimalogin') {
    
   
    require_once libfile('function/misc');
    loaducenter();
   
    require_once libfile('function/member');
    
    if ($formhash == FORMHASH && $_GET['phoneloginsubmit']) {
        $phone = addslashes($_GET['phone']);
        $phone_password = daddslashes($_GET['phone_password']);
   
        
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        
        if(!$_config['g_openmobilelogin']){
            echo json_encode(array('code' => -1,'data' => 'notopenmobile'));
            exit;
        }
        
        if(empty($phone)){
            echo json_encode(array('code' => -1,'data' => 'phonenull'));
            exit;
        }
        
        if (!preg_match("/^1[123456789]{1}\d{9}$/", $phone)) {
            echo json_encode(array('code' => -1,'data' => 'bind_phone_error'));
            exit;
        }
        
        if(empty($phone_password)){
            echo json_encode(array('code' => -1,'data' => 'passwordnull'));
            exit;
        }
        
        if (strlen($phone_password)<6) {
            echo json_encode(array('code' => -1,'data' => 'password6'));
            exit;
        }
        
        
        //20170816 start
        if($_config['g_isopenmimaimgcode']){
            if(empty($_GET['seccodeverify']) || empty($_GET['seccodehash'])){
                echo json_encode(array('code' => -1,'data' => 'paramerror'));
                exit;
            }
            if (!check_seccode($_GET['seccodeverify'], $_GET['seccodehash'])) {
                echo json_encode(array('code' => -1,'data' => 'seccode_invalid'));
                exit;
            }
        }
        
        //20170816 end
        
       
        $_G['uid'] = $_G['member']['uid'] = 0;
        $_G['username'] = $_G['member']['username'] = $_G['member']['password'] = '';
        
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $userinfo =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
            if(empty($userinfo['mobile'])){
                echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
                exit;
            }
            //20170805 add end
        }else{
            $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
            if(!empty($userinfo)){
                if(empty($userinfo['username'])){
                    C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyid($userinfo['id']);
                    $userinfo = array();
                    echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
                    exit;
                }
            }
            
            if(empty($userinfo)){
                   echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
                   exit;
            }
        }
        
        
        $member = getuserbyuid($userinfo['uid'], 1);
        if (!$member || empty($member['uid'])) {
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyphone($phone);
            echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
            exit;
        }
        
        if(file_exists(DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/uc.inc.php')){
            require_once DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/uc.inc.php';
        }else{
            echo json_encode(array('code' => -1,'data' => 'err_systemerror'));
            exit();
        }
        
        
        
        $questionid = daddslashes($_GET['questionid']);
        $answer = daddslashes($_GET['answer']);
       
        if(intval($questionid) > 0 && empty($answer)){
            echo json_encode(array('code' => -1,'data' => 'answernull'));
            exit;
        }
        if(intval($questionid) == 0){
            $questionid = "";
            $answer = "";
        }
        
        
        $uid = UC::logincheck($member['username'],$phone_password,$questionid,$answer);
        
        
        
        if (!is_numeric($uid)) {
            
            if($uid == "too_many_errors"){
                echo json_encode(array('code' => -1,'data' => 'logintoomanyerror'));
                exit;
            }else{
                echo json_encode(array('code' => -1,'data' => 'loginerror'));
                exit;
            }
            
        }
        
        if($uid == -2) {
            echo json_encode(array('code' => -1,'data' => 'mimaerror'));
            exit;
        } elseif($uid == -3) {
            echo json_encode(array('code' => -1,'data' => 'answerset'));
            exit;
        } elseif($uid <= 0 && $uid != -2 && $uid != -3) {
            echo json_encode(array('code' => -1,'data' => 'loginerror'));
            exit;
        }
        
        if($member['uid'] != $uid){
            echo json_encode(array('code' => -1,'data' => 'loginerror'));
            exit;
        }
        
        
        if ($member['_inarchive']) {
            C::t('common_member_archive')->move_to_master($member['uid']);
        }
        
        setloginstatus($member, $_GET['cookietime'] ? 2592000 : 0);
        checkfollowfeed();
        if ($_G['group']['forcelogin']) {
            if ($_G['group']['forcelogin'] == 1) {
                clearcookies();
                echo json_encode(array('code' => -1,'data' => 'err_location_login_force_qq'));
                exit;
            } elseif ($_G['group']['forcelogin'] == 2 && $_GET['loginfield'] != 'email') {
                clearcookies();
                echo json_encode(array('code' => -1,'data' => 'err_location_login_force_mail'));
                exit;
            }
        }
        if ($_G['member']['lastip'] && $_G['member']['lastvisit']) {
            dsetcookie('lip', $_G['member']['lastip'] . ',' . $_G['member']['lastvisit']);
        }
        C::t('common_member_status')->update($_G['uid'], array('lastip' => $_G['clientip'], 'port' => $_G['remoteport'], 'lastvisit' => TIMESTAMP, 'lastactivity' => TIMESTAMP));
        $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
        
        
        
        if ($_G['member']['adminid'] != 1) {
            if ($_G['setting']['accountguard']['loginoutofdate'] && $_G['member']['lastvisit'] && TIMESTAMP - $_G['member']['lastvisit'] > 90 * 86400) {
                C::t('common_member')->update($_G['uid'], array('freeze' => 2));
                C::t('common_member_validate')->insert(array(
                'uid' => $_G['uid'],
                'submitdate' => TIMESTAMP,
                'moddate' => 0,
                'admin' => '',
                'submittimes' => 1,
                'status' => 0,
                'message' => '',
                'remark' => '',
                ), false, true);
                manage_addnotify('verifyuser');
                echo json_encode(array('code' => -1,'data' => 'err_location_login_outofdate'));
                exit;
                //showmessage('location_login_outofdate', 'home.php?mod=spacecp&ac=profile&op=password&resend=1', array('type' => 1), array('showdialog' => true, 'striptags' => false, 'locationtime' => true));
            }
        }
        
        $param = array(
            'username' => $_G['member']['username'],
            'usergroup' => $_G['group']['grouptitle'],
            'uid' => $_G['member']['uid'],
            'groupid' => $_G['groupid'],
            'syn' => $ucsynlogin ? 1 : 0
        );
        $extra = array(
            'showdialog' => true,
            'locationtime' => true,
            'extrajs' => $ucsynlogin
        );
        
        
        $loginmessage = $_G['groupid'] == 8 ? 'login_succeed_inactive_member' : 'login_succeed';
        $location = $_G['groupid'] == 8 ? 'home.php?mod=space&do=profile&uid='.$_G['uid'] : dreferer();
        
       
        if($_config['g_mtiaozhuanhome'] == 'shouye'){
            @include_once './data/sysdata/cache_domain.php';
        
            $location = $domain['defaultindex'];
        }elseif($_config['g_mtiaozhuanhome'] == 'diy' && !empty($_config['g_mdiyurl'])){
            $location = $_config['g_mdiyurl'];
        }
        
        echo json_encode(array('code' => 200,'data' => 'loginsuccess','url' => $location));
        exit;
        //showmessage($loginmessage, $location, $param, $extra);
        
        
        
    }else{
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        $mobilecolor = !empty($_config['g_mobilecolor'])?$_config['g_mobilecolor']:"#4eabe8";
        $mlogowidth = !empty($_config['g_mlogowidth'])?$_config['g_mlogowidth']:"3rem";
        $mlogoheight = !empty($_config['g_mlogoheight'])?$_config['g_mlogoheight']:"1rem";
        if(!$_G['uid'] && $_config['g_openmobilelogin']){
   
            $g_mstyle = $_config['g_mstyle'];
            if (empty($g_mstyle)){
                $g_mstyle = 'v1';
            }
            $g_mstyle = $g_mstyle.'/';
            
            if($_config['g_phonemimalogin']){
                include template('jzsjiale_sms:'.$g_mstyle.'loginmima');
            }else{
                include template('jzsjiale_sms:'.$g_mstyle.'login');
            }
        }else{
            
            $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
            
            $location = "home.php?mod=space&do=profile&uid=".$_G['uid'];
            if($_config['g_mtiaozhuanhome'] == 'shouye'){
                @include_once './data/sysdata/cache_domain.php';
            
                $location = $domain['defaultindex'];
            }elseif($_config['g_mtiaozhuanhome'] == 'diy' && !empty($_config['g_mdiyurl'])){
                $location = $_config['g_mdiyurl'];
            }
            dheader("Location: ".$location);
            dexit();
            
        }
        
    }
}elseif ($act == 'usernamelogin') {
    require_once libfile('function/misc');
    loaducenter();
     
    require_once libfile('function/member');
    
    if ($formhash == FORMHASH && $_GET['usernameloginsubmit']) {
        
        $username = addslashes($_GET['username']);
        $username_password = daddslashes($_GET['username_password']);
         
        
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        
        if(!$_config['g_openmobilelogin']){
            echo json_encode(array('code' => -1,'data' => 'notopenmobile'));
            exit;
        }
       
        if(empty($username)){
            echo json_encode(array('code' => -1,'data' => 'usernamenull'));
            exit;
        }
        
        
        if(empty($username_password)){
            echo json_encode(array('code' => -1,'data' => 'passwordnull'));
            exit;
        }
        
        if (strlen($username_password)<6) {
            echo json_encode(array('code' => -1,'data' => 'password6'));
            exit;
        }
        
        
        //20170816 start
        if($_config['g_isopenmimaimgcode']){
            if(empty($_GET['seccodeverify']) || empty($_GET['seccodehash'])){
                echo json_encode(array('code' => -1,'data' => 'paramerror'));
                exit;
            }
            if (!check_seccode($_GET['seccodeverify'], $_GET['seccodehash'])) {
                echo json_encode(array('code' => -1,'data' => 'seccode_invalid'));
                exit;
            }
        }
        
        //20170816 end
        
         
        $_G['uid'] = $_G['member']['uid'] = 0;
        $_G['username'] = $_G['member']['username'] = $_G['member']['password'] = '';
        
       
        
        
        if(file_exists(DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/uc.inc.php')){
            require_once DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/uc.inc.php';
        }else{
            echo json_encode(array('code' => -1,'data' => 'err_systemerror'));
            exit();
        }
        
        
        
        $questionid = daddslashes($_GET['questionid']);
        $answer = daddslashes($_GET['answer']);
         
        if(intval($questionid) > 0 && empty($answer)){
            echo json_encode(array('code' => -1,'data' => 'answernull'));
            exit;
        }
        if(intval($questionid) == 0){
            $questionid = "";
            $answer = "";
        }
        
        
        $uid = UC::logincheck($username,$username_password,$questionid,$answer);
        
        
        
        if (!is_numeric($uid)) {
        
            if($uid == "too_many_errors"){
                echo json_encode(array('code' => -1,'data' => 'logintoomanyerror'));
                exit;
            }else{
                echo json_encode(array('code' => -1,'data' => 'loginerror'));
                exit;
            }
        
        }
        
        if($uid == -2) {
            echo json_encode(array('code' => -1,'data' => 'mimaerror'));
            exit;
        } elseif($uid == -3) {
            echo json_encode(array('code' => -1,'data' => 'answerset'));
            exit;
        } elseif($uid <= 0 && $uid != -2 && $uid != -3) {
            echo json_encode(array('code' => -1,'data' => 'loginerror'));
            exit;
        }
        
        
        if($uid > 0){
            $member = getuserbyuid($uid, 1);
            if (!$member || empty($member['uid'])) {
                echo json_encode(array('code' => -1,'data' => 'loginerror'));
                exit;
            }
        }else{
            echo json_encode(array('code' => -1,'data' => 'loginerror'));
            exit;
        }
        
        
        if ($member['_inarchive']) {
            C::t('common_member_archive')->move_to_master($member['uid']);
        }
        
        setloginstatus($member, $_GET['cookietime'] ? 2592000 : 0);
        checkfollowfeed();
        if ($_G['group']['forcelogin']) {
            if ($_G['group']['forcelogin'] == 1) {
                clearcookies();
                echo json_encode(array('code' => -1,'data' => 'err_location_login_force_qq'));
                exit;
            } elseif ($_G['group']['forcelogin'] == 2 && $_GET['loginfield'] != 'email') {
                clearcookies();
                echo json_encode(array('code' => -1,'data' => 'err_location_login_force_mail'));
                exit;
            }
        }
        if ($_G['member']['lastip'] && $_G['member']['lastvisit']) {
            dsetcookie('lip', $_G['member']['lastip'] . ',' . $_G['member']['lastvisit']);
        }
        C::t('common_member_status')->update($_G['uid'], array('lastip' => $_G['clientip'], 'port' => $_G['remoteport'], 'lastvisit' => TIMESTAMP, 'lastactivity' => TIMESTAMP));
        $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
        
        
        
        if ($_G['member']['adminid'] != 1) {
            if ($_G['setting']['accountguard']['loginoutofdate'] && $_G['member']['lastvisit'] && TIMESTAMP - $_G['member']['lastvisit'] > 90 * 86400) {
                C::t('common_member')->update($_G['uid'], array('freeze' => 2));
                C::t('common_member_validate')->insert(array(
                'uid' => $_G['uid'],
                'submitdate' => TIMESTAMP,
                'moddate' => 0,
                'admin' => '',
                'submittimes' => 1,
                'status' => 0,
                'message' => '',
                'remark' => '',
                ), false, true);
                manage_addnotify('verifyuser');
                echo json_encode(array('code' => -1,'data' => 'err_location_login_outofdate'));
                exit;
                //showmessage('location_login_outofdate', 'home.php?mod=spacecp&ac=profile&op=password&resend=1', array('type' => 1), array('showdialog' => true, 'striptags' => false, 'locationtime' => true));
            }
        }
        
        $param = array(
            'username' => $_G['member']['username'],
            'usergroup' => $_G['group']['grouptitle'],
            'uid' => $_G['member']['uid'],
            'groupid' => $_G['groupid'],
            'syn' => $ucsynlogin ? 1 : 0
        );
        $extra = array(
            'showdialog' => true,
            'locationtime' => true,
            'extrajs' => $ucsynlogin
        );
        
        
        $loginmessage = $_G['groupid'] == 8 ? 'login_succeed_inactive_member' : 'login_succeed';
        $location = $_G['groupid'] == 8 ? 'home.php?mod=space&do=profile&uid='.$_G['uid'] : dreferer();
        
        if($_config['g_mtiaozhuanhome'] == 'shouye'){
            @include_once './data/sysdata/cache_domain.php';
        
            $location = $domain['defaultindex'];
        }elseif($_config['g_mtiaozhuanhome'] == 'diy' && !empty($_config['g_mdiyurl'])){
            $location = $_config['g_mdiyurl'];
        }
        
        
        echo json_encode(array('code' => 200,'data' => 'loginsuccess','url' => $location));
        exit;
        //showmessage($loginmessage, $location, $param, $extra);
        
    }else{
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        $mobilecolor = !empty($_config['g_mobilecolor'])?$_config['g_mobilecolor']:"#4eabe8";
        $mlogowidth = !empty($_config['g_mlogowidth'])?$_config['g_mlogowidth']:"3rem";
        $mlogoheight = !empty($_config['g_mlogoheight'])?$_config['g_mlogoheight']:"1rem";
        if(!$_G['uid'] && $_config['g_openmobilelogin']){
   
            $g_mstyle = $_config['g_mstyle'];
            if (empty($g_mstyle)){
                $g_mstyle = 'v1';
            }
            $g_mstyle = $g_mstyle.'/';
            
            if($_config['g_mloginusernameplugin']){
                include template('jzsjiale_sms:'.$g_mstyle.'usernamelogin');
            }else{
                include template('jzsjiale_sms:'.$g_mstyle.'login');
            }
        }else{
            
            $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
            
            $location = "home.php?mod=space&do=profile&uid=".$_G['uid'];
            if($_config['g_mtiaozhuanhome'] == 'shouye'){
                @include_once './data/sysdata/cache_domain.php';
            
                $location = $domain['defaultindex'];
            }elseif($_config['g_mtiaozhuanhome'] == 'diy' && !empty($_config['g_mdiyurl'])){
                $location = $_config['g_mdiyurl'];
            }
            dheader("Location: ".$location);
            dexit();
            
        }
        
    }
}elseif ($act == 'login') {
    
   
    require_once libfile('function/misc');
    loaducenter();
   
    require_once libfile('function/member');
    
    if ($formhash == FORMHASH && $_GET['phoneloginsubmit']) {
        $phone = addslashes($_GET['phone']);
        $seccode = addslashes($_GET['seccode']);
        
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        
        if(!$_config['g_openmobilelogin']){
            echo json_encode(array('code' => -1,'data' => 'notopenmobile'));
            exit;
        }
        
        if(empty($phone)){
            echo json_encode(array('code' => -1,'data' => 'phonenull'));
            exit;
        }
        
        if (!preg_match("/^1[123456789]{1}\d{9}$/", $phone)) {
            echo json_encode(array('code' => -1,'data' => 'bind_phone_error'));
            exit;
        }
        
        if(empty($seccode)){
            echo json_encode(array('code' => -1,'data' => 'seccodenull'));
            exit;
        }
        
        
        $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($phone,$seccode);
        if ($codeinfo) {
            if ((TIMESTAMP - $codeinfo[dateline]) > $_config['g_youxiaoqi']) {
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
                echo json_encode(array('code' => -1,'data' => 'err_seccodeguoqi'));
                exit;
            }
        } else {
            echo json_encode(array('code' => -1,'data' => 'err_seccodeerror'));
            exit;
        }
        
        
        
        $_G['uid'] = $_G['member']['uid'] = 0;
        $_G['username'] = $_G['member']['username'] = $_G['member']['password'] = '';
        
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $userinfo =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
            if(empty($userinfo['mobile'])){
                //20171224 add start
                if(!$_config['g_phoneloginreg']){
                    echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
                    exit;
                }else{
                    
                    if($_config['g_phoneisusername']){
                        $username_regtmp = $phone;
                    }else{
                        $username_regtmp = $_config['g_mregphoneqianzhui'].get_rand_username(8);
                    }
                    
                    
                    $email_regtmp = "reg_".substr($phone,0,3).time().substr($phone,7,4)."@null.null";
                    
                    $user = C::t('common_member')->fetch_by_username($username_regtmp);
                    if($user){
                        $username_regtmp = $_config['g_mregphoneqianzhui'].get_rand_username(8);
                        
                        $user = C::t('common_member')->fetch_by_username($username_regtmp);
                        if($user){
                            echo json_encode(array('code' => -1,'data' => 'usernamecunzai'));
                            exit;
                        }
                        
                    }
                    $password = $seccode;
                    $profile = array (
                        "mobile" => $phone,
                    );
                    require_once DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/uc.inc.php';
                    
                    $isreturnuid = 0;
                    $isopenmemail = 0;
                    if($_config['g_isopenmemail']){
                        $isreturnuid = 1;
                        $isopenmemail = 1;
                    }
                    if(!$_config['g_discuzf']){
                        $uid = UC::regist($username_regtmp,$password,$email_regtmp,$profile,$isreturnuid,$isopenmemail);
                    }else{
                        $uid = UC::regist_new($username_regtmp,$password,$email_regtmp,$codeinfo['phone'],$profile,$isreturnuid,$isopenmemail);
                    }
                    
                    if (!is_numeric($uid)) {
                        if($uid == "username_len_invalid"){
                            echo json_encode(array('code' => -1,'data' => 'username_len_invalid_error'));
                            exit;
                        }elseif($uid == "password_len_invalid"){
                            echo json_encode(array('code' => -1,'data' => 'password_len_invalid_error'));
                            exit;
                        }else{
                            echo json_encode(array('code' => -1,'data' => 'registererror'));
                            exit;
                        }
                    
                    }else{
                        if ($uid<=0) {
                            switch ($uid) {
                                case -4:echo json_encode(array('code' => -1,'data' => 'registeremailillegal')); exit(); break;
                                case -5:echo json_encode(array('code' => -1,'data' => 'registeremailillegal')); exit(); break;
                                case -6:echo json_encode(array('code' => -1,'data' => 'registeremailduplicate')); exit(); break;
                                default: echo json_encode(array('code' => -1,'data' => 'registererror')); exit(); break;
                            };
                        }
                    }
                    
                    
                    $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_uid($uid);
                     
                    
                    if ($uid && $phone && $seccode && !$userinfo) {
                    
                        //weibaochitongbuxianshanchu
                        C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($_G['uid']);
                    
                        $data = array(
                            'uid' => $_G['uid'],
                            'username' => $_G['username'],
                            'phone' => $codeinfo['phone'],
                            'dateline' => TIMESTAMP
                        );
                    
                        C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data, true);
                    
                        C::t('common_member_profile')->update($_G['uid'], array('mobile'=> $phone));
                    
                    
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
                    
                        $location = $_G['groupid'] == 8 ? 'home.php?mod=space&do=profile&uid='.$_G['uid'] : dreferer();
        
                        if($_config['g_mtiaozhuanhome'] == 'shouye'){
                            @include_once './data/sysdata/cache_domain.php';
                        
                            $location = $domain['defaultindex'];
                        }elseif($_config['g_mtiaozhuanhome'] == 'diy' && !empty($_config['g_mdiyurl'])){
                            $location = $_config['g_mdiyurl'];
                        }
                        
                        echo json_encode(array('code' => 200,'data' => 'loginsuccess','url' => $location));
                        exit();
                    
                    }else{
                        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                        echo json_encode(array(
                            'code' => 200,
                            'data' => 'registersuccess_phoneerror'
                        ));
                        exit();
                    }
                }
                //20171224 add end
            }
            //20170805 add end
        }else{
            $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
            if(!empty($userinfo)){
                if(empty($userinfo['username'])){
                    C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyid($userinfo['id']);
                    $userinfo = array();
                    echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
                    exit;
                }
            }
            
            if(empty($userinfo)){
                   //20171224 add start
                   if(!$_config['g_phoneloginreg']){
                       echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
                       exit;
                   }else{
                       
                       if($_config['g_phoneisusername']){
                           $username_regtmp = $phone;
                       }else{
                           $username_regtmp = $_config['g_mregphoneqianzhui'].get_rand_username(8);
                       }
                       
                       
                       $email_regtmp = "reg_".substr($phone,0,3).time().substr($phone,7,4)."@null.null";
                       
                       $user = C::t('common_member')->fetch_by_username($username_regtmp);
                       if($user){
                           $username_regtmp = $_config['g_mregphoneqianzhui'].get_rand_username(8);
                            
                            $user = C::t('common_member')->fetch_by_username($username_regtmp);
                            if($user){
                                echo json_encode(array('code' => -1,'data' => 'usernamecunzai'));
                                exit;
                            }
                       }
                       $password = $seccode;
                       $profile = array (
                           "mobile" => $phone,
                       );
                       require_once DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/uc.inc.php';
                       
                       $isreturnuid = 0;
                       $isopenmemail = 0;
                       if($_config['g_isopenmemail']){
                           $isreturnuid = 1;
                           $isopenmemail = 1;
                       }
                       if(!$_config['g_discuzf']){
                           $uid = UC::regist($username_regtmp,$password,$email_regtmp,$profile,$isreturnuid,$isopenmemail);
                       }else{
                           $uid = UC::regist_new($username_regtmp,$password,$email_regtmp,$codeinfo['phone'],$profile,$isreturnuid,$isopenmemail);
                       }
                       
                       if (!is_numeric($uid)) {
                           if($uid == "username_len_invalid"){
                               echo json_encode(array('code' => -1,'data' => 'username_len_invalid_error'));
                               exit;
                           }elseif($uid == "password_len_invalid"){
                               echo json_encode(array('code' => -1,'data' => 'password_len_invalid_error'));
                               exit;
                           }else{
                               echo json_encode(array('code' => -1,'data' => 'registererror'));
                               exit;
                           }
                       
                       }else{
                           if ($uid<=0) {
                               switch ($uid) {
                                   case -4:echo json_encode(array('code' => -1,'data' => 'registeremailillegal')); exit(); break;
                                   case -5:echo json_encode(array('code' => -1,'data' => 'registeremailillegal')); exit(); break;
                                   case -6:echo json_encode(array('code' => -1,'data' => 'registeremailduplicate')); exit(); break;
                                   default: echo json_encode(array('code' => -1,'data' => 'registererror')); exit(); break;
                               };
                           }
                       }
                       
                       
                       $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_uid($uid);
                        
                       
                       if ($uid && $phone && $seccode && !$userinfo) {
                       
                           //weibaochitongbuxianshanchu
                           C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($_G['uid']);
                       
                           $data = array(
                               'uid' => $_G['uid'],
                               'username' => $_G['username'],
                               'phone' => $codeinfo['phone'],
                               'dateline' => TIMESTAMP
                           );
                       
                           C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data, true);
                       
                           C::t('common_member_profile')->update($_G['uid'], array('mobile'=> $phone));
                       
                       
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
                       
                           $location = $_G['groupid'] == 8 ? 'home.php?mod=space&do=profile&uid='.$_G['uid'] : dreferer();
                       
                           if($_config['g_mtiaozhuanhome'] == 'shouye'){
                                @include_once './data/sysdata/cache_domain.php';
                            
                                $location = $domain['defaultindex'];
                            }elseif($_config['g_mtiaozhuanhome'] == 'diy' && !empty($_config['g_mdiyurl'])){
                                $location = $_config['g_mdiyurl'];
                            }
                       
                           echo json_encode(array('code' => 200,'data' => 'loginsuccess','url' => $location));
                           exit();
                       
                       }else{
                           C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                           echo json_encode(array(
                               'code' => 200,
                               'data' => 'registersuccess_phoneerror'
                           ));
                           exit();
                       }
                   }
                   //20171224 add end
            }
        }
        
        
        
        
        
        $member = getuserbyuid($userinfo['uid'], 1);
        if (!$member || empty($member['uid'])) {
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyphone($phone);
            echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
            exit;
        }
        if ($member['_inarchive']) {
            C::t('common_member_archive')->move_to_master($member['uid']);
        }
        
        setloginstatus($member, $_GET['cookietime'] ? 2592000 : 0);
        checkfollowfeed();
        if ($_G['group']['forcelogin']) {
            if ($_G['group']['forcelogin'] == 1) {
                clearcookies();
                echo json_encode(array('code' => -1,'data' => 'err_location_login_force_qq'));
                exit;
            } elseif ($_G['group']['forcelogin'] == 2 && $_GET['loginfield'] != 'email') {
                clearcookies();
                echo json_encode(array('code' => -1,'data' => 'err_location_login_force_mail'));
                exit;
            }
        }
        if ($_G['member']['lastip'] && $_G['member']['lastvisit']) {
            dsetcookie('lip', $_G['member']['lastip'] . ',' . $_G['member']['lastvisit']);
        }
        C::t('common_member_status')->update($_G['uid'], array('lastip' => $_G['clientip'], 'port' => $_G['remoteport'], 'lastvisit' => TIMESTAMP, 'lastactivity' => TIMESTAMP));
        $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
        
        
        
        if ($_G['member']['adminid'] != 1) {
            if ($_G['setting']['accountguard']['loginoutofdate'] && $_G['member']['lastvisit'] && TIMESTAMP - $_G['member']['lastvisit'] > 90 * 86400) {
                C::t('common_member')->update($_G['uid'], array('freeze' => 2));
                C::t('common_member_validate')->insert(array(
                'uid' => $_G['uid'],
                'submitdate' => TIMESTAMP,
                'moddate' => 0,
                'admin' => '',
                'submittimes' => 1,
                'status' => 0,
                'message' => '',
                'remark' => '',
                ), false, true);
                manage_addnotify('verifyuser');
                echo json_encode(array('code' => -1,'data' => 'err_location_login_outofdate'));
                exit;
                //showmessage('location_login_outofdate', 'home.php?mod=spacecp&ac=profile&op=password&resend=1', array('type' => 1), array('showdialog' => true, 'striptags' => false, 'locationtime' => true));
            }
        }
        
        $param = array(
            'username' => $_G['member']['username'],
            'usergroup' => $_G['group']['grouptitle'],
            'uid' => $_G['member']['uid'],
            'groupid' => $_G['groupid'],
            'syn' => $ucsynlogin ? 1 : 0
        );
        $extra = array(
            'showdialog' => true,
            'locationtime' => true,
            'extrajs' => $ucsynlogin
        );
        $loginmessage = $_G['groupid'] == 8 ? 'login_succeed_inactive_member' : 'login_succeed';
        $location = $_G['groupid'] == 8 ? 'home.php?mod=space&do=profile&uid='.$_G['uid'] : dreferer();
        
        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
        
        if($_config['g_mtiaozhuanhome'] == 'shouye'){
            @include_once './data/sysdata/cache_domain.php';
        
            $location = $domain['defaultindex'];
        }elseif($_config['g_mtiaozhuanhome'] == 'diy' && !empty($_config['g_mdiyurl'])){
            $location = $_config['g_mdiyurl'];
        }
        
        echo json_encode(array('code' => 200,'data' => 'loginsuccess','url' => $location));
        exit;
        //showmessage($loginmessage, $location, $param, $extra);
        
        
        
    }else{
        
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        $mobilecolor = !empty($_config['g_mobilecolor'])?$_config['g_mobilecolor']:"#4eabe8";
        $mlogowidth = !empty($_config['g_mlogowidth'])?$_config['g_mlogowidth']:"3rem";
        $mlogoheight = !empty($_config['g_mlogoheight'])?$_config['g_mlogoheight']:"1rem";
        if(!$_G['uid'] && $_config['g_openmobilelogin']){
   
            $g_mstyle = $_config['g_mstyle'];
            if (empty($g_mstyle)){
                $g_mstyle = 'v1';
            }
            $g_mstyle = $g_mstyle.'/';
            
            if($_config['g_phonemimalogin']){
                include template('jzsjiale_sms:'.$g_mstyle.'loginmima');
            }else{
                include template('jzsjiale_sms:'.$g_mstyle.'login');
            }
        }else{
            
            $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
            
            $location = "home.php?mod=space&do=profile&uid=".$_G['uid'];
            if($_config['g_mtiaozhuanhome'] == 'shouye'){
                @include_once './data/sysdata/cache_domain.php';
            
                $location = $domain['defaultindex'];
            }elseif($_config['g_mtiaozhuanhome'] == 'diy' && !empty($_config['g_mdiyurl'])){
                $location = $_config['g_mdiyurl'];
            }
            dheader("Location: ".$location);
            dexit();
            
        }
        
    }
}elseif ($act == 'lostpw') {
    
    if ($formhash == FORMHASH && $_GET['phonelostpwsubmit']) {
        
        $phone = addslashes($_GET['phone']);
        $seccode = addslashes($_GET['seccode']);
        
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
       
        if(!$_config['g_openmobilezhaohui']){
            echo json_encode(array('code' => -1,'data' => 'notopenmobile'));
            exit;
        }
        
        if(empty($phone)){
            echo json_encode(array('code' => -1,'data' => 'phonenull'));
            exit;
        }
        
        if (!preg_match("/^1[123456789]{1}\d{9}$/", $phone)) {
            echo json_encode(array('code' => -1,'data' => 'bind_phone_error'));
            exit;
        }
        
        if(empty($seccode)){
            echo json_encode(array('code' => -1,'data' => 'seccodenull'));
            exit;
        }
        
        
        $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($phone,$seccode);
        if ($codeinfo) {
            if ((TIMESTAMP - $codeinfo[dateline]) > $_config['g_youxiaoqi']) {
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
                echo json_encode(array('code' => -1,'data' => 'err_seccodeguoqi'));
                exit;
            }
        } else {
            echo json_encode(array('code' => -1,'data' => 'err_seccodeerror'));
            exit;
        }
        
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $userinfo =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
            if(empty($userinfo['mobile'])){
                echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
                exit;
            }
            //20170805 add end
        }else{
            $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
            if(!empty($userinfo)){
                if(empty($userinfo['username'])){
                    C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyid($userinfo['id']);
                    $userinfo = array();
                    echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
                    exit;
                    
                }
            }
            
            if(empty($userinfo)){
                echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
                exit;
            }
        }
        
        
        
        $member = getuserbyuid($userinfo['uid'], 1);
        if (!$member || empty($member['uid'])) {
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyphone($phone);
            
            echo json_encode(array('code' => -1,'data' => 'err_getpasswd_account_notmatch'));
            exit;
        }elseif ($member['adminid'] == 1 || $member['adminid'] == 2) {
            
            echo json_encode(array('code' => -1,'data' => 'err_getpasswd_account_invalid'));
            exit;
        }
        
         
        
        $table_ext = $member['_inarchive'] ? '_archive' : '';
        $idstring = random(6);
        C::t('common_member_field_forum' . $table_ext)->update($member['uid'], array('authstr' => "$_G[timestamp]\t1\t$idstring"));
        
        $sign = my_make_getpws_sign($member['uid'], $idstring);
        
        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
        
        //$location = $_G['siteurl']."member.php?mod=getpasswd&uid=".$member['uid']."&id=".$idstring."&sign=".$sign;
        
        $location = "plugin.php?id=jzsjiale_sms:mobile&act=getpasswd&uid=".$member['uid']."&idstring=".$idstring."&sign=".$sign;
        
        //showmessage('getpasswd_send_succeed', "member.php?mod=getpasswd&uid=".$member['uid']."&id=".$idstring."&sign=".$sign, array(), array('showdialog' => 0, 'locationtime' => true));
        echo json_encode(array('code' => 200,'data' => 'lostpwsuccess','url' => $location));
        exit;
        
    }else{
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        $mobilecolor = !empty($_config['g_mobilecolor'])?$_config['g_mobilecolor']:"#4eabe8";
        $mlogowidth = !empty($_config['g_mlogowidth'])?$_config['g_mlogowidth']:"3rem";
        $mlogoheight = !empty($_config['g_mlogoheight'])?$_config['g_mlogoheight']:"1rem";
        if(!$_G['uid'] && $_config['g_openmobilezhaohui']){
            
            $g_mstyle = $_config['g_mstyle'];
            if (empty($g_mstyle)){
                $g_mstyle = 'v1';
            }
            $g_mstyle = $g_mstyle.'/';
            
            include template('jzsjiale_sms:'.$g_mstyle.'lostpw');
        }else{
        
            $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
            dheader("Location: home.php?mod=space&do=profile&uid=".$_G['uid']);
            dexit();
        
        }
    }
    
}elseif ($act == 'bangding') {
    
    if ($formhash == FORMHASH && $_GET['phonebangdingsubmit']) {
        
        $phone = addslashes($_GET['phone']);
        $seccode = addslashes($_GET['seccode']);
        $username = addslashes($_GET['username']);
        $password = addslashes($_GET['password']);
        
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        
        if(!$_config['g_openmobilebangding']){
            echo json_encode(array('code' => -1,'data' => 'notopenmobile'));
            exit;
        }
        
        //jiechubangding start
        if($_GET['jiechubangding']){
            if($_config['g_isopenmbangdingmima']){
                if(empty($password)){
                    echo json_encode(array('code' => -1,'data' => 'passwordnull'));
                    exit;
                }
                
                loaducenter();
                list($result) = uc_user_login($_G['uid'], $password, 1, 0);
                if ($result < 0){
                    echo json_encode(array('code' => -1,'data' => 'err_mima'));
                    exit;
                }
            }
            
            
            if($_config['g_jiebangyzoldphone']){
                $oldmobile = "";
                $oldseccode = daddslashes($_GET['oldseccode']);
            
                if($_config['g_tongyiuser']){
                    //20170920 add start
                    $olduser =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_uid($_G['uid']);
                    $oldmobile = $olduser['mobile'];
                    //20170920 add end
                }else{
                    //20170920 add start
                    $olduser =  C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_uid($_G['uid']);
                    $oldmobile = $olduser['phone'];
                    //20170920 add end
                }
             
                if (!$oldmobile){
                    echo json_encode(array('code' => -1,'data' => 'getoldmobilenull'));
                    exit;
                }
                if (!$oldseccode){
                    echo json_encode(array('code' => -1,'data' => 'getoldseccodenull'));
                    exit;
                }
            
                $oldcodeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($oldmobile,$oldseccode);
                if ($oldcodeinfo) {
                    if ((TIMESTAMP - $oldcodeinfo[dateline]) > $_config['g_youxiaoqi']) {
                        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($oldmobile,$oldseccode);
                        //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
                        echo json_encode(array('code' => -1,'data' => 'err_oldseccodeguoqi'));
                        exit;
                    }
                } else {
                    echo json_encode(array('code' => -1,'data' => 'err_oldseccodeerror'));
                    exit;
                }
            }
            
            
            $uid = $_G['uid'];
            $username = $_G['username'];
            
            if($_config['g_tongyiuser']){
                if($_config['g_jiebangnewphone']){
                    if(empty($phone)){
                        echo json_encode(array('code' => -1,'data' => 'phonenull'));
                        exit;
                    }
                    
                    if (!preg_match("/^1[123456789]{1}\d{9}$/", $phone)) {
                        echo json_encode(array('code' => -1,'data' => 'bind_phone_error'));
                        exit;
                    }
                    
                    if(empty($seccode)){
                        echo json_encode(array('code' => -1,'data' => 'seccodenull'));
                        exit;
                    }
                    
                    
                    $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($phone,$seccode);
                    if ($codeinfo) {
                        if ((TIMESTAMP - $codeinfo[dateline]) > $_config['g_youxiaoqi']) {
                            C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                            //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
                            echo json_encode(array('code' => -1,'data' => 'err_seccodeguoqi'));
                            exit;
                        }
                    } else {
                        echo json_encode(array('code' => -1,'data' => 'err_seccodeerror'));
                        exit;
                    }
                    
                    $phoneuser =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
                    if(!empty($phoneuser)){
                        echo json_encode(array('code' => -1,'data' => 'phonecunzai'));
                        exit;
                    }
                    //weibaochitongbuxianshanchu
                    C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($uid);
                    
                    $data = array(
                        'uid' => $uid,
                        'username' => $username,
                        'phone' => $phone,
                        'dateline' => TIMESTAMP
                    );
                    
                    C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data,true);
                    
                    if(C::t('common_member_profile')->update($uid, array('mobile'=> $phone))){
                        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                        if($_config['g_jiebangyzoldphone']){
                            C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($oldmobile,$oldseccode);
                        }
                        
                        //verify start
                        if($_config['g_isopenautoverify'] && $_config['g_mobileverify']){
                            $verifyuid = $uid;
                            $memberautoverify = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$verifyuid);
                            if(empty($memberautoverify)){
                                C::t('common_member_verify')->insert(array('uid' => $verifyuid,'verify'.$_config['g_mobileverify'] => 1));
                            }else{
                                C::t('common_member_verify')->update($verifyuid, array('verify'.$_config['g_mobileverify'] => 1));
                            }
                        
                        }
                        
                        
                        //verify end
                    
                        //gengxinrenzheng start
                        $memberverifyres = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$uid);
                        for ($verifyx=1; $verifyx<=7; $verifyx++) {
                            if($memberverifyres['verify'+$verifyx] != 1){
                                $memberverifyinfores = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify_info').' WHERE uid = '.$uid.' and verifytype = '.$verifyx);
                                if(empty($memberverifyinfores)){
                                    continue;
                                }
                                $verifyinfo = dunserialize($memberverifyinfores['field']);
                                $verifyinfo['mobile'] = $phone;
                                 
                                C::t('common_member_verify_info')->update($memberverifyinfores['vid'], array('field' => serialize($verifyinfo)));
                            }
                        }
                        //gengxinrenzheng end
                    
                        echo json_encode(array('code' => 200,'data' => 'chongxinbangdingok'));
                        exit;
                    }else{
                        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                        if($_config['g_jiebangyzoldphone']){
                            C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($oldmobile,$oldseccode);
                        }
                        echo json_encode(array('code' => -1,'data' => 'bangdingerror'));
                        exit;
                    }
                    
                }else{
                    
                    if($_config['g_jiebangyzoldphone']){
                        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($oldmobile,$oldseccode);
                    }
                    
                    //20170805 add start
                    C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($uid);
                    if(C::t('common_member_profile')->update($uid, array('mobile'=> ''))){
                        
                        
                        //verify start
                        if($_config['g_isopenautoverify'] && $_config['g_mobileverify']){
                            $verifyuid = $uid;
                            $memberautoverify = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$verifyuid);
                            if(empty($memberautoverify)){
                                C::t('common_member_verify')->insert(array('uid' => $verifyuid,'verify'.$_config['g_mobileverify'] => 0));
                            }else{
                                C::t('common_member_verify')->update($verifyuid, array('verify'.$_config['g_mobileverify'] => 0));
                            }
                        
                        }
                        
                        
                        //verify end
                    
                        //gengxinrenzheng start
                        $memberverifyres = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$uid);
                        for ($verifyx=1; $verifyx<=7; $verifyx++) {
                            if($memberverifyres['verify'+$verifyx] != 1){
                                $memberverifyinfores = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify_info').' WHERE uid = '.$uid.' and verifytype = '.$verifyx);
                                if(empty($memberverifyinfores)){
                                    continue;
                                }
                                $verifyinfo = dunserialize($memberverifyinfores['field']);
                                $verifyinfo['mobile'] = "";
                                 
                                C::t('common_member_verify_info')->update($memberverifyinfores['vid'], array('field' => serialize($verifyinfo)));
                            }
                        }
                        //gengxinrenzheng end
                        
                        echo json_encode(array('code' => 200,'data' => 'jiechuok'));
                        exit;
                       
                    }else{
                        echo json_encode(array('code' => -1,'data' => 'jiechuerror'));
                        exit;
                    }
                    //20170805 add end
                }
            }else{
                if($_config['g_jiebangnewphone']){
                    if(empty($phone)){
                        echo json_encode(array('code' => -1,'data' => 'phonenull'));
                        exit;
                    }
                
                    if (!preg_match("/^1[123456789]{1}\d{9}$/", $phone)) {
                        echo json_encode(array('code' => -1,'data' => 'bind_phone_error'));
                        exit;
                    }
                
                    if(empty($seccode)){
                        echo json_encode(array('code' => -1,'data' => 'seccodenull'));
                        exit;
                    }
                
                
                    $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($phone,$seccode);
                    if ($codeinfo) {
                        if ((TIMESTAMP - $codeinfo[dateline]) > $_config['g_youxiaoqi']) {
                            C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                            //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
                            echo json_encode(array('code' => -1,'data' => 'err_seccodeguoqi'));
                            exit;
                        }
                    } else {
                        echo json_encode(array('code' => -1,'data' => 'err_seccodeerror'));
                        exit;
                    }
                
                    $phoneuser = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
                    if(!empty($phoneuser)){
                        echo json_encode(array('code' => -1,'data' => 'phonecunzai'));
                        exit;
                    }
                    $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_username($username);
                    if(!empty($userinfo)){
                        echo json_encode(array('code' => -1,'data' => 'err_yibangding'));
                        exit;
                    }
                    
                    //weibaochitongbuxianshanchu
                    C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($uid);
                    
                    $data = array(
                        'uid' => $uid,
                        'username' => $username,
                        'phone' => $phone,
                        'dateline' => TIMESTAMP
                    );
                    
                    if(C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data,true)){
                    
                        C::t('common_member_profile')->update($uid, array('mobile'=> $phone));
                    
                        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                        if($_config['g_jiebangyzoldphone']){
                            C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($oldmobile,$oldseccode);
                        }
                        
                        //verify start
                        if($_config['g_isopenautoverify'] && $_config['g_mobileverify']){
                            $verifyuid = $uid;
                            $memberautoverify = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$verifyuid);
                            if(empty($memberautoverify)){
                                C::t('common_member_verify')->insert(array('uid' => $verifyuid,'verify'.$_config['g_mobileverify'] => 1));
                            }else{
                                C::t('common_member_verify')->update($verifyuid, array('verify'.$_config['g_mobileverify'] => 1));
                            }
                        
                        }
                        
                        
                        //verify end
                    
                        //gengxinrenzheng start
                        $memberverifyres = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$uid);
                        for ($verifyx=1; $verifyx<=7; $verifyx++) {
                            if($memberverifyres['verify'+$verifyx] != 1){
                                $memberverifyinfores = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify_info').' WHERE uid = '.$uid.' and verifytype = '.$verifyx);
                                if(empty($memberverifyinfores)){
                                    continue;
                                }
                                $verifyinfo = dunserialize($memberverifyinfores['field']);
                                $verifyinfo['mobile'] = $phone;
                                 
                                C::t('common_member_verify_info')->update($memberverifyinfores['vid'], array('field' => serialize($verifyinfo)));
                            }
                        }
                        //gengxinrenzheng end
                    
                        echo json_encode(array('code' => 200,'data' => 'chongxinbangdingok'));
                        exit;
                    }else{
                        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                        if($_config['g_jiebangyzoldphone']){
                            C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($oldmobile,$oldseccode);
                        }
                        echo json_encode(array('code' => -1,'data' => 'bangdingerror'));
                        exit;
                    }
                
                }else{
                    if($_config['g_jiebangyzoldphone']){
                        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($oldmobile,$oldseccode);
                    }
                    //20170805 add start
                    C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($uid);
                    if(C::t('common_member_profile')->update($uid, array('mobile'=> ''))){
                        
                        
                        //verify start
                        if($_config['g_isopenautoverify'] && $_config['g_mobileverify']){
                            $verifyuid = $uid;
                            $memberautoverify = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$verifyuid);
                            if(empty($memberautoverify)){
                                C::t('common_member_verify')->insert(array('uid' => $verifyuid,'verify'.$_config['g_mobileverify'] => 0));
                            }else{
                                C::t('common_member_verify')->update($verifyuid, array('verify'.$_config['g_mobileverify'] => 0));
                            }
                        
                        }
                        
                        
                        //verify end
                
                        //gengxinrenzheng start
                        $memberverifyres = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$uid);
                        for ($verifyx=1; $verifyx<=7; $verifyx++) {
                            if($memberverifyres['verify'+$verifyx] != 1){
                                $memberverifyinfores = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify_info').' WHERE uid = '.$uid.' and verifytype = '.$verifyx);
                                if(empty($memberverifyinfores)){
                                    continue;
                                }
                                $verifyinfo = dunserialize($memberverifyinfores['field']);
                                $verifyinfo['mobile'] = "";
                                 
                                C::t('common_member_verify_info')->update($memberverifyinfores['vid'], array('field' => serialize($verifyinfo)));
                            }
                        }
                        //gengxinrenzheng end
                
                        echo json_encode(array('code' => 200,'data' => 'jiechuok'));
                        exit;
                         
                    }else{
                        echo json_encode(array('code' => -1,'data' => 'jiechuerror'));
                        exit;
                    }
                    //20170805 add end
                }
            }
        }
        //jiechubangding end
        
        
        
        if(empty($phone)){
            echo json_encode(array('code' => -1,'data' => 'phonenull'));
            exit;
        }
        
        if (!preg_match("/^1[123456789]{1}\d{9}$/", $phone)) {
            echo json_encode(array('code' => -1,'data' => 'bind_phone_error'));
            exit;
        }
        
        if(empty($seccode)){
            echo json_encode(array('code' => -1,'data' => 'seccodenull'));
            exit;
        }
        
        
        if(!$_G['uid']){
            if(empty($username)){
                echo json_encode(array('code' => -1,'data' => 'usernamenull'));
                exit;
            }
            
            if(empty($password)){
                echo json_encode(array('code' => -1,'data' => 'passwordnull'));
                exit;
            }
            
            
            $username = iconv('UTF-8', CHARSET.'//ignore', urldecode($username));
             
            $user = C::t('common_member')->fetch_by_username($username);
            if(empty($user)){
                echo json_encode(array('code' => -1,'data' => 'error_nouser'));
                exit;
            }
            
            $uid = $user['uid'];
            
            loaducenter();
            list($result) = uc_user_login($uid, $password, 1, 0);
            if ($result < 0){
                echo json_encode(array('code' => -1,'data' => 'err_mima'));
                exit;
            }
        }else{
            
            if($_config['g_isopenmbangdingmima']){
                
                if(empty($password)){
                    echo json_encode(array('code' => -1,'data' => 'passwordnull'));
                    exit;
                }
                
                loaducenter();
                list($result) = uc_user_login($_G['uid'], $password, 1, 0);
                if ($result < 0){
                    echo json_encode(array('code' => -1,'data' => 'err_mima'));
                    exit;
                }
            }
            
            $uid = $_G['uid'];
            $username = $_G['username'];
            
        }
        
        
        if($_config['g_tongyiuser']){
            //20170920 add start
            $olduser2 =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_uid($uid);
            $oldmobile2 = $olduser2['mobile'];
            //20170920 add end
        }else{
            //20170920 add start
            $olduser2 =  C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_uid($uid);
            $oldmobile2 = $olduser2['phone'];
            //20170920 add end
        }
        
        if(!empty($oldmobile2)){
            echo json_encode(array('code' => -1,'data' => 'err_yibangding'));
            exit;
        }
        
        
        $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($phone,$seccode);
        if ($codeinfo) {
            if ((TIMESTAMP - $codeinfo[dateline]) > $_config['g_youxiaoqi']) {
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
                echo json_encode(array('code' => -1,'data' => 'err_seccodeguoqi'));
                exit;
            }
        } else {
            echo json_encode(array('code' => -1,'data' => 'err_seccodeerror'));
            exit;
        }
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $phoneuser =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
            if(!empty($phoneuser)){
                echo json_encode(array('code' => -1,'data' => 'phonecunzai'));
                exit;
            }
            //weibaochitongbuxianshanchu
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($uid);
            
            $data = array(
                'uid' => $uid,
                'username' => $username,
                'phone' => $phone,
                'dateline' => TIMESTAMP
            );
            
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data,true);
            
            if(C::t('common_member_profile')->update($uid, array('mobile'=> $phone))){
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                
                //verify start
                if($_config['g_isopenautoverify'] && $_config['g_mobileverify']){
                    $verifyuid = $uid;
                    $memberautoverify = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$verifyuid);
                    if(empty($memberautoverify)){
                        C::t('common_member_verify')->insert(array('uid' => $verifyuid,'verify'.$_config['g_mobileverify'] => 1));
                    }else{
                        C::t('common_member_verify')->update($verifyuid, array('verify'.$_config['g_mobileverify'] => 1));
                    }
                
                }
                
                
                //verify end
                
                //gengxinrenzheng start
                $memberverifyres = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$uid);
                for ($verifyx=1; $verifyx<=7; $verifyx++) {
                    if($memberverifyres['verify'+$verifyx] != 1){
                        $memberverifyinfores = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify_info').' WHERE uid = '.$uid.' and verifytype = '.$verifyx);
                        if(empty($memberverifyinfores)){
                            continue;
                        }
                        $verifyinfo = dunserialize($memberverifyinfores['field']);
                        $verifyinfo['mobile'] = $phone;
                         
                        C::t('common_member_verify_info')->update($memberverifyinfores['vid'], array('field' => serialize($verifyinfo)));
                    }
                }
                //gengxinrenzheng end
                
                echo json_encode(array('code' => 200,'data' => 'bangdingsuccess'));
                exit;
            }else{
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                echo json_encode(array('code' => -1,'data' => 'bangdingerror'));
                exit;
            }
            
        }else{
            $phoneuser = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
            
            if(!empty($phoneuser)){
                echo json_encode(array('code' => -1,'data' => 'phonecunzai'));
                exit;
            }
            
            $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_username($username);
            if(!empty($userinfo)){
                echo json_encode(array('code' => -1,'data' => 'err_yibangding'));
                exit;
            }
            
            //weibaochitongbuxianshanchu
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($uid);
            
            $data = array(
                'uid' => $uid,
                'username' => $username,
                'phone' => $phone,
                'dateline' => TIMESTAMP
            );
            
            if(C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data,true)){
            
                C::t('common_member_profile')->update($uid, array('mobile'=> $phone));
            
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                
                
                //verify start
                if($_config['g_isopenautoverify'] && $_config['g_mobileverify']){
                    $verifyuid = $uid;
                    $memberautoverify = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$verifyuid);
                    if(empty($memberautoverify)){
                        C::t('common_member_verify')->insert(array('uid' => $verifyuid,'verify'.$_config['g_mobileverify'] => 1));
                    }else{
                        C::t('common_member_verify')->update($verifyuid, array('verify'.$_config['g_mobileverify'] => 1));
                    }
                
                }
                
                
                //verify end
                
                //gengxinrenzheng start
                $memberverifyres = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$uid);
                for ($verifyx=1; $verifyx<=7; $verifyx++) {
                    if($memberverifyres['verify'+$verifyx] != 1){
                        $memberverifyinfores = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify_info').' WHERE uid = '.$uid.' and verifytype = '.$verifyx);
                        if(empty($memberverifyinfores)){
                            continue;
                        }
                        $verifyinfo = dunserialize($memberverifyinfores['field']);
                        $verifyinfo['mobile'] = $phone;
                         
                        C::t('common_member_verify_info')->update($memberverifyinfores['vid'], array('field' => serialize($verifyinfo)));
                    }
                }
                //gengxinrenzheng end
                
                echo json_encode(array('code' => 200,'data' => 'bangdingsuccess'));
                exit;
            }else{
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                echo json_encode(array('code' => -1,'data' => 'bangdingerror'));
                exit;
            }
        }
        
        
    }else{
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        $mobilecolor = !empty($_config['g_mobilecolor'])?$_config['g_mobilecolor']:"#4eabe8";
        $mlogowidth = !empty($_config['g_mlogowidth'])?$_config['g_mlogowidth']:"3rem";
        $mlogoheight = !empty($_config['g_mlogoheight'])?$_config['g_mlogoheight']:"1rem";
        if((!$_G['uid'] || $_GET['qiangzhibangding'] == 'yes') && $_config['g_openmobilebangding']){
            if($_G['uid']){
                
                $isbangdingphoneok = false;
                if($_config['g_tongyiuser']){
                    //20170805 add start
                    $isbangdingphone =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_uid($_G['uid']);
                    
                    if(!empty($isbangdingphone['mobile'])){
                        $isbangdingphoneok = true;
                    }
                    //20170805 add end
                }else{
                    $isbangdingphone = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_uid($_G['uid']);
                    if(!empty($isbangdingphone)){
                        $isbangdingphoneok = true;
                    }
                }
                
                if($isbangdingphoneok){
                    $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
                    dheader("Location: home.php?mod=space&do=profile&uid=".$_G['uid']);
                    dexit();
                }else{
                    
                    $g_mstyle = $_config['g_mstyle'];
                    if (empty($g_mstyle)){
                        $g_mstyle = 'v1';
                    }
                    $g_mstyle = $g_mstyle.'/';
                    
                    include template('jzsjiale_sms:'.$g_mstyle.'bangdinglogin');
                }
            }else{
                
                $g_mstyle = $_config['g_mstyle'];
                if (empty($g_mstyle)){
                    $g_mstyle = 'v1';
                }
                $g_mstyle = $g_mstyle.'/';
                
                include template('jzsjiale_sms:'.$g_mstyle.'bangding');
            }
            
        }elseif($_G['uid']  && $_config['g_openmobilebangding']){
            
            if($_config['g_tongyiuser']){
                    //20170805 add start
                    $userinfo =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_uid($_G['uid']);
                    if(!empty($userinfo)){
                        $userinfo['phone'] = $userinfo['mobile'];
                        if(empty($userinfo['mobile'])){
                            $userinfo = array();
                        }
                    }
                    //20170805 add end
                }else{
                    $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_username($_G['username']);
                    if(!empty($userinfo)){
                        if(empty($userinfo['phone'])){
                            C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyid($userinfo['id']);
                            $userinfo = array();
                        }
                    }
                }
            
                $g_mstyle = $_config['g_mstyle'];
                if (empty($g_mstyle)){
                    $g_mstyle = 'v1';
                }
                $g_mstyle = $g_mstyle.'/';
                
                include template('jzsjiale_sms:'.$g_mstyle.'bangdinglogin');
                
        }else{
        
            $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
            dheader("Location: home.php?mod=space&do=profile&uid=".$_G['uid']);
            dexit();
        
        }
    }
    
}elseif ($act == 'tiaokuan') {
    
    global $_G;
    $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
    $mobilecolor = !empty($_config['g_mobilecolor'])?$_config['g_mobilecolor']:"#4eabe8";
    $mlogowidth = !empty($_config['g_mlogowidth'])?$_config['g_mlogowidth']:"3rem";
    $mlogoheight = !empty($_config['g_mlogoheight'])?$_config['g_mlogoheight']:"1rem";
    $g_tiaokuan = $_config['g_tiaokuan'];
    
    $g_mstyle = $_config['g_mstyle'];
    if (empty($g_mstyle)){
        $g_mstyle = 'v1';
    }
    $g_mstyle = $g_mstyle.'/';
    
    include template('jzsjiale_sms:'.$g_mstyle.'tiaokuan');
    
}elseif ($act == 'getpasswd') {
    
    if($_GET['uid'] && $_GET['idstring'] && $_GET['sign'] === my_make_getpws_sign($_GET['uid'], $_GET['idstring'])) {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        $discuz_action = 141;
        
        
        $member = getuserbyuid($_GET['uid'], 1);
        $table_ext = isset($member['_inarchive']) ? '_archive' : '';
        $member = array_merge(C::t('common_member_field_forum'.$table_ext)->fetch($_GET['uid']), $member);
        list($dateline, $operation, $idstring) = explode("\t", $member['authstr']);
        
        if($dateline < TIMESTAMP - 86400 * 3 || $operation != 1 || $idstring != $_GET['idstring']) {
          
            if($formhash == FORMHASH && $_GET['getpwsubmit']){
                echo json_encode(array('code' => -1,'data' => 'getpasswdillegal'));
                exit;
            }else{
                showmessage('getpasswd_illegal');
            }
            
            
        }
        
        if ($formhash == FORMHASH && $_GET['getpwsubmit'] && $_GET['newpasswd1'] == $_GET['newpasswd2']) {
            
            if($_GET['newpasswd1'] != addslashes($_GET['newpasswd1'])) {
                echo json_encode(array('code' => -1,'data' => 'profilepasswd_illegal'));
                exit;
            }
            if(strlen($_GET['newpasswd1']) < 6) {
                echo json_encode(array('code' => -1,'data' => 'password6'));
                exit;
            }
            
            loaducenter();
            uc_user_edit(addslashes($member['username']), $_GET['newpasswd1'], $_GET['newpasswd1'], addslashes($member['email']), 1, 0);
            $password = md5(random(10));
            
            if(isset($member['_inarchive'])) {
                C::t('common_member_archive')->move_to_master($member['uid']);
            }
            C::t('common_member')->update($_GET['uid'], array('password' => $password));
            C::t('common_member_field_forum')->update($_GET['uid'], array('authstr' => ''));

          
            $location = "member.php?mod=logging&action=login&mobile=2";
            /*
            if($_config['g_mtiaozhuanhome']){
                @include_once './data/sysdata/cache_domain.php';
                
                $location = $domain['defaultindex'];
            }
            */
        
            echo json_encode(array('code' => 200,'data' => 'getpasswdsucceed','url' => $location));
            exit;
        }else{
            $hashid = $_GET['idstring'];
            $uid = $_GET['uid'];
            $mobilecolor = !empty($_config['g_mobilecolor'])?$_config['g_mobilecolor']:"#4eabe8";
            $mlogowidth = !empty($_config['g_mlogowidth'])?$_config['g_mlogowidth']:"3rem";
            $mlogoheight = !empty($_config['g_mlogoheight'])?$_config['g_mlogoheight']:"1rem";
            
            $g_mstyle = $_config['g_mstyle'];
            if (empty($g_mstyle)){
                $g_mstyle = 'v1';
            }
            $g_mstyle = $g_mstyle.'/';
            
            include template('jzsjiale_sms:'.$g_mstyle.'getpasswd');
        }
        
    }else{
        showmessage('parameters_error');
    }
    
}


function my_make_getpws_sign($uid, $idstring) {
    global $_G;
    $link = "{$_G['siteurl']}member.php?mod=getpasswd&uid={$uid}&id={$idstring}";
    return dsign($link);
}
function crypto_rand_secure($min, $max)
{
    $range = $max - $min;
    if ($range < 1) return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd >= $range);
    return $min + $rnd;
}

function get_rand_username($length = 8)
{
    $username = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
        $username .= $codeAlphabet[crypto_rand_secure(0, $max)];
    }

    return $username;
}
?>