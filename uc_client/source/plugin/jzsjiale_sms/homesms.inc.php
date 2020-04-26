<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$act = addslashes($_GET['act']);
$formhash =  addslashes($_GET['formhash'])? addslashes($_GET['formhash']):'';
loadcache('plugin');
global $_G, $lang;

require_once DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/smstools.inc.php';


if (empty($act)) {
    global $_G;
    $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
    if (! $_config['g_openpclogin']) {
        showmessage(lang('plugin/jzsjiale_sms', 'err_gongnengweikaiqi'));
    }
        
    $navtitle = lang('plugin/jzsjiale_sms', 'loginpopuptitle');
    require_once libfile('function/misc');
    loaducenter();
    if ($_G['uid']) {
        $referer = dreferer();
        $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
        $param = array('username' => $_G['member']['username'], 'usergroup' => $_G['group']['grouptitle'], 'uid' => $_G['member']['uid']);
        showmessage('login_succeed', $referer ? $referer : './', $param, array('showdialog' => 1, 'locationtime' => true, 'extrajs' => $ucsynlogin));
    }
    require_once libfile('function/member');

    if (submitcheck('loginsubmit')) {
        $phone = daddslashes($_GET['phone_login']);
        $seccode = daddslashes($_GET['seccode']);
        if (!$phone || !$seccode){
            showmessage(lang('plugin/jzsjiale_sms', 'paramerror'));
        }
        if (!preg_match("/^1[123456789]{1}\d{9}$/", $phone)) {
            showmessage(lang('plugin/jzsjiale_sms', 'bind_phone_error'));
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
        
        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
        
        $_G['uid'] = $_G['member']['uid'] = 0;
        $_G['username'] = $_G['member']['username'] = $_G['member']['password'] = '';
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $userinfo =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
            if(empty($userinfo['mobile'])){
                //20171222 add start
                if(!$_config['g_phoneloginreg']){
                   showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'));
                }else{
                    
                    if($_config['g_phoneisusername']){
                        $username_regtmp = $phone;
                    }else{
                        $username_regtmp = $_config['g_pcregphoneqianzhui'].get_rand_username(8);
                    }
                    
                    $email_regtmp = "reg_".substr($phone,0,3).time().substr($phone,7,4)."@null.null";
                    
                    $user = C::t('common_member')->fetch_by_username($username_regtmp);
                    if($user){
                         $username_regtmp = $_config['g_pcregphoneqianzhui'].get_rand_username(8);
                         $user = C::t('common_member')->fetch_by_username($username_regtmp);
                         if($user){
                            showmessage(lang('plugin/jzsjiale_sms', 'usernamecunzai'));
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
                    
                    if(!$_config['g_discuzf']){
                        $uid = UC::regist($username_regtmp,$password,$email_regtmp,$profile,$isreturnuid,$isopenmemail);
                    }else{
                        $uid = UC::regist_new($username_regtmp,$password,$email_regtmp,$codeinfo['phone'],$profile,$isreturnuid,$isopenmemail);
                    }
                    
                    if (!is_numeric($uid)) {
                        if($uid == "username_len_invalid"){
                            showmessage(lang('plugin/jzsjiale_sms', 'username_len_invalid_error'));
                            exit;
                        }elseif($uid == "password_len_invalid"){
                            showmessage(lang('plugin/jzsjiale_sms', 'password_len_invalid_error'));
                            exit;
                        }else{
                            showmessage(lang('plugin/jzsjiale_sms', 'registererror'));
                            exit;
                        }
                    
                    }else{
                        if ($uid<=0) {
                            switch ($uid) {
                                case -4:showmessage(lang('plugin/jzsjiale_sms', 'registeremailillegal')); exit(); break;
                                case -5:showmessage(lang('plugin/jzsjiale_sms', 'registeremailillegal')); exit(); break;
                                case -6:showmessage(lang('plugin/jzsjiale_sms', 'registeremailduplicate')); exit(); break;
                                default: showmessage(lang('plugin/jzsjiale_sms', 'registererror')); exit(); break;
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
                    
                        $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
                        $param = array(
                            'username' => $_G['member']['username'],
                            'usergroup' => $_G['group']['grouptitle'],
                            'uid' => $_G['member']['uid'],
                            'groupid' => $_G['groupid'],
                            'syn' => $ucsynlogin ? 1 : 0
                        );
                        
                        $loginmessage = $_G['groupid'] == 8 ? 'login_succeed_inactive_member' : 'login_succeed';
                        $location = $_G['groupid'] == 8 ? 'home.php?mod=space&do=home' : dreferer();
                        
                        /*
                        if($_config['g_mtiaozhuanhome']){
                            @include_once './data/sysdata/cache_domain.php';
                        
                            $location = $domain['defaultindex'];
                        }
                        */
                        if($_config['g_mtiaozhuanhome'] == 'shouye'){
                            @include_once './data/sysdata/cache_domain.php';
                        
                            $location = $domain['defaultindex'];
                        }elseif($_config['g_mtiaozhuanhome'] == 'diy' && !empty($_config['g_pcdiyurl'])){
                            $location = $_config['g_pcdiyurl'];
                        }
                        showmessage($loginmessage, $location, $param, array('showdialog' => true, 'striptags' => false, 'locationtime' => true));
                        
                        exit();
                    
                    }else{
                        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                        showmessage(lang('plugin/jzsjiale_sms', 'registersuccess_phoneerror'));
                        exit();
                    }
                    
                }
                //20171222 add end
            }
            //20170805 add end
        }else{
            $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
            if(!empty($userinfo)){
                if(empty($userinfo['username'])){
                    C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyid($userinfo['id']);
                    showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'.$userinfo['id']));
                    $userinfo = array();
                }
            }
            if(empty($userinfo)){
                //20171222 add start
                if(!$_config['g_phoneloginreg']){
                    showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'));
                }else{
                    if($_config['g_phoneisusername']){
                        $username_regtmp = $phone;
                    }else{
                        $username_regtmp = $_config['g_pcregphoneqianzhui'].get_rand_username(8);
                    }
                    
                    $email_regtmp = "reg_".substr($phone,0,3).time().substr($phone,7,4)."@null.null";
                    
                    $user = C::t('common_member')->fetch_by_username($username_regtmp);
                    if($user){
                         $username_regtmp = $_config['g_pcregphoneqianzhui'].get_rand_username(8);
                         $user = C::t('common_member')->fetch_by_username($username_regtmp);
                         if($user){
                            showmessage(lang('plugin/jzsjiale_sms', 'usernamecunzai'));
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
                    
                    if(!$_config['g_discuzf']){
                        $uid = UC::regist($username_regtmp,$password,$email_regtmp,$profile,$isreturnuid,$isopenmemail);
                    }else{
                        $uid = UC::regist_new($username_regtmp,$password,$email_regtmp,$codeinfo['phone'],$profile,$isreturnuid,$isopenmemail);
                    }
                    
                     
                    if (!is_numeric($uid)) {
                        if($uid == "username_len_invalid"){
                            showmessage(lang('plugin/jzsjiale_sms', 'username_len_invalid_error'));
                            exit;
                        }elseif($uid == "password_len_invalid"){
                            showmessage(lang('plugin/jzsjiale_sms', 'password_len_invalid_error'));
                            exit;
                        }else{
                            showmessage(lang('plugin/jzsjiale_sms', 'registererror'));
                            exit;
                        }
                    
                    }else{
                        if ($uid<=0) {
                            switch ($uid) {
                                case -4:showmessage(lang('plugin/jzsjiale_sms', 'registeremailillegal')); exit(); break;
                                case -5:showmessage(lang('plugin/jzsjiale_sms', 'registeremailillegal')); exit(); break;
                                case -6:showmessage(lang('plugin/jzsjiale_sms', 'registeremailduplicate')); exit(); break;
                                default: showmessage(lang('plugin/jzsjiale_sms', 'registererror')); exit(); break;
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
                    
                        $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
                        $param = array(
                            'username' => $_G['member']['username'],
                            'usergroup' => $_G['group']['grouptitle'],
                            'uid' => $_G['member']['uid'],
                            'groupid' => $_G['groupid'],
                            'syn' => $ucsynlogin ? 1 : 0
                        );
                    
                        $loginmessage = $_G['groupid'] == 8 ? 'login_succeed_inactive_member' : 'login_succeed';
                        $location = $_G['groupid'] == 8 ? 'home.php?mod=space&do=home' : dreferer();
                        
                        if($_config['g_mtiaozhuanhome'] == 'shouye'){
                            @include_once './data/sysdata/cache_domain.php';
                        
                            $location = $domain['defaultindex'];
                        }elseif($_config['g_mtiaozhuanhome'] == 'diy' && !empty($_config['g_pcdiyurl'])){
                            $location = $_config['g_pcdiyurl'];
                        }
                        
                        showmessage($loginmessage, $location, $param, array('showdialog' => true, 'striptags' => false, 'locationtime' => true));
                    
                        exit();
                    
                    }else{
                        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                        showmessage(lang('plugin/jzsjiale_sms', 'registersuccess_phoneerror'));
                        exit();
                    }
                    
                }
                //20171222 add end
            }
        }
        
   
        $member = getuserbyuid($userinfo['uid'], 1);
        if (!$member || empty($member['uid'])) {
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyphone($phone);
            showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'));
        }
        if ($member['_inarchive']) {
            C::t('common_member_archive')->move_to_master($member['uid']);
        }
        setloginstatus($member, $_GET['cookietime'] ? 2592000 : 0);
        checkfollowfeed();
        if ($_G['group']['forcelogin']) {
            if ($_G['group']['forcelogin'] == 1) {
                clearcookies();
                showmessage('location_login_force_qq');
            } elseif ($_G['group']['forcelogin'] == 2 && $_GET['loginfield'] != 'email') {
                clearcookies();
                showmessage('location_login_force_mail');
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
                showmessage('location_login_outofdate', 'home.php?mod=spacecp&ac=profile&op=password&resend=1', array('type' => 1), array('showdialog' => true, 'striptags' => false, 'locationtime' => true));
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
        $location = $_G['groupid'] == 8 ? 'home.php?mod=space&do=home' : dreferer();
        
        if($_config['g_mtiaozhuanhome'] == 'shouye'){
               @include_once './data/sysdata/cache_domain.php';
                        
               $location = $domain['defaultindex'];
        }elseif($_config['g_mtiaozhuanhome'] == 'diy' && !empty($_config['g_pcdiyurl'])){
               $location = $_config['g_pcdiyurl'];
        }
        showmessage($loginmessage, $location, $param, $extra);
    } else {
        
        
        if($_config['g_phonemimalogin']){
            include template('jzsjiale_sms:loginmima');
        }else{
            include template('jzsjiale_sms:login');
        }
        
    }
}elseif ($act == 'phonemimalogin') {
    global $_G;
    $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
    if (! $_config['g_openpclogin']) {
        showmessage(lang('plugin/jzsjiale_sms', 'err_gongnengweikaiqi'));
    }
    
    if (! $_config['g_phonemimalogin']) {
        showmessage(lang('plugin/jzsjiale_sms', 'err_notopenphonemimalogin'));
    }
        
    $navtitle = lang('plugin/jzsjiale_sms', 'loginpopuptitle');
    require_once libfile('function/misc');
    loaducenter();
    if ($_G['uid']) {
        $referer = dreferer();
        $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
        $param = array('username' => $_G['member']['username'], 'usergroup' => $_G['group']['grouptitle'], 'uid' => $_G['member']['uid']);
        showmessage('login_succeed', $referer ? $referer : './', $param, array('showdialog' => 1, 'locationtime' => true, 'extrajs' => $ucsynlogin));
    }
    require_once libfile('function/member');

    if (submitcheck('loginsubmit')) {
        $phone = daddslashes($_GET['phone_login']);
        $phone_password = daddslashes($_GET['phone_password']);
        
        if(empty($phone)){
            showmessage(lang('plugin/jzsjiale_sms', 'phonenull'));
        }
        
        if (!preg_match("/^1[123456789]{1}\d{9}$/", $phone)) {
            showmessage(lang('plugin/jzsjiale_sms', 'bind_phone_error'));
        }
        
        if(empty($phone_password)){
            showmessage(lang('plugin/jzsjiale_sms', 'passwordnull'));
        }
        
        if (strlen($phone_password)<6) {
            showmessage(lang('plugin/jzsjiale_sms', 'password6'));
        }
        
        
        //20170816 start
        if($_config['g_isopenmimaimgcode']){
            if(empty($_GET['seccodeverify']) || empty($_GET['seccodehash'])){
                showmessage(lang('plugin/jzsjiale_sms', 'paramerror'));
               
            }
            if (!check_seccode($_GET['seccodeverify'], $_GET['seccodehash'])) {
                showmessage(lang('plugin/jzsjiale_sms', 'seccode_invalid'));
                
            }
        }
        
        //20170816 end
        
        
        $_G['uid'] = $_G['member']['uid'] = 0;
        $_G['username'] = $_G['member']['username'] = $_G['member']['password'] = '';
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $userinfo =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
            if(empty($userinfo['mobile'])){
                showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'));
            }
            //20170805 add end
        }else{
            $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
            if(!empty($userinfo)){
                if(empty($userinfo['username'])){
                    C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyid($userinfo['id']);
                    showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'.$userinfo['id']));
                    $userinfo = array();
                }
            }
            
            if(empty($userinfo)){
                showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'));
            }
        }
        
        
        $member = getuserbyuid($userinfo['uid'], 1);
        if (!$member || empty($member['uid'])) {
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyphone($phone);
            showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'));
        }
        
        
        
        if(file_exists(DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/uc.inc.php')){
            require_once DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/uc.inc.php';
        }else{
            showmessage(lang('plugin/jzsjiale_sms', 'err_systemerror'));
        }
        
        
        $questionid = daddslashes($_GET['questionid']);
        $answer = daddslashes($_GET['answer']);
         
        if(intval($questionid) > 0 && empty($answer)){
            showmessage(lang('plugin/jzsjiale_sms', 'answernull'));
        }
        if(intval($questionid) == 0){
            $questionid = "";
            $answer = "";
        }
        
        
        $uid = UC::logincheck($member['username'],$phone_password,$questionid,$answer);
        
        if (!is_numeric($uid)) {
        
            if($uid == "too_many_errors"){
                showmessage(lang('plugin/jzsjiale_sms', 'logintoomanyerror'));
            }else{
                showmessage(lang('plugin/jzsjiale_sms', 'loginerror'));
            }
        
        }
        
        if($uid == -2) {
            showmessage(lang('plugin/jzsjiale_sms', 'mimaerror'));
        } elseif($uid == -3) {
            showmessage(lang('plugin/jzsjiale_sms', 'answerset'));
        } elseif($uid <= 0 && $uid != -2 && $uid != -3) {
            showmessage(lang('plugin/jzsjiale_sms', 'loginerror'));
        }
        
        if($member['uid'] != $uid){
            showmessage(lang('plugin/jzsjiale_sms', 'loginerror'));
        }
        
        

        if ($member['_inarchive']) {
            C::t('common_member_archive')->move_to_master($member['uid']);
        }
        setloginstatus($member, $_GET['cookietime'] ? 2592000 : 0);
        checkfollowfeed();
        if ($_G['group']['forcelogin']) {
            if ($_G['group']['forcelogin'] == 1) {
                clearcookies();
                showmessage('location_login_force_qq');
            } elseif ($_G['group']['forcelogin'] == 2 && $_GET['loginfield'] != 'email') {
                clearcookies();
                showmessage('location_login_force_mail');
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
                showmessage('location_login_outofdate', 'home.php?mod=spacecp&ac=profile&op=password&resend=1', array('type' => 1), array('showdialog' => true, 'striptags' => false, 'locationtime' => true));
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
        $location = $_G['groupid'] == 8 ? 'home.php?mod=space&do=home' : dreferer();
        
        if($_config['g_mtiaozhuanhome'] == 'shouye'){
               @include_once './data/sysdata/cache_domain.php';
                        
               $location = $domain['defaultindex'];
        }elseif($_config['g_mtiaozhuanhome'] == 'diy' && !empty($_config['g_pcdiyurl'])){
               $location = $_config['g_pcdiyurl'];
        }
        
        showmessage($loginmessage, $location, $param, $extra);
    } else {
        include template('jzsjiale_sms:loginmima');
    }
}elseif ($act == 'sendseccode') {
    
    if ($formhash == FORMHASH) {
  
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        $g_accesskeyid = $_config['g_accesskeyid'];
        $g_accesskeysecret = $_config['g_accesskeysecret'];
        $webbianma = $_G['charset'];
        //$g_xiane = $_config['g_xiane'];
        $g_xiane = !empty($_config['g_xiane'])?$_config['g_xiane']:10;
        $g_zongxiane = ($_config['g_zongxiane']>0)?$_config['g_zongxiane']:0;
        $g_zhanghaoxiane = ($_config['g_zhanghaoxiane']>0)?$_config['g_zhanghaoxiane']:0;
        $g_isopenhtmlspecialchars = !empty($_config['g_isopenhtmlspecialchars'])?true:false;
        
        $g_templateid = "";
        $g_sign = "";
        $type = intval($_GET[type]);
        //type 0ceshi 1zhuce 2shenfenyanzheng 3denglu 4xiugaimima
        $clentip = "";
        
        if($_config['g_checkip']){
            require_once DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/checkip.class.php';
            $checkip = new Checkip();
            
            $clentip = $checkip->get_client_ip();
            
            $isipok = $checkip->ipaccessok();
            if(!$isipok){
                echo json_encode(array('code' => -1,'data' => 'err_checkiperror'));
                exit;
            }
        }
        
        if($g_zongxiane){
            $phonesendallcount = C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->count_all_by_day();
            
            if($phonesendallcount >= $g_zongxiane){
                echo json_encode(array('code' => -1,'data' => 'err_seccodezongxiane'));
                exit;
            }
        }
        
        if($_G['uid'] && $g_zhanghaoxiane && ($type == 2 || $type == 5)){
            $uidphonesendcount = C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->count_by_uid_day($_G['uid']);
   
            if($uidphonesendcount >= $g_zhanghaoxiane){
                echo json_encode(array('code' => -1,'data' => 'err_zhanghaoseccodexiane'));
                exit;
            }
        }
        
        
        if(empty($g_accesskeyid)){
            echo json_encode(array('code' => -1,'data' => 'peizhierror'));
            exit;
        }
        if(empty($g_accesskeysecret)){
            echo json_encode(array('code' => -1,'data' => 'peizhierror'));
            exit;
        }
        
        
        if($type == 1){
            $g_openregister = $_config['g_openregister'];
            
            if(!$g_openregister){
                echo json_encode(array('code' => -1,'data' => 'notopenregister'));
                exit;
            }else{
                $g_templateid = $_config['g_registerid'];
                $g_sign = $_config['g_registersign'];
            }
        }elseif($type == 2){
            $g_openyanzheng = $_config['g_openyanzheng'];
            
            if(!$g_openyanzheng){
                echo json_encode(array('code' => -1,'data' => 'notopenyanzheng'));
                exit;
            }else{
                $g_templateid = $_config['g_yanzhengid'];
                $g_sign = $_config['g_yanzhengsign'];
            }
        }elseif($type == 3){
            $g_openlogin = $_config['g_openlogin'];
            
            if(!$g_openlogin){
                echo json_encode(array('code' => -1,'data' => 'notopenlogin'));
                exit;
            }else{
                $g_templateid = $_config['g_loginid'];
                $g_sign = $_config['g_loginsign'];
            }
        }elseif($type == 4){
            $g_openmima = $_config['g_openmima'];
            
            if(!$g_openmima){
                echo json_encode(array('code' => -1,'data' => 'notopenmima'));
                exit;
            }else{
                $g_templateid = $_config['g_mimaid'];
                $g_sign = $_config['g_mimasign'];
            }
        }elseif($type == 5){
            $g_openyanzheng = $_config['g_openyanzheng'];
            
            if(!$g_openyanzheng){
                echo json_encode(array('code' => -1,'data' => 'notopenyanzheng'));
                exit;
            }else{
                $g_templateid = $_config['g_yanzhengid'];
                $g_sign = $_config['g_yanzhengsign'];
            }
        }else{
            $g_openyanzheng = $_config['g_openyanzheng'];
            
            if(!$g_openyanzheng){
                echo json_encode(array('code' => -1,'data' => 'notopenyanzheng'));
                exit;
            }else{
                $g_templateid = $_config['g_yanzhengid'];
                $g_sign = $_config['g_yanzhengsign'];
            }
        }
        
        
        
        $phone = addslashes($_GET['phone']);
        if(empty($phone)){
            echo json_encode(array('code' => -1,'data' => 'paramerror'));
            exit;
        }
        if (!preg_match("/^1[123456789]{1}\d{9}$/", $phone)) {
            echo json_encode(array('code' => -1,'data' => 'bind_phone_error'));
            exit;
        }
        
        if($_config['g_isopenimgcode']){
            if(empty($_GET['seccodeverify']) || empty($_GET['seccodehash'])){
                echo json_encode(array('code' => -1,'data' => 'paramerror'));
                exit;
            }
            $getseccodeverify = $_GET['seccodeverify'];
            if($_G['setting']['seccodedata']['type'] == '1' && $webbianma == 'gbk' && $_GET['inajax']){
                $getseccodeverify = diconv($getseccodeverify, 'UTF-8', 'GBK');
            }
            if (!check_seccode($getseccodeverify, $_GET['seccodehash'])) {
                echo json_encode(array('code' => -1,'data' => 'seccode_invalid'));
                exit;
            }
        }
        
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $user =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
            //20170805 add end
            
            //20170920 add start
            if($type == 5 && $_config['g_jiebangyzoldphone']){
                $olduser =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_uid($_G['uid']);
                $oldmobile = $olduser['mobile'];
            }
            //20170920 add end
        }else{
            $user = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
            //20170920 add start
            if($type == 5 && $_config['g_jiebangyzoldphone']){
                $olduser =  C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_uid($_G['uid']);
                $oldmobile = $olduser['phone'];
            }
            //20170920 add end
        }
     
       
        if ($user && ($type == 1 || $type == 2)) {
            echo json_encode(array('code' => -1,'data' => 'err_phonebind'));
            exit;
        }
        /*
        if (!$user && $type == 3) {
            echo json_encode(array('code' => -1,'data' => 'err_nouser'));
            exit;
        }
        */
        //20171222 add start
        if (!$user && $type == 3) {
            if(!$_config['g_phoneloginreg']){
                echo json_encode(array('code' => -1,'data' => 'err_nouser'));
                exit;
            }     
        }
        //20171222 add end
        if (!$user && $type == 4) {
            echo json_encode(array('code' => -1,'data' => 'err_nouser'));
            exit;
        }
        
        
        if ((!$user || $phone != $oldmobile) && $type == 5 && $_config['g_jiebangyzoldphone']) {
            echo json_encode(array('code' => -1,'data' => 'err_oldphonebind'));
            exit;
        }
        
        
        $phonesendcount = C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->count_by_phone_day($phone);
        
        if($phonesendcount >= $g_xiane){
            echo json_encode(array('code' => -1,'data' => 'err_seccodexiane'));
            exit;
        }
        
        
        
        if(empty($g_templateid)){
            echo json_encode(array('code' => -1,'data' => 'peizhierror'));
            exit;
        }
        if(empty($g_sign)){
            echo json_encode(array('code' => -1,'data' => 'peizhierror'));
            exit;
        }
        
        $code = generate_code();
        
        if(empty($code) || $code == null){
            echo json_encode(array('code' => -1,'data' => 'generatecodeerror'));
            exit;
        }
        
        
        $sms_param_array = array();
        $sms_param_array['code']=(string)$code;
        
        if(($type == 1 && $_config['g_openregisterproduct']) || ($type == 2 && $_config['g_openyanzhengproduct']) || ($type == 3 && $_config['g_openloginproduct']) || ($type == 4 && $_config['g_openmimaproduct']) || ($type == 5 && $_config['g_openyanzhengproduct'])){
            $g_product = $_config['g_product'];
            $sms_param_array['product']=!empty($g_product)?$g_product:'';
            $sms_param_array['product'] = getbianma($sms_param_array['product'],$webbianma,$g_isopenhtmlspecialchars);
        }
        
        
        $sms_param = json_encode($sms_param_array);
   
        
        $g_sign=getbianma($g_sign,$webbianma,$g_isopenhtmlspecialchars);
        
        //quoqishijian
        $g_youxiaoqi = $_config['g_youxiaoqi'];
        if(empty($g_youxiaoqi)){
            $g_youxiaoqi = 600;
        }
        //echo "====".date('Y-m-d H:i:s',strtotime("+".$g_youxiaoqi." second"));exit;
        $expire = strtotime("+".$g_youxiaoqi." second");
        
        $uid = $_G['uid'];
        
       
        $retdata = "";
        $phonecode = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone($phone);
        if ($phonecode) {
            if (($phonecode['dateline'] + 60) > TIMESTAMP) {
                echo json_encode(array('code' => -1,'data' => 'err_seccodefasong'));
                exit;
            } else {
                $smstools = new SMSTools();
                $smstools->__construct($g_accesskeyid, $g_accesskeysecret);
                $retdata = $smstools->smssend($code,$expire,$type,$uid,$phone,$g_sign,$g_templateid,$sms_param,$clentip);
            }
        } else {
                $smstools = new SMSTools();
                $smstools->__construct($g_accesskeyid, $g_accesskeysecret);
                $retdata = $smstools->smssend($code,$expire,$type,$uid,$phone,$g_sign,$g_templateid,$sms_param,$clentip);
        }
      

        switch ($retdata){
            case 'success':
                echo json_encode(array('code' => 200,'data' => 'smssuccess'));
                break;
            case 'isv.MOBILE_NUMBER_ILLEGAL':
                echo json_encode(array('code' => -1,'data' => 'isvMOBILE_NUMBER_ILLEGAL'));
                break;
            case 'isv.BUSINESS_LIMIT_CONTROL':
                echo json_encode(array('code' => -1,'data' => 'isvBUSINESS_LIMIT_CONTROL'));
                break;
            case 'error':
                echo json_encode(array('code' => -1,'data' => 'smserror'));
                break;
            default:
                echo json_encode(array('code' => -1,'data' => 'smserror'));
                break;
        }
        
    } else {
        include template('jzsjiale_sms:sendseccode');
    }
}elseif ($act == 'sendseccodeold') {
    
    include template('jzsjiale_sms:sendseccode2');
}elseif ($act == 'sendseccodealone') {
    
    include template('jzsjiale_sms:sendseccodealone');
}elseif ($act == 'sendseccodereg') {
    
    include template('jzsjiale_sms:sendseccodereg');
}elseif ($act == 'sendseccodelostpw') {
    
    include template('jzsjiale_sms:sendseccodelostpw');
}elseif ($act == 'lostpw') {
    global $_G;
    $_config = $_G['cache']['plugin']['jzsjiale_sms'];
 
    if (!$_config['g_openpczhaohui']) {
        showmessage(lang('plugin/jzsjiale_sms', 'err_gongnengweikaiqi'));
    }
    if (submitcheck('lostpwsubmit')) {
        $phone = addslashes($_GET['phone_zhaohui']);
        $seccode = addslashes($_GET['seccode']);
        if (! $phone || ! $seccode) {
            showmessage(lang('plugin/jzsjiale_sms', 'paramerror'));
        }
        
        if (! preg_match("/^1[123456789]{1}\d{9}$/", $phone)) {
            showmessage(lang('plugin/jzsjiale_sms', 'bind_phone_error'));
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
        
        
        
        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $userinfo =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
            if(empty($userinfo['mobile'])){
                showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'));
            }
        }else{
            $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
            if(!empty($userinfo)){
                if(empty($userinfo['username'])){
                    C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyid($userinfo['id']);
                    showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'.$userinfo['id']));
                    $userinfo = array();
                }
            }
            
            if(empty($userinfo)){
                showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'));
            }
        }
        
        
        $member = getuserbyuid($userinfo['uid'], 1);
        if (!$member || empty($member['uid'])) {
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyphone($phone);
            showmessage('getpasswd_account_notmatch');
        }elseif ($member['adminid'] == 1 || $member['adminid'] == 2) {
            showmessage('getpasswd_account_invalid');
        }
        
       
        
        $table_ext = $member['_inarchive'] ? '_archive' : '';
        $idstring = random(6);
        C::t('common_member_field_forum' . $table_ext)->update($member['uid'], array('authstr' => "$_G[timestamp]\t1\t$idstring"));
        require libfile('function/member');
        $sign = make_getpws_sign($member['uid'], $idstring);
        showmessage('getpasswd_send_succeed', "member.php?mod=getpasswd&uid=".$member['uid']."&id=".$idstring."&sign=".$sign, array(), array('showdialog' => 0, 'locationtime' => true));
    }
}elseif ($act == 'aloneregister') {
    global $_G;
    $_config = $_G['cache']['plugin']['jzsjiale_sms'];
 
    if (!$_config['g_openpcregister']) {
        showmessage(lang('plugin/jzsjiale_sms', 'err_gongnengweikaiqi'));
    }
    if (!$_config['g_pcregphonetab']) {
        showmessage(lang('plugin/jzsjiale_sms', 'err_pcregphonetabweikaiqi'));
    }
    if ($formhash == FORMHASH && $_GET['jzsjiale_sms_alone_submit']) {
        
        if ($_config['g_pcregqzmobile']) {
            showmessage(lang('plugin/jzsjiale_sms', 'registererror'));
            exit;
        }
        
        if(isset($_GET["inajax"]) && $_GET["inajax"]==1){
            $phone = addslashes($_GET['alone_phone_reg']);
            $seccode = addslashes($_GET['alone_seccode']);
            $username = addslashes($_GET['alone_username']);
            $password = addslashes($_GET['alone_password1']);
            $password2 = addslashes($_GET['alone_password2']);
            $referer = addslashes($_GET['referer']);
            
            if(empty($phone)){
                showmessage(lang('plugin/jzsjiale_sms', 'phonenull'));
            }
            
            if (!preg_match("/^1[123456789]{1}\d{9}$/", $phone)) {
                showmessage(lang('plugin/jzsjiale_sms', 'bind_phone_error'));
            }
            
            if(empty($seccode)){
                showmessage(lang('plugin/jzsjiale_sms', 'seccodenull'));
            }
            
            if(empty($password)){
                showmessage(lang('plugin/jzsjiale_sms', 'passwordnull'));
            }
            
            if (strlen($password)<6) {
                showmessage(lang('plugin/jzsjiale_sms', 'password6'));
            }
            
            if(empty($password2)){
                showmessage(lang('plugin/jzsjiale_sms', 'password2null'));
            }
            
            if (strlen($password2)<6) {
                showmessage(lang('plugin/jzsjiale_sms', 'password6'));
            }
            
            if($password != $password2){
                showmessage(lang('plugin/jzsjiale_sms', 'passworderr'));
            }
           
            if(!$_config['g_pcregphoneusername']){
                if($_config['g_phoneisusername']){
                    $username = $phone;
                }else{
                    $username = $_config['g_pcregphoneqianzhui'].get_rand_username(8);
                }
                
            }else{
                if(empty($username)){
                    showmessage(lang('plugin/jzsjiale_sms', 'usernamenull'));
                }
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
            
            
            //$username = iconv('UTF-8', CHARSET.'//ignore', urldecode($username));
            
            $email = "reg_".substr($phone,0,3).time().substr($phone,7,4)."@null.null";
            
            
            $user = C::t('common_member')->fetch_by_username($username);
            if($user){
                if(!$_config['g_pcregphoneusername']){
                    $username = $_config['g_pcregphoneqianzhui'].get_rand_username(8);
                    
                    $user = C::t('common_member')->fetch_by_username($username);
                    if($user){
                        showmessage(lang('plugin/jzsjiale_sms', 'usernamecunzai'));
                        exit;
                    }
                }else{
                    showmessage(lang('plugin/jzsjiale_sms', 'usernamecunzai'));
                    exit;
                }
                
            }
           
            $profile = array (
                "mobile" => $phone,
            );
            require_once DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/uc.inc.php';
            
            $isreturnuid = 0;
            $isopenmemail = 0;
            if(!$_config['g_discuzf']){
                $uid = UC::regist($username,$password,$email,$profile,$isreturnuid,$isopenmemail);
            }else{
                $uid = UC::regist_new($username,$password,$email,$codeinfo['phone'],$profile,$isreturnuid,$isopenmemail);
            }
            
         
            if (!is_numeric($uid)) {
                if($uid == "username_len_invalid"){
                    showmessage(lang('plugin/jzsjiale_sms', 'username_len_invalid_error'));
                    exit;
                }elseif($uid == "password_len_invalid"){
                    showmessage(lang('plugin/jzsjiale_sms', 'password_len_invalid_error'));
                    exit;
                }else{
                    showmessage(lang('plugin/jzsjiale_sms', 'registererror'));
                    exit;
                }
                
            }else{
                if ($uid<=0) {
                    switch ($uid) {
                        case -4:showmessage(lang('plugin/jzsjiale_sms', 'registeremailillegal')); exit(); break;
                        case -5:showmessage(lang('plugin/jzsjiale_sms', 'registeremailillegal')); exit(); break;
                        case -6:showmessage(lang('plugin/jzsjiale_sms', 'registeremailduplicate')); exit(); break;
                        default: showmessage(lang('plugin/jzsjiale_sms', 'registererror')); exit(); break;
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
             
                $url_forward = !empty($referer)?$referer:$_G['siteurl'];
                $param = array('bbname' => $_G['setting']['bbname'], 'username' => $_G['username'], 'usergroup' => $_G['group']['grouptitle'], 'uid' => $_G['uid']);
    			if(strpos($url_forward, $_G['setting']['regname']) !== false || strpos($url_forward, 'buyinvitecode') !== false) {
    				$url_forward = 'forum.php';
    			}
    			$refreshtime = 10000;
    			$message = 'register_succeed';
    			$locationmessage = 'register_succeed_location';
    			$href = str_replace("'", "\'", $url_forward);
    			$extra = array(
    				'showid' => 'succeedmessage',
    				'extrajs' => '<script type="text/javascript">'.
    					'setTimeout("window.location.href =\''.$href.'\';", '.$refreshtime.');'.
    					'$(\'succeedmessage_href\').href = \''.$href.'\';'.
    					'$(\'main_message\').style.display = \'none\';'.
    					'$(\'main_succeed\').style.display = \'\';'.
    					'$(\'succeedlocation\').innerHTML = \''.lang('message', $locationmessage).'\';'.
    				'</script>',
    				'striptags' => false,
    			);
                
                showmessage($message, $url_forward,$param,$extra);
                exit();
            
            }else{
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                showmessage(lang('plugin/jzsjiale_sms', 'registersuccess_phoneerror'));
                exit();
            }
            
        }else{
            showmessage(lang('plugin/jzsjiale_sms', 'paramerror'));
        }
        
    }else{
        $url ="member.php?mod=".$_G['setting']['regname'];
        header("Location: $url");
        die(0);
    }
}

function getbianma($data, $webbianma = "gbk",$openhtmlspecialchars = true)
{
    if ($webbianma == "gbk") {
        $data = diconv($data, 'GBK', 'UTF-8');
    }
    if($openhtmlspecialchars){
        $data = isset($data) ? trim(htmlspecialchars($data, ENT_QUOTES)) : '';
    }
    return $data;
}

function generate_code($length = 6)
{
    return rand(pow(10, ($length - 1)), pow(10, $length) - 1);
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