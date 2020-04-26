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


if($act=='quxiaorenzheng'){
	$uid = dintval($_GET['uid']);
	
    $setting = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$uid);
                    
	if(empty($setting))
		cpmsg('jzsjiale_sms:empty', '', 'error');
	if(submitcheck('submit')){
	
	    global $_G;
	    $_config = $_G['cache']['plugin']['jzsjiale_sms'];
	   
	    if(!$_config['g_mobileverify']){
	        cpmsg('jzsjiale_sms:qingshezhiverify', '', 'error');
	    }
	    if($setting['verify'.$_config['g_mobileverify']]){
	        C::t('common_member_verify')->update($uid, array('verify'.$_config['g_mobileverify'] => 0));
	    }
		cpmsg('jzsjiale_sms:delrenzhengok', 'action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=dzverify', 'succeed');
	}
	cpmsg('jzsjiale_sms:delrenzheng','action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=dzverify&act=quxiaorenzheng&uid='.$uid.'&submit=yes','form',array('username' => $setting['uid']));
}elseif($act=='lijirenzheng'){
    $uid = dintval($_GET['uid']);
    $memberprofile = DB::fetch_first('SELECT * FROM '.DB::table('common_member_profile').' WHERE uid = '.$uid);
    if(empty($memberprofile['mobile'])){
        cpmsg('jzsjiale_sms:renzhengnomobile', 'action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=dzverify', 'error');
    }
    
    if(submitcheck('submit')){
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        if($_config['g_mobileverify']){
            $memberautoverify = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$uid);
            if(empty($memberautoverify)){
                C::t('common_member_verify')->insert(array('uid' => $uid,'verify'.$_config['g_mobileverify'] => 1));
            }else{
                C::t('common_member_verify')->update($uid, array('verify'.$_config['g_mobileverify'] => 1));
            }
            cpmsg('jzsjiale_sms:renzhengchenggong', 'action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=dzverify', 'succeed');
        }else{
            cpmsg('jzsjiale_sms:qingshezhiverify', '', 'error');
        }
    }
    cpmsg('jzsjiale_sms:lijirenzhengtip','action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=dzverify&act=lijirenzheng&uid='.$uid.'&submit=yes','form',array('username' => $uid));
}elseif($act=='yijianquanburenzheng'){
	if(submitcheck('submit') || $formhash == FORMHASH){
	    
	    global $_G;
	    $_config = $_G['cache']['plugin']['jzsjiale_sms'];
	    if(!$_config['g_mobileverify']){
	        cpmsg('jzsjiale_sms:qingshezhiverify', '', 'error');
	    }
	    
	    $g_verifynum = ($_config['g_verifynum']>0)?$_config['g_verifynum']:50;
	 
	    
	    $pertask = dintval(daddslashes(trim($_GET['pertask'])))?dintval(daddslashes(trim($_GET['pertask']))):$g_verifynum;
	    $next = dintval(daddslashes(trim($_GET['next'])));
	    if($next){
	        $current = $next;
	    }else{
	        $current = 0;
	    }
	    $nextcurrent = $current + ($pertask-1);
	    $next = $nextcurrent+1;
	    $nextlink = "action=plugins&operation=config&do=$pluginid&identifier=jzsjiale_sms&pmod=dzverify&act=yijianquanburenzheng&next=$next&pertask=$pertask&formhash=".FORMHASH;
	     
	    
	    $count = C::t('#jzsjiale_sms#jzsjiale_sms_member')->count_by_isexists_mobile(true);
	    $dzuserlist = C::t('#jzsjiale_sms#jzsjiale_sms_member')->range_by_isexists_mobile(true,$current,$pertask,'DESC');

	    foreach ($dzuserlist as $dzu){
	        if(!empty($dzu['mobile'])){
	            $memberautoverify = DB::fetch_first('SELECT * FROM '.DB::table('common_member_verify').' WHERE uid = '.$dzu['uid']);
	            if(empty($memberautoverify)){
	                C::t('common_member_verify')->insert(array('uid' => $dzu['uid'],'verify'.$_config['g_mobileverify'] => 1));
	            }else{
	                if(!$memberautoverify['verify'.$_config['g_mobileverify']]){
	                    C::t('common_member_verify')->update($dzu['uid'], array('verify'.$_config['g_mobileverify'] => 1));
	                }
	                
	            }
	        }
	    }
	    
	    if($current < $count-1){
	        cpmsg(plang('curr_createverify').cplang('counter_processing', array('current' => ($current+1), 'next' => ($nextcurrent+1))), $nextlink, 'loading',array('count' => $count));
	    }else{
	        cpmsg('jzsjiale_sms:caozuowancheng', 'action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=dzverify', 'succeed');
	    }
	    
	}
	cpmsg('jzsjiale_sms:yijianquanburenzheng','action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=dzverify&act=yijianquanburenzheng&submit=yes','form',array());
}elseif($act=='yijianquanbuquxiaorenzheng'){
	if(submitcheck('submit')){
	    global $_G;
	    $_config = $_G['cache']['plugin']['jzsjiale_sms'];
	    if(!$_config['g_mobileverify']){
	        cpmsg('jzsjiale_sms:qingshezhiverify', '', 'error');
	    }
	    
	    DB::query("UPDATE ".DB::table('common_member_verify')." SET verify".$_config['g_mobileverify']." = 0");
		cpmsg('jzsjiale_sms:caozuowancheng', 'action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=dzverify', 'succeed');
	}
	cpmsg('jzsjiale_sms:yijianquanbuquxiaorenzheng','action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=dzverify&act=yijianquanbuquxiaorenzheng&submit=yes','form',array());
}elseif($act=='qingchuwushoujihaorenzheng'){
    if(submitcheck('submit') || $formhash == FORMHASH){
	    
	    global $_G;
	    $_config = $_G['cache']['plugin']['jzsjiale_sms'];
	    if(!$_config['g_mobileverify']){
	        cpmsg('jzsjiale_sms:qingshezhiverify', '', 'error');
	    }
	    
	    $pertask = dintval(daddslashes(trim($_GET['pertask'])))?dintval(daddslashes(trim($_GET['pertask']))):50;
	    $next = dintval(daddslashes(trim($_GET['next'])));
	    if($next){
	        $current = $next;
	    }else{
	        $current = 0;
	    }
	    $nextcurrent = $current + ($pertask-1);
	    $next = $nextcurrent+1;
	    $nextlink = "action=plugins&operation=config&do=$pluginid&identifier=jzsjiale_sms&pmod=dzverify&act=qingchuwushoujihaorenzheng&next=$next&pertask=$pertask&formhash=".FORMHASH;
	    
	     
	    $count = C::t('#jzsjiale_sms#jzsjiale_sms_member')->count_common_member_verify();
	    $dzuserverifylist = C::t('#jzsjiale_sms#jzsjiale_sms_member')->range_common_member_verify($current,$pertask,'DESC');

	    foreach ($dzuserverifylist as $dzu){
	        if($dzu['verify'.$_config['g_mobileverify']]){
	            $dzuserlist = DB::fetch_first('SELECT * FROM '.DB::table('common_member_profile').' WHERE uid = '.$dzu['uid']);
	            if(empty($dzuserlist['mobile'])){
	                C::t('common_member_verify')->update($dzu['uid'], array('verify'.$_config['g_mobileverify'] => 0));
	            }
	        }
	    }
	    
	    if($current < $count-1){
	        cpmsg(plang('curr_cancelverify').cplang('counter_processing', array('current' => ($current+1), 'next' => ($nextcurrent+1))), $nextlink, 'loading',array('count' => $count));
	    }else{
	        cpmsg('jzsjiale_sms:caozuowancheng', 'action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=dzverify', 'succeed');
	    }
	    
	}
	cpmsg('jzsjiale_sms:qingchuwushoujihaorenzheng','action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=dzverify&act=qingchuwushoujihaorenzheng&submit=yes','form',array());
}


/////////tip start

echo '<div class="colorbox"><h4>'.plang('aboutdzverify').'</h4>'.
    '<table cellspacing="0" cellpadding="3"><tr>'.
    '<td valign="top">'.plang('dzverifydescription').'</td></tr></table>'.
    '<div style="width:95%" align="right">'.plang('copyright').'</div></div>';

/////////tip end

showformheader('plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=dzverify', 'enctype');


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

global $_G;
$_config = $_G['cache']['plugin']['jzsjiale_sms'];
if(!$_config['g_mobileverify']){
    cpmsg('jzsjiale_sms:qingshezhiverify', '', 'error');
}

$alldzverify = C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_all_with_username_and_mobile_verify($keyword,$start,$pagesize,'DESC',$_config['g_mobileverify']);
$count = C::t('#jzsjiale_sms#jzsjiale_sms_member')->count_all_with_username_and_mobile_verify($keyword);


showtablerow('', array('width="150"', 'width="150"', 'width="150"', ''), array(
plang('userphone'),
"<input size=\"20\" name=\"keyword\" type=\"text\" value=\"$keyword\" />
<input class=\"btn\" type=\"submit\" value=\"" . cplang('search') . "\" />"
    )
);

showtableheader(plang('dzverifylist').'(&nbsp;&nbsp;<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=dzverify&act=yijianquanburenzheng" style="color:red;">'.plang('yijianquanburenzheng').'</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=dzverify&act=yijianquanbuquxiaorenzheng" style="color:red;">'.plang('yijianquanbuquxiaorenzheng').'</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=dzverify&act=qingchuwushoujihaorenzheng" style="color:blue;">'.plang('qingchuwushoujihaorenzheng').'</a>&nbsp;&nbsp;)');
showsubtitle(plang('dzverifylisttitle'));
foreach($alldzverify as $d){
    showtablerow('', array('width="50"'), array(
    '<span title="'.$d['uid'].'"><a href="home.php?mod=space&uid='.$d['uid'].'&do=profile" target="_blank">'.$d['uid'].'</a></span>',
    '<span title="'.$d['uid'].'"><a href="home.php?mod=space&uid='.$d['uid'].'&do=profile" target="_blank">'.$d['username'].'</a></span>',
    $d['phone'],
    $d['verify']?'<span title="'.plang("renzhengchenggong").'" style="color:green;font-weight:bold;">'.plang("renzhengchenggong").'</span>':'<span title="'.plang("weirenzheng").'" style="color:red;">'.plang("weirenzheng").'</span>',
    dgmdate($d['dateline']),
    $d['verify']? '<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=dzverify&act=quxiaorenzheng&uid='.$d['uid'].'" style="color:red;">'.plang('quxiaorenzheng').'</a>':'<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=dzverify&act=lijirenzheng&uid='.$d['uid'].'" style="color:green;">'.plang('lijirenzheng').'</a>')
    );
}

$mpurl = ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=dzverify&keyword='.$keyword.'&op='.$_GET['op'];
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