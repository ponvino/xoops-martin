<?php
include '../../mainfile.php';
include XOOPS_ROOT_PATH.'/modules/martin/include/common.php';
if(!defined('MODULE_URL')) define('MODULE_URL',XOOPS_URL . '/modules/martin/');

global $xoopsUser;
$cart_handler =& xoops_getmodulehandler("cart", 'martin');
$hotel_handler =& xoops_getmodulehandler("hotel", 'martin');

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : $order_id;
$order_pay = isset($_POST['order_pay']) ? trim($_POST['order_pay']) : null;
if(!$order_id) redirect_header(XOOPS_URL,1,'非法闯入.');
if($_POST && !$order_pay) redirect_header('javascript:history.go(-1);',1,'没有选择支付方式.');
$order_pay_method = is_numeric($order_pay) ? 2 : 1;

if($order_id > 0 && !empty($order_pay) && $order_pay_method > 0)
{
	if($cart_handler->ChangeOrderPay($order_id,$order_pay_method,$order_pay))
	{
		if($order_pay_method == 2)
		{
			$msg = '我们已经收到您的订单,为了尽快订房请您及时付款.';
			$change_url = XOOPS_URL .'/hotel/';
		}else{
			$config_file = MARTIN_ROOT_PATH . "pay/$order_pay/config/config.php";
			if(file_exists($config_file)) include $config_file;
			$config = ${$order_pay};
			var_dump($config);
			exit;
			$msg = '支付页面跳转中,请稍候....';
			$change_url = XOOPS_URL .'/hotel/';
		}
		redirect_header($change_url,2,$msg);exit;
	}else{
		redirect_header('javascript:history.go(-1);',1,'没有选择支付方式.');
	}
}

$order = $cart_handler->GetOrderInfo($order_id);
if(!$order) redirect_header(XOOPS_URL,1,'非法闯入.');
if($cart_handler->CheckOrderClose($order_id)) redirect_header(XOOPS_URL,1,'非法闯入.');

//var_dump($order['order_pay']);
$xoopsOption["template_main"] = "martin_hotel_pay.html";
include XOOPS_ROOT_PATH.'/header.php';
include XOOPS_ROOT_PATH.'/modules/martin/HotelSearchLeft.php';

$xoopsOption['xoops_pagetitle'] =  '支付方式选择 - '.$xoopsConfig['sitename'];
$xoopsTpl -> assign("xoops_pagetitle", $xoopsOption["xoops_pagetitle"]);
$xoopsTpl -> assign('hotel_static_prefix',$xoopsModuleConfig['hotel_static_prefix']);
$xoopsTpl -> assign('module_url',MODULE_URL);
$xoopsTpl -> assign('order_id',$order_id);
$xoopsTpl -> assign('order_pay',$order['order_pay']);
$xoopsTpl -> assign('order_pay_str',$order['order_pay_str']);
$xoopsTpl -> assign('line_pays',getModuleArray('line_pays','line_pays',true));
$xoopsTpl -> assign('online_pays',getModuleArray('online_pays','online_pays',true));

include XOOPS_ROOT_PATH.'/footer.php';
