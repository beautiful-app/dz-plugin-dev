<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$sql = <<<EOF
DROP TABLE IF EXISTS `pre_jzsjiale_sms_user`;
CREATE TABLE `pre_jzsjiale_sms_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11)  NULL DEFAULT '0',
  `username` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `dateline` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
   INDEX phone_name (`phone`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `pre_jzsjiale_sms_code`;
CREATE TABLE `pre_jzsjiale_sms_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11)  NULL DEFAULT '0',
  `phone` bigint(20) unsigned NOT NULL,
  `seccode` varchar(50) NOT NULL DEFAULT '000000',
  `expire` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
   INDEX phone_name (`phone`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `pre_jzsjiale_sms_smslist`;
CREATE TABLE `pre_jzsjiale_sms_smslist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11)  NULL DEFAULT '0',
  `phone` bigint(20) unsigned NOT NULL,
  `seccode` varchar(50) NOT NULL DEFAULT '000000',
  `ip` varchar(255) NULL DEFAULT '',
  `msg` varchar(999) NOT NULL DEFAULT '',
  `type` int(10) unsigned NOT NULL DEFAULT '0',
  `status` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
   INDEX phone_name (`phone`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `pre_jzsjiale_sms_tpllang`;
CREATE TABLE `pre_jzsjiale_sms_tpllang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enname` varchar(255) NULL DEFAULT '',
  `cnname` varchar(1024) NULL DEFAULT '',
   PRIMARY KEY (`id`),
   INDEX enname_name (`enname`)
) ENGINE=MyISAM;
EOF;
runquery ( $sql );


$query = DB::query("SHOW COLUMNS FROM ".DB::table('common_member_status'));
while($row = DB::fetch($query)) {
    $col_field[]=$row['Field'];
}

if(!in_array('port', $col_field)){
    $sql = "Alter table ".DB::table('common_member_status')." add `port` smallint(6) NOT NULL DEFAULT 0;";
    DB::query($sql);
}
unset($col_field);


@unlink(DISCUZ_ROOT . './source/plugin/jzsjiale_sms/discuz_plugin_jzsjiale_sms.xml');
@unlink(DISCUZ_ROOT . './source/plugin/jzsjiale_sms/discuz_plugin_jzsjiale_sms_SC_GBK.xml');
@unlink(DISCUZ_ROOT . './source/plugin/jzsjiale_sms/discuz_plugin_jzsjiale_sms_SC_UTF8.xml');
@unlink(DISCUZ_ROOT . './source/plugin/jzsjiale_sms/discuz_plugin_jzsjiale_sms_TC_BIG5.xml');
@unlink(DISCUZ_ROOT . './source/plugin/jzsjiale_sms/discuz_plugin_jzsjiale_sms_TC_UTF8.xml');
@unlink(DISCUZ_ROOT . './source/plugin/jzsjiale_sms/install.php');
@unlink(DISCUZ_ROOT . './source/plugin/jzsjiale_sms/upgrade.php');

$finish = TRUE;
?>