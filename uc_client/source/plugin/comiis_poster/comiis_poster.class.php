<?php
//海报生成1.2.1最新版本
//本插件有鱼乐圈本地学习研究使用
//禁止传播，如果使用效果不错，请购买正版
//BY：鱼乐圈水族 QQ：1071533
 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class mobileplugin_comiis_poster{
	function global_footer_mobile() {
		global $_G;
		if((($_G['basescript'] == 'forum' || $_G['basescript'] == 'group') && (CURMODULE == 'forumdisplay' || CURMODULE == 'viewthread')) || ($_G['basescript'] == 'portal' && CURMODULE == 'view') || ($_G['basescript'] == 'home' && CURMODULE == 'space' && (!empty($_GET['id']) || !empty($_GET['picid'])) && ($_GET['do'] == 'album' || $_GET['do'] == 'blog'))){
			$comiis_poster = $_G['cache']['plugin']['comiis_poster'];
			if(!empty($_GET['inajax']) && $_GET['comiis_poster'] == 'yes'){
				include_once DISCUZ_ROOT.'./source/plugin/comiis_poster/comiis_poster.inc.php';
			}
			include_once template('comiis_poster:comiis_key');
			return $return;
		}
	}
}
function comiis_poster_hex2rgba($color) {
	global $_G;
	$color = str_replace('#', '', $color);
	return strlen($color) > 3 ? 'rgba('.hexdec(substr($color, 0, 2)).','.hexdec(substr($color, 2, 2)).','.hexdec(substr($color, 4, 2)).','.$_G['cache']['plugin']['comiis_poster']['opacity'].')' : 'rgba('.hexdec(substr($color, 0, 1)).','.hexdec(substr($color, 1, 1)).','.hexdec(substr($color, 2, 1)).','.$_G['cache']['plugin']['comiis_poster']['opacity'].')';
}