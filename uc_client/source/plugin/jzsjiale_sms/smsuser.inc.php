<?php


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$act = $_GET['act'];
$formhash =  $_GET['formhash']? $_GET['formhash']:'';
loadcache('plugin');
global $_G, $lang;


require_once DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/utils.class.php';
$utils = new Utils();



if($act=='bangding'){
    if(submitcheck('submit')){

        $setting = $_GET['setting'];
        $dsp = array('dateline'=>TIMESTAMP);
        $dsp['username'] = daddslashes(trim($setting['username']));
        $dsp['phone'] = daddslashes(trim($setting['phone']));

        if(empty($dsp['username'])){
            cpmsg('jzsjiale_sms:dusername_null', '', 'error');
        }
        if(empty($dsp['phone'])){
            cpmsg('jzsjiale_sms:dphone_null', '', 'error');
        }
        if(!$utils->isMobile($dsp['phone'])){
            cpmsg('jzsjiale_sms:dphoneerror_null', '', 'error');
        }
       
        
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $smsuser =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($dsp['phone']);
            if(!empty($smsuser)){
                cpmsg('jzsjiale_sms:phonecunzai', '', 'error');
            }
        }else{
            $smsuser = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($dsp['phone']);
            
            if(!empty($smsuser)){
                cpmsg('jzsjiale_sms:phonecunzai', '', 'error');
            }
            
            $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_username($dsp['username']);
             
            if(!empty($userinfo)){
                cpmsg('jzsjiale_sms:err_yibangding', '', 'error');
            }
        }

        
        
        $member = C::t('common_member')->fetch_by_username($dsp['username']);
        
        if(empty($member)){
            cpmsg('jzsjiale_sms:nousername', '', 'error');
        }

        $dsp['uid'] = $member['uid'];

        //weibaochitongbuxianshanchu
        C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($dsp['uid']);
        
        if(C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($dsp,true)){
          
            C::t('common_member_profile')->update($dsp['uid'], array('mobile'=> $dsp['phone']));
            
            //verify start
            if($_config['g_isopenautoverify'] && $_config['g_mobileverify']){
                $verifyuid = $dsp['uid'];
                $memberautoverify = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$verifyuid);
                if(empty($memberautoverify)){
                    C::t('common_member_verify')->insert(array('uid' => $verifyuid,'verify'.$_config['g_mobileverify'] => 1));
                }else{
                    C::t('common_member_verify')->update($verifyuid, array('verify'.$_config['g_mobileverify'] => 1));
                }
            
            }
            
            
            //verify end
            
            //gengxinrenzheng start
            $memberverifyres = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$dsp['uid']);
            for ($verifyx=1; $verifyx<=7; $verifyx++) {
                if($memberverifyres['verify'+$verifyx] != 1){
                    $memberverifyinfores = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify_info').' WHERE uid = '.$dsp['uid'].' and verifytype = '.$verifyx);
                    if(empty($memberverifyinfores)){
                        continue;
                    }
                    $verifyinfo = dunserialize($memberverifyinfores['field']);
                    $verifyinfo['mobile'] = $dsp['phone'];
                     
                    C::t('common_member_verify_info')->update($memberverifyinfores['vid'], array('field' => serialize($verifyinfo)));
                }
            }
            //gengxinrenzheng end
            
            cpmsg('jzsjiale_sms:addok', 'action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=smsuser', 'succeed');
        }else{
            cpmsg('jzsjiale_sms:error', '', 'error');
        }
    }


    showformheader('plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=smsuser&act=bangding', 'enctype');
    showtableheader(plang('addbangdingtitle'), '');
    showsetting(plang('dusername'),'setting[username]','','text','','',plang('dusername_msg'));
    showsetting(plang('dphone'),'setting[phone]','','text','','',plang('dphone_msg'));

    showsubmit('submit', 'submit');
    showtablefooter();
    showformfooter();

    exit();
}elseif($act=='jiechubangding'){
	$id = dintval($_GET['id']);
	$setting = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch($id);
	if(empty($setting))
		cpmsg('jzsjiale_sms:empty', '', 'error');
	if(submitcheck('submit')){
		C::t('#jzsjiale_sms#jzsjiale_sms_user')->delete($id);
		C::t('common_member_profile')->update($setting['uid'], array('mobile'=> ''));
		
		global $_G;
		$_config = $_G['cache']['plugin']['jzsjiale_sms'];
		//verify start
		if($_config['g_mobileverify']){
		    $verifyuid = $setting['uid'];
		    $memberautoverify = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$verifyuid);
		    if(empty($memberautoverify)){
		        C::t('common_member_verify')->insert(array('uid' => $verifyuid,'verify'.$_config['g_mobileverify'] => 0));
		    }else{
		        C::t('common_member_verify')->update($verifyuid, array('verify'.$_config['g_mobileverify'] => 0));
		    }
		
		}
		
		
		//verify end
		
		//gengxinrenzheng start
		$memberverifyres = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$setting['uid']);
		for ($verifyx=1; $verifyx<=7; $verifyx++) {
		    if($memberverifyres['verify'+$verifyx] != 1){
		        $memberverifyinfores = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify_info').' WHERE uid = '.$setting['uid'].' and verifytype = '.$verifyx);
		        if(empty($memberverifyinfores)){
		            continue;
		        }
		        $verifyinfo = dunserialize($memberverifyinfores['field']);
		        $verifyinfo['mobile'] = "";
		         
		        C::t('common_member_verify_info')->update($memberverifyinfores['vid'], array('field' => serialize($verifyinfo)));
		    }
		}
		//gengxinrenzheng end
		
		cpmsg('jzsjiale_sms:jiechuok', 'action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=smsuser', 'succeed');
	}
	cpmsg('jzsjiale_sms:delbangding','action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=smsuser&act=jiechubangding&id='.$id.'&submit=yes','form',array('username' => $setting['username']));
}


/////////tip start

echo '<div class="colorbox"><h4>'.plang('aboutsmsuser').'</h4>'.
    '<table cellspacing="0" cellpadding="3"><tr>'.
    '<td valign="top">'.plang('smsuserdescription').'</td></tr></table>'.
    '<div style="width:95%" align="right">'.plang('copyright').'</div></div>';

/////////tip end

showformheader('plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=smsuser', 'enctype');


$keyword = daddslashes(trim($_GET['keyword']));

$page = intval($_GET['page']);
$page = $page > 0 ? $page : 1;
$pagesize = 20;
$start = ($page - 1) * $pagesize;

//20170703start
$map = array();
if(!empty($keyword)){
    $map['keyword'] = $keyword;
}


$allsmsuser = C::t('#jzsjiale_sms#jzsjiale_sms_user')->range_by_map($map,$start,$pagesize,'DESC');
$count = C::t('#jzsjiale_sms#jzsjiale_sms_user')->count_by_map($map);


showtablerow('', array('width="150"', 'width="150"', 'width="150"', ''), array(
plang('userphone'),
"<input size=\"20\" name=\"keyword\" type=\"text\" value=\"$keyword\" />
<input class=\"btn\" type=\"submit\" value=\"" . cplang('search') . "\" />"
    )
);

showtableheader(plang('smsuserlist').'(&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=smsuser&act=bangding" style="color:red;">'.plang('bangding').'</a>&nbsp;&nbsp;&nbsp;&nbsp;)');
showsubtitle(plang('smsuserlisttitle'));
foreach($allsmsuser as $d){
    showtablerow('', array('width="50"'), array(
    $d['id'],
    '<span title="'.$d['uid'].'"><a href="home.php?mod=space&uid='.$d['uid'].'&do=profile" target="_blank">'.$d['uid'].'</a></span>',
    '<span title="'.$d['uid'].'"><a href="home.php?mod=space&uid='.$d['uid'].'&do=profile" target="_blank">'.$d['username'].'</a></span>',
    $d['phone'],
    dgmdate($d['dateline']),
    '<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=smsuser&act=jiechubangding&id='.$d['id'].'" style="color:red;">'.plang('jiechubangding').'</a>')
    );
}

$mpurl = ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=smsuser&keyword='.$keyword;
$multipage = multi($count, $pagesize, $page, $mpurl);
//showsubmit('', '', '', '', $multipage);


//search start
showsubmit('', '', '', '', $multipage);

//search end


showtablefooter();
showformfooter();




function plang($str) {
    return lang('plugin/jzsjiale_sms', $str);
}
?>