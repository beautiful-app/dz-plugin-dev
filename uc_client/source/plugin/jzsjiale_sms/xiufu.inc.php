<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$sql = <<<EOF

CREATE TABLE IF NOT EXISTS `pre_jzsjiale_sms_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11)  NULL DEFAULT '0',
  `username` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `dateline` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
   INDEX phone_name (`phone`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `pre_jzsjiale_sms_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11)  NULL DEFAULT '0',
  `phone` bigint(20) unsigned NOT NULL,
  `seccode` varchar(50) NOT NULL DEFAULT '000000',
  `expire` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
   INDEX phone_name (`phone`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `pre_jzsjiale_sms_smslist` (
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

CREATE TABLE IF NOT EXISTS `pre_jzsjiale_sms_tpllang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enname` varchar(255) NULL DEFAULT '',
  `cnname` varchar(1024) NULL DEFAULT '',
   PRIMARY KEY (`id`),
   INDEX enname_name (`enname`)
) ENGINE=MyISAM;
EOF;

runquery($sql);

$query = DB::query("SHOW COLUMNS FROM ".DB::table('common_member_status'));
while($row = DB::fetch($query)) {
    $col_field[]=$row['Field'];
}

if(!in_array('port', $col_field)){
    $sql = "Alter table ".DB::table('common_member_status')." add `port` smallint(6) NOT NULL DEFAULT 0;";
    DB::query($sql);
}
unset($col_field);



$query = DB::query("SHOW COLUMNS FROM ".DB::table('jzsjiale_sms_user'));
while($row = DB::fetch($query)) {
    $col_field[]=$row['Field'];
}

if(!in_array('id', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_user')." add `id` int(11) NOT NULL AUTO_INCREMENT;";
    DB::query($sql);
}
if(!in_array('uid', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_user')." add `uid` int(11)  NULL DEFAULT '0';";
    DB::query($sql);
}
if(!in_array('username', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_user')." add `username` varchar(255) DEFAULT NULL;";
    DB::query($sql);
}
if(!in_array('phone', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_user')." add `phone` varchar(255) DEFAULT NULL;";
    DB::query($sql);
}
if(!in_array('dateline', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_user')." add `dateline` int(11) DEFAULT NULL;";
    DB::query($sql);
}
unset($col_field);


$query = DB::query("SHOW COLUMNS FROM ".DB::table('jzsjiale_sms_code'));
while($row = DB::fetch($query)) {
    $col_field[]=$row['Field'];
}

if(!in_array('id', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_code')." add `id` int(11) NOT NULL AUTO_INCREMENT;";
    DB::query($sql);
}
if(!in_array('uid', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_code')." add `uid` int(11)  NULL DEFAULT '0';";
    DB::query($sql);
}
if(!in_array('phone', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_code')." add `phone` bigint(20) unsigned NOT NULL;";
    DB::query($sql);
}
if(!in_array('seccode', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_code')." add `seccode` varchar(50) NOT NULL DEFAULT '000000';";
    DB::query($sql);
}
if(!in_array('expire', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_code')." add `expire` int(10) unsigned NOT NULL DEFAULT '0';";
    DB::query($sql);
}
if(!in_array('dateline', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_code')." add `dateline` int(11) DEFAULT NULL;";
    DB::query($sql);
}
unset($col_field);



$query = DB::query("SHOW COLUMNS FROM ".DB::table('jzsjiale_sms_smslist'));
while($row = DB::fetch($query)) {
    $col_field[]=$row['Field'];
}

if(!in_array('id', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_smslist')." add `id` int(11) NOT NULL AUTO_INCREMENT;";
    DB::query($sql);
}
if(!in_array('uid', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_smslist')." add `uid` int(11)  NULL DEFAULT '0';";
    DB::query($sql);
}
if(!in_array('phone', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_smslist')." add `phone` bigint(20) unsigned NOT NULL;";
    DB::query($sql);
}
if(!in_array('seccode', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_smslist')." add `seccode` varchar(50) NOT NULL DEFAULT '000000';";
    DB::query($sql);
}
if(!in_array('ip', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_smslist')." add `ip` varchar(255) NULL DEFAULT '';";
    DB::query($sql);
}
if(!in_array('msg', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_smslist')." add `msg` varchar(999) NOT NULL DEFAULT '';";
    DB::query($sql);
}
if(!in_array('type', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_smslist')." add `type` int(10) unsigned NOT NULL DEFAULT '0';";
    DB::query($sql);
}
if(!in_array('status', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_smslist')." add `status` int(10) unsigned NOT NULL DEFAULT '0';";
    DB::query($sql);
}
if(!in_array('dateline', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_smslist')." add `dateline` int(11) DEFAULT NULL;";
    DB::query($sql);
}
unset($col_field);



$query = DB::query("SHOW COLUMNS FROM ".DB::table('jzsjiale_sms_tpllang'));
while($row = DB::fetch($query)) {
    $col_field[]=$row['Field'];
}

if(!in_array('id', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_tpllang')." add `id` int(11) NOT NULL AUTO_INCREMENT;";
    DB::query($sql);
}
if(!in_array('enname', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_tpllang')." add `enname` varchar(255) NULL DEFAULT '';";
    DB::query($sql);
}
if(!in_array('cnname', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_tpllang')." add `cnname` varchar(1024) NULL DEFAULT '';";
    DB::query($sql);
}
unset($col_field);

cpmsg('jzsjiale_sms:xiufuok', 'action=plugins&operation=config&do='.$pluginid.'&identifier=jzsjiale_sms', 'succeed');

?>