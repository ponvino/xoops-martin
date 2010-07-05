<?php
/**
 * @alipay
 * @license http://www.blags.org/
 * @created:2010年07月05日 22时43分
 * @copyright 1997-2010 The Martin Group
 * @author Martin <china.codehome@gmail.com> 
 * */
require_once(MARTIN_ROOT_PATH . "pay/$order_pay/alipay_service.php");
$config_file = MARTIN_ROOT_PATH . "pay/$order_pay/config/config.php";
if(file_exists($config_file)) include $config_file;

$config = ${$order_pay};
if(is_array($config))
{
	foreach($config as $key => $value)
	{
		${$key} = $value;
	}
}

$parameter = array(
"service" => "sign_protocol_with_partner", //交易类型，必填实物交易＝trade_create_by_buyer（需要填写物流） 虚拟物品交易＝create_digital_goods_trade_p 捐赠＝create_donate_trade_p
"partner" =>$partner,                                               //合作商户号
"_input_charset" => $_input_charset,                                //字符集，默认为GBK

);
$alipay = new alipay_service($parameter,$security_code,$sign_type);
//var_dump($parameter );
$link=$alipay->create_url();
redirect_header($link,2,'支付页面跳转.....');
