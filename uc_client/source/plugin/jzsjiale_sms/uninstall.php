<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$sql = <<<EOF
DROP TABLE IF EXISTS `pre_jzsjiale_sms_user`;
DROP TABLE IF EXISTS `pre_jzsjiale_sms_code`;
DROP TABLE IF EXISTS `pre_jzsjiale_sms_smslist`;
DROP TABLE IF EXISTS `pre_jzsjiale_sms_tpllang`;
EOF;

runquery($sql);

$finish = TRUE;