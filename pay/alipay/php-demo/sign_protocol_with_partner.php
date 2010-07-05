<?php
/**
　* 名称 创建交易页面 
　* 功能  支付宝外部服务接口控制
　* 版本  0.6
　* 日期  2006-6-10
　* 作者   http://www.buybay.org
  * 联系   Email： raftcham@hotmail.com  Homepage：http://www.buybay.org
　* 版权   Copyright2006 Buybay NetTech
　*/
require_once("alipay_service.php");
require_once("alipay_config.php");
$parameter = array(
"service" => "sign_protocol_with_partner", //交易类型，必填实物交易＝trade_create_by_buyer（需要填写物流） 虚拟物品交易＝create_digital_goods_trade_p 捐赠＝create_donate_trade_p
"partner" =>$partner,                                               //合作商户号
"_input_charset" => $_input_charset,                                //字符集，默认为GBK

);
$alipay = new alipay_service($parameter,$security_code,$sign_type);
print_r($parameter );
$link=$alipay->create_url();
print <<<EOT
<br/>
<a href= $link  target ="_blank">submit</a>
EOT;

?>

