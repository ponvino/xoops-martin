<?php
/**
��* ���� ��������ҳ�� 
��* ����  ֧�����ⲿ����ӿڿ���
��* �汾  0.6
��* ����  2006-6-10
��* ����   http://www.buybay.org
  * ��ϵ   Email�� raftcham@hotmail.com  Homepage��http://www.buybay.org
��* ��Ȩ   Copyright2006 Buybay NetTech
��*/
require_once("alipay_service.php");
require_once("alipay_config.php");
$parameter = array(
"service" => "sign_protocol_with_partner", //�������ͣ�����ʵ�ｻ�ף�trade_create_by_buyer����Ҫ��д������ ������Ʒ���ף�create_digital_goods_trade_p ������create_donate_trade_p
"partner" =>$partner,                                               //�����̻���
"_input_charset" => $_input_charset,                                //�ַ�����Ĭ��ΪGBK

);
$alipay = new alipay_service($parameter,$security_code,$sign_type);
print_r($parameter );
$link=$alipay->create_url();
print <<<EOT
<br/>
<a href= $link  target ="_blank">submit</a>
EOT;

?>

