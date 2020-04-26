<?php


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$act = $_GET['act'];
loadcache('plugin');
global $_G, $lang;

if($act=='saveyuyan'){
    
    $smstpllang = $_GET['smstpllang'];
    
    loadcache('pluginlanguage_template');
    
    foreach ($smstpllang as $stlkey => $stlvalue){
        C::t('#jzsjiale_sms#jzsjiale_sms_tpllang')->updatelang($stlkey,$stlvalue);
        
        $_G['cache']['pluginlanguage_template']['jzsjiale_sms'][$stlkey]=$stlvalue;
    }
    
    $_G['cache']['pluginlanguage_template']['jzsjiale_sms']['diylang']=1;
    
    savecache('pluginlanguage_template', $_G['cache']['pluginlanguage_template']);
    cleartemplatecache();
    
    cpmsg('jzsjiale_sms:update_success', 'action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=yuyan', 'succeed');
    
    
}elseif($act=='recache'){

    $db_smstmplang = C::t('#jzsjiale_sms#jzsjiale_sms_tpllang')->getall();

    loadcache('pluginlanguage_template');
    
    foreach ($db_smstmplang as $stllang){
        
        $_G['cache']['pluginlanguage_template']['jzsjiale_sms'][$stllang['enname']]=$stllang['cnname'];
    }
    
    $_G['cache']['pluginlanguage_template']['jzsjiale_sms']['diylang']=1;
    savecache('pluginlanguage_template', $_G['cache']['pluginlanguage_template']);
    cleartemplatecache();
    
    cpmsg('jzsjiale_sms:cache_success', 'action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=yuyan', 'succeed');


    dexit();
}elseif($act=='huanyuanyuyan'){

    C::t('#jzsjiale_sms#jzsjiale_sms_tpllang')->delall();
    updatecache();
    cleartemplatecache();
    cpmsg('jzsjiale_sms:huanyuanyuyan_success', 'action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=yuyan', 'succeed');


    dexit();
}//f ro m w w w.m oq u8. co m


/////////tip start

echo '<div class="colorbox"><h4>'.plang('aboutyuyan').'</h4>'.
'<table cellspacing="0" cellpadding="3"><tr>'.
'<td valign="top">'.plang('yuyandescription').'</td></tr></table>'.
'<div style="width:95%" align="right">'.plang('copyright').'</div></div>';

/////////tip end
global $_G;
$_config = $_G['cache']['plugin']['jzsjiale_sms'];

if(!$_config['g_isopenyuyan']){
    
    cpmsg('jzsjiale_sms:yuyanweiqiyong', 'action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms', 'error');
    
    dexit();
}

loadcache('pluginlanguage_template');
$jzsjiale_sms_tpl_lang = $_G['cache']['pluginlanguage_template']['jzsjiale_sms'];

$db_smstmplang = C::t('#jzsjiale_sms#jzsjiale_sms_tpllang')->getall();

//$db_smstmplang_enname = array_column($db_smstmplang, 'enname');

$db_smstmplang_enname = array();
foreach ($db_smstmplang as $key => $value) {
    $db_smstmplang_enname[] = $value['enname'];
}

foreach ($jzsjiale_sms_tpl_lang as $smstmplangkey => $smstpllang){
    
    if(!in_array($smstmplangkey,$db_smstmplang_enname)){
        $dsp = array();
        $dsp['enname']=$smstmplangkey;
        $dsp['cnname']=$smstpllang;
        
        C::t('#jzsjiale_sms#jzsjiale_sms_tpllang')->insert($dsp,true);
    }
}

$db_smstmplang_new = C::t('#jzsjiale_sms#jzsjiale_sms_tpllang')->getall();



showformheader('plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=yuyan&act=saveyuyan', 'enctype');
showtableheader(plang('yuyantitle').'(&nbsp;&nbsp;<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=yuyan&act=recache" style="color:red;">'.plang('recache').'</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms&pmod=yuyan&act=huanyuanyuyan" style="color:red;">'.plang('huanyuanyuyan').'</a>&nbsp;&nbsp;)', '');

showsubmit('submit');

foreach ($db_smstmplang_new as $db_smstpllang){
    if($db_smstpllang['enname'] != "diylang"){
        showsetting($db_smstpllang['enname'],'smstpllang['.$db_smstpllang['enname'].']',$db_smstpllang['cnname'],'text','','',plang('smstpllang_msg1').'{lang jzsjiale_sms:'.$db_smstpllang['enname'].'}'.plang('smstpllang_msg2'));
    }
}

showsubmit('submit');
showtablefooter();
showformfooter();



function plang($str) {
    return lang('plugin/jzsjiale_sms', $str);
}
?>