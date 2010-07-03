目前处于更新到v0.5
支持支持虚拟物品、实物交易、捐赠等支付宝能提供的全系列交易方式

如有功能方面的疑问可以到 http://bbs.buybay.org 上面找raftcham
也可以Email:raftcham@hotmail.com 联系我

使用方法：
1.修改alipay_config.php文件，将$partner和$security_code和$seller_email值按照你在支付宝中的账号信息填写
2.修改index.php设置好你要出售的商品的各种属性（如果是测试，请将price改为0.01之类）
将service改成create_digital_goods_trade_p，费用,通知返回地址等即可实现实物交易，详细参数请自行设定
将service改成trade_create_by_buyer，修改物流方式以及费用等即可实现实物交易，详细参数请自行设定
将service改成create_donate_trade_p，total_fee费用等即可实捐赠项目，详细参数请自行设定

其中out_trade_no每次必须不同，不过为了调试方便，我用一个随机函数代替，记得用你自己的数据填充上区

3.将log.txt文件的权限改为777，以便记录相关数据方便调试
4.上传该代码到您的网站上，然后访问index.php 所在地址，在出现的界面中点击submit连接，就会创建一个交易，然后就可以依次按照支付宝的规程进行了，每次操作之后都会产生一个log文件被记录在log.txt文件里面，可以察看相关内容以便于调试。

change log：
2006-6-10 
增加支持http的访问模式，解决很多不支持ssl的主机的使用问题
2006-6-1
解决多种字符集使用格式，比如utf-8等，并且修正一些小错误
2006-5-30
增加实物交易、捐赠功能
2006-5-26
增加return_url返回模式，可以和notify_url同时使用
增加alipay_config.php文件，将商家号码以及安全验证号放于其中以便修改

2006-5-25
1.滤过递交给或接受到支付宝的$_POST中的空值，增加papa_filter函数
2.完善notify_url.php 的返回值（success or fail）