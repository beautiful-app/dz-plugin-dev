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
class mobileplugin_comiis_app_video{
	function discuzcode($param){
		global $_G, $pid;
		if($param['caller'] == 'discuzcode'){
			if(($_G['forum_thread']['isgroup'] || ($_G['basescript']=='forum' && CURMODULE=='viewthread')) && dstrpos($_G['discuzcodemessage'], array('[/img]')) !== FALSE){
				if(preg_match_all("/\[img\=?([\w,]*)\]\s*(.*?)\s*\[\/img\]/is", $_G['discuzcodemessage'], $matches)) {
					foreach($matches[2] as $key => $img){
						$_G['discuzcodemessage'] = str_replace($matches[0][$key], '<img src="'.$matches[2][$key].'" style="max-width:100%" />', $_G['discuzcodemessage']); 
					}
				}
			}
			if(($_G['forum_thread']['isgroup'] && $_G['cache']['plugin']['comiis_app_video']['comiis_group']) || ($_G['basescript']=='forum' && CURMODULE=='viewthread' && in_array($_G['fid'], unserialize($_G['cache']['plugin']['comiis_app_video']['comiis_forum'])))){
				if(dstrpos($_G['discuzcodemessage'], array('[/media]', '[/flash]', '[/audio]', '[/attach]')) !== FALSE){
					$_G['comiis_video_attach'] = C::t('forum_attachment_n')->fetch_all_by_id(getattachtableid($_G['tid']), 'pid', $pid);
					include_once DISCUZ_ROOT.'./source/plugin/comiis_app_video/comiis_app_video.fun.php';
					$_G['discuzcodemessage'] = comiis_discuzcode($_G['discuzcodemessage'], $param);
				}
			}
		}
	}
	function global_header_mobile(){
		global $_G;
		if($_G['is_comiis_portal'] == 1 || (($_G['forum_thread']['isgroup'] || $_G['basescript'] == 'group') && ($_G['cache']['plugin']['comiis_app_video']['comiis_group'] || $_G['cache']['plugin']['comiis_app_video']['comiis_group_list'])) || ($_G['basescript']=='forum' && CURMODULE=='viewthread' && in_array($_G['fid'], unserialize($_G['cache']['plugin']['comiis_app_video']['comiis_forum']))) || ($_G['basescript']=='forum' && CURMODULE=='forumdisplay' && in_array($_G['fid'], unserialize($_G['cache']['plugin']['comiis_app_video']['comiis_forum_video'])))){
			return '<style>.comiis_video{width:100%;height:100%;}</style>
			<script>    
			var comiis_document_logkskrtusr_heightktxn = 0;
			$(function(){
				comiis_setmkkc_widthnhdw();
				$(window).scroll(function() {
					if($(document).height() != comiis_document_logkskrtusr_heightktxn){
						comiis_document_logkskrtusr_heightktxn = $(document).height();
						comiis_setmkkc_widthnhdw();
					}
				});
			});
			function comiis_setmkkc_widthnhdw(){
				$(".comiis_no_width").each(function(){
					var comiis_tempheight = $(this).width() / parseFloat("'.$_G['cache']['plugin']['comiis_app_video']['comiis_scale'].'");
					$(this).css("height", (comiis_tempheight > $(this).width() ? $(this).width() : comiis_tempheight)).removeClass("comiis_no_width");
					'.(($_G['basescript']=='forum' && CURMODULE=='viewthread') ? '$(".comiis_video_height").css("height", $(window).width() / parseFloat("'.$_G['cache']['plugin']['comiis_app_video']['comiis_scale'].'"));' : '').'
				});		
			}
			function comiis_setvideowidth(){
				comiis_setmkkc_widthnhdw();
			}
			comiis_setmkkc_widthnhdw();
			</script>';
		}
	}
    function global_comiis_forumdisplay_list_bottom(){
		global $_G;
		if(($_G['basescript'] == 'plugin' && CURMODULE=='comiis_app_portal') || ($_G['basescript']=='forum' || $_G['basescript'] == 'group') && CURMODULE=='forumdisplay' && $_G['style']['directory'] == './template/comiis_app' && (in_array($_G['fid'], unserialize($_G['cache']['plugin']['comiis_app_video']['comiis_forum_audio'])) || in_array($_G['fid'], unserialize($_G['cache']['plugin']['comiis_app_video']['comiis_forum_video'])) || (($_G['basescript'] == 'group' && $_G['cache']['plugin']['comiis_app_video']['comiis_group_list']) || ($_G['basescript'] == 'group' && $_G['cache']['plugin']['comiis_app_video']['comiis_group_list_audio'])))){
			include_once DISCUZ_ROOT.'./source/plugin/comiis_app_video/comiis_app_video.fun.php';
			return _comiis_forumdisplay_video_list();
		}
	}
	function global_comiis_video_box(){
		global $_G;
		if($_G['style']['directory'] == './template/comiis_app' && ($_G['basescript'] == 'forum' || $_G['basescript'] == 'group') && CURMODULE == 'post'){
			$users = unserialize($_G['cache']['plugin']['comiis_app_video']['comiis_upload_group']);
			if(isset($users[0]) && ($users[0] == '0' || $users[0] == '')){
				unset($users[0]);
			}
			if(($_G['basescript'] == 'group' && $_G['cache']['plugin']['comiis_app_video']['comiis_group_uploadvideo']) || in_array($_G['fid'], unserialize($_G['cache']['plugin']['comiis_app_video']['comiis_upload_video'])) && count($users) && in_array($_G['member']['groupid'], $users)){
				include_once DISCUZ_ROOT.'./source/plugin/comiis_app_video/comiis_app_video.fun.php';
				$_G['comiis_video_is_comiis'] = 1;
				$_G['comiis_video_access_token'] = _comiis_getyoukukey();
				include_once DISCUZ_ROOT.'./source/plugin/comiis_app_video/language/language.'.currentlang().'.php';
				include template('comiis_app_video:hook');
				return $html;
			}
		}
	}
}
class mobileplugin_comiis_app_video_forum extends mobileplugin_comiis_app_video{
    function viewthread_postbottom_mobile(){
		global $_G;
		if($_G['is_comiis_portal'] == 1 || (($_G['forum_thread']['isgroup'] || $_G['basescript'] == 'group') && ($_G['cache']['plugin']['comiis_app_video']['comiis_group'] || $_G['cache']['plugin']['comiis_app_video']['comiis_group_list'])) || ($_G['basescript']=='forum' && CURMODULE=='viewthread' && in_array($_G['fid'], unserialize($_G['cache']['plugin']['comiis_app_video']['comiis_forum']))) || ($_G['basescript']=='forum' && CURMODULE=='forumdisplay' && in_array($_G['fid'], unserialize($_G['cache']['plugin']['comiis_app_video']['comiis_forum_video'])))){
			$re_js = '<script>comiis_setmkkc_widthnhdw();</script>';
			return array($re_js, $re_js, $re_js, $re_js, $re_js);
		}
	}
    function forumdisplay_thread_mobile_output(){
		global $_G;
		if($_G['basescript']=='forum' && CURMODULE=='forumdisplay' && $_G['style']['directory'] != './template/comiis_app' && (in_array($_G['fid'], unserialize($_G['cache']['plugin']['comiis_app_video']['comiis_forum_audio'])) || in_array($_G['fid'], unserialize($_G['cache']['plugin']['comiis_app_video']['comiis_forum_video'])) || ($_G['forum_thread']['isgroup'] && ($_G['cache']['plugin']['comiis_app_video']['comiis_group_list'] || $_G['cache']['plugin']['comiis_app_video']['comiis_group_list_audio'])))){
			include_once DISCUZ_ROOT.'./source/plugin/comiis_app_video/comiis_app_video.fun.php';
			return _comiis_forumdisplay_video_list();
		}
	}
	function post_bottom_mobile_output(){
		global $_G;
		if($_G['style']['directory'] != './template/comiis_app'){
			$users = unserialize($_G['cache']['plugin']['comiis_app_video']['comiis_upload_group']);
			if(isset($users[0]) && ($users[0] == '0' || $users[0] == '')){
				unset($users[0]);
			}
			if(($_G['basescript'] == 'group' && $_G['cache']['plugin']['comiis_app_video']['comiis_group_uploadvideo']) || in_array($_G['fid'], unserialize($_G['cache']['plugin']['comiis_app_video']['comiis_upload_video'])) && count($users) && in_array($_G['member']['groupid'], $users)){
				include_once DISCUZ_ROOT.'./source/plugin/comiis_app_video/comiis_app_video.fun.php';
				$_G['comiis_video_is_comiis'] = 0;
				$_G['comiis_video_access_token'] = _comiis_getyoukukey();
				include_once DISCUZ_ROOT.'./source/plugin/comiis_app_video/language/language.'.currentlang().'.php';
				include template('comiis_app_video:hook');
				return $html;
			}
		}
	}
}
