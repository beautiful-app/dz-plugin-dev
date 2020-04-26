<?php

if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
require_once DISCUZ_ROOT . './source/plugin/comiis_sms/language/language.' . currentlang() . '.php';
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
}
if ($comiis_upload == 1) {
    loadcache($plugin_id . '_up');
    if ($_G['cache'][$plugin_id . '_up']['up'] != 1) {
        save_syscache($plugin_id . '_up', array('up' => 1));
        if (!function_exists('comiis_app_load_sms_data')) {
            if (!file_exists(DISCUZ_ROOT . './source/plugin/' . $plugin_id . '/comiis_info/comiis.php')) {
                comiis_app_sms_data_error();
                return false;
            }
            include DISCUZ_ROOT . './source/plugin/' . $plugin_id . '/comiis_info/comiis.php';
        }
        if (!function_exists('comiis_app_load_sms_data')) {
            comiis_app_sms_data_error();
            return false;
        }
        comiis_app_load_sms_data($plugin_id);
        save_syscache($plugin_id . '_up', array('up' => 0));
    }
}
if (!file_exists(DISCUZ_ROOT . './source/plugin/' . $plugin_id . '/comiis_info/comiis_md5file.php')) {
    comiis_app_sms_data_error();
    return false;
}
include DISCUZ_ROOT . './source/plugin/' . $plugin_id . '/comiis_info/comiis_md5file.php';

if (!submitcheck('exportsubmit')) {
    showformheader('plugins&operation=config&do=' . $_G['gp_do'] . '&identifier=comiis_sms&pmod=comiis_export');
    showtableheader($comiis_sms['41']);
    showsetting($comiis_sms['43'], 'uid', 1, 'radio', '', '', $comiis_sms['42'] . $comiis_sms['43']);
    showsetting($comiis_sms['44'], 'username', 1, 'radio', '', '', $comiis_sms['42'] . $comiis_sms['44']);
    showsetting($comiis_sms['45'], 'tel', 1, 'radio', '', '', $comiis_sms['42'] . $comiis_sms['45']);
    showsetting($comiis_sms['46'], 'regip', 1, 'radio', '', '', $comiis_sms['42'] . $comiis_sms['46']);
    showsetting($comiis_sms['47'], 'dateline', 1, 'radio', '', '', $comiis_sms['42'] . $comiis_sms['47']);
    showsetting($comiis_sms['48'], 'type', 1, 'radio', '', '', $comiis_sms['42'] . $comiis_sms['48']);
    showsetting($comiis_sms['229'], 'province', 1, 'radio', '', '', $comiis_sms['42'] . $comiis_sms['229']);
    showsetting($comiis_sms['230'], 'ua', 1, 'radio', '', '', $comiis_sms['42'] . $comiis_sms['230']);
    showsetting($comiis_sms['49'], 'snum', '0', 'text', '', '', $comiis_sms['51'] . $comiis_sms['49']);
    showsetting($comiis_sms['50'], 'enum', '1000', 'text', '', '', $comiis_sms['51'] . $comiis_sms['50']);
    showsubmit('exportsubmit', 'submit');
    showtablefooter();
    showformfooter();
} else {
    $startlimit = intval($_GET['snum']);
    $nums = intval($_GET['enum']) ? intval($_GET['enum']) : '100';
    $comiis_sms_log = DB::fetch_all('SELECT cm.*, m.username FROM %t cm LEFT JOIN %t m ON cm.uid = m.uid ORDER BY dateline DESC ' . DB::limit($startlimit, $nums), array('comiis_sms_user', 'common_member'));
    $comiis_export = ($_GET['uid'] ? $comiis_sms['43'] . ',' : '') . ($_GET['username'] ? $comiis_sms['44'] . ',' : '') . ($_GET['tel'] ? $comiis_sms['45'] . ',' : '') . ($_GET['regip'] ? $comiis_sms['46'] . ',' : '') . ($_GET['dateline'] ? $comiis_sms['47'] . ',' : '') . ($_GET['type'] ? $comiis_sms['52'] . ',' : '') . ($_GET['province'] ? $comiis_sms['229'] . ',' : '') . ($_GET['ua'] ? $comiis_sms['230'] . ',' : '') . "\n";
    foreach ($comiis_sms_log as $k => $v) {
        $comiis_export .= ($_GET['uid'] ? $v['uid'] . ',' : '') . ($_GET['username'] ? $v['username'] . ',' : '') . ($_GET['tel'] ? $v['tel'] . ',' : '') . ($_GET['regip'] ? $v['regip'] . ',' : '') . ($_GET['dateline'] ? dgmdate($v['dateline']) . ',' : '') . ($_GET['type'] ? ($v['type'] == 1 ? $comiis_sms['53'] : $comiis_sms['54']) . ',' : '') . ($_GET['province'] ? $v['province'] . ',' : '') . ($_GET['ua'] ? $v['ua'] . ',' : '') . "\n";
    }
    $filename = 'comiis_MobileUser_' . date('Ymd', TIMESTAMP) . '.csv';
    ob_end_clean();
    header('Content-Encoding: none');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . $filename);
    header('Pragma: no-cache');
    header('Expires: 0');
    if ($_G['charset'] != 'gbk') {
        $comiis_export = diconv($comiis_export, $_G['charset'], 'GBK');
    }
    echo $comiis_export;
    exit(0);
}
function comiis_app_sms_data_error()
{
    cpmsg('Please enter the correct KEY !', '', 'error', array(), '', 0);
    return null;
}