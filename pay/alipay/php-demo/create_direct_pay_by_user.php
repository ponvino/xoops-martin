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
"service" => "create_direct_pay_by_user", //交易类型，必填实物交易＝trade_create_by_buyer（需要填写物流） 虚拟物品交易＝create_digital_goods_trade_p 捐赠＝create_donate_trade_p
"partner" =>$partner,                                               //合作商户号
"agent"=> $agent,
"return_url" =>$return_url,  //同步返回
"notify_url" =>$notify_url,  //异步返回
"_input_charset" => $_input_charset,                                //字符集，默认为GBK
"subject" => "多项测试",                                                //商品名称，必填
"body" => "jhshs哈哈1234567",                                         //商品描述，必填
"out_trade_no" => time() ,                      //商品外部交易号，必填,每次测试都须修改
"total_fee" => "0.01",                                 //商品单价，必填
"payment_type"=>"1",                               // 商品支付类型 1 ＝商品购买 2＝服务购买 3＝网络拍卖 4＝捐赠 5＝邮费补偿 6＝奖金
"show_url" => "http://www.buyaby.org/",            //商品相关网站公司
"seller_email" => $seller_email,                //卖家邮箱，必填
"paymethod" => "bankPay",//"directPay"
"defaultbank" =>  "CMB",
"royalty_type"=> "10",
"royalty_parameters"=> "yao2857@yahoo.com.cn^0.01^机票测试",

);
$alipay = new alipay_service($parameter,$security_code,$sign_type);
print_r($parameter );
$link=$alipay->create_url();
print <<<EOT
<br/>
<a href= $link  target ="_blank">submit</a>
EOT;

?>

