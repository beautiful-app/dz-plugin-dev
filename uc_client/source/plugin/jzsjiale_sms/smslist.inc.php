<?php


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$act = $_GET['act'];
$formhash =  $_GET['formhash']? $_GET['formhash']:'';
global $_G, $lang;

require_once DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/utils.class.php';
$utils = new Utils();

if($act=='qingliweek1'){
    if(submitcheck('submit')){
        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteweek1();
        C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteweek1();
        cpmsg('jzsjiale_sms:qingliweek1ok', 'action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=smslist', 'succeed');
    }
    cpmsg('jzsjiale_sms:delqingliweek1','action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=smslist&act=qingliweek1&submit=yes','form');
}


//zongtiaoshu
loadcache('plugin');
$_config = $_G['cache']['plugin']['jzsjiale_sms'];
$phonesendallcount = C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->count_all_by_day();
$g_zongxiane = ($_config['g_zongxiane']>0)?$_config['g_zongxiane']:0;
echo "<span style='color:red;'>".plang("zongxiane")."</span>".($g_zongxiane?$g_zongxiane:plang("zongxianebuxiane"));
echo "<br/>";
echo "<span style='color:red;'>".plang("jinrifasong")."</span>".$phonesendallcount;


showformheader('plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=smslist', 'enctype');


$phone = daddslashes(trim($_GET['phone']));
$status = daddslashes(trim($_GET['status']));
$date = daddslashes(trim($_GET['date']));

$page = intval($_GET['page']);
$page = $page > 0 ? $page : 1;
$pagesize = 20;
$start = ($page - 1) * $pagesize;

//20170703start
$map = array();
if(!empty($phone)){
    $map['phone'] = $phone;
}
if(!empty($status) || $status == '0'){
    $map['status'] = $status;
}
if(!empty($date)){
    $map['dateline'] = strtotime($date);
}

$allsmslist = C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->range_by_map($map,$start,$pagesize,'DESC');
$count = C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->count_by_map($map);


$status_options = "<option value=\"all\"  " . (($status == 'all') ? "selected='selected'" : '') . ">" . plang('statusall') . "</option>"
                . "<option value='0' " . (($status == '0') ? "selected='selected'" : '') . ">" . plang('statuserror') . "</option>"
                . "<option value='1' " . (($status == '1') ? "selected='selected'" : '') . ">" . plang('statussuccess') . "</option>";

echo '<script src="static/js/calendar.js" type="text/javascript"></script>';
showtablerow('', array('width="50"', 'width="60"', 'width="75"', 'width="80"', 'width="50"', 'width="60"', 'width="40"', ''), array(
plang('smsstarttime'),
"<input type=\"text\" onclick=\"showcalendar(event, this)\" value=\"$date\" name=\"date\" class=\"txt\">",
plang('smsstatus'),
"<select name=\"status\">$status_options</select>",
plang('smsphone'),
"<input size=\"20\" name=\"phone\" type=\"text\" value=\"$phone\" />
<input class=\"btn\" type=\"submit\" value=\"" . cplang('search') . "\" />"
    )
);

echo '<br/><span style="color:red;">'.plang('ipchecktip').'</span><br/>';
showtableheader(plang('smslist').'(&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=smslist&act=qingliweek1" style="color:red;">'.plang('qingliweek1').'</a>&nbsp;&nbsp;&nbsp;&nbsp;)');
showsubtitle(plang('smslisttitle'));
foreach($allsmslist as $d){
    showtablerow('', array('width="50"'), array(
    $d['id'],
    $d['phone'],
    '<span title="'.$d['uid'].'"><a href="home.php?mod=space&uid='.$d['uid'].'&do=profile" target="_blank">'.$d['uid'].'</a></span>',
    $d['seccode'],
    $utils->gettype($d['type']),
    $utils->getstatus($d['status']),
    dgmdate($d['dateline']),
    $d['msg'],
    $d['ip'])
    );
}

$mpurl = ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=smslist&phone='.$phone.'&status='.$status.'&date='.$date;
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