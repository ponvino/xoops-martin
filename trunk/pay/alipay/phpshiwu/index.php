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
"service" => "trade_create_by_buyer", //�������ͣ�����ʵ�ｻ�ף�trade_create_by_buyer����Ҫ��д������ ������Ʒ���ף�create_digital_goods_trade_p ������create_donate_trade_p
"partner" =>$partner,                                               //�����̻���
"return_url" =>$return_url,  //ͬ������
"notify_url" =>$notify_url,  //�첽����
"_input_charset" => $_input_charset,                                //�ַ�����Ĭ��ΪGBK
"subject" => "�������",                                                //��Ʒ���ƣ�����
"body" => "test���� only",                                           //��Ʒ����������
"out_trade_no" => time() ,                      //��Ʒ�ⲿ���׺ţ�����,ÿ�β��Զ����޸�
"price" => "0.01",                                 //��Ʒ���ۣ�����
"discount"=>"",                                    //�ۿ�        
"payment_type"=>"1",                               // ��Ʒ֧������ 1 ����Ʒ���� 2�������� 3���������� 4������ 5���ʷѲ��� 6������
"quantity" => "1",                                 //��Ʒ����������
"show_url" => "http://www.sina.com.cn",            //��Ʒ�����վ
"logistics_type"=>"EXPRESS",                  //�������ͣ�VIRTUAL��������Ʒ POST��ƽ�� EMS��EMS EXPRESS��������ݹ�˾
"logistics_fee"=>"0.01",                          //��������
"logistics_payment"=>"SELLER_PAY",                //����֧������: SELLER_PAY=����֧�� BUYER_PAY=���֧�� BUYER_PAY_AFTER_RECEIVE=��������
"seller_email" => $seller_email,                //�������䣬����

);
$alipay = new alipay_service($parameter,$security_code,$sign_type);
print_r($parameter );
$link=$alipay->create_url();
print <<<EOT
<br/>
<a href= $link >submit</a>
EOT;

?>

