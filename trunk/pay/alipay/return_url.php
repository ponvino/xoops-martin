<?php
include "../../../../mainfile.php";
if(!defined('ALIPAY_ROOT_PATH')) define('ALIPAY_ROOT_PATH',XOOPS_ROOT_PATH . '/modules/martin/pay/alipay/');
require_once(ALIPAY_ROOT_PATH . "alipay_notify.php");
require_once(ALIPAY_ROOT_PATH . "config/config.php");

$config = $alipay;
if(is_array($config))
{
	foreach($config as $key => $value)
	{
		${$key} = $value;
	}
}

function  log_result($word) {
	$fp = fopen("log.txt","a");	
	flock($fp, LOCK_EX) ;
	fwrite($fp,$word."��ִ�����ڣ�".strftime("%Y%m%d%H%I%S",time())."\t\n");
	flock($fp, LOCK_UN); 
	fclose($fp);
	//chmod('log.txt',777);
}


$alipay = new alipay_notify($partner,$security_code,$sign_type,$_input_charset,$transport);
$verify_result = $alipay->return_verify();
//echo urldecode($_SERVER["QUERY_STRING"]);
if($verify_result) {
	echo "success";
	//����������Զ������,������ݲ�ͬ��trade_status���в�ͬ����
	log_result("verify_success"); //����֤��������ļ�	
}
else  {
	echo "fail";
	//����������Զ�����룬����������Զ������,������ݲ�ͬ��trade_status���в�ͬ����
	log_result ("verify_failed");
}
	
?>
