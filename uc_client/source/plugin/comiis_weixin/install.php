<?php

 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE IF EXISTS `pre_comiis_weixin`;

CREATE TABLE `pre_comiis_weixin` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(32) NOT NULL,
  `key` varchar(32) NOT NULL,
  `uid` mediumint(8) NOT NULL DEFAULT '0',
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(80) NOT NULL,
  `sex` tinyint(1) NOT NULL,
  `city` varchar(80) NOT NULL,
  `province` varchar(80) NOT NULL,
  `country` varchar(80) NOT NULL,
  `headimgurl` varchar(255) NOT NULL,
  `privilege` text NOT NULL,
  `unionid` varchar(255) NOT NULL,
  `dateline` int(10) NOT NULL,
  `edit` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `pre_comiis_weixin_key`;

CREATE TABLE `pre_comiis_weixin_key` (
  `key` varchar(8) NOT NULL,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`key`,`uid`)
) ENGINE=MyISAM;



EOF;

runquery($sql);

$finish = TRUE;

?>