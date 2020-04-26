<?php
//海报生成1.2.1最新版本
//本插件有鱼乐圈本地学习研究使用
//禁止传播，如果使用效果不错，请购买正版
//BY：鱼乐圈水族 QQ：1071533
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
loadcache('plugin'); 
$comiis_poster = $_G['cache']['plugin']['comiis_poster'];
if($_GET['mod'] == 'qrcode' && $_GET['url']){
	if(!$comiis_poster['code'] && (substr($_GET['url'], 0, 7) == 'http://' || substr($_GET['url'], 0, 7) == 'https:/' || substr($_GET['url'], 0, 2) == '//')){
		if(substr($_GET['url'], 0, strlen($_G['siteurl'])) != $_G['siteurl']){
			exit;
		}
	}
	include_once DISCUZ_ROOT.'./source/plugin/comiis_poster/qrcode.class.php';
	$url = urldecode($_GET['url']);
	echo QRcode::png($url, false, 4, 4, 0);
}elseif(!empty($_GET['inajax']) && $_GET['comiis_poster'] == 'yes'){
	global $comiis_poster_data, $comiis_poster_mob, $comiis_poster_url, $comiis_poster;
	include_once DISCUZ_ROOT.'./source/plugin/comiis_poster/comiis_poster.php';
	include template('common/header_ajax');
	include_once template('comiis_poster:comiis_hook');
	include template('common/footer_ajax');
	exit;
}