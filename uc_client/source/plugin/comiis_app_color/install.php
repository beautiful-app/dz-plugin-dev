<?php
/**
 * 
 * 草-根-吧提醒：为保证草根吧资源的更新维护保障，防止草根吧首发资源被恶意泛滥，
 *             希望所有下载草根吧资源的会员不要随意把草根吧首发资源提供给其他人;
 *             如被发现，将取消草根吧VIP会员资格，停止一切后期更新支持以及所有补丁BUG等修正服务；
 *          
 * 草.根.吧出品 必属精品
 * 草根吧 全网首发 https://Www.Caogen8.co
 * 官网：www.Cgzz8.com (请收藏备用!)
 * 本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 * 技术支持/更新维护：QQ 2575 163778
 * 谢谢支持，感谢你对.草根吧.的关注和信赖！！！   
 * 
 */
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$comiis_app_color_install_lang = '';
require_once DISCUZ_ROOT.'./source/plugin/comiis_app_color/language/language.'.currentlang().'.php';
$sql = <<<EOF
DROP TABLE IF EXISTS `pre_comiis_app_style`;
CREATE TABLE `pre_comiis_app_style` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `displayorder` smallint(6) NOT NULL default '0',
  `name` varchar(30) NOT NULL,
  `default` tinyint(1) NOT NULL default '0',
  `show` tinyint(1) NOT NULL default '0',
  `css` text NOT NULL,
  `color1` varchar(10) NOT NULL,
  `color2` varchar(10) NOT NULL,
  `color3` varchar(10) NOT NULL,
  `color4` varchar(10) NOT NULL,
  `color5` varchar(10) NOT NULL,
  `color6` varchar(10) NOT NULL,
  `color7` varchar(10) NOT NULL,
  `color8` varchar(10) NOT NULL,
  `color9` varchar(10) NOT NULL,
  `color10` varchar(10) NOT NULL,
  `color11` varchar(10) NOT NULL,
  `color12` varchar(10) NOT NULL,
  `color13` varchar(10) NOT NULL,
  `color14` varchar(10) NOT NULL,
  `color15` varchar(10) NOT NULL,
  `color16` varchar(10) NOT NULL,
  `color17` varchar(10) NOT NULL,
  `color18` varchar(10) NOT NULL,
  `color19` varchar(10) NOT NULL,
  `color20` varchar(10) NOT NULL,
  `color21` varchar(10) NOT NULL,
  `color22` varchar(10) NOT NULL,
  `color23` varchar(10) NOT NULL,
  `color24` varchar(10) NOT NULL,
  `color25` varchar(10) NOT NULL,
  `color26` varchar(10) NOT NULL,
  `color27` varchar(10) NOT NULL,
  `color28` varchar(10) NOT NULL,
  `color29` varchar(10) NOT NULL,
  `color30` varchar(10) NOT NULL,
  `color31` varchar(10) NOT NULL,
  `color32` varchar(10) NOT NULL,
  `color33` varchar(10) NOT NULL,
  `color34` varchar(10) NOT NULL,
  `color35` varchar(10) NOT NULL,
  `color36` varchar(10) NOT NULL,
  `color37` varchar(10) NOT NULL,
  `color38` varchar(10) NOT NULL,
  `color39` varchar(10) NOT NULL,
  `color40` varchar(10) NOT NULL,
  `color41` varchar(10) NOT NULL,
  `color42` varchar(10) NOT NULL,
  `color43` varchar(10) NOT NULL,
  `color44` varchar(10) NOT NULL,
  `color45` varchar(10) NOT NULL,
  `color46` varchar(10) NOT NULL,
  `color47` varchar(10) NOT NULL,
  `color48` varchar(10) NOT NULL,
  `color49` varchar(10) NOT NULL,
  `color50` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;
DROP TABLE IF EXISTS `pre_comiis_app_userstyle`;
CREATE TABLE `pre_comiis_app_userstyle` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) NOT NULL default '0',
  `css` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`id`,`uid`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM;
EOF;
$sql .= $comiis_app_color_install_lang;
runquery($sql);
$comiis_value = DB::fetch_all("SELECT * FROM %t WHERE `show`='1'", array('comiis_app_style'));
$comiis_cache = array();
foreach($comiis_value as $style){
	$comiis_cache[] = array(
		'id' => $style['id'],
		'name' => $style['name'],
		'color' => $style['color1'],
	);
}
save_syscache('comiis_app_style', $comiis_cache);
$finish = TRUE;