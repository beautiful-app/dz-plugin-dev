<?php
//海报生成1.2.1最新版本
//本插件有鱼乐圈本地学习研究使用
//禁止传播，如果使用效果不错，请购买正版
//BY：鱼乐圈水族 QQ：1071533
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(!empty($_GET['src'])){
	$is_openimage_url = 0;
	if(substr($_GET['src'], 0, 7) == 'http://' || substr($_GET['src'], 0, 7) == 'https:/' || substr($_GET['src'], 0, 2) == '//'){
		$openimage_url = explode("\n", str_replace("\r\n", "\n", $_G['cache']['plugin']['comiis_poster']['openimage_url']));
		if(is_array($openimage_url)){
			$openimage_url[] = $_G['siteurl'];
			foreach($openimage_url as $temp) {
				$temp = trim($temp);
				if(strlen($temp) && substr($_GET['src'], 0, strlen($temp)) == $temp){
					$is_openimage_url = 1;
					break;
				}
			}
		}
	}else{
		$is_openimage_url = 1;
	}
	if($is_openimage_url == 1){
		$imginfo = @getimagesize($_GET['src']);
		if($imginfo === FALSE) {
			exit;
		}elseif(in_array($imginfo['mime'], array('image/jpeg', 'image/gif', 'image/png'))){
			dheader('Content-Type: image');
			@readfile($_GET['src']);
		}
	}
}