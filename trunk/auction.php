<?php
include '../../mainfile.php';
include XOOPS_ROOT_PATH.'/modules/martin/include/common.php';

global $xoopsUser;
if(!$xoopsUser) redirect_header(XOOPS_URL . '/user.php?xoops_redirect=/'.$_SERVER['REQUEST_URI'],1,'您还没有登录.');

$auction_id = isset($_GET['auction_id']) ? intval($_GET['auction_id']) : $id;
$auction_id = isset($_POST['auction_id']) ? intval($_POST['auction_id']) : $auction_id;
if(!$auction_id) redirect_header(XOOPS_URL,3,'非法访问.');

$hotel_handler =& xoops_getmodulehandler("hotel", 'martin');
$auction_handler =& xoops_getmodulehandler("auction", 'martin');
$auction_obj = $auction_handler->get($auction_id);

//保存数据
$action = isset($_GET['action']) ? trim($_GET['action']) : null;
global $xoopsUser;
$uid = $xoopsUser->getVar('uid');
if($action == 'save')
{
	$AuctionData = array ('uid' => intval($uid) , 'auction_id' => $auction_id , 
		'bid_count' => isset($_POST['RoomCount']) ? intval(trim($_POST['RoomCount'])) : 0,
		'bid_price' => isset($_POST['AuctionPrice']) ? round(trim($_POST['AuctionPrice']),2) : 0.00,
		'check_in_time' => isset($_POST['Check_in_date']) ? strtotime(trim($_POST['Check_in_date'])) : 0,
		'check_out_time' => isset($_POST['Check_out_date']) ? strtotime(trim($_POST['Check_out_date'])) : 0,
		'bid_time' => time() , 'bid_status' => 1,
		);
	if($auction_handler->AddUserAuction($AuctionData))
	{
		redirect_header(XOOPS_URL.'/modules/martin/auction.php/auction-'.$auction_id.$xoopsModuleConfig['hotel_static_prefix'],2,'提交成功.');
	}else{
		redirect_header('javascript:history.go(-1);',2,'提交失败.');
	}
	exit();
}

//判断是否存在
if(!$auction_obj->auction_id()) redirect_header(XOOPS_URL,3,'非法访问.');
//是否结束
if($auction_obj->apply_end_date() < time()) redirect_header(XOOPS_URL,3,'该竞拍已经结束.');

$auction_data = array();
foreach($auction_obj->vars as $key => $var)
{
	$auction_data[$key] = $auction_obj->$key();
}

$rooms = $auction_handler->GetAuctionRooms($auction_id);

$AuctionDate = array(
			'min'=>intval($auction_obj->check_in_date() - strtotime(date('Y-m-d')) )/(3600*24),
			'max'=>intval($auction_obj->check_out_date() - strtotime(date('Y-m-d')) )/(3600*24),
			);
//var_dump($AuctionDate);

$xoopsOption["template_main"] = "martin_auction.html";

include XOOPS_ROOT_PATH.'/header.php';
include XOOPS_ROOT_PATH.'/modules/martin/HotelSearchLeft.php';

$xoopsOption['xoops_pagetitle'] =   $auction_obj->auction_name(). ' - 竞拍 - '.$xoopsConfig['sitename'];

$xoopsTpl -> assign("xoops_pagetitle", $xoopsOption["xoops_pagetitle"]);
$xoopsTpl -> assign("module_url", XOOPS_URL . '/modules/martin/');
$xoopsTpl -> assign('auction_id',$auction_id);
$xoopsTpl -> assign('auction',$auction_data);
$xoopsTpl -> assign('auctiondate',$AuctionDate);
$xoopsTpl -> assign('rooms',$rooms);
$xoopsTpl -> assign('bids',$auction_handler->getAuctionBidList($auction_id));
$xoopsTpl -> assign('hotel_static_prefix',$xoopsModuleConfig['hotel_static_prefix']);


include XOOPS_ROOT_PATH.'/footer.php';
?>

