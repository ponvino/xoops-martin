<?php
/**
　* 名称 返回页面
　* 功能  支付宝外部服务接口控制
　* 版本  0.6
　* 日期  2006-6-10
　* 作者   http://www.buybay.org
  * 联系   Email： raftcham@hotmail.com  Homepage：http://www.buybay.org
　* 版权   Copyright2006 Buybay NetTech
　*/
$partner = "";//合作伙伴ID
$security_code = "";//安全检验码
$seller_email = "";//卖家邮箱
$_input_charset = "utf-8"; //字符编码格式
$sign_type = "MD5"; //加密方式
$transport= "http";//访问模式,你可以根据自己的服务器是否支持ssl访问而选择http以及https访问模式
$notify_url = "http://www.XXXX./notify_url.php";// 异步返回地址
$return_url = "http://10.2.5.51:8082/liuzhuo/new_php_direct1/return_url.php"; //同步返回地址
?>