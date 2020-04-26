<?php

 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class mobileplugin_comiis_weixinupload{
	function global_footer_mobile(){
		global $_G, $comiis_signPackage;
		if(!$_G['uid']){
			return;
		}
		$comiis_wxup_iswx = 0;
		if($_G['basescript'] == 'forum' && (CURMODULE == 'post' || CURMODULE == 'viewthread')){
			if(empty($_G['cache']['plugin'])){
				loadcache('plugin');
			}
			$comiis_weixinupload = $_G['cache']['plugin']['comiis_weixinupload'];
			$fids = unserialize($comiis_weixinupload['forum']);
			if(isset($fids[0]) && ($fids[0] == '0' || $fids[0] == '')){
				unset($fids[0]);
			}
			if(count($fids) && !in_array($_G['fid'], $fids)){
				return;
			}
			$users = unserialize($comiis_weixinupload['user']);
			if(isset($users[0]) && ($users[0] == '0' || $users[0] == '')){
				unset($users[0]);
			}
			if(count($users) && !in_array($_G['member']['groupid'], $users)){
				return;
			}
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false && $comiis_weixinupload['appid'] && $comiis_weixinupload['appsecret']){
				$comiis_hash = md5(substr(md5($_G['config']['security']['authkey']), 8).$_G['uid']);
				$comiis_wxup_iswx = 1;
				$comiis_app_switch['comiis_wxappid'] = $comiis_weixinupload['appid'];
				$comiis_app_switch['comiis_wxappsecret'] = $comiis_weixinupload['appsecret'];
				if(file_exists(DISCUZ_ROOT.'./template/comiis_app/comiis/php/jssdk.php')){
					include_once DISCUZ_ROOT.'./template/comiis_app/comiis/php/jssdk.php';
				}else{
					include_once DISCUZ_ROOT.'./source/plugin/comiis_weixinupload/jssdk.php';
				}// f  r om www.mo qu8. co m
			}
		}
		include DISCUZ_ROOT.'./source/plugin/comiis_weixinupload/language/language.'.currentlang().'.php';
		include template('comiis_weixinupload:hook');
		return $html;
	}
}