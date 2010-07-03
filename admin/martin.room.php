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
$room_id = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;
$room_date = isset($_GET['room_date']) ? trim($_GET['room_date']) : 0;
$hotel_id = isset($_GET['hotel_id']) ? intval($_GET['hotel_id']) : 0 ;
$typeid = isset($_GET['typeid']) ? intval($_GET['typeid']) : 0 ;
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
//确认删除

$confirm = (isset($_POST['confirm'])) ? $_POST['confirm'] : 0;
//parameter 参数
martin_adminMenu( 6 , "订房后台 > 客房管理");

$room_handler =& xoops_getmodulehandler('room', MARTIN_DIRNAME,true);
$hotelservice_handler =& xoops_getmodulehandler('hotelservice', MARTIN_DIRNAME,true);

//$HotelServiceObj = $hotelservice_handler->create();
$RoomObj = $id > 0 ? $room_handler->get($id) : $room_handler->create();

switch($action)
{
	case "add":
		include MARTIN_ROOT_PATH.'include/form.room.php';
		martin_collapsableBar('createtable', 'createtableicon', "添加客房", '添加客房');
		CreateButton();
		$TypeList = $room_handler->getRoomTypeList();
		$hotelList = $hotelservice_handler->getHotelList($hotel_id);
		$form = & new form_room($RoomObj,&$hotelList,&$TypeList);
		$form->display();
		martin_close_collapsable('createtable', 'createtableicon');
		break;
	case "typeadd":
		include MARTIN_ROOT_PATH.'include/form.room.type.php';
		martin_collapsableBar('createtable', 'createtableicon', "添加客房类别", '添加客房类别');
		CreateButton();
		$roomType = array();
		if($typeid > 0)
		{
			$roomType = $room_handler->getRoomTypeList($typeid);
			$roomType = array('room_type_id'=>$typeid,'room_type_info'=>$roomType[$typeid]);
		}
		$form = & new form_room_type($roomType);
		$form->display();
		martin_close_collapsable('createtable', 'createtableicon');
		break;
	case "addprice":
		include MARTIN_ROOT_PATH.'include/form.room.price.php';
		martin_collapsableBar('createtable', 'createtableicon', "添加客房价格", '添加客房价格');
		CreateButton();
		$room_date = isset($_GET['room_date']) ? trim($_GET['room_date']) : null;
		$RoomPrice =  ($room_id > 0 && $room_date) ? $room_handler->getRoomPrice($room_id,$room_date) : array();
		$RoomPrice =  ($room_id > 0 && empty($RoomPrice)) ? $room_handler->getRoomPrice($room_id) : $RoomPrice;
		$RoomList = $room_handler->getRoomList($room_id);
		//var_dump($RoomPrice);
		$form = & new form_room_price($RoomPrice,$RoomList);
		$form->display();
		martin_close_collapsable('createtable', 'createtableicon');
		break;
	case "save":
		$RoomObj->setVar('room_id',$id);
		$RoomObj->setVar('room_type_id', (isset($_POST['room_type_id'])) ? intval($_POST['room_type_id']) : 0);
		$RoomObj->setVar('hotel_id', (isset($_POST['hotel_id'])) ? intval($_POST['hotel_id']) : 0);
		$RoomObj->setVar('room_count', (isset($_POST['room_count'])) ? intval($_POST['room_count']) : 0);
		$RoomObj->setVar('room_bed_type', (isset($_POST['room_bed_type'])) ? intval($_POST['room_bed_type']) : 0);
		$RoomObj->setVar('room_name', (isset($_POST['room_name'])) ? addslashes($_POST['room_name']) : '');
		$RoomObj->setVar('room_area', (isset($_POST['room_area'])) ? intval($_POST['room_area']) : 0);
		$RoomObj->setVar('room_floor', (isset($_POST['room_floor'])) ? addslashes($_POST['room_floor']) : '');
		$RoomObj->setVar('room_initial_price', (isset($_POST['room_initial_price'])) ? round($_POST['room_initial_price'],2) : 0);
		$RoomObj->setVar('room_is_add_bed', (isset($_POST['room_is_add_bed'])) ? intval($_POST['room_is_add_bed']) : 0);
		$RoomObj->setVar('room_add_money', (isset($_POST['room_add_money'])) ? intval($_POST['room_add_money']) : 0);
		$RoomObj->setVar('room_bed_info', (isset($_POST['room_bed_info'])) ? addslashes($_POST['room_bed_info']) : '');
		$RoomObj->setVar('room_status', (isset($_POST['room_status'])) ? intval($_POST['room_status']) : 0);
		$RoomObj->setVar('room_sented_coupon', (isset($_POST['room_sented_coupon'])) ? round($_POST['room_sented_coupon'],2) : 0);
		if(!$id) $RoomObj->setNew();
		if ($RoomObj->isNew()) {
			$redirect_msg = '添加成功';
		} else {
			$redirect_msg = '修改成功';
		}
		$redirect_to = 'martin.room.php?action=list';
		if($room_handler->CheckHotelRoomExist($RoomObj))
		{
			redirect_header('javascript:history.go(-1);', 2, '该酒店已经添加了此房型.');
			exit();
		}
		if(!$room_handler->insert($RoomObj))
		{
			redirect_header('javascript:history.go(-1);', 2, '操作失败');
			exit();
		}
		redirect_header($redirect_to, 2, $redirect_msg);
		break;
	case "typesave":
		$typeData = array('room_type_id'=>$typeid,'room_type_info'=>trim($_POST['room_type_info']));

		if(!$typeid){
			$redirect_msg = '添加成功';
		} else {
			$redirect_msg = '修改成功';
		}
		$redirect_to = 'martin.room.php?action=typelist';
		if(!$room_handler->insertType($typeData))
		{
			redirect_header('javascript:history.go(-1);', 2, '操作失败');
			exit();
		}
		redirect_header($redirect_to, 2, $redirect_msg);
		break;
	case "pricesave":
		$room_prices = $_POST['room_price'];
		$room_is_totay_specials = $_POST['room_is_totay_special'];
		$room_advisory_range_smalls = $_POST['room_advisory_range_small'];
		$room_advisory_range_maxs = $_POST['room_advisory_range_max'];
		$room_sented_coupons = $_POST['room_sented_coupon'];
		$room_dates = $_POST['room_date'];
		//var_dump($_POST['room_is_totay_special']);exit;
		
		$Data = array();	
		foreach($room_prices as $key => $room_price)
		{
			$dateTime = strtotime($room_dates[$key]);
			$Data[] = array('room_id'=>intval($_POST['room_id']),
						'room_price'=>$room_prices[$key],
						'room_is_totay_special'=>isset($room_is_totay_specials[$dateTime]) ? intval($room_is_totay_specials[$dateTime]) : 0,
						'room_advisory_range_small'=>round($room_advisory_range_smalls[$key],2),
						'room_advisory_range_max'=>round($room_advisory_range_maxs[$key],2),
						'room_sented_coupon'=>round($room_sented_coupons[$key],2),
						'room_date'=>strtotime($room_dates[$key])
						);
		}
		
		$IsOld = false;
		$redirect_msg = '添加成功';
		if($room_id && $room_date)
		{
			$IsOld = true;
			$redirect_msg = '修改成功';
			$Data = array('room_id'=>$room_id,
						'room_price'=>intval($_POST['room_price']),
						'room_is_totay_special'=>intval($_POST['room_is_totay_special']),
						'room_advisory_range_small'=>round($_POST['room_advisory_range_small'],2),
						'room_advisory_range_max'=>round($_POST['room_advisory_range_max'],2),
						'room_sented_coupon'=>round($_POST['room_sented_coupon'],2),
						'room_date'=>strtotime($room_date)
						);
		}
		$redirect_to = 'martin.room.php?action=pricelist';
		
		//var_dump($IsOld);
		//var_dump($Data);exit;
		
		if(!$room_handler->InsertRoomPrice($Data,$IsOld))
		{
			redirect_header('javascript:history.go(-1);', 2, '操作失败<br>原因：数据重复.');
			exit();
		}
		redirect_header($redirect_to, 2, $redirect_msg);

		break;
	case "del":
		if(!$confirm)
		{
			xoops_confirm(array('op' => 'del', 'id' => $id, 'confirm' => 1, 'name' => $RoomObj->room_name()), '?action=del', "删除 '" . $RoomObj->room_name() . "'. <br /> <br /> 确定删除该客房吗?", _DELETE);
		}else{
			if($room_handler->delete($RoomObj))
			{
				$redirect_msg = "删除成功";
				$redirect_to = "martin.room.php";
			}else{
				$redirect_msg = "删除失败";
				$redirect_to = "javascript:history.go(-1);";
			}
			redirect_header($redirect_to,2,$redirect_msg);
		}
		break;
	case "typedel":
		$roomType = $room_handler->getRoomTypeList($typeid);
		if(!$confirm)
		{
			xoops_confirm(array('op' => 'del', 'typeid' => $typeid, 'confirm' => 1, 'name' => $roomType[$typeid]), '?action=typedel&typeid='.$typeid, "删除 '" .$roomType[$typeid]. "'. <br /> <br /> 确定删除该客房类别吗?", _DELETE);
		}else{
			if($room_handler->deleteRoomType($typeid))
			{
				$redirect_msg = "删除成功";
				$redirect_to = "martin.room.php?action=typelist";
			}else{
				$redirect_msg = "删除失败";
				$redirect_to = "javascript:history.go(-1);";
			}
			redirect_header($redirect_to,2,$redirect_msg);
		}
		break;
	case "pricedel":
		$RoomPrice = ($room_id > 0 && $room_date ) ? $room_handler->getRoomPrice($room_id,$room_date) : array();
		if(!$confirm)
		{
			xoops_confirm(array('op' => 'del', 'hotel_id' => $hotel_id, 'confirm' => 1, 'name' => $RoomPrice['room_name'] ), "?action=pricedel&room_id=$room_id&room_date=".date("Y-m-d",$RoomPrice['room_date']), "删除 '" . $RoomPrice['room_name'] . " : " . date('Y-m-d',$RoomPrice['room_date']) . "'. <br /> <br /> 确定删除该价格吗?", _DELETE);
		}else{
			if($room_handler->deleteRoomPrice($room_id,date("Y-m-d",$RoomPrice['room_date'])))
			{
				$redirect_msg = "删除成功";
				$redirect_to = "martin.room.php?action=pricelist";
			}else{
				$redirect_msg = "删除失败";
				$redirect_to = "javascript:history.go(-1);";
			}
			redirect_header($redirect_to,2,$redirect_msg);
		}
		break;
	case "deletepassdata":
		if(!$confirm)
		{
			xoops_confirm(array('op' => 'del', 'hotel_id' => $hotel_id, 'confirm' => 1 ), "?action=deletepassdata", "确定过期数据吗?一旦删除不能恢复.", _DELETE);
		}else{
			if($room_handler->TruncatePassData($date))
			{
				$redirect_msg = "清空成功";
				$redirect_to = "martin.room.php?action=pricelist";
			}else{
				$redirect_msg = "清空失败";
				$redirect_to = "javascript:history.go(-1);";
			}
			redirect_header($redirect_to,2,$redirect_msg);
		}
		break;
	case "list":
		martin_collapsableBar('createtable', 'createtableicon', " 客房列表", '客房列表');
		CreateButton();
		$RoomObjs = $room_handler->getRooms($xoopsModuleConfig['perpage'], $start, 0);
		$Cout = $room_handler->getCount();
		include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
		$pagenav = new XoopsPageNav($Cout, $xoopsModuleConfig['perpage'], $start, 'action='.$action.'&start');
		$pavStr = '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
		
		echo $pavStr."<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
		echo "<tr>";
		echo "<td class='bg3' align='left'><b>ID</b></td>";
		echo "<td class='bg3' align='left'><b>客房类别名称</b></td>";
		echo "<td class='bg3' align='left'><b>酒店名称</b></td>";
		echo "<td class='bg3' align='left'><b>房间名称</b></td>";
		echo "<td class='bg3' align='left'><b>房间面积</b></td>";
		echo "<td class='bg3' align='left'><b>房间楼层</b></td>";
		echo "<td class='bg3' align='left'><b>曾送现金卷</b></td>";
		echo "<td class='bg3' align='left'><b>公开状态</b></td>";
		echo "<td width='100' class='bg3' align='center'><b>操作</b></td>";
		echo "</tr>";
		$Status = array('<div style="background-color:#FF0000">编辑中</div>','<div style="background-color:#00FF00">已发布</div>');
		if (count($RoomObjs) > 0) {
			foreach ( $RoomObjs as $key => $thiscat) {
				$modify = "<a href='?action=add&id=" . $thiscat->room_id() ."'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif'/></a>";
				$addPrice = "<a href='?action=addprice&room_id=" . $thiscat->room_id() ."' title='添加价格'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/add.jpg'/></a>";
				$delete = "<a href='?action=del&id=" . $thiscat->room_id() ."'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif'/></a>";
				echo "<tr><td class='even' align='left'>".$thiscat->room_id() . "</td>";
				echo "<td class='even' align='left'>".$thiscat->room_type_info() . "</td>";
				echo "<td class='even' align='left'>".$thiscat->hotel_name() . "</td>";
				echo "<td class='even' align='left'>".$thiscat->room_name() . "</td>";
				echo "<td class='even' align='left'>".$thiscat->room_area() . "</td>";
				echo "<td class='even' align='left'>".$thiscat->room_floor() . "</td>";
				echo "<td class='even' align='left'>".$thiscat->room_sented_coupon() . "</td>";
				echo "<td class='even' align='left'>".$Status[$thiscat->room_status()] . "</td>";
				echo "<td class='even' align='center'> $addPrice $modify $delete </td></tr>";
			}
		} else {
			echo "<tr>";
			echo "<td class='head' align='center' colspan= '9'>" . MARTIN_IS_NUll . "</td>";
			echo "</tr>";
		}
		echo "</table>\n";
		echo '<div style="text-align:right;">' . $pavStr . '</div>';
		echo "<br />";
		martin_close_collapsable('createtable', 'createtableicon');
		echo "<br>";
		break;
	case "typelist":
		martin_collapsableBar('createtable', 'createtableicon', " 客房类别列表", '客房类别列表');
		CreateButton();
		$roomTypeList = $room_handler->getRoomTypeList();
		
		echo "<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
		echo "<tr>";
		echo "<td class='bg3' align='left'><b>ID</b></td>";
		echo "<td class='bg3' align='left'><b>客房类别名称</b></td>";
		echo "<td width='60' class='bg3' align='center'><b>操作</b></td>";
		echo "</tr>";
		if (count($roomTypeList) > 0) {
			foreach ( $roomTypeList as $key => $thiscat) {
				$modify = "<a href='?action=typeadd&typeid=" . $key ."'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif'/></a>";
				$delete = "<a href='?action=typedel&typeid=" . $key."'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif'/></a>";
				echo "<tr><td class='even' align='lefet'>". $key  . "</td>";
				echo "<td class='even' align='lefet'>".$thiscat . "</td>";
				echo "<td class='even' align='center'> $modify $delete </td></tr>";
			}
		} else {
			echo "<tr>";
			echo "<td class='head' align='center' colspan= '3'>" . MARTIN_IS_NUll . "</td>";
			echo "</tr>";
		}
		echo "</table>\n";
		/*nclude_once XOOPS_ROOT_PATH . '/class/pagenav.php';
		$pagenav = new XoopsPageNav($Cout, $xoopsModuleConfig['perpage'], 0, 'start');
		echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
		echo "<br />";*/
		martin_close_collapsable('createtable', 'createtableicon');
		echo "<br>";
		break;
	case "pricelist":
		martin_collapsableBar('createtable', 'createtableicon', "客房价格列表", '客房价格列表');
		CreateButton();
		$Prices = $room_handler->GetRoomPriceList($xoopsModuleConfig['perpage'], $start);
		
		echo "<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
		echo "<tr>";
		echo "<td class='bg3' align='left'><b>客房名称</b></td>";
		echo "<td class='bg3' align='left'><b>价格</b></td>";
		echo "<td class='bg3' align='left'><b>咨询价格范围</b></td>";
		echo "<td class='bg3' align='left'><b>价格时间</b></td>";
		echo "<td width='60' class='bg3' align='center'><b>操作</b></td>";
		echo "</tr>";
		$Cout = $room_handler->GetRoomPriceCount();
		if ($Cout > 0) {
			foreach ( $Prices as $key => $price) {
				$modify = "<a href='?action=addprice&room_id={$price['room_id']}&room_date={$price['room_date']}'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif'/></a>";
				$delete = "<a href='?action=pricedel&room_id={$price['room_id']}&room_date={$price['room_date']}'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif'/></a>";
				echo "<td class='even' align='left'>".$price['room_name'] . "</td>";
				echo "<td class='even' align='left'>".$price['room_price'] . "</td>";
				echo "<td class='even' align='left'>".$price['room_advisory_range_small'] . '-' . $price['room_advisory_range_max'] ."  </td>";
				echo "<td class='even' align='left'>".$price['room_date'] ."  </td>";
				echo "<td class='even' align='center'> $modify $delete </td></tr>";
			}
		} else {
			echo "<tr>";
			echo "<td class='head' align='center' colspan= '5'>" . MARTIN_IS_NUll . "</td>";
			echo "</tr>";
		}
		echo "</table>\n";
		include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
		$pagenav = new XoopsPageNav($Cout, $xoopsModuleConfig['perpage'], $start , 'action='.$action.'&start');
		echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
		echo "<br />";
		
		martin_close_collapsable('createtable', 'createtableicon');
		break;
	default:
		redirect_header( XOOPS_URL , 2, '非法访问.' ) ;
	break;
}

function CreateButton()
{
	global $action;
	$arr = array('addservicetype'=>array('url'=>'martin.room.php?action=typeadd','value'=>'添加客房类别'),
					'servicetypelist'=>array('url'=>'martin.room.php?action=typelist','value'=>'客房类别列表'),
					'addservice'=>array('url'=>'martin.room.php?action=add','value'=>'添加客房'),
					'servicetype'=>array('url'=>'martin.room.php?action=list','value'=>'客房列表'),
					'addprice'=>array('url'=>'martin.room.php?action=addprice','value'=>'添加客房价格'),
					'price'=>array('url'=>'martin.room.php?action=pricelist','value'=>'客房价格列表'),
					);
	$arr = $action == "pricelist" ? array_merge($arr , array('delte_pass_data'=>array('url'=>'martin.room.php?action=deletepassdata','value'=>'删除过期数据'))) : $arr;
	Create_button($arr);
}
//底部
include "martin.footer.php";
?>
