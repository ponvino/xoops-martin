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
"service" => "refund_fastpay_by_platform_pwd", //交易类型，
"partner" =>$partner,                                               //合作商户号
"notify_url" =>$notify_url,  //异步返回
"_input_charset" => $_input_charset,                                //字符集，默认为GBK
"batch_no" => "200806170001",         //日期+流水号，比如200806170001
"refund_date" => "2008-06-17 19:13:13",       //商品描述，必填
"batch_num" => "1",                                 //商品单价，必填
"detail_data"=> "2008051946355333^0.01^刘卓测试协商退款|ap1@itour.cc^^ap4@itour.cc^^0.01^分润退款|ap2@itour.cc^^ap4@itour.cc^^0.01^分润退款",                               // 票款+收费+分润   格式如上
"return_type" => "html",            //商品相关网站公司

);
$alipay = new alipay_service($parameter,$security_code,$sign_type);
print_r($parameter );
$link=$alipay->create_url();
print <<<EOT
<br/>
<a href= $link  target ="_blank">submit</a>
EOT;

?>

