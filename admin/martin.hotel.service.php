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
$typeid = !empty($_POST['typeid']) ? intval($_POST['typeid']) : intval(@$_GET['typeid']) ;
$hotel_id = isset($_GET['hotel_id']) ? intval($_GET['hotel_id']) : 0 ;
$service_id = isset($_GET['service_id']) ? intval($_GET['service_id']) : 0 ;
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
//确认删除
$confirm = (isset($_POST['confirm'])) ? $_POST['confirm'] : 0;
//parameter 参数

martin_adminMenu( 3 , "订房后台 > 酒店服务");

$hotelservice_handler =& xoops_getmodulehandler('hotelservice', MARTIN_DIRNAME,true);
$hotelservicetype_handler =& xoops_getmodulehandler('hotelservicetype', MARTIN_DIRNAME,true);

$HotelServiceObj = $id > 0 ? $hotelservice_handler->get($id) : $hotelservice_handler->create();
$HotelServiceTypeObj = $typeid > 0 ? $hotelservicetype_handler->get($typeid) : $hotelservicetype_handler->create();

switch($action)
{
	case "add":
		include MARTIN_ROOT_PATH.'include/form.hotel.service.php';
		martin_collapsableBar('createtable', 'createtableicon', "添加服务", '添加服务');
		CreateButton();
		$TypeList = $hotelservicetype_handler->GetList();
		$form = & new form_hotel_service($HotelServiceObj,&$TypeList);
		$form->display();
		martin_close_collapsable('createtable', 'createtableicon');
		break;
	case "typeadd":
		include MARTIN_ROOT_PATH.'include/form.hotel.service.type.php';
		martin_collapsableBar('createtable', 'createtableicon', "添加服务类别", '添加服务类别');
		CreateButton();
		$form = & new form_hotel_service_type($HotelServiceTypeObj);
		$form->display();
		martin_close_collapsable('createtable', 'createtableicon');
		break;
	case "addhotel":
		include MARTIN_ROOT_PATH.'include/form.hotel.service.relation.php';
		martin_collapsableBar('createtable', 'createtableicon', "添加酒店服务", '添加酒店服务');
		CreateButton();
		$serviceList = $hotelservice_handler->getServiceList($service_id);
		$hotelList = $hotelservice_handler->getHotelList($hotel_id);
		$Relation = $hotelservice_handler->getHotelServiceRelation($hotel_id,$service_id);
		$form = & new form_hotel_service_relation($Relation,$serviceList,$hotelList);
		$form->display();
		martin_close_collapsable('createtable', 'createtableicon');
		break;
	case "save":
		$HotelServiceObj->setVar('service_id',$id);
		$HotelServiceObj->setVar('service_type_id', (isset($_POST['service_type_id'])) ? intval($_POST['service_type_id']) : 0);
		$HotelServiceObj->setVar('service_unit', (isset($_POST['service_unit'])) ? addslashes($_POST['service_unit']) : '');
		$HotelServiceObj->setVar('service_name', (isset($_POST['service_name'])) ? addslashes($_POST['service_name']) : '');
		$HotelServiceObj->setVar('service_instruction', (isset($_POST['service_instruction'])) ? addslashes($_POST['service_instruction']) : '');
		if(!$id) $HotelServiceObj->setNew();
		if ($HotelServiceObj->isNew()) {
			$redirect_msg = '添加成功';
		} else {
			$redirect_msg = '修改成功';
		}
		$redirect_to = 'martin.hotel.service.php?action=list';
		if(!$hotelservice_handler->insert($HotelServiceObj))
		{
			redirect_header('javascript:history.go(-1);', 2, '操作失败');
			exit();
		}
		redirect_header($redirect_to, 2, $redirect_msg);
		break;
	case "typesave":
		$HotelServiceTypeObj->setVar('service_type_id', $typeid);
		$HotelServiceTypeObj->setVar('service_type_name', (isset($_POST['service_type_name'])) ? addslashes($_POST['service_type_name']) : '');
		if(!$typeid) $HotelServiceTypeObj->setNew();
		if ($HotelServiceTypeObj->isNew()) {
			$redirect_msg = '添加成功';
		} else {
			$redirect_msg = '修改成功';
		}
		$redirect_to = 'martin.hotel.service.php?action=typelist';
		if(!$hotelservicetype_handler->insert($HotelServiceTypeObj))
		{
			redirect_header('javascript:history.go(-1);', 2, '操作失败');
			exit();
		}
		redirect_header($redirect_to, 2, $redirect_msg);
		break;
	case "hotelsave":
		$RelationData = array('hotel_id'=>intval($_POST['hotel_id']),'service_id'=>intval($_POST['service_id']),'service_extra_price'=>intval($_POST['service_extra_price']));
		
		$IsOld = false;
		$redirect_msg = '添加成功';
		if($hotel_id && $service_id)
		{
			$IsOld = true;
			$redirect_msg = '修改成功';
			$RelationData = array('hotel_id'=>$hotel_id,'service_id'=>$service_id,'service_extra_price'=>intval($_POST['service_extra_price']));
		}
		$redirect_to = 'martin.hotel.service.php?action=hotellist';
		
		//var_dump($IsOld);
		//var_dump($RelationData);exit;
		
		if(!$hotelservice_handler->InsertRelation($RelationData,$IsOld))
		{
			redirect_header('javascript:history.go(-1);', 2, '操作失败<br>原因：数据重复.');
			exit();
		}
		redirect_header($redirect_to, 2, $redirect_msg);

		break;
	case "del":
		if(!$confirm)
		{
			xoops_confirm(array('op' => 'del', 'id' => $id, 'confirm' => 1, 'name' => $HotelServiceObj->service_name()), '?action=del', "删除 '" . $HotelServiceObj->service_name() . "'. <br /> <br /> 确定删除该服务吗?", _DELETE);
		}else{
			if($hotelservice_handler->delete($HotelServiceObj))
			{
				$redirect_msg = "删除成功";
				$redirect_to = "martin.hotel.service.php";
			}else{
				$redirect_msg = "删除失败";
				$redirect_to = "javascript:history.go(-1);";
			}
			redirect_header($redirect_to,2,$redirect_msg);
		}
		break;
	case "typedel":
		if(!$confirm)
		{
			xoops_confirm(array('op' => 'del', 'typeid' => $typeid, 'confirm' => 1, 'name' => $HotelServiceTypeObj->service_type_name()), '?action=typedel', "删除 '" . $HotelServiceTypeObj->service_type_name() . "'. <br /> <br /> 确定删除该服务类别吗?", _DELETE);
		}else{
			if($hotelservicetype_handler->delete($HotelServiceTypeObj))
			{
				$redirect_msg = "删除成功";
				$redirect_to = "martin.hotel.service.php?action=typelist";
			}else{
				$redirect_msg = "删除失败";
				$redirect_to = "javascript:history.go(-1);";
			}
			redirect_header($redirect_to,2,$redirect_msg);
		}
		break;
	case "hoteldel":
		$Relation = $hotelservice_handler->getHotelServiceRelation($hotel_id,$service_id);
		if(!$confirm)
		{
			xoops_confirm(array('op' => 'del', 'hotel_id' => $hotel_id, 'confirm' => 1, 'name' => $Relation['hotel_name'] ), "?action=hoteldel&hotel_id=$hotel_id&service_id=$service_id", "删除 '" . $Relation['hotel_name'] . " : " .$Relation['service_name'] . "'. <br /> <br /> 确定删除该Relation吗?", _DELETE);
		}else{
			if($hotelservice_handler->DeleteServiceRelation($hotel_id,$service_id))
			{
				$redirect_msg = "删除成功";
				$redirect_to = "martin.hotel.service.php?action=hotellist";
			}else{
				$redirect_msg = "删除失败";
				$redirect_to = "javascript:history.go(-1);";
			}
			redirect_header($redirect_to,2,$redirect_msg);
		}
		break;
	case "list":
		martin_collapsableBar('createtable', 'createtableicon', " 服务列表", '服务列表');
		CreateButton();
		$HotelServiceObjs = $hotelservice_handler->getHotelServices($xoopsModuleConfig['perpage'], $start, 0);
		
		echo "<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
		echo "<tr>";
		echo "<td class='bg3' align='left'><b>ID</b></td>";
		echo "<td class='bg3' align='left'><b>服务类别名称</b></td>";
		echo "<td class='bg3' align='left'><b>单位</b></td>";
		echo "<td class='bg3' align='left'><b>服务名称</b></td>";
		echo "<td width='60' class='bg3' align='center'><b>操作</b></td>";
		echo "</tr>";
		$Cout = $hotelservice_handler->getCount();
		if (count($HotelServiceObjs) > 0) {
			foreach ( $HotelServiceObjs as $key => $thiscat) {
				$modify = "<a href='?action=add&id=" . $thiscat->service_id() ."'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif'/></a>";
				$delete = "<a href='?action=del&id=" . $thiscat->service_id() ."'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif'/></a>";
				echo "<tr><td class='even' align='left'>".$thiscat->service_id() . "</td>";
				echo "<td class='even' align='left'>".$thiscat->service_type_name() . "</td>";
				echo "<td class='even' align='left'>".$thiscat->service_unit() . "</td>";
				echo "<td class='even' align='left'>".$thiscat->service_name() . "</td>";
				echo "<td class='even' align='center'> $modify $delete </td></tr>";
			}
		} else {
			echo "<tr>";
			echo "<td class='head' align='center' colspan= '4'>" . MARTIN_IS_NUll . "</td>";
			echo "</tr>";
		}
		echo "</table>\n";
		include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
		$pagenav = new XoopsPageNav($Cout, $xoopsModuleConfig['perpage'], $start, "action=$action&start");
		echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
		echo "<br />";
		martin_close_collapsable('createtable', 'createtableicon');
		echo "<br>";
		break;
	case "typelist":
		martin_collapsableBar('createtable', 'createtableicon', " 服务类别列表", '服务类别列表');
		CreateButton();
		$HotelServiceTypeObjs = $hotelservicetype_handler->getHotelServiceTypes($xoopsModuleConfig['perpage'], $start, 0);
		
		echo "<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
		echo "<tr>";
		echo "<td class='bg3' align='left'><b>ID</b></td>";
		echo "<td class='bg3' align='left'><b>服务类别名称</b></td>";
		echo "<td width='60' class='bg3' align='center'><b>操作</b></td>";
		echo "</tr>";
		$Cout = $hotelservicetype_handler->getCount();
		if (count($HotelServiceTypeObjs) > 0) {
			foreach ( $HotelServiceTypeObjs as $key => $thiscat) {
				$modify = "<a href='?action=typeadd&typeid=" . $thiscat->service_type_id() ."'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif'/></a>";
				$delete = "<a href='?action=typedel&typeid=" . $thiscat->service_type_id() ."'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif'/></a>";
				echo "<tr><td class='even' align='lefet'>".$thiscat->service_type_id() . "</td>";
				echo "<td class='even' align='lefet'>".$thiscat->service_type_name() . "</td>";
				echo "<td class='even' align='center'> $modify $delete </td></tr>";
			}
		} else {
			echo "<tr>";
			echo "<td class='head' align='center' colspan= '3'>" . MARTIN_IS_NUll . "</td>";
			echo "</tr>";
		}
		echo "</table>\n";
		include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
		$pagenav = new XoopsPageNav($Cout, $xoopsModuleConfig['perpage'], $start, "action=$action&start");
		echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
		echo "<br />";
		martin_close_collapsable('createtable', 'createtableicon');
		echo "<br>";
		break;
	case "hotellist":
		martin_collapsableBar('createtable', 'createtableicon', "酒店服务列表", '酒店服务列表');
		CreateButton();
		$HotelServiceRelations = $hotelservice_handler->getHotelServiceRelations($xoopsModuleConfig['perpage'], $start);
		
		echo "<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
		echo "<tr>";
		echo "<td class='bg3' align='left'><b>酒店名称</b></td>";
		echo "<td class='bg3' align='left'><b>服务名称</b></td>";
		echo "<td class='bg3' align='left'><b>服务价格</b></td>";
		echo "<td width='60' class='bg3' align='center'><b>操作</b></td>";
		echo "</tr>";
		$Cout = $hotelservice_handler->GetRelationCount();
		if (count($HotelServiceRelations) > 0) {
			foreach ( $HotelServiceRelations as $key => $relation) {
				$modify = "<a href='?action=addhotel&hotel_id={$relation['hotel_id']}&service_id={$relation['service_id']}'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif'/></a>";
				$delete = "<a href='?action=hoteldel&hotel_id={$relation['hotel_id']}&service_id={$relation['service_id']}'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif'/></a>";
				echo "<td class='even' align='left'>".$relation['hotel_name'] . "</td>";
				echo "<td class='even' align='left'>".$relation['service_name'] . "</td>";
				echo "<td class='even' align='left'>".$relation['service_extra_price'] ."  </td>";
				echo "<td class='even' align='center'> $modify $delete </td></tr>";
			}
		} else {
			echo "<tr>";
			echo "<td class='head' align='center' colspan= '4'>" . MARTIN_IS_NUll . "</td>";
			echo "</tr>";
		}
		echo "</table>\n";
		include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
		$pagenav = new XoopsPageNav($Cout, $xoopsModuleConfig['perpage'], $start , "action=$action&start");
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
	Create_button(array('addservicetype'=>array('url'=>'martin.hotel.service.php?action=typeadd','value'=>'添加服务类别'),
					'servicetypelist'=>array('url'=>'martin.hotel.service.php?action=typelist','value'=>'服务类别列表'),
					'addservice'=>array('url'=>'martin.hotel.service.php?action=add','value'=>'添加服务'),
					'servicetype'=>array('url'=>'martin.hotel.service.php?action=list','value'=>'服务列表'),
					'addhotel'=>array('url'=>'martin.hotel.service.php?action=addhotel','value'=>'添加酒店服务'),
					'hotelservice'=>array('url'=>'martin.hotel.service.php?action=hotellist','value'=>'酒店服务列表')));
}

//底部
include "martin.footer.php";
?>
