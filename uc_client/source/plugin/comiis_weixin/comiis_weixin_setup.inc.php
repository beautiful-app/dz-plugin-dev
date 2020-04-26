<?php

if (!defined('IN_DISCUZ') && !$_G['uid']) {
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

if (!in_array($_GET['ops'], array('setup', 'edit'))) {
    $_GET['ops'] = 'setup';
}
$comiis_isweixin = strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ? true : false;
$_G['comiis_weixin'] = $_G['cache']['plugin']['comiis_weixin'];
$comiis_is_weixin_user = 'DB'->fetch_first('SELECT * FROM ' . 'DB'->table('comiis_weixin') . ' WHERE `uid`=\'' . $_G['uid'] . '\'');
require_once DISCUZ_ROOT . './source/plugin/comiis_weixin/source/function_comiis_weixin.php';
comiis_get_weixin_lang();
$_G['basescript'] = 'comiis_app_home';
$comiis_foot = 'no';
if ($_GET['ops'] == 'setup') {
    if (defined('IN_MOBILE')) {
        include template('common/header');
        echo '<link rel="stylesheet" type="text/css" href="source/plugin/comiis_weixin/style/comiis.css" /><style>body.bg {background:#f3f3f3;}</style>';
        include_once template('comiis_weixin:touch/comiis_weixin_setup');
        include template('common/footer');
        exit(0);
    }
} elseif ($_GET['ops'] == 'edit') {
    if (submitcheck('editsubmit')) {
        $password = '';
        include_once libfile('function/member');
        loaducenter();
        $username = dhtmlspecialchars(trim($_GET['newname']));
        $usernamelen = dstrlen($username);
        if (!($usernamelen >= 3)) {
            showmessage('profile_username_tooshort');
        } elseif ($usernamelen > 15) {
            showmessage('profile_username_toolong');
        }
        if ($username != $_G['member']['username']) {
            if (uc_get_user(addslashes($username)) || 'C'->t('common_member')->fetch_uid_by_username($username) || 'C'->t('common_member_archive')->fetch_uid_by_username($username)) {
                showmessage('profile_username_duplicate');
            }
        }// f r om ww w.mo qu8. co m
        if ($_G['setting']['strongpw']) {
            $strongpw_str = array();
            if (in_array(1, $_G['setting']['strongpw']) && !preg_match('/\\d+/', $_GET['password2'])) {
                $strongpw_str[] = lang('member/template', 'strongpw_1');
            }
            if (in_array(2, $_G['setting']['strongpw']) && !preg_match('/[a-z]+/', $_GET['password2'])) {
                $strongpw_str[] = lang('member/template', 'strongpw_2');
            }
            if (in_array(3, $_G['setting']['strongpw']) && !preg_match('/[A-Z]+/', $_GET['password2'])) {
                $strongpw_str[] = lang('member/template', 'strongpw_3');
            }
            if (in_array(4, $_G['setting']['strongpw']) && !preg_match('/[^a-zA-z0-9]+/', $_GET['password2'])) {
                $strongpw_str[] = lang('member/template', 'strongpw_4');
            }
            if ($strongpw_str) {
                showmessage(lang('member/template', 'password_weak') . implode(',', $strongpw_str));
            }
        }
        if ($_GET['password'] !== $_GET['password2']) {
            showmessage('profile_passwd_notmatch');
        }
        if (!$_GET['password'] || $_GET['password'] != addslashes($_GET['password'])) {
            showmessage('profile_passwd_illegal');
        }
        if ($_G['setting']['pwlength']) {
            if (!(strlen($_GET['password']) >= $_G['setting']['pwlength'])) {
                showmessage('profile_password_tooshort', '', array('pwlength' => $_G['setting']['pwlength']));
            }
        }
        $ucresult = uc_user_edit(addslashes($_G['member']['username']), null, $_GET['password'], null, 1);
        if ($ucresult == 0 - 1) {
            showmessage('profile_passwd_wrong', '', array(), array('return' => true));
        }
        'C'->t('common_member')->update($_G['uid'], array('password' => md5(random(10))));
        if ($username != $_G['member']['username']) {
            $oldname = $_G['member']['username'];
            $newname = $username;
            'DB'->query('UPDATE ' . 'DB'->table('common_adminnote') . (' SET admin=\'' . $newname . '\' WHERE admin=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('common_block') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('common_block_item') . (' SET title=\'' . $newname . '\' WHERE title=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('common_block_item_data') . (' SET title=\'' . $newname . '\' WHERE title=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('common_card_log') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('common_failedlogin') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('common_grouppm') . (' SET author=\'' . $newname . '\' WHERE author=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('common_invite') . (' SET fusername=\'' . $newname . '\' WHERE fusername=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('common_member') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('common_member_security') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('common_member_validate') . (' SET admin=\'' . $newname . '\' WHERE admin=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('common_member_verify_info') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('common_member_security') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('common_mytask') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('common_report') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('common_session') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('common_word') . (' SET admin=\'' . $newname . '\' WHERE admin=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_activityapply') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_announcement') . (' SET author=\'' . $newname . '\' WHERE author=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_collection') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_collectioncomment') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_collectionfollow') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_collectionteamworker') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_forumrecommend') . (' SET author=\'' . $newname . '\' WHERE author=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_groupuser') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_imagetype') . (' SET name=\'' . $newname . '\' WHERE name=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_order') . (' SET buyer=\'' . $newname . '\' WHERE buyer=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_order') . (' SET admin=\'' . $newname . '\' WHERE admin=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_pollvoter') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_post') . (' SET author=\'' . $newname . '\' WHERE author=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_postcomment') . (' SET author=\'' . $newname . '\' WHERE author=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_promotion') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_ratelog') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_rsscache') . (' SET author=\'' . $newname . '\' WHERE author=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_thread') . (' SET author=\'' . $newname . '\' WHERE author=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_threadmod') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_trade') . (' SET seller=\'' . $newname . '\' WHERE seller=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_tradelog') . (' SET seller=\'' . $newname . '\' WHERE seller=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_tradelog') . (' SET buyer=\'' . $newname . '\' WHERE buyer=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_warning') . (' SET author=\'' . $newname . '\' WHERE author=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_album') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_blog') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_clickuser') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_comment') . (' SET author=\'' . $newname . '\' WHERE author=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_docomment') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_doing') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_feed') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_feed_app') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_follow') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_follow') . (' SET fusername=\'' . $newname . '\' WHERE fusername=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_follow_feed') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_follow_feed_archiver') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_friend') . (' SET fusername=\'' . $newname . '\' WHERE fusername=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_friend_request') . (' SET fusername=\'' . $newname . '\' WHERE fusername=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_notification') . (' SET author=\'' . $newname . '\' WHERE author=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_pic') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_poke') . (' SET fromusername=\'' . $newname . '\' WHERE fromusername=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_share') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_show') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_specialuser') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_visitor') . (' SET vusername=\'' . $newname . '\' WHERE vusername=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_specialuser') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('portal_article_title') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('portal_comment') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('portal_rsscache') . (' SET author=\'' . $newname . '\' WHERE author=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('portal_topic') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('portal_topic_pic') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_collection') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_collectioncomment') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_collectionfollow') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('forum_collectionteamworker') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_follow') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_follow') . (' SET username=\'' . $fusername . '\' WHERE username=\'' . $fusername . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_follow_feed') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . 'DB'->table('home_follow_feed_archiver') . (' SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . UC_DBTABLEPRE . ('admins SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . UC_DBTABLEPRE . ('badwords SET admin=\'' . $newname . '\' WHERE admin=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . UC_DBTABLEPRE . ('feeds SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . UC_DBTABLEPRE . ('members SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . UC_DBTABLEPRE . ('mergemembers SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'DB'->query('UPDATE ' . UC_DBTABLEPRE . ('protectedmembers SET username=\'' . $newname . '\' WHERE username=\'' . $oldname . '\''));
            'C'->t('common_member')->update_cache($_G['uid'], array('username' => $newname));
        }
        'DB'->update('comiis_weixin', array('edit' => 1), 'DB'->field('uid', $_G['uid']));
        showmessage($_G['comiis_wxlang']['070'], dreferer(), array());
    } elseif (defined('IN_MOBILE')) {
        include template('common/header');
        echo '<link rel="stylesheet" type="text/css" href="source/plugin/comiis_weixin/style/comiis.css" /><style>body.bg {background:#f3f3f3;}</style>';
        include_once template('comiis_weixin:touch/comiis_weixin_setup');
        include template('common/footer');
        exit(0);
    }
}