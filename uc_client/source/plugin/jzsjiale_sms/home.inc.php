<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}


loadcache('plugin');
global $_G, $lang;

$formhash =  addslashes($_GET['formhash'])? addslashes($_GET['formhash']):'';

if ($formhash == FORMHASH) {

    global $_G;
    $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
    if($_GET['jiechubangding']){
        
        if($_config['g_isopenbangdingmima']){
            loaducenter();
            list($result) = uc_user_login($_G['uid'], $_GET['password'], 1, 0);
            if ($result < 0){
                showmessage(plang('mimaerror'));
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
                showmessage(plang('getoldmobilenull'));
            }
            if (!$oldseccode){
                showmessage(plang('getoldseccodenull'));
            }
            
            $oldcodeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($oldmobile,$oldseccode);
            if ($oldcodeinfo) {
                if ((TIMESTAMP - $oldcodeinfo[dateline]) > $_config['g_youxiaoqi']) {
                    C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($oldmobile,$oldseccode);
                    //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
                    showmessage(plang('err_oldseccodeguoqi'));
                }
            } else {
                showmessage(plang('err_oldseccodeerror'));
            }
        }
        
        if($_config['g_tongyiuser']){
            
            if($_config['g_jiebangnewphone']){
                $phone = daddslashes($_GET['phone']);
                $seccode = daddslashes($_GET['seccode']);
                if (!$phone || !$seccode){
                    showmessage(plang('paramerror'));
                }
                if (!preg_match("/^1[123456789]{1}\d{9}$/", $phone)) {
                    showmessage(plang('bind_phone_error'));
                }
                $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($phone,$seccode);
                if ($codeinfo) {
                    if ((TIMESTAMP - $codeinfo[dateline]) > $_config['g_youxiaoqi']) {
                        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                        //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
                        showmessage(plang('err_seccodeguoqi'));
                    }
                } else {
                    showmessage(plang('err_seccodeerror'));
                }
                
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                
                if($_config['g_jiebangyzoldphone']){
                    C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($oldmobile,$oldseccode);
                }//f rom ww w.m oqu8. co m
                
                $phoneuser =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
                if(!empty($phoneuser)){
                    showmessage(plang('phonecunzai'));
                }
                
                $member = C::t('common_member')->fetch_by_username($_G['username']);
                
                if(empty($member)){
                    showmessage(plang('nousername'));
                }
                //weibaochitongbuxianshanchu
                C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($_G['uid']);
                
                $data = array(
                    'uid' => $_G['uid'],
                    'username' => $_G['username'],
                    'phone' => $phone,
                    'dateline' => TIMESTAMP
                );
                
                
                C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data,true);
                
                if(C::t('common_member_profile')->update($_G['uid'], array('mobile'=> $phone))){
                    
                    
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
                    
                    //gengxinrenzheng start
                    $memberverifyres = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$_G['uid']);
                    for ($verifyx=1; $verifyx<=7; $verifyx++) {
                        if($memberverifyres['verify'+$verifyx] != 1){
                            $memberverifyinfores = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify_info').' WHERE uid = '.$_G['uid'].' and verifytype = '.$verifyx);
                            if(empty($memberverifyinfores)){
                                continue;
                            }
                            $verifyinfo = dunserialize($memberverifyinfores['field']);
                            $verifyinfo['mobile'] = $phone;
                             
                            C::t('common_member_verify_info')->update($memberverifyinfores['vid'], array('field' => serialize($verifyinfo)));
                        }
                    }
                    //gengxinrenzheng end
                    $tiaozhuanurl = dreferer();
                    if($_config['g_jiechutiaozhuanhome']){
                        $tiaozhuanurl = $_G['siteurl'];
                    }
                    showmessage(plang('chongxinbangdingok'), $tiaozhuanurl, array(), array('alert' => 'right', 'locationtime' => true, 'msgtype' => 2, 'showdialog' => true, 'showmsg' => true));
                }else{
                    showmessage(plang('jiechuerror'));
                }
            }else{
                
                if($_config['g_jiebangyzoldphone']){
                    C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($oldmobile,$oldseccode);
                }
                
                //20170805 add start
                C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($_G['uid']);
                if(C::t('common_member_profile')->update($_G['uid'], array('mobile'=> ''))){
                    
                    //verify start
                    if($_config['g_isopenautoverify'] && $_config['g_mobileverify']){
                        $verifyuid = $_G['uid'];
                        $memberautoverify = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$verifyuid);
                        if(empty($memberautoverify)){
                            C::t('common_member_verify')->insert(array('uid' => $verifyuid,'verify'.$_config['g_mobileverify'] => 0));
                        }else{
                            C::t('common_member_verify')->update($verifyuid, array('verify'.$_config['g_mobileverify'] => 0));
                        }
                    
                    }
                    
                    
                    //verify end
                    
                    
                    //gengxinrenzheng start
                    $memberverifyres = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$_G['uid']);
                    for ($verifyx=1; $verifyx<=7; $verifyx++) {
                        if($memberverifyres['verify'+$verifyx] != 1){
                            $memberverifyinfores = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify_info').' WHERE uid = '.$_G['uid'].' and verifytype = '.$verifyx);
                            if(empty($memberverifyinfores)){
                                continue;
                            }
                            $verifyinfo = dunserialize($memberverifyinfores['field']);
                            $verifyinfo['mobile'] = "";
                             
                            C::t('common_member_verify_info')->update($memberverifyinfores['vid'], array('field' => serialize($verifyinfo)));
                        }
                    }
                    //gengxinrenzheng end
                    
                    $tiaozhuanurl = dreferer();
                    if($_config['g_jiechutiaozhuanhome']){
                        $tiaozhuanurl = $_G['siteurl'];
                    }
                    showmessage(plang('jiechuok'), $tiaozhuanurl, array(), array('alert' => 'right', 'locationtime' => true, 'msgtype' => 2, 'showdialog' => true, 'showmsg' => true));
                }else{
                    showmessage(plang('jiechuerror'));
                }
                //20170805 add end
            }
            
        }else{
            
            if($_config['g_jiebangnewphone']){
                $phone = daddslashes($_GET['phone']);
                $seccode = daddslashes($_GET['seccode']);
                if (!$phone || !$seccode){
                    showmessage(plang('paramerror'));
                }
                if (!preg_match("/^1[123456789]{1}\d{9}$/", $phone)) {
                    showmessage(plang('bind_phone_error'));
                }
                $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($phone,$seccode);
                if ($codeinfo) {
                    if ((TIMESTAMP - $codeinfo[dateline]) > $_config['g_youxiaoqi']) {
                        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                        //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
                        showmessage(plang('err_seccodeguoqi'));
                    }
                } else {
                    showmessage(plang('err_seccodeerror'));
                }
            
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                
                if($_config['g_jiebangyzoldphone']){
                    C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($oldmobile,$oldseccode);
                }
            
                $phoneuser =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
                if(!empty($phoneuser)){
                    showmessage(plang('phonecunzai'));
                }
            
                $member = C::t('common_member')->fetch_by_username($_G['username']);
            
                if(empty($member)){
                    showmessage(plang('nousername'));
                }
                //weibaochitongbuxianshanchu
                C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($_G['uid']);
            
                $data = array(
                    'uid' => $_G['uid'],
                    'username' => $_G['username'],
                    'phone' => $phone,
                    'dateline' => TIMESTAMP
                );
            
            
                if(C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data,true)){
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
                    
                    
                    //gengxinrenzheng start
                    $memberverifyres = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$_G['uid']);
                    for ($verifyx=1; $verifyx<=7; $verifyx++) {
                        if($memberverifyres['verify'+$verifyx] != 1){
                            $memberverifyinfores = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify_info').' WHERE uid = '.$_G['uid'].' and verifytype = '.$verifyx);
                            if(empty($memberverifyinfores)){
                                continue;
                            }
                            $verifyinfo = dunserialize($memberverifyinfores['field']);
                            $verifyinfo['mobile'] = $phone;
                             
                            C::t('common_member_verify_info')->update($memberverifyinfores['vid'], array('field' => serialize($verifyinfo)));
                        }
                    }
                    //gengxinrenzheng end
                    $tiaozhuanurl = dreferer();
                    if($_config['g_jiechutiaozhuanhome']){
                        $tiaozhuanurl = $_G['siteurl'];
                    }
                    showmessage(plang('chongxinbangdingok'), $tiaozhuanurl, array(), array('alert' => 'right', 'locationtime' => true, 'msgtype' => 2, 'showdialog' => true, 'showmsg' => true));
                }else{
                    showmessage(plang('jiechuerror'));
                }
            }else{
                
                if($_config['g_jiebangyzoldphone']){
                    C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($oldmobile,$oldseccode);
                }
                
                if(C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($_G['uid'])){
                    C::t('common_member_profile')->update($_G['uid'], array('mobile'=> ''));
                    
                    
                    //verify start
                    if($_config['g_isopenautoverify'] && $_config['g_mobileverify']){
                        $verifyuid = $_G['uid'];
                        $memberautoverify = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$verifyuid);
                        if(empty($memberautoverify)){
                            C::t('common_member_verify')->insert(array('uid' => $verifyuid,'verify'.$_config['g_mobileverify'] => 0));
                        }else{
                            C::t('common_member_verify')->update($verifyuid, array('verify'.$_config['g_mobileverify'] => 0));
                        }
                    
                    }
                    
                    
                    //verify end
                    
                    
                    //gengxinrenzheng start
                    $memberverifyres = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$_G['uid']);
                    for ($verifyx=1; $verifyx<=7; $verifyx++) {
                        if($memberverifyres['verify'+$verifyx] != 1){
                            $memberverifyinfores = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify_info').' WHERE uid = '.$_G['uid'].' and verifytype = '.$verifyx);
                            if(empty($memberverifyinfores)){
                                continue;
                            }
                            $verifyinfo = dunserialize($memberverifyinfores['field']);
                            $verifyinfo['mobile'] = "";
                             
                            C::t('common_member_verify_info')->update($memberverifyinfores['vid'], array('field' => serialize($verifyinfo)));
                        }
                    }
                    //gengxinrenzheng end
                    $tiaozhuanurl = dreferer();
                    if($_config['g_jiechutiaozhuanhome']){
                        $tiaozhuanurl = $_G['siteurl'];
                    }
                    showmessage(plang('jiechuok'), $tiaozhuanurl, array(), array('alert' => 'right', 'locationtime' => true, 'msgtype' => 2, 'showdialog' => true, 'showmsg' => true));
                }else{
                    showmessage(plang('jiechuerror'));
                }
            }
            
        }
        
    }
    
    
    $phone = daddslashes($_GET['phone']);
    $seccode = daddslashes($_GET['seccode']);
    if (!$phone || !$seccode){
        showmessage(plang('paramerror'));
    }
    if (!preg_match("/^1[123456789]{1}\d{9}$/", $phone)) {
        showmessage(plang('bind_phone_error'));
    }

    if($_config['g_isopenbangdingmima']){
        loaducenter();
        list($result) = uc_user_login($_G['uid'], $_GET['password'], 1, 0);
        if ($result < 0){
            showmessage(plang('mimaerror'));
        }
    }// f rom w w w.m oqu8. co m
    
    $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($phone,$seccode);
    if ($codeinfo) {
        if ((TIMESTAMP - $codeinfo[dateline]) > $_config['g_youxiaoqi']) {
            C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
            //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
            showmessage(plang('err_seccodeguoqi'));
        }
    } else {
        showmessage(plang('err_seccodeerror'));
    }

    C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
    
    
    if($_config['g_tongyiuser']){
        //20170805 add start
        $phoneuser =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
        if(!empty($phoneuser)){
            showmessage(plang('phonecunzai'));
        }
        
        $member = C::t('common_member')->fetch_by_username($_G['username']);
        
        if(empty($member)){
            showmessage(plang('nousername'));
        }
        //weibaochitongbuxianshanchu
        C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($_G['uid']);
        
        $data = array(
            'uid' => $_G['uid'],
            'username' => $_G['username'],
            'phone' => $phone,
            'dateline' => TIMESTAMP
        );
        
        C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data,true);
            
        if(C::t('common_member_profile')->update($_G['uid'], array('mobile'=> $phone))){
            
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
            
            
            //gengxinrenzheng start
            $memberverifyres = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$_G['uid']);
            for ($verifyx=1; $verifyx<=7; $verifyx++) {
                if($memberverifyres['verify'+$verifyx] != 1){
                    $memberverifyinfores = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify_info').' WHERE uid = '.$_G['uid'].' and verifytype = '.$verifyx);
                    if(empty($memberverifyinfores)){
                        continue;
                    }
                    $verifyinfo = dunserialize($memberverifyinfores['field']);
                    $verifyinfo['mobile'] = $phone;
                     
                    C::t('common_member_verify_info')->update($memberverifyinfores['vid'], array('field' => serialize($verifyinfo)));
                }
            }
            //gengxinrenzheng end
            $tiaozhuanurl = dreferer();
            if($_config['g_jiechutiaozhuanhome']){
                $tiaozhuanurl = $_G['siteurl'];
            }
            showmessage(plang('bangdingok'), $tiaozhuanurl, array(), array('alert' => 'right', 'locationtime' => true, 'msgtype' => 2, 'showdialog' => true, 'showmsg' => true));
        }else{
            showmessage(plang('bangdingerror'));
        }
        //20170805 add end
    }else{
        $phoneuser = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
        
        if(!empty($phoneuser)){
            showmessage(plang('phonecunzai'));
        }
        $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_username($_G['username']);
        if(!empty($userinfo)){
            showmessage(plang('err_yibangding'));
        }
        
        $member = C::t('common_member')->fetch_by_username($_G['username']);
        
        if(empty($member)){
            showmessage(plang('nousername'));
        }
        
        //weibaochitongbuxianshanchu
        C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($_G['uid']);
        
        $data = array(
            'uid' => $_G['uid'],
            'username' => $_G['username'],
            'phone' => $phone,
            'dateline' => TIMESTAMP
        );
        
        if(C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data,true)){
        
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
            
            //gengxinrenzheng start
            $memberverifyres = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$_G['uid']);
            for ($verifyx=1; $verifyx<=7; $verifyx++) {
                if($memberverifyres['verify'+$verifyx] != 1){
                    $memberverifyinfores = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify_info').' WHERE uid = '.$_G['uid'].' and verifytype = '.$verifyx);
                    if(empty($memberverifyinfores)){
                        continue;
                    }
                    $verifyinfo = dunserialize($memberverifyinfores['field']);
                    $verifyinfo['mobile'] = $phone;
                     
                    C::t('common_member_verify_info')->update($memberverifyinfores['vid'], array('field' => serialize($verifyinfo)));
                }
            }
            //gengxinrenzheng end
            $tiaozhuanurl = dreferer();
            if($_config['g_jiechutiaozhuanhome']){
                $tiaozhuanurl = $_G['siteurl'];
            }
            showmessage(plang('bangdingok'), $tiaozhuanurl, array(), array('alert' => 'right', 'locationtime' => true, 'msgtype' => 2, 'showdialog' => true, 'showmsg' => true));
        }else{
            showmessage(plang('bangdingerror'));
        }
    }
    
    
}else{
    global $_G;
    $_config = $_G['cache']['plugin']['jzsjiale_sms'];
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
    
}


function plang($str) {
    return lang('plugin/jzsjiale_sms', $str);
}
?>