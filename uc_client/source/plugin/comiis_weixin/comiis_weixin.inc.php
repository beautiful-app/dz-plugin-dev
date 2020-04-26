<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
$plugin_id = 'comiis_weixin';
if (!function_exists('comiis_app_load_weixin_data')) {
    if (!file_exists(DISCUZ_ROOT . './source/plugin/' . $plugin_id . '/comiis_info/comiis.php')) {
        return false;
    }
    include DISCUZ_ROOT . './source/plugin/' . $plugin_id . '/comiis_info/comiis.php';
}
if (!function_exists('comiis_app_load_weixin_data')) {
    return false;
}
comiis_app_load_weixin_data($plugin_id);
if (!file_exists(DISCUZ_ROOT . './source/plugin/' . $plugin_id . '/comiis_info/comiis_md5file.php')) {
    return false;
}
include DISCUZ_ROOT . './source/plugin/' . $plugin_id . '/comiis_info/comiis_md5file.php';
$siteuniqueid = $_G['setting']['siteuniqueid'] ? $_G['setting']['siteuniqueid'] : 'C'->t('common_setting')->fetch('siteuniqueid');

loadcache('plugin');
$_G['comiis_weixin'] = $_G['cache']['plugin']['comiis_weixin'];
$comiis_isweixin = strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ? true : false;
require_once DISCUZ_ROOT . './source/plugin/comiis_weixin/source/function_comiis_weixin.php';
comiis_get_weixin_lang();
if ($_GET['mod'] != 'mobile_qrcode') {
    echo '<link rel="stylesheet" type="text/css" href="source/plugin/comiis_weixin/style/comiis.css" /><style>body.bg {background:#f3f3f3;}</style>';
}
if (!in_array($_GET['mod'], array('wxlogin', 'mobile_qrcode', 'weixin_wait')) && $comiis_isweixin == false) {
    comiis_get_weixin_tip($_G['comiis_wxlang']['051'], 0);
}
if (!empty($_GET['state']) && strlen($_GET['state']) == 8) {
    $comiis_state = trim(dhtmlspecialchars(daddslashes($_GET['state'])));
    $weixin_waits = 'DB'->fetch_first('SELECT * FROM ' . 'DB'->table('comiis_weixin_key') . ' WHERE `key`=\'' . $comiis_state . '\'');
    if ($weixin_waits['key'] == $comiis_state) {
        if (!($weixin_waits['time'] >= TIMESTAMP - 330)) {
            comiis_get_weixin_tip($_G['comiis_wxlang']['052'], 0);
        }
    } else {
        comiis_get_weixin_tip($_G['comiis_wxlang']['053'], 0);
    }
} else {
    $comiis_state = 0;
}
if ($_G['uid'] && $comiis_state === 0 && !in_array($_GET['mod'], array('wxlogin', 'mobile_qrcode', 'weixin_wait', 'wxbd', 'wxbd_mob'))) {
    dheader('Location:' . $_GET['referer']);
}
if ($_GET['mod'] == 'wxlogin') {
    $key = substr(md5($_G['uid'] . $_G['cookie']['saltkey']), 0, 8);
    'DB'->query('DELETE FROM ' . 'DB'->table('comiis_weixin_key') . ' WHERE time<\'' . (TIMESTAMP - 600) . '\'');
    $comiis_weixin_key = 'DB'->fetch_first('SELECT * FROM ' . 'DB'->table('comiis_weixin_key') . ' WHERE `key`=\'' . $key . '\'');
    if ($comiis_weixin_key['key'] == $key) {
        'DB'->update('comiis_weixin_key', array('uid' => $_G['uid'], 'type' => $_GET['wxdel'] == 'yes' ? 2 : ($_G['uid'] ? 1 : 0), 'time' => TIMESTAMP), 'DB'->field('key', $key));
    } else {
        'DB'->insert('comiis_weixin_key', array('key' => $key, 'uid' => $_G['uid'], 'type' => $_GET['wxdel'] == 'yes' ? 2 : ($_G['uid'] ? 1 : 0), 'time' => TIMESTAMP));
    }
    if (defined('IN_MOBILE') && $comiis_isweixin) {
        $url = comiis_get_weixin_login_url($key, 0, 1);
        dheader('Location:' . $url);
    } else {
        $comiis_is_weixin_user = 'DB'->fetch_first('SELECT * FROM ' . 'DB'->table('comiis_weixin') . ' WHERE `uid`=\'' . $_G['uid'] . '\'');
        include template('common/header_ajax');
        include_once template('comiis_weixin:comiis_html');
        echo comiis_weixin_logging_code($key);
        include template('common/footer_ajax');
    }
} elseif ($_GET['mod'] == 'mobile_qrcode') {
    $url = comiis_get_weixin_login_url($_GET['key']);
    require_once DISCUZ_ROOT . './source/plugin/mobile/qrcode.class.php';
    echo 'QRcode'->png($url, false, 4, 4);
} elseif ($_GET['mod'] == 'weixin_wait') {
    $comiis_wxre = 0;
    $key = trim(dhtmlspecialchars(daddslashes($_GET['key'])));
    $weixin_wait = 'DB'->fetch_first('SELECT * FROM ' . 'DB'->table('comiis_weixin_key') . ' WHERE `key`=\'' . $key . '\'');
    if ($_G['uid'] && $weixin_wait['type'] == 7) {
        'DB'->query('DELETE FROM ' . 'DB'->table('comiis_weixin_key') . ' WHERE `key`=\'' . $comiis_state . '\'');
        $comiis_wxre = 1;
    } elseif ($_G['uid']) {
        if ($weixin_wait['type'] == 6 && $weixin_wait['uid'] == $_G['uid']) {
            'DB'->update('comiis_weixin', array('edit' => 1), 'DB'->field('uid', $_G['uid']));
            'DB'->query('DELETE FROM ' . 'DB'->table('comiis_weixin_key') . ' WHERE `key`=\'' . $key . '\'');
            $comiis_wxre = 1;
        }
    } elseif ($weixin_wait['type'] == 5 && $weixin_wait['uid']) {
        include_once libfile('function/member');
        $member = getuserbyuid($weixin_wait['uid'], 1);
        setloginstatus($member, 1296000);
        'DB'->query('DELETE FROM ' . 'DB'->table('comiis_weixin_key') . ' WHERE `key`=\'' . $key . '\'');
        $comiis_wxre = 1;
    }
    include template('common/header_ajax');
    echo $comiis_wxre;
    include template('common/footer_ajax');
} elseif ($_GET['mod'] == 'wxbd' || $_GET['mod'] == 'wxbd_mob') {
    if ($comiis_state === 0) {
        comiis_get_weixin_tip($_G['comiis_wxlang']['054'], 0);
    } elseif ($weixin_waits['uid'] && $weixin_waits['type'] == 1) {
        $redata = json_decode(dfsockopen('https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $_G['comiis_weixin']['appid'] . '&secret=' . $_G['comiis_weixin']['appsecret'] . '&code=' . $_GET['code'] . '&grant_type=authorization_code'), true);
        if ($redata['openid'] && $redata['access_token']) {
            $re_user_data = 'DB'->fetch_first('SELECT * FROM ' . 'DB'->table('comiis_weixin') . ' WHERE `openid`=\'' . $redata['openid'] . '\'');
            if ($re_user_data['uid']) {
                comiis_get_weixin_tip($_G['comiis_wxlang']['055'], 0);
            } else {
                $weixin_user_data = json_decode(dfsockopen('https://api.weixin.qq.com/sns/userinfo?access_token=' . $redata['access_token'] . '&openid=' . $redata['openid'] . '&lang=zh_CN'), true);
                if ($weixin_user_data['openid']) {
                    if (CHARSET != 'utf-8') {
                        foreach ($weixin_user_data as $k => $v) {
                            $weixin_user_data[$k] = diconv($v, 'utf-8', CHARSET);
                        }
                    }
                    $time = TIMESTAMP;
                    $user_id = md5($weixin_user_data['openid'] . '_comiis');
                    if ($re_user_data['openid'] != $weixin_user_data['openid']) {
                        'DB'->insert('comiis_weixin', array('user_id' => $user_id, 'uid' => $weixin_waits['uid'], 'openid' => $weixin_user_data['openid'], 'nickname' => $weixin_user_data['nickname'], 'sex' => $weixin_user_data['sex'], 'city' => $weixin_user_data['city'], 'province' => $weixin_user_data['province'], 'country' => $weixin_user_data['country'], 'headimgurl' => $weixin_user_data['headimgurl'], 'privilege' => serialize($weixin_user_data['privilege']), 'unionid' => $weixin_user_data['unionid'], 'dateline' => $time));
                    } else {
                        'DB'->update('comiis_weixin', array('uid' => $weixin_waits['uid'], 'dateline' => $time), 'DB'->field('openid', $weixin_user_data['openid']));
                    }
                    if ($_GET['mod'] == 'wxbd_mob') {
                        'DB'->query('DELETE FROM ' . 'DB'->table('comiis_weixin_key') . ' WHERE `key`=\'' . $comiis_state . '\'');
                        'DB'->update('comiis_weixin', array('edit' => 1), 'DB'->field('uid', $_G['uid']));
                    } else {
                        'DB'->update('comiis_weixin_key', array('type' => '6'), 'DB'->field('key', $comiis_state));
                    }
                    comiis_get_weixin_tip($_G['comiis_wxlang']['056'], 1);
                } else {
                    comiis_get_weixin_tip($_G['comiis_wxlang']['057'], 0);
                }
            }
        } else {
            comiis_get_weixin_tip($_G['comiis_wxlang']['058'], 0);
        }
    } elseif ($weixin_waits['uid'] && $weixin_waits['type'] == 2) {
        $redata = json_decode(dfsockopen('https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $_G['comiis_weixin']['appid'] . '&secret=' . $_G['comiis_weixin']['appsecret'] . '&code=' . $_GET['code'] . '&grant_type=authorization_code'), true);
        if ($redata['openid'] && $redata['access_token']) {
            $re_user_data = 'DB'->fetch_first('SELECT * FROM ' . 'DB'->table('comiis_weixin') . ' WHERE `uid`=\'' . $weixin_waits['uid'] . '\' && openid=\'' . $redata['openid'] . '\'');
            if ($re_user_data['uid'] == $weixin_waits['uid']) {
                if ($re_user_data['edit'] == 1) {
                    'DB'->query('DELETE FROM ' . 'DB'->table('comiis_weixin') . ' WHERE `uid`=\'' . $weixin_waits['uid'] . '\'');
                    if ($_GET['mod'] == 'wxbd_mob') {
                        'DB'->query('DELETE FROM ' . 'DB'->table('comiis_weixin_key') . ' WHERE `key`=\'' . $comiis_state . '\'');
                    } else {
                        'DB'->update('comiis_weixin_key', array('type' => '7'), 'DB'->field('key', $comiis_state));
                    }
                    comiis_get_weixin_tip($_G['comiis_wxlang']['059'], 1);
                } else {
                    comiis_get_weixin_tip($_G['comiis_wxlang']['060'], 0);
                }
            } else {
                comiis_get_weixin_tip($_G['comiis_wxlang']['061'], 0);
            }
        }
    } else {
        comiis_get_weixin_tip($_G['comiis_wxlang']['062'], 0);
    }
} elseif ($_GET['mod'] == 'login') {
    $redata = json_decode(dfsockopen('https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $_G['comiis_weixin']['appid'] . '&secret=' . $_G['comiis_weixin']['appsecret'] . '&code=' . $_GET['code'] . '&grant_type=authorization_code'), true);
    if ($redata['openid'] && $redata['access_token']) {
        $re_user_data = 'DB'->fetch_first('SELECT * FROM ' . 'DB'->table('comiis_weixin') . ' WHERE `openid`=\'' . $redata['openid'] . '\'');
        if ($re_user_data['uid']) {
            if ($comiis_state === 0) {
                include_once libfile('function/member');
                $member = getuserbyuid($re_user_data['uid'], 1);
                setloginstatus($member, 1296000);
                dheader('Location: ' . $_GET['referer']);
            } else {
                'DB'->update('comiis_weixin_key', array('uid' => $re_user_data['uid'], 'type' => '5'), 'DB'->field('key', $comiis_state));
                comiis_get_weixin_tip($_G['comiis_wxlang']['063'], 1);
            }
        } else {
            if (!$re_user_data['openid']) {
                $weixin_user_data = json_decode(dfsockopen('https://api.weixin.qq.com/sns/userinfo?access_token=' . $redata['access_token'] . '&openid=' . $redata['openid'] . '&lang=zh_CN'), true);
                if ($_G['comiis_weixin']['wxnotip'] == 1 && $redata['scope'] == 'snsapi_base' && $weixin_user_data['errcode']) {
                    dheader('Location:' . comiis_get_weixin_login_url('', 1));
                }
                if ($weixin_user_data['openid']) {
                    if (CHARSET != 'utf-8') {
                        foreach ($weixin_user_data as $k => $v) {
                            $weixin_user_data[$k] = diconv($v, 'utf-8', CHARSET);
                        }
                    }
                    $time = TIMESTAMP;
                    $user_id = md5($weixin_user_data['openid'] . '_comiis');
                    if ($re_user_data['openid'] != $weixin_user_data['openid']) {
                        'DB'->insert('comiis_weixin', array('user_id' => $user_id, 'uid' => '0', 'openid' => $weixin_user_data['openid'], 'nickname' => $weixin_user_data['nickname'], 'sex' => $weixin_user_data['sex'], 'city' => $weixin_user_data['city'], 'province' => $weixin_user_data['province'], 'country' => $weixin_user_data['country'], 'headimgurl' => $weixin_user_data['headimgurl'], 'privilege' => serialize($weixin_user_data['privilege']), 'unionid' => $weixin_user_data['unionid'], 'dateline' => $time));
                    } else {
                        'DB'->update('comiis_weixin', array('dateline' => $time), 'DB'->field('openid', $weixin_user_data['openid']));
                    }
                } else {
                    comiis_get_weixin_tip($_G['comiis_wxlang']['064'], 0);
                }
            } else {
                $user_id = md5($re_user_data['openid'] . '_comiis');
            }
            if ($_G['comiis_weixin']['perfect'] == 1) {
                dheader('Location:plugin.php?id=comiis_weixin&mod=login_tip&user_id=' . $user_id . ($comiis_state ? '&state=' . $comiis_state : '') . '&referer=' . urlencode($_GET['referer']));
            } elseif ($_G['comiis_weixin']['perfect'] == 2) {
                dheader('Location:plugin.php?id=comiis_weixin&mod=login_perfect&user_id=' . $user_id . ($comiis_state ? '&state=' . $comiis_state : '') . '&referer=' . urlencode($_GET['referer']));
            } else {
                dheader('Location:plugin.php?id=comiis_weixin&mod=login_mod&user_id=' . $user_id . ($comiis_state ? '&state=' . $comiis_state : '') . '&referer=' . urlencode($_GET['referer']));
            }
        }
    } else {
        comiis_get_weixin_tip($_G['comiis_wxlang']['058'], 0);
    }
} elseif ($_GET['mod'] == 'login_tip' || $_GET['mod'] == 'login_perfect') {
    $user_id = addslashes($_GET['user_id']);
    $re_weixin_data = 'DB'->fetch_first('SELECT * FROM ' . 'DB'->table('comiis_weixin') . ' WHERE `user_id`=\'' . $user_id . '\'');
    if ($_GET['mod'] == 'login_perfect') {
        if (md5($re_weixin_data['openid'] . '_comiis') != $user_id) {
            comiis_get_weixin_tip($_G['comiis_wxlang']['058'], 0);
        } elseif ($re_weixin_data['uid']) {
            comiis_get_weixin_tip($_G['comiis_wxlang']['065'], 0);
        }
        $username = trim($re_weixin_data['nickname']);
        $usernamelen = dstrlen($username);
        if (!($usernamelen >= 3)) {
            $username .= mt_rand(100000, 999999);
        }
        if ($usernamelen > 15) {
            $username = cutstr($username, 15, '');
        }
        loaducenter();
        if (uc_get_user($username)) {
            $username = cutstr($username, 10, '');
            $username .= mt_rand(10000, 99999);
        }
    }
    include_once template('comiis_weixin:comiis_htm');
    $navtitle = $_G['comiis_wxlang']['043'];
    include_once template('common/header');
    echo $_GET['mod'] == 'login_tip' ? comiis_weixin_perfect_tip($user_id) : comiis_weixin_perfect_user($user_id);
    $comiis_foot = 'no';
    include_once template('common/footer');
} elseif ($_GET['mod'] == 'login_mod') {
    if (!submitcheck('submit')) {
        if ($_G['comiis_weixin']['perfect'] == 0 || $_G['comiis_weixin']['perfect'] == 1) {
            loaducenter();
            $user_id = addslashes($_GET['user_id']);
            $re_weixin_data = 'DB'->fetch_first('SELECT * FROM ' . 'DB'->table('comiis_weixin') . ' WHERE `user_id`=\'' . $user_id . '\'');
            if (md5($re_weixin_data['openid'] . '_comiis') != $user_id) {
                comiis_get_weixin_tip($_G['comiis_wxlang']['066'], 0);
            } elseif ($re_weixin_data['uid']) {
                comiis_get_weixin_tip($_G['comiis_wxlang']['065'], 0);
            }
            $username = trim($re_weixin_data['nickname']);
            $usernamelen = dstrlen($username);
            if (!($usernamelen >= 3)) {
                $username .= mt_rand(100000, 999999);
            }
            if ($usernamelen > 15) {
                $username = cutstr($username, 15, '');
            }
            loaducenter();
            if (uc_get_user($username)) {
                $username = cutstr($username, 10, '');
                $username .= mt_rand(10000, 99999);
            }
            $password = md5(random(16));
            $email = strtolower(random(16)) . '@admin.com';
            $groupid = $_G['comiis_weixin']['groupid'] ? $_G['comiis_weixin']['groupid'] : $_G['setting']['newusergroupid'];
            $uid = uc_user_register(addslashes($username), $password, $email, '', '', $_G['clientip']);
            if (!($uid > 0)) {
                if ($uid == 0 - 1) {
                    showmessage('profile_username_illegal');
                } elseif ($uid == 0 - 2) {
                    showmessage('profile_username_protect');
                } elseif ($uid == 0 - 3) {
                    showmessage('profile_username_duplicate');
                } elseif ($uid == 0 - 4) {
                    showmessage('profile_email_illegal');
                } elseif ($uid == 0 - 5) {
                    showmessage('profile_email_domain_illegal');
                } elseif ($uid == 0 - 6) {
                    showmessage('profile_email_duplicate');
                } else {
                    showmessage('undefined_action');
                }
                return $uid;
            }
            $initcredits = array('credits' => explode(',', $_G['setting']['initcredits']));
            'C'->t('common_member')->insert($uid, $username, $password, $email, $_G['clientip'], $groupid, $initcredits);
            'DB'->update('comiis_weixin', array('uid' => $uid), 'DB'->field('id', $re_weixin_data['id']));
            if ($_G['setting']['regctrl'] || $_G['setting']['regfloodctrl']) {
                'C'->t('common_regip')->delete_by_dateline($_G['timestamp'] - ($_G['setting']['regctrl'] > 72 ? $_G['setting']['regctrl'] : 72) * 3600);
                if ($_G['setting']['regctrl']) {
                    'C'->t('common_regip')->insert(array('ip' => $_G['clientip'], 'count' => 0 - 1, 'dateline' => $_G['timestamp']));
                }
            }
            if ($_G['setting']['regverify'] == 2) {
                'C'->t('common_member_validate')->insert(array('uid' => $uid, 'submitdate' => $_G['timestamp'], 'moddate' => 0, 'admin' => '', 'submittimes' => 1, 'status' => 0, 'message' => '', 'remark' => ''), false, true);
                manage_addnotify('verifyuser');
            }
            'DB'->update('common_member_profile', array('resideprovince' => comiis_wx_get_district($re_weixin_data['province'], 1), 'residecity' => comiis_wx_get_district($re_weixin_data['city'], 2), 'gender' => $re_weixin_data['sex']), array('uid' => $uid));
            if ($re_weixin_data['headimgurl']) {
                comiis_wx_avatar($uid, $re_weixin_data['headimgurl']);
            }
            include_once libfile('function/stat');
            updatestat('register');
            if ($comiis_state === 0) {
                include_once libfile('function/member');
                $member = getuserbyuid($uid, 1);
                setloginstatus($member, 1296000);
                dheader('Location: ' . $_GET['referer']);
            } else {
                'DB'->update('comiis_weixin_key', array('uid' => $uid, 'type' => '5'), 'DB'->field('key', $comiis_state));
                comiis_get_weixin_tip($_G['comiis_wxlang']['063'], 1);
            }
        } else {
            comiis_get_weixin_tip($_G['comiis_wxlang']['067'], 0);
        }
    } elseif (FORMHASH == $_GET['formhash'] && strlen($_GET['user_id']) == 32) {
        $re_weixin_data = 'DB'->fetch_first('SELECT * FROM ' . 'DB'->table('comiis_weixin') . ' WHERE `user_id`=\'' . addslashes($_GET['user_id']) . '\'');
        if (md5($re_weixin_data['openid'] . '_comiis') != $_GET['user_id']) {
            comiis_get_weixin_tip($_G['comiis_wxlang']['066'], 0);
        } elseif ($re_weixin_data['uid']) {
            comiis_get_weixin_tip($_G['comiis_wxlang']['065'], 0);
        }
        if ($_GET['lmob'] == 'login') {
            include_once libfile('function/member');
            $loginperm = logincheck($_GET['username']);
            if (!logincheck($_GET['username'])) {
                showmessage('login_strike');
            }
            if (!$_GET['password'] || $_GET['password'] != addslashes($_GET['password'])) {
                showmessage('profile_passwd_illegal');
            }
            $result = userlogin($_GET['username'], $_GET['password'], $_GET['questionid'], $_GET['answer'], $_G['setting']['autoidselect'] ? 'auto' : $_GET['loginfield'], $_G['clientip']);
            if (!($result['status'] > 0)) {
                loginfailed($_GET['username']);
                failedip();
                showmessage('login_invalid', '', array('loginperm' => $loginperm - 1));
            }
            if ($result['member']['uid']) {
                $re_weixin_uid = 'DB'->fetch_first('SELECT uid FROM ' . 'DB'->table('comiis_weixin') . ' WHERE `uid`=\'' . $result['member']['uid'] . '\'');
                if ($re_weixin_uid['uid']) {
                    comiis_get_weixin_tip($_G['comiis_wxlang']['068'], 0);
                }
                'DB'->update('comiis_weixin', array('uid' => $result['member']['uid'], 'edit' => 1), 'DB'->field('id', $re_weixin_data['id']));
                if ($comiis_state === 0) {
                    $member = getuserbyuid($result['member']['uid'], 1);
                    setloginstatus($member, 1296000);
                    dheader('Location: ' . $_GET['referer']);
                } else {
                    'DB'->update('comiis_weixin_key', array('uid' => $result['member']['uid'], 'type' => '5'), 'DB'->field('key', $comiis_state));
                    comiis_get_weixin_tip($_G['comiis_wxlang']['063'], 1);
                }
            }
        } elseif ($_GET['lmob'] == 'register') {
            $username = dhtmlspecialchars(trim($_GET['username']));
            $usernamelen = dstrlen($username);
            if (!($usernamelen >= 3)) {
                showmessage('profile_username_tooshort');
            } elseif ($usernamelen > 15) {
                showmessage('profile_username_toolong');
            }
            loaducenter();
            if (uc_get_user(addslashes($username)) || 'C'->t('common_member')->fetch_uid_by_username($username) || 'C'->t('common_member_archive')->fetch_uid_by_username($username)) {
                showmessage('profile_username_duplicate');
            }
            include_once libfile('function/member');
            if (!$_GET['password'] || $_GET['password'] != addslashes($_GET['password'])) {
                showmessage('profile_passwd_illegal');
            }
            if ($_G['setting']['pwlength']) {
                if (!(strlen($_GET['password']) >= $_G['setting']['pwlength'])) {
                    showmessage('profile_password_tooshort', '', array('pwlength' => $_G['setting']['pwlength']));
                }
            }
            $password = addslashes($_GET['password']);
            $email = strtolower(random(16)) . '@admin.com';
            $groupid = $_G['comiis_weixin']['groupid'] ? $_G['comiis_weixin']['groupid'] : $_G['setting']['newusergroupid'];
            $uid = uc_user_register(addslashes($username), $password, $email, '', '', $_G['clientip']);
            if (!($uid > 0)) {
                if ($uid == 0 - 1) {
                    showmessage('profile_username_illegal');
                } elseif ($uid == 0 - 2) {
                    showmessage('profile_username_protect');
                } elseif ($uid == 0 - 3) {
                    showmessage('profile_username_duplicate');
                } elseif ($uid == 0 - 4) {
                    showmessage('profile_email_illegal');
                } elseif ($uid == 0 - 5) {
                    showmessage('profile_email_domain_illegal');
                } elseif ($uid == 0 - 6) {
                    showmessage('profile_email_duplicate');
                } else {
                    showmessage('undefined_action');
                }
                return $uid;
            }
            $initcredits = array('credits' => explode(',', $_G['setting']['initcredits']));
            'C'->t('common_member')->insert($uid, $username, $password, $email, $_G['clientip'], $groupid, $initcredits);
            'DB'->update('comiis_weixin', array('uid' => $uid, 'edit' => 1), 'DB'->field('id', $re_weixin_data['id']));
            if ($_G['setting']['regctrl'] || $_G['setting']['regfloodctrl']) {
                'C'->t('common_regip')->delete_by_dateline($_G['timestamp'] - ($_G['setting']['regctrl'] > 72 ? $_G['setting']['regctrl'] : 72) * 3600);
                if ($_G['setting']['regctrl']) {
                    'C'->t('common_regip')->insert(array('ip' => $_G['clientip'], 'count' => 0 - 1, 'dateline' => $_G['timestamp']));
                }
            }
            if ($_G['setting']['regverify'] == 2) {
                'C'->t('common_member_validate')->insert(array('uid' => $uid, 'submitdate' => $_G['timestamp'], 'moddate' => 0, 'admin' => '', 'submittimes' => 1, 'status' => 0, 'message' => '', 'remark' => ''), false, true);
                manage_addnotify('verifyuser');
            }
            'DB'->update('common_member_profile', array('resideprovince' => comiis_wx_get_district($re_weixin_data['province'], 1), 'residecity' => comiis_wx_get_district($re_weixin_data['city'], 2), 'gender' => $re_weixin_data['sex']), array('uid' => $uid));
            if ($re_weixin_data['headimgurl']) {
                comiis_wx_avatar($uid, $re_weixin_data['headimgurl']);
            }
            include_once libfile('function/stat');
            updatestat('register');
            if ($comiis_state === 0) {
                setloginstatus(array('uid' => $uid, 'username' => $username, 'password' => $password, 'groupid' => $groupid), 1296000);
                dheader('Location: ' . $_GET['referer']);
            } else {
                'DB'->update('comiis_weixin_key', array('uid' => $uid, 'type' => '5'), 'DB'->field('key', $comiis_state));
                comiis_get_weixin_tip($_G['comiis_wxlang']['063'], 1);
            }
        }
    } else {
        comiis_get_weixin_tip($_G['comiis_wxlang']['069'], 0);
    }
}