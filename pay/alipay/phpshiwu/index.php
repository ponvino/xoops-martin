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
"service" => "trade_create_by_buyer", //交易类型，必填实物交易＝trade_create_by_buyer（需要填写物流） 虚拟物品交易＝create_digital_goods_trade_p 捐赠＝create_donate_trade_p
"partner" =>$partner,                                               //合作商户号
"return_url" =>$return_url,  //同步返回
"notify_url" =>$notify_url,  //异步返回
"_input_charset" => $_input_charset,                                //字符集，默认为GBK
"subject" => "多项测试",                                                //商品名称，必填
"body" => "test哈哈 only",                                           //商品描述，必填
"out_trade_no" => time() ,                      //商品外部交易号，必填,每次测试都须修改
"price" => "0.01",                                 //商品单价，必填
"discount"=>"",                                    //折扣        
"payment_type"=>"1",                               // 商品支付类型 1 ＝商品购买 2＝服务购买 3＝网络拍卖 4＝捐赠 5＝邮费补偿 6＝奖金
"quantity" => "1",                                 //商品数量，必填
"show_url" => "http://www.sina.com.cn",            //商品相关网站
"logistics_type"=>"EXPRESS",                  //物流类型：VIRTUAL＝虚拟物品 POST＝平邮 EMS＝EMS EXPRESS＝其他快递公司
"logistics_fee"=>"0.01",                          //物流费用
"logistics_payment"=>"SELLER_PAY",                //物流支付类型: SELLER_PAY=卖家支付 BUYER_PAY=买家支付 BUYER_PAY_AFTER_RECEIVE=货到付款
"seller_email" => $seller_email,                //卖家邮箱，必填

);
$alipay = new alipay_service($parameter,$security_code,$sign_type);
print_r($parameter );
$link=$alipay->create_url();
print <<<EOT
<br/>
<a href= $link >submit</a>
EOT;

?>

