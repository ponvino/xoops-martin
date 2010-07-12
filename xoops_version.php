<?php
if (!defined('XOOPS_ROOT_PATH')){ exit(); }

$modversion = array(
	"name"			=> "Martin 订房模块",
	"version"		=> 1.0,
	"description"	=>  "martin 创业是条艰苦的路.",
	"credits" 		=> "The Xoops Project, The WF-projects, The martin Community",
	"image"			=> "images/logo.png",
	"dirname"		=> 'martin',
	"author"		=> "Martin",
	"help" 			=> "http://www.blags.org"
	);
/*$modversion["sqlfile"]["mysql"] = "sql/martin_1.0.sql";

$modversion["tables"] = array(
	$modversion["dirname"]."_hotel",
	$modversion["dirname"]."_hotel_service",
	$modversion["dirname"]."_hotel_city",
	$modversion["dirname"]."_hotel_promotions",
	$modversion["dirname"]."_hotel_service_relation",
	$modversion["dirname"]."_hotel_service_type",
	$modversion["dirname"]."_room",
	$modversion["dirname"]."_room_type",
	$modversion["dirname"]."_room_price",
	$modversion["dirname"]."_order",
	$modversion["dirname"]."_order_room",
	$modversion["dirname"]."_group",
	$modversion["dirname"]."_group_room",
	$modversion["dirname"]."_aution",
	$modversion["dirname"]."_aution_room",
);
*/
// Admin things
$modversion["hasAdmin"] = 1;
$modversion["adminindex"] = "admin/index.php";
$modversion["adminmenu"] = "admin/menu.php";


//是否有前台
$modversion["hasMain"] = 1;

//blocks
$modversion["blocks"] = array();
$i = 0;
$i++;
$modversion['blocks'][$i]['file'] = "martin.hotel.php";
$modversion['blocks'][$i]['name'] = 'hotel search';
$modversion['blocks'][$i]['description'] = "hotel Search";
$modversion['blocks'][$i]['show_func'] = "martin_hotel_search_show";
$modversion['blocks'][$i]['edit_func'] = "martin_hotel_search_edit";
$modversion['blocks'][$i]['options'] = "";
$modversion['blocks'][$i]['template'] = "martin_block_hotel.html";

//blocks

//templates
$i = 0;
$i++;
$modversion['templates'][$i]['file'] = 'martin_admin_menu.html';
$modversion['templates'][$i]['description'] = 'admin menu in martin';
$i++;
$modversion['templates'][$i]['file'] = 'martin_auction.html';
$modversion['templates'][$i]['description'] = 'martin auction';
$i++;
$modversion['templates'][$i]['file'] = 'martin_group.html';
$modversion['templates'][$i]['description'] = 'martin group';
$i++;
$modversion['templates'][$i]['file'] = 'martin_hotel.html';
$modversion['templates'][$i]['description'] = 'martin hotel';
$i++;
$modversion['templates'][$i]['file'] = 'martin_hotel_book.html';
$modversion['templates'][$i]['description'] = 'martin hotel book';
$i++;
$modversion['templates'][$i]['file'] = 'martin_hotel_pay.html';
$modversion['templates'][$i]['description'] = 'martin hotel pay';
$i++;
$modversion['templates'][$i]['file'] = 'martin_hotel_search.html';
$modversion['templates'][$i]['description'] = 'martin hotel search';
$i++;
$modversion['templates'][$i]['file'] = 'martin_hotel_search_left.html';
$modversion['templates'][$i]['description'] = 'martin search left';


//end templates


// module Settings
$i = 0;
$i++;
$modversion['config'][$i]['name'] = 'google_api';
$modversion['config'][$i]['title'] = 'MARTIN_GOOGLE_API_TITLE';
$modversion['config'][$i]['description'] = 'MARTIN_GOOGLE_API_DESCRIPTION';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '';
//$modversion['config'][$i]['options'] = '';
$i++;
$modversion['config'][$i]['name'] = 'perpage';
$modversion['config'][$i]['title'] = 'MARTIN_PERPAGE_TITLE';
$modversion['config'][$i]['description'] = 'MARTIN_PERPAGE_DESCRIPTION';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '20';
$i++;
$modversion['config'][$i]['name'] = 'hotelrank';
$modversion['config'][$i]['title'] = 'MARTIN_HOTELRANK_TITLE';
$modversion['config'][$i]['description'] = 'MARTIN_HOTELRANK_DESCRIPTION';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '3-三星级'.chr(13).'4-四星级'.chr(13).'5-五星级';
$i++;
$modversion['config'][$i]['name'] = 'thumbnail_width';
$modversion['config'][$i]['title'] = 'MARTIN_THUMBNAIL_WIDTH_TITLE';
$modversion['config'][$i]['description'] = 'MARTIN_THUMBNAIL_WIDTH_DESCRIPTION';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1000';
$i++;
$modversion['config'][$i]['name'] = 'thumbnail_height';
$modversion['config'][$i]['title'] = 'MARTIN_THUMBNAIL_HEIGHT_TITLE';
$modversion['config'][$i]['description'] = 'MARTIN_THUMBNAIL_HEIGHT_DESCRIPTION';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1000';
$i++;
$modversion['config'][$i]['name'] = 'order_type';
$modversion['config'][$i]['title'] = 'MARTIN_ORDER_TYPE_TITLE';
$modversion['config'][$i]['description'] = 'MARTIN_ORDER_TYPE_DESCRIPTION';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '1-直接预定'.chr(13).'2-查询预定';
$i++;
$modversion['config'][$i]['name'] = 'order_mode';
$modversion['config'][$i]['title'] = 'MARTIN_ORDER_MODE_TITLE';
$modversion['config'][$i]['description'] = 'MARTIN_ORDER_MODE_DESCRIPTION';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '1-普通订单'.chr(13).'2-团购订单'.chr(13).'2-竞价订单';
$i++;
$modversion['config'][$i]['name'] = 'order_status';
$modversion['config'][$i]['title'] = 'MARTIN_ORDER_STATUS_TITLE';
$modversion['config'][$i]['description'] = 'MARTIN_ORDER_STATUS_INTRO';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '';
$i++;
$modversion['config'][$i]['name'] = 'order_pay_method';
$modversion['config'][$i]['title'] = 'MARTIN_PAY_METHOD_TITLE';
$modversion['config'][$i]['description'] = 'MARTIN_PAY_METHOD_INTRO';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '';
$i++;
$modversion['config'][$i]['name'] = 'order_document_type';
$modversion['config'][$i]['title'] = 'MARTIN_DOCUMENT_TYPE_TITLE';
$modversion['config'][$i]['description'] = 'MARTIN_DOCUMENT_TYPE_INTRO';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '';
$i++;
$modversion['config'][$i]['name'] = 'hotel_static_prefix';
$modversion['config'][$i]['title'] = 'MARTIN_HOTELSTATIC_TITLE';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '.html';
$i++;
$modversion['config'][$i]['name'] = 'room_bed_type';
$modversion['config'][$i]['title'] = 'MARTIN_BEDTYPE_TITLE';
$modversion['config'][$i]['description'] = 'MARTIN_BEDTYPE_DESCRIPTION';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '';
$i++;
$modversion['config'][$i]['name'] = 'user_nationality';
$modversion['config'][$i]['title'] = 'MARTIN_NATIONALITY_TITLE';
$modversion['config'][$i]['description'] = 'MARTIN_NATIONALITY_DESCRIPTION';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '';
$i++;
$modversion['config'][$i]['name'] = 'hotel_recommend';
$modversion['config'][$i]['title'] = 'MARTIN_RECOMMEND_TITLE';
$modversion['config'][$i]['description'] = 'MARTIN_RECOMMEND_DESCRIPTION';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '';
$i++;
$modversion['config'][$i]['name'] = 'register_point';
$modversion['config'][$i]['title'] = 'MARTIN_REGISTER_POINT';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '';
$i++;
$modversion['config'][$i]['name'] = 'hotel_guide';
$modversion['config'][$i]['title'] = 'MARTIN_HOTEL_GUIDE';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '';
$i++;
$modversion['config'][$i]['name'] = 'hotel_today_special';
$modversion['config'][$i]['title'] = 'MARTIN_TODAY_SPECIAL';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '';
$i++;
$modversion['config'][$i]['name'] = 'online_pays';
$modversion['config'][$i]['title'] = 'MARTIN_ONLINE_PAY';
$modversion['config'][$i]['description'] = 'MARTIN_ONLINE_PAY_DESCRIPTION';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '';
$i++;
$modversion['config'][$i]['name'] = 'line_pays';
$modversion['config'][$i]['title'] = 'MARTIN_LINE_PAY';
$modversion['config'][$i]['description'] = 'MARTIN_LINE_PAY_DESCRIPTION';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '';
$i++;
$modversion['config'][$i]['name'] = 'google_width_height';
$modversion['config'][$i]['title'] = 'MARTIN_GOOGLE_WIDTH_HEIGHT';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '750|400';



?>
