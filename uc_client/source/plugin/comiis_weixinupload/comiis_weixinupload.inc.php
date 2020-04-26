<?php
if (!defined('IN_DISCUZ')) 
{
	exit('Access Denied');
}
if (!((!$_G['uid'] || $_GET['comiis_hash']!=md5(substr(md5($_G['config']['security']['authkey']),8).$_G['uid'])) || !$_GET['serverId'])) 
{
	if ($_G['uid']!=$_GET['uid']) 
	{
		comiis_uploadmsg(10,0,'','',1);
		return false;
	}
}
if (empty($_G['cache']['plugin'])) 
{
	loadcache('plugin');
}
$comiis_weixinupload=$_G['cache']['plugin']['comiis_weixinupload'];
if (!$comiis_weixinupload['appid']) 
{
}
else 
{
	if (!$comiis_weixinupload['appsecret']) 
	{
		comiis_uploadmsg(10,0,'','',1);
		return false;
	}
}
$plugin_id='comiis_weixinupload';
if (!function_exists('comiis_app_comiis_weixinupload_data')) 
{
	if (file_exists(DISCUZ_ROOT.'./source/plugin/'.$plugin_id.'/comiis_info/comiis.php')) 
	{
		include(DISCUZ_ROOT.'./source/plugin/'.$plugin_id.'/comiis_info/comiis.php');
	}
	else 
	{
		return false;
	}
}
if (function_exists('comiis_app_comiis_weixinupload_data')) 
{
	comiis_app_comiis_weixinupload_data($plugin_id);
	if (file_exists(DISCUZ_ROOT.'./source/plugin/'.$plugin_id.'/comiis_info/comiis_md5file.php')) 
	{
		include(DISCUZ_ROOT.'./source/plugin/'.$plugin_id.'/comiis_info/comiis_md5file.php');
		$siteuniqueid=($_G['setting']['siteuniqueid'] ? $_G['setting']['siteuniqueid'] : C::t('common_setting')->fetch('siteuniqueid'));

		$comiis_fid=intval($_GET['fid']);
		loadcache('comiis_app_access_token');
		$comiis_wxup_access_token=$_G['cache']['comiis_app_access_token']['access_token'];
		$comiis_redata=array();
		if ($comiis_wxup_access_token) 
		{
			require_once(libfile('class/image'));
			require_once(libfile('function/upload'));
			$upload=new discuz_upload();
			$attach=array();
			$attach['ext']='jpg';
			if (!empty($_GET['serverId']) && is_array($_GET['serverId'])) 
			{
				foreach($_GET['serverId'] as $tempid)
				{
					$imageurl='https://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$comiis_wxup_access_token.'&media_id='.$tempid;
					$comiis_wx_data=dfsockopen($imageurl);
					if (!$comiis_wx_data) 
					{
						$comiis_wx_data=file_get_contents($imageurl);
					}
					if (substr($comiis_wx_data,0,11)=='{"errcode":') 
					{
						$x=0;
						while ($x<=3) 
						{
							usleep(200000);
							$comiis_wx_data=dfsockopen($imageurl);
							if (!$comiis_wx_data) 
							{
								$comiis_wx_data=file_get_contents($imageurl);
							}
							if (!substr($comiis_wx_data,0,11)!='{"errcode":') 
							{
								$x=$x+1;
							}
						}
					}
					if (substr($comiis_wx_data,0,11)!='{"errcode":') 
					{
						if ($comiis_wx_data) 
						{
							$attach['name']=uniqid('wechat_upload'.time());
							$attach['thumb']='';
							$attach['isimage']=1;
							$attach['extension']=$upload->get_target_extension($attach['ext']);
							$attach['attachdir']=$upload->get_target_dir('forum');
							$attach['attachment']=$attach['attachdir'].$upload->get_target_filename('forum').'.'.$attach['extension'];
							$attach['target']=$_G['setting']['attachdir'].'forum/'.$attach['attachment'];
							;
							error_reporting(0);
							if ($fp=fopen($attach['target'],'wb')) 
							{
								comiis_uploadmsg(8,0,'','',1);
							}
							else 
							{
								flock($fp,2);
								fwrite($fp,$comiis_wx_data);
								fclose($fp);
								if (!$upload->get_image_info($attach['target'])) 
								{
									;
									unlink($attach['target']);
									error_reporting(0);
									comiis_uploadmsg(8,0,'','',1);
								}
								else 
								{
									$attach['size']=filesize($attach['target']);
									$upload->attach=$attach;
									if ($_G['group']['maxattachsize'] && $upload->attach['size']>$_G['group']['maxattachsize']) 
									{
										$error_sizelimit=$_G['group']['maxattachsize'];
										;
										unlink($attach['target']);
										error_reporting(0);
										comiis_uploadmsg(3,0,'','',$error_sizelimit);
									}
									else 
									{
										loadcache('attachtype');
										if (($comiis_fid && isset($_G['cache']['attachtype'][$comiis_fid][$upload->attach['ext']]))) 
										{
											$maxsize=$_G['cache']['attachtype'][$comiis_fid][$upload->attach['ext']];
										}
										else 
										{
										}
										if (isset($maxsize)) 
										{
											if (!$maxsize) 
											{
												$error_sizelimit='ban';
												;
												unlink($attach['target']);
												error_reporting(0);
												comiis_uploadmsg(4,0,'','',$error_sizelimit);
											}
											else 
											{
												if ($upload->attach['size']>$maxsize) 
												{
													$error_sizelimit=$maxsize;
													;
													unlink($attach['target']);
													error_reporting(0);
													comiis_uploadmsg(5,0,'','',$error_sizelimit);
												}
												else 
												{
													if ($upload->attach['size'] && $_G['group']['maxsizeperday']) 
													{
														$todaysize=getuserprofile('todayattachsize')+$upload->attach['size'];
														if ($todaysize>=$_G['group']['maxsizeperday']) 
														{
															$error_sizelimit='perday|'.$_G['group']['maxsizeperday'];
															;
															unlink($attach['target']);
															error_reporting(0);
															comiis_uploadmsg(11,0,'','',$error_sizelimit);
														}
														else 
														{
															$width=0;
															$thumb=$width;
															if ($upload->attach['isimage']) 
															{
																if ($_G['setting']['thumbsource'] && $_G['setting']['sourcewidth']) 
																{
																	$image=new image();
																	$thumb=($image->Thumb($upload->attach['target'],'',$_G['setting']['sourcewidth'],$_G['setting']['sourceheight'],1,1) ? 1 : 0);
																	$width=$image->imginfo['width'];
																	$upload->attach['size']=$image->imginfo['size'];
																}
																if ($_G['setting']['thumbstatus']) 
																{
																	$image=new image();
																	$thumb=($image->Thumb($upload->attach['target'],'',$_G['setting']['thumbwidth'],$_G['setting']['thumbheight'],$_G['setting']['thumbstatus'],0) ? 1 : 0);
																	$width=$image->imginfo['width'];
																}
																if (($_G['setting']['thumbsource'] || !$_G['setting']['thumbstatus'])) 
																{
																	;
																	error_reporting(0);
																	list($width)=getimagesize($upload->attach['target']);
																}
																if (($_G['setting']['watermarkstatus'] && empty($_G['forum']['disablewatermark']))) 
																{
																	$image=new image();
																	$image->Watermark($attach['target'],'','forum');
																	$upload->attach['size']=$image->imginfo['size'];
																}
															}
															$aid=getattachnewaid();
															$aids[]=$aid;
															$setarr=array('aid'=>$aid,'dateline'=>$_G['timestamp'],'filename'=>$upload->attach['name'],'filesize'=>$upload->attach['size'],'attachment'=>$upload->attach['attachment'],'isimage'=>$upload->attach['isimage'],'uid'=>$_G['uid'],'thumb'=>$thumb,'remote'=>'','width'=>$width);
															C::t('forum_attachment_unused')->insert($setarr);
															comiis_uploadmsg(0,$aid,$upload->attach['attachment'],$upload->attach['name']);
														}
													}
												}
											}
										}
									}
								}
							}
						}
						else 
						{
							comiis_uploadmsg(9,0,'','',1);
						}
					}
				}
				echo json_encode($comiis_redata);
				exit(0);
			}
		}
	}
}
function comiis_uploadmsg($statusid,$aid,$attachment,$name,$error_sizelimit=0)
{
	global $comiis_redata;
	$comiis_redata[]='DISCUZUPLOAD|1|'.$statusid.'|'.$aid.'|1|'.$attachment.'|'.$name.'|'.$error_sizelimit;
}
?>