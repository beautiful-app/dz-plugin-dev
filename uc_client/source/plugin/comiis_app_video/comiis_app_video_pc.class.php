<?php
/**
 * 
 * 克米出品 必属精品
 * 克米设计工作室 版权所有 http://www.Comiis.com
 * 专业论坛首页及风格制作, 页面设计美化, 数据搬家/升级, 程序二次开发, 网站效果图设计, 页面标准DIV+CSS生成, 各类大中小型企业网站设计...
 * 我们致力于为企业提供优质网站建设、网站推广、网站优化、程序开发、域名注册、虚拟主机等服务，
 * 一流设计和解决方案为企业量身打造适合自己需求的网站运营平台，最大限度地使企业在信息时代稳握无限商机。
 *
 *   电话: 0668-8810200
 *   手机: 13450110120  15813025137
 *    Q Q: 21400445  8821775  11012081  327460889
 * E-mail: ceo@comiis.com
 *
 * 工作时间: 周一到周五早上09:00-11:00, 下午03:00-05:00, 晚上08:30-10:30(周六、日休息)
 * 克米设计用户交流群: ①群83667771 ②群83667772 ③群83667773 ④群110900020 ⑤群110900021 ⑥群70068388 ⑦群110899987
 * 
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class plugin_comiis_app_video{
	function discuzcode($param){
		global $_G, $pid;
		if($param['caller'] == 'discuzcode'){
			if($_G['forum_thread']['isgroup'] || ($_G['basescript']=='forum' && CURMODULE=='viewthread' && in_array($_G['fid'], unserialize($_G['cache']['plugin']['comiis_app_video']['comiis_forum'])))){
				if(dstrpos($_G['discuzcodemessage'], array('[/media]', '[/flash]', '[/audio]', '[/attach]')) !== FALSE){
					$_G['comiis_video'] = 1;
					$_G['comiis_video_attach'] = C::t('forum_attachment_n')->fetch_all_by_id(getattachtableid($_G['tid']), 'pid', $pid);
					include_once DISCUZ_ROOT.'./source/plugin/comiis_app_video/comiis_app_video.fun.php';
					$_G['discuzcodemessage'] = comiis_discuzcode($_G['discuzcodemessage'], $param);
				}
			}
		}
	}
	function global_header(){
		global $_G;
		if(($_G['basescript']=='forum' || $_G['basescript']=='group') && CURMODULE=='post'){
			return '<script src="./source/plugin/comiis_app_video/static/jquery.min.js"></script><script>jQuery.noConflict();</script><script src="./source/plugin/comiis_app_video/static/upload.js" charset="utf-8"></script>';
		}
	}
}
class plugin_comiis_app_video_forum extends plugin_comiis_app_video{
    function post_editorctrl_left(){
        global $_G;
		$users = unserialize($_G['cache']['plugin']['comiis_app_video']['comiis_upload_group']);
		if(isset($users[0]) && ($users[0] == '0' || $users[0] == '')){
			unset($users[0]);
		}
		if(($_G['basescript'] == 'group' && $_G['cache']['plugin']['comiis_app_video']['comiis_group_uploadvideo']) || in_array($_G['fid'], unserialize($_G['cache']['plugin']['comiis_app_video']['comiis_upload_video'])) && count($users) && in_array($_G['member']['groupid'], $users)){
			include_once DISCUZ_ROOT.'./source/plugin/comiis_app_video/language/language.'.currentlang().'.php';
			include template('comiis_app_video:comiis_upkey');
			return $html;
		}
    }
}
class plugin_comiis_app_video_group extends plugin_comiis_app_video {
	function post_editorctrl_left(){
        global $_G;
		$users = unserialize($_G['cache']['plugin']['comiis_app_video']['comiis_upload_group']);
		if(isset($users[0]) && ($users[0] == '0' || $users[0] == '')){
			unset($users[0]);
		}
		if(($_G['basescript'] == 'group' && $_G['cache']['plugin']['comiis_app_video']['comiis_group_uploadvideo']) || in_array($_G['fid'], unserialize($_G['cache']['plugin']['comiis_app_video']['comiis_upload_video'])) && count($users) && in_array($_G['member']['groupid'], $users)){
			include_once DISCUZ_ROOT.'./source/plugin/comiis_app_video/language/language.'.currentlang().'.php';
			include template('comiis_app_video:comiis_upkey');
			return $html;
		}
	}
}
