<?php
global $xoopsModuleConfig,$xoopsModule,$xoopsTpl,$hotel_handler;

include_once XOOPS_ROOT_PATH.'/modules/martin/include/functions.php';
//$hotel_handler =& xoops_getmodulehandler("hotel", 'martin');
//$room_handler =& xoops_getmodulehandler("room", 'martin');
//$promotion_handler =& xoops_getmodulehandler("hotelpromotion", 'martin');
//$service_handler =& xoops_getmodulehandler("hotelservice", 'martin');
$group_handler =& xoops_getmodulehandler("group", 'martin');
$auction_handler =& xoops_getmodulehandler("auction", 'martin');
$news_handler =& xoops_getmodulehandler("hotelnews", 'martin');

$ViewedhotelIDs = array_filter(explode(',',$_COOKIE['ViewedHotels']));

$hotel_guide = explode(",",$xoopsModuleConfig['hotel_guide']);
$hotel_today_special = explode(",",$xoopsModuleConfig['hotel_today_special']);
$hotel_news_ids = (is_array($hotel_guide) && is_array($hotel_today_special)) ? array_merge($hotel_guide,$hotel_today_special) : null;
$hotelnews = $news_handler->GetHotelNews($hotel_news_ids);

$hotel_guide_rows = array();
$hotel_today_special_rows = array();
foreach($hotelnews as $key => $row)
{
	if(in_array($key,$hotel_guide) && count($hotel_guide_rows) < 6)
	{
		$hotel_guide_rows[] = $row;
	}
	if(in_array($key,$hotel_today_special) && count($hotel_today_special_rows) < 6)
	{
		$hotel_today_special_rows[] = $row;
	}
}


ob_start();
$Tpl = new xoopsTpl();
$Tpl -> assign('module_url',XOOPS_URL.'/modules/martin/');
$Tpl -> assign("hotelrank",getModuleArray('hotelrank','hotelrank',true));
$Tpl -> assign('hotelrankcount',$hotel_handler->getHotelRankCount());
$Tpl -> assign('ViewedHotels',$hotel_handler->GetViewedHotels($ViewedhotelIDs));
$Tpl -> assign('groupList',$group_handler->GetGroupList());
$Tpl -> assign('auctionList',$auction_handler->GetAuctionList());
$Tpl -> assign('hotel_guide_rows',$hotel_guide_rows);
$Tpl -> assign('hotel_today_special_rows',$hotel_today_special_rows);
$Tpl -> assign('cityList',$hotel_handler->GetCityList('WHERE city_parentid = 0'));
$Tpl -> assign('hotel_static_prefix',$xoopsModuleConfig['hotel_static_prefix']);
$Tpl->display('db:martin_hotel_search_left.html');
$xoopsTpl -> assign('martin_hotel_search_left',ob_get_contents());

ob_end_clean();

unset($Tpl,$news_handler,$hotel_guide_rows,$hotel_today_special_rows);
