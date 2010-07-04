<?php

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

global $adminmenu;

$adminmenu = array();

$adminmenu[]= array("link"=>"admin/index.php","title"=>"订房首页");

$adminmenu[]= array("link"=>"admin/martin.order.php","title"=>"订单管理");

$adminmenu[]= array("link"=>"admin/martin.hotel.php","title"=>"酒店管理");

$adminmenu[]= array("link"=>"admin/martin.hotel.service.php","title"=>"酒店服务");

$adminmenu[]= array("link"=>"admin/martin.hotel.promotion.php","title"=>"酒店促销");

$adminmenu[]= array("link"=>"admin/martin.hotel.city.php","title"=>"城市管理");
//add by martin
$adminmenu[]= array("link"=>"admin/martin.room.php","title"=>"客房管理");

$adminmenu[]= array("link"=>"admin/martin.group.php","title"=>"团购管理");

$adminmenu[]= array("link"=>"admin/martin.auction.php","title"=>"竞价管理");

$adminmenu[]= array("link"=>"admin/martin.about.php","title"=>"关于作者");

if (isset($xoopsModule)) {
	$i = 0;
	$headermenu[$i]['title'] = "模块参数";
	$headermenu[$i]['link'] = '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule->getVar('mid');
	
	$i++;
	$headermenu[$i]['title'] = '订房区块管理';
	$headermenu[$i]['link'] = 'martin.block.php';
	
	$i++;
	$headermenu[$i]['title'] = "更新模块";
	$headermenu[$i]['link'] = XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin&op=update&module=" . $xoopsModule->getVar('dirname');
	
	$i++;
	$headermenu[$i]['title'] = "支付方式配置";
	$headermenu[$i]['link'] = XOOPS_URL . "/modules/martin/admin/martin.pay.php";

}

// misc: comments, synchronize, achive, batch import
?>
