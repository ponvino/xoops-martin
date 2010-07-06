<?php
include "../../../../mainfile.php";
include XOOPS_ROOT_PATH.'/modules/martin/include/common.php';
if(!defined('MODULE_URL')) define('MODULE_URL',XOOPS_URL . '/modules/martin/');
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
//alipay���ز���
$return_arr = $_GET;
if(is_array($return_arr))
{
	foreach($return_arr as $key => $value)
	{
		${$key} = is_numeric($value) ? round($value,2) : null;
		${$key} = is_string($value) ? trim($value) : ${$key};
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

global $xoopsUser;
$cart_handler =& xoops_getmodulehandler("cart", 'martin');
$order = $cart_handler->GetOrderInfo($order_id);
if(!$order) redirect_header(XOOPS_URL,1,'�Ƿ�����.');
if($cart_handler->CheckOrderClose($order_id)) redirect_header(XOOPS_URL,1,'�Ƿ�����.');

$alipay = new alipay_notify($partner,$security_code,$sign_type,$_input_charset,$transport);
$verify_result = $alipay->return_verify();
//echo urldecode($_SERVER["QUERY_STRING"]);
if($verify_result) {
	//���¶���״̬
	/*if($order['order_pay_money'] != $total_fee)
	{
		redirect_header(XOOPS_URL,1,'�Ƿ�����.');
	}*/
	$cart_handler->UpdateOrderStatus($order_id,7);
	$msg = '֧���ɹ�,�����Ѿ��յ����Ķ���,���ǻᾡ��Ϊ������.';
	$change_url = XOOPS_URL .'/hotel/';
	//echo "success";
	//����������Զ������,������ݲ�ͬ��trade_status���в�ͬ����
	log_result("verify_success"); //����֤��������ļ�	
}
else  {
	$msg = '֧��ʧ��,Ϊ�˾��충��������ʱ����.';
	$change_url = MODULE_URL .'pay.php?order_id'.$order_id;
	//����������Զ�����룬����������Զ������,������ݲ�ͬ��trade_status���в�ͬ����
	log_result ("verify_failed");
}
redirect_header($change_url,2,$msg);

?>
