<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$sql = <<<EOF
CREATE TABLE IF NOT EXISTS `pre_jzsjiale_sms_tpllang` (
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

$query = DB::query("SHOW COLUMNS FROM ".DB::table('jzsjiale_sms_smslist'));
while($row = DB::fetch($query)) {
    $col_field[]=$row['Field'];
}

if(!in_array('ip', $col_field)){
    $sql = "Alter table ".DB::table('jzsjiale_sms_smslist')." add `ip` varchar(255) NULL DEFAULT '';";
    DB::query($sql);
}
unset($col_field);

$finish = TRUE;
?>