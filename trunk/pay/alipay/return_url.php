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
	fwrite($fp,$word."：执行日期：".strftime("%Y%m%d%H%I%S",time())."\t\n");
	flock($fp, LOCK_UN); 
	fclose($fp);
	//chmod('log.txt',777);
}


$alipay = new alipay_notify($partner,$security_code,$sign_type,$_input_charset,$transport);
$verify_result = $alipay->return_verify();
//echo urldecode($_SERVER["QUERY_STRING"]);
if($verify_result) {
	echo "success";
	//这里放入你自定义代码,比如根据不同的trade_status进行不同操作
	log_result("verify_success"); //将验证结果存入文件	
}
else  {
	echo "fail";
	//这里放入你自定义代码，这里放入你自定义代码,比如根据不同的trade_status进行不同操作
	log_result ("verify_failed");
}
	
?>
