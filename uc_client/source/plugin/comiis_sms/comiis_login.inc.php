<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require_once DISCUZ_ROOT . './source/plugin/comiis_sms/language/language.' . currentlang() . '.php';
$_G['comiis_sms'] = $_G['cache']['plugin']['comiis_sms'];
if (!in_array($_GET['action'], array('login'))) {
    showmessage($comiis_sms['79']);
}
$plugin_id = 'comiis_sms';
$comiis_upload = 0;
$comiis_info = array();
$comiis_system_config = array();
$comiis_md5file = array();
$siteuniqueid = $_G['setting']['siteuniqueid'] ? $_G['setting']['siteuniqueid'] : C::t('common_setting')->fetch('siteuniqueid');
if (file_exists(DISCUZ_ROOT . './source/plugin/' . $plugin_id . '/comiis_info/comiis_md5file.php')) {
    include DISCUZ_ROOT . './source/plugin/' . $plugin_id . '/comiis_info/comiis_md5file.php';

} else {
    $comiis_upload = 1;
}
if ($_GET['comiis_up_sn'] === 'yes') {
    $comiis_upload = 1;
}// f r om www.mo qu8. co m
if ($comiis_upload == 1) {
    loadcache($plugin_id . '_up');
    if ($_G['cache'][$plugin_id . '_up']['up'] != 1) {
        save_syscache($plugin_id . '_up', array('up' => 1));
        if (!function_exists('comiis_app_load_sms_data')) {
            if (!file_exists(DISCUZ_ROOT . './source/plugin/' . $plugin_id . '/comiis_info/comiis.php')) {
                return false;
            }
            include DISCUZ_ROOT . './source/plugin/' . $plugin_id . '/comiis_info/comiis.php';
        }
        if (!function_exists('comiis_app_load_sms_data')) {
            return false;
        }
        comiis_app_load_sms_data($plugin_id);
        save_syscache($plugin_id . '_up', array('up' => 0));
    }
}
if (!file_exists(DISCUZ_ROOT . './source/plugin/' . $plugin_id . '/comiis_info/comiis_md5file.php')) {
    return false;
}
include DISCUZ_ROOT . './source/plugin/' . $plugin_id . '/comiis_info/comiis_md5file.php';

if ($_G['uid']) {
    showmessage($comiis_sms['79']);
}
DB::query('DELETE FROM ' . DB::table('comiis_sms_temp') . ' WHERE dateline<\'' . (TIMESTAMP - 86400) . '\'');
if ($_GET['action'] == 'login' && $_G['comiis_sms']['tel_reglogin']) {
    loadcache('plugin');
    $_G['comiis_sms'] = $_G['cache']['plugin']['comiis_sms'];
    if ($_G['comiis_sms']['login_seccodeverify']) {
        list($seccodecheck) = seccheck('login');
        if ($seccodecheck) {
            $sectpl = '<table><tr><th><span class="rq">*</span><sec>: </th><td><sec><br /><sec></td></tr></table>';
            $sechash = !isset($sechash) ? 'S' . ($_G['inajax'] ? 'A' : '') . $_G['sid'] : $sechash . random(3);
        }
    }
    if (defined('IN_MOBILE')) {
        include_once template('comiis_sms:comiis_mobreg_js');
    }
    include_once template('comiis_sms:comiis_login');
}