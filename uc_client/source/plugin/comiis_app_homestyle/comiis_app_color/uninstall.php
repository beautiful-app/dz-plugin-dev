<?php
/**
 * 
 * ��-��-�����ѣ�Ϊ��֤�ݸ�����Դ�ĸ���ά�����ϣ���ֹ�ݸ����׷���Դ�����ⷺ�ģ�
 *             ϣ���������زݸ�����Դ�Ļ�Ա��Ҫ����Ѳݸ����׷���Դ�ṩ��������;
 *             �类���֣���ȡ���ݸ���VIP��Ա�ʸ�ֹͣһ�к��ڸ���֧���Լ����в���BUG����������
 *          
 * ��.��.�ɳ�Ʒ ������Ʒ
 * �ݸ��� ȫ���׷� https://Www.Caogen8.co
 * ������www.Cgzz8.com (���ղر���!)
 * ����Դ��Դ�������ռ�,��������ѧϰ����������������ҵ��;����������24Сʱ��ɾ��!
 * ����֧��/����ά����QQ 2575 163778
 * лл֧�֣���л���.�ݸ���.�Ĺ�ע������������   
 * 
 */
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
loadcache('plugin');
if($_G['cache']['plugin']['comiis_app_color']['del'] == 1){
$sql = <<<EOF
DROP TABLE pre_comiis_app_style;
DROP TABLE pre_comiis_app_userstyle;
EOF;
runquery($sql);
}
