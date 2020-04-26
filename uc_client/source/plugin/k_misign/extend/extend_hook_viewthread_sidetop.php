<?php
!defined('IN_DISCUZ') && exit('Access Denied');

$op = in_array($_GET['op'], array('install', 'uninstall')) ? $_GET['op'] : '';
@include_once DISCUZ_ROOT.'./source/plugin/k_misign/language/extend_hook_viewthread_sidetop.'.currentlang().'.php';

if($op == 'install'){
	
}elseif($op == 'uninstall'){
	@unlink(DISCUZ_ROOT.'./source/plugin/k_misign/language/extend_hook_viewthread_sidetop.'.currentlang().'.php');
	@unlink(DISCUZ_ROOT.'./source/plugin/k_misign/extend/extend_hook_viewthread_sidetop.php');
	cpmsg('update_success', "action=plugins&operation=config&do=".$do."&identifier=k_misign&pmod=cp_extend", 'succeed');
}else{
	$setting = $_G['cache']['plugin']['k_misign'];
	$setting['pluginurl'] = $setting['pluginurl'] ? $setting['pluginurl'] : 'plugin.php?id=k_misign:';
	require_once libfile('function/core', 'plugin/k_misign');
	$lastedop = $_G['cache']['plugin']['dsu_paulsign']['lastedop'];
	
	if(empty($_GET['tid']) || !is_array($postlist)) return array();
	$pids = array_keys($postlist);
	$authorids = array();
	foreach($postlist as $pid => $pinfo){
		$authorids[] = $pinfo['authorid'];
	}
	$authorids = array_unique($authorids);
	$authorids = array_filter($authorids);
	if(empty($authorids)) return array();
	$days = array();
	$nlvtext =str_replace(array("\r\n", "\n", "\r"), '/hhf/', $setting['lvtext']);
	list($lv1name, $lv2name, $lv3name, $lv4name, $lv5name, $lv6name, $lv7name, $lv8name, $lv9name, $lv10name, $lvmastername) = explode("/hhf/", $nlvtext);
	foreach(C::t('#k_misign#plugin_k_misign')->fetch_all($authorids) as $mrc) {
		$days[$mrc['uid']]['days'] = $mrc['days'];
		if(!array_key_exists($mrc['qdxq'],$emots)) {
			$mrc['qdxq'] = end(array_keys($emots));
		}
		$days[$mrc['uid']]['time'] = dgmdate($mrc['time'], 'u');
		$days[$mrc['uid']]['lasted'] = $mrc['lasted'];
		$days[$mrc['uid']]['levelarray'] = get_level($mrc['days']);
		$days[$mrc['uid']]['level'] = $temp['level'];
		$days[] = $mrc;
	}
	$echoq = array();
	foreach($postlist as $key => $val) {
		if($days[$postlist[$key]['authorid']]['days']) {
			$echoonce .= '<div style="border:2px #ccc dashed; margin:auto 14px 10px;padding:2px 5px">'.$extendlang['ljqd'].lang('plugin/k_misign', 'maohao').$days[$postlist[$key]['authorid']]['days'].' '.lang('plugin/k_misign', 'days').'<br />'.$extendlang['lxqd'].lang('plugin/k_misign', 'maohao').$days[$postlist[$key]['authorid']]['lasted'].' '.lang('plugin/k_misign', 'days').'<br />'.$days[$postlist[$key]['authorid']]['level'].'</div>';
		} else {
			$echoonce = '<p>'.$extendlang['noqd'].'</p>';
		}
		$echoq[] = $echoonce;
		$echoonce = '';
	}
	return $echoq;
}
?>