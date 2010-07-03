<?php
include "header.php";
/*
 * 处理
 **/

//头部
include "martin.header.php";

//parameter 参数
$action = isset($_POST['action']) ? $_POST['action'] : @$_GET['action'];
$action = empty($action) ? 'list' : $action;
$action = trim(strtolower($action));
$id = !empty($_POST['id']) ? $_POST['id'] : @$_GET['id'] ;
$id = intval($id);
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
//确认删除
$confirm = (isset($_POST['confirm'])) ? $_POST['confirm'] : 0;
//parameter 参数

martin_adminMenu( 8 , "订房后台 > 竞价管理");

$auction_handler =& xoops_getmodulehandler('auction', MARTIN_DIRNAME,true);
$hotelservice_handler =& xoops_getmodulehandler('hotelservice', MARTIN_DIRNAME,true);

//$HotelServiceObj = $hotelservice_handler->create();
$auctionObj = $id > 0 ? $auction_handler->get($id) : $auction_handler->create();

switch($action)
{
	case "add":
		include MARTIN_ROOT_PATH.'include/form.auction.php';
		martin_collapsableBar('createtable', 'createtableicon', "添加竞价", '添加竞价');
		CreateButton();
		//Create_button(array('addcity'=>array('url'=>'mconfirmartin.hotel.city.php?action=add','value'=>'添加城市')));	
		$form = & new form_auction($auctionObj,$auction_handler->getRoomList($id),$hotelservice_handler->GetHotelList());
		
		$form->display();
		martin_close_collapsable('createtable', 'createtableicon');
		break;
	case "save":
		//var_dump(($_POST['auction_info']));exit;
		$auctionObj->setVar('auction_id',$id);
		$auctionObj->setVar('auction_name',(isset($_POST['auction_name']) ? addslashes($_POST['auction_name']) : ''));
		$auctionObj->setVar('auction_info',(isset($_POST['auction_info']) ? ($_POST['auction_info']) : ''));
		$auctionObj->setVar('check_in_date', (isset($_POST['check_in_date'])) ? strtotime($_POST['check_in_date']) : 0);
		$auctionObj->setVar('check_out_date', (isset($_POST['check_out_date'])) ? strtotime($_POST['check_out_date']) : 0);
		$auctionObj->setVar('apply_start_date', (isset($_POST['apply_start_date'])) ? strtotime($_POST['apply_start_date']) : 0);
		$auctionObj->setVar('apply_end_date', (isset($_POST['apply_end_date'])) ? strtotime($_POST['apply_end_date']) : 0);
		$auctionObj->setVar('auction_price', (isset($_POST['auction_price'])) ? round($_POST['auction_price'],2) : 0);
		$auctionObj->setVar('auction_low_price', (isset($_POST['auction_low_price'])) ? round($_POST['auction_low_price'],2) : 0);
		$auctionObj->setVar('auction_add_price', (isset($_POST['auction_add_price'])) ? round($_POST['auction_add_price'],2) : 0);
		$auctionObj->setVar('auction_can_use_coupon', (isset($_POST['auction_can_use_coupon'])) ? intval($_POST['auction_can_use_coupon'],2) : 0);
		$auctionObj->setVar('auction_sented_coupon', (isset($_POST['auction_sented_coupon'])) ? round($_POST['auction_sented_coupon'],2) : 0);
		$auctionObj->setVar('auction_status', (isset($_POST['auction_status'])) ? intval($_POST['auction_status'],2) : 0);
		$auctionObj->setVar('auction_add_time', time());

		$room_counts = array();
		$room_ids = $_POST['room_id'];
		foreach($room_ids as $room_id)
		{
			$room_counts[] = $_POST['room_count_'.$room_id];
		}

		//var_dump($auctionObj);exit;
		$isNew = false;
		if(!$id) 
		{
			$isNew = true;
			$auctionObj->setNew();
		}
		if ($auctionObj->isNew()) {
			$redirect_msg = '添加成功';
			$redirect_to = 'martin.auction.php';
		} else {
			$redirect_msg = '修改成功';
			$redirect_to = 'martin.auction.php';
		}
		
		if(!is_array($room_ids) || empty($room_ids))
		{
			redirect_header('javascript:history.go(-1);', 2, '房间添加失败<br>没有选择房间');
			exit();
		}

		if(!$auction_id = $auction_handler->insert($auctionObj))
		{
			redirect_header('javascript:history.go(-1);', 2, '操作失败');
			exit();
		}

		//$auction_id = $id > 0 ? $id : $auctionObj->auction_id();
		
		//var_dump($auction_id);
		if($auction_id > 0)
		{
			if(!$auction_handler->InsertAuctionRoom($auction_id,$room_ids,$room_counts,$isNew))
			{
				redirect_header('javascript:history.go(-1);', 2, '房间添加失败');
				exit();
			}
		}else{
			redirect_header('javascript:history.go(-1);', 2, '房间添加失败');
			exit();
		}

		redirect_header($redirect_to, 2, $redirect_msg);
		break;
	case "del":	
		if(!$confirm)
		{
			xoops_confirm(array('op' => 'del', 'id' => $auctionObj->auction_id(), 'confirm' => 1, 'name' => $auctionObj->auction_name()), '?action=del', "删除 '" . $auctionObj->auction_name() . "'. <br /> <br /> 确定删除该竞价吗?", _DELETE);
		}else{
			if($auction_handler->delete($auctionObj))
			{
				$redirect_msg = "删除成功";
				$redirect_to = "martin.auction.php";
			}else{
				$redirect_msg = "删除失败";
				$redirect_to = "javascript:history.go(-1);";
			}
			redirect_header($redirect_to,2,$redirect_msg);
		}
		break;
	case "list":
		martin_collapsableBar('createtable', 'createtableicon', " 竞价列表", '竞价列表');
		CreateButton();
		$Status = array('<div style="background-color:#FF0000">编辑中</div>','<div style="background-color:#00FF00">已发布</div>');
		$AuctionObjs = $auction_handler->getAuctions($xoopsModuleConfig['perpage'], $start, 0);
		$Cout = $auction_handler->getCount();
		include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
		$pagenav = new XoopsPageNav($Cout, $xoopsModuleConfig['perpage'], $start, 'start');
		$pavStr = '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

		// Creating the objects for top categories
		echo $pavStr."<table width='100%' cellspacing=1 cellpadding=12 border=0 class = outer>";
		echo "<tr>";
		echo "<td class='bg3' align='left'><b>ID</b></td>";
		echo "<td class='bg3' align='left'><b>标题</b></td>";
		echo "<td class='bg3' align='left'><b>竞价开始时间</b></td>";
		echo "<td class='bg3' align='left'><b>竞价结束时间</b></td>";
		echo "<td class='bg3' align='left'><b>入住时间</b></td>";
		echo "<td class='bg3' align='left'><b>退房时间</b></td>";
		echo "<td class='bg3' align='left'><b>起拍价</b></td>";
		echo "<td class='bg3' align='left'><b>低价</b></td>";
		echo "<td class='bg3' align='left'><b>加价幅度</b></td>";
		echo "<td class='bg3' align='left'><b>曾送现金卷</b></td>";
		echo "<td class='bg3' align='left'><b>公开状态</b></td>";
		echo "<td width='60' class='bg3' align='center'><b>操作</b></td>";
		echo "</tr>";
		if (count($AuctionObjs) > 0) {
			foreach ( $AuctionObjs as $key => $thiscat) {
				$modify = "<a href='?action=add&id=" . $thiscat->auction_id() ."'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif'/></a>";
				$delete = "<a href='?action=del&id=" . $thiscat->auction_id() ."'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif'/></a>";
				echo "<tr><td class='even' align='left'>".$thiscat->auction_id() . "</td>";
				echo "<td class='even' align='left'>".$thiscat->auction_name() . "</td>";
				echo "<td class='even' align='left'>".date('Y-m-d',$thiscat->check_in_date()) . "</td>";
				echo "<td class='even' align='left'>".date('Y-m-d',$thiscat->check_out_date()) . "</td>";
				echo "<td class='even' align='left'>".date('Y-m-d',$thiscat->apply_start_date()) . "</td>";
				echo "<td class='even' align='left'>".date('Y-m-d',$thiscat->apply_end_date()) . "</td>";
				echo "<td class='even' align='left'>".$thiscat->auction_price() . "</td>";
				echo "<td class='even' align='left'>".$thiscat->auction_low_price() . "</td>";
				echo "<td class='even' align='left'>".$thiscat->auction_add_price() . "</td>";
				echo "<td class='even' align='left'>".$thiscat->auction_sented_coupon() . "</td>";
				echo "<td class='even' align='left'>".$Status[$thiscat->auction_status()] . "</td>";
				echo "<td class='even' align='center'>  $modify $delete </td></tr>";			
			}
		} else {
			echo "<tr>";
			echo "<td class='head' align='center' colspan= '12'>" . MARTIN_IS_NUll . "</td>";
			echo "</tr>";
			$categoryid = '0';
		}
		echo "</table>\n";
		echo '<div style="text-align:right;">' . $pavStr . '</div>';
		echo "<br />";
		martin_close_collapsable('createtable', 'createtableicon');
		break;
	default:
		redirect_header( XOOPS_URL , 2, '非法访问.' ) ;
	break;
}

function CreateButton()
{
	$arr = array('addservicetype'=>array('url'=>'martin.auction.php?action=add','value'=>'添加竞价'),
					'servicetypelist'=>array('url'=>'martin.auction.php?action=list','value'=>'竞价列表'),);
	Create_button($arr);
}

//底部
include "martin.footer.php";
?>
