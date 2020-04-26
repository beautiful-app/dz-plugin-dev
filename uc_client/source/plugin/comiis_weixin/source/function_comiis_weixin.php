<?php

 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function comiis_get_weixin_lang() {
	global $_G;
	loadcache('plugin');
	$_G['comiis_wxlang'] = $lang_temp1 = array();
	$lang_temp1 = explode("\n", str_replace("\r\n", "\n", $_G['cache']['plugin']['comiis_weixin']['language']));
	if(is_array($lang_temp1)){
		foreach($lang_temp1 as $temp) {
			list($k, $v) = explode('=', $temp);
			$k = trim($k);
			$_G['comiis_wxlang'][$k] = trim($v);
		}
	}
}

function comiis_get_weixin_login_url($state = '', $scope = 0, $wx = 0) {
	global $_G;
	return 'https://open.weixin.qq.com/connect/'.($_G['comiis_weixin']['wxconnect'] == 0 ? 'oauth2/authorize' : 'qrconnect').'?appid='.$_G['comiis_weixin']['appid'].'&redirect_uri='.urlencode(($_G['comiis_weixin']['reurl'] ? 'http://'. $_G['comiis_weixin']['reurl']. '/' : $_G['siteurl']).'plugin.php?id=comiis_weixin&mod='.($wx == 1 ? 'wxbd_mob' : ($_G['uid'] ? 'wxbd' : 'login')).'&referer='.urlencode(dreferer())).'&response_type=code&scope='.(($_G['comiis_weixin']['wxnotip'] == 0 || $scope == 1) ? 'snsapi_userinfo' : 'snsapi_base').($state ? '&state='.$state : '').'#wechat_redirect';
}

function comiis_get_weixin_tip($data, $type = 0) {
	if($_GET['mod'] == 'wxbd_mob'){
		showmessage($data);
	}else{
		include_once template('comiis_weixin:comiis_htm');
		include_once template('common/header');
		echo comiis_get_weixin_tip_html($data, $type);
		$comiis_foot = 'no';
		include_once template('common/footer');
		exit;
	}
}

function comiis_wx_get_district($name, $level = '1') {
	global $_G;
	$names = array();
	if($level == '1'){
		$names[] = $name . $_G['comiis_wxlang']['071'];
		$names[] = $name . $_G['comiis_wxlang']['072'];
	}else{
		$names[] = $name . $_G['comiis_wxlang']['072'];
		$names[] = $name . $_G['comiis_wxlang']['073'];
	}
	return DB::result_first("SELECT name FROM %t WHERE level = %d AND name IN(%n)", array('common_district', $level, $names));
}

function comiis_wx_avatar($uid, $avatar) {
	if($content = dfsockopen($avatar)) {
		$tmpFile = DISCUZ_ROOT.'./data/attachment/temp/comiis_avatar_'.TIMESTAMP.'_'.random(3);
		file_put_contents($tmpFile, $content);
		if(!is_file($tmpFile)) {
			return false;
		}
		$result = comiis_wx_upload($uid, $tmpFile);
		unlink($tmpFile);
		C::t('common_member')->update($uid, array('avatarstatus'=>'1'));
		return $result;
	}
}

function comiis_wx_upload($uid, $temp_file) {
	global $_G;
	list($width, $height, $type, $attr) = getimagesize($temp_file);
	if (!$width) {
		return false;
	}
	if($width < 10 || $height < 10 || $type == 4) {
		return false;
	}
	$imageType = array(1 => '.gif', 2 => '.jpg', 3 => '.png');
	$filetype = $imgType[$type];
	if(!$filetype) {
		$filetype = '.jpg';
	}
	$avatarPath = $_G['setting']['attachdir'];
	$temp_avatar = $avatarPath.'./temp/upload'.$uid.$filetype;
	file_exists($temp_avatar) && @unlink($temp_avatar);
	file_put_contents($temp_avatar, file_get_contents($temp_file));
	if(!is_file($temp_avatar)) {
		return false;
	}
	$temp_avatar_big = './temp/upload'.$uid.'big'.$filetype;
	$temp_avatar_middle = './temp/upload'.$uid.'middle'.$filetype;
	$temp_avatar_small = './temp/upload'.$uid.'small'.$filetype;
	$image = new image;
	if($image->Thumb($temp_avatar, $temp_avatar_big, 200, 200, 1) <= 0) {
		return false;
	}
	if($image->Thumb($temp_avatar, $temp_avatar_middle, 120, 120, 1) <= 0) {
		return false;
	}
	if($image->Thumb($temp_avatar, $temp_avatar_small, 48, 48, 2) <= 0) {
		return false;
	}
	$temp_avatar_big = $avatarPath.$temp_avatar_big;
	$temp_avatar_middle = $avatarPath.$temp_avatar_middle;
	$temp_avatar_small = $avatarPath.$temp_avatar_small;
	$avatar1 = comiis_wx_byte2hex(file_get_contents($temp_avatar_big));
	$avatar2 = comiis_wx_byte2hex(file_get_contents($temp_avatar_middle));
	$avatar3 = comiis_wx_byte2hex(file_get_contents($temp_avatar_small));
	$extra = '&avatar1='.$avatar1.'&avatar2='.$avatar2.'&avatar3='.$avatar3;
	$result = comiis_wx_uc_api_post_ex('user', 'rectavatar', array('uid' => $uid), $extra);
	@unlink($temp_avatar);
	@unlink($temp_avatar_big);
	@unlink($temp_avatar_middle);
	@unlink($temp_avatar_small);
	return true;
}

function comiis_wx_byte2hex($string) {
	$buffer = '';
	$value = unpack('H*', $string);
	$value = str_split($value[1], 2);
	$b = '';
	foreach($value as $k => $v) {
		$b .= strtoupper($v);
	}
	return $b;
}

function comiis_wx_uc_api_post_ex($module, $action, $arg = array(), $extra = '') {
	$s = $sep = '';
	foreach($arg as $k => $v) {
		$k = urlencode($k);
		if(is_array($v)) {
			$s2 = $sep2 = '';
			foreach($v as $k2 => $v2) {
				$k2 = urlencode($k2);
				$s2 .= "$sep2{$k}[$k2]=".urlencode(uc_stripslashes($v2));
				$sep2 = '&';
			}
			$s .= $sep.$s2;
		} else {
			$s .= "$sep$k=".urlencode(uc_stripslashes($v));
		}
		$sep = '&';
	}
	$postdata = uc_api_requestdata($module, $action, $s, $extra);
	return uc_fopen2(UC_API.'/index.php', 500000, $postdata, '', TRUE, UC_IP, 20);
}?>