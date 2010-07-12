<?php
/**
 * @订房搜索
 * @license http://www.blags.org/
 * @created:2010年05月19日 22时38分
 * @copyright 1997-2010 The Martin Group
 * @author Martin <china.codehome@gmail.com> 
 * */
if (!defined('XOOPS_ROOT_PATH')) exit();

/**
 * hoel search show function
 **/
function martin_hotel_search_show($options)
{
	global $xoopsModuleConfig,$xoopsModule,$xoopsTpl;
	//新闻
	/*if($xoopsModule->dirname() != 'martin')
	{*/
		$module_handler =& xoops_gethandler('module');
		$config_handler =& xoops_gethandler('config');
		$xoopsModule =& $module_handler->getByDirname('martin');
		$xoopsModuleConfig =& $config_handler->getConfigsByCat(0, $xoopsModule->getVar('mid'));	
	/*}*/
	//var_dump($xoopsModuleConfig);
	
	include_once XOOPS_ROOT_PATH.'/modules/martin/include/functions.php';
	$hotel_handler =& xoops_getmodulehandler("hotel", 'martin');
	$group_handler =& xoops_getmodulehandler("group", 'martin');
	$auction_handler =& xoops_getmodulehandler("auction", 'martin');
	$news_handler =& xoops_getmodulehandler("hotelnews", 'martin');

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

	$block['module_url'] = XOOPS_URL.'/modules/martin/';
	$block["hotelrank"] = getModuleArray('hotelrank','hotelrank',true,null,$xoopsModuleConfig);
	$block['groupList'] = $group_handler->GetGroupList();
	$block['auctionList'] = $auction_handler->GetAuctionList();
	$block['hotel_guide_rows']  = $hotel_guide_rows;
	$block['hotel_today_special_rows'] = $hotel_today_special_rows;
	$block['cityList'] = $hotel_handler->GetCityList('WHERE city_parentid = 0');
	$block['hotel_static_prefix'] = $xoopsModuleConfig['hotel_static_prefix'];

	unset($hotel_handler,$group_handler,$auction_handler,$news_handler,$hotel_guide_rows,$hotel_today_special_rows);
	//var_dump($block);
	return $block;
}

/**
 * hoel search edit function
 **/
function martin_hotel_search_edit($options)
{
	return '';
}

?>
