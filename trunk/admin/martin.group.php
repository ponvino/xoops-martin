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

martin_adminMenu( 7 , "订房后台 > 团购管理");

$group_handler =& xoops_getmodulehandler('group', MARTIN_DIRNAME,true);
$hotelservice_handler =& xoops_getmodulehandler('hotelservice', MARTIN_DIRNAME,true);

//$HotelServiceObj = $hotelservice_handler->create();
$GroupObj = $id > 0 ? $group_handler->get($id) : $group_handler->create();

switch($action)
{
	case "add":
		include MARTIN_ROOT_PATH.'include/form.group.php';
		martin_collapsableBar('createtable', 'createtableicon', "添加团购", '添加团购');
		CreateButton();
		//Create_button(array('addcity'=>array('url'=>'mconfirmartin.hotel.city.php?action=add','value'=>'添加城市')));	
		$form = & new form_group($GroupObj,$group_handler->getRoomList($id),$hotelservice_handler->GetHotelList());
		
		$form->display();
		martin_close_collapsable('createtable', 'createtableicon');
		break;
	case "save":
		//var_dump(($_POST['group_info']));exit;
		$GroupObj->setVar('group_id',$id);
		$GroupObj->setVar('group_name',(isset($_POST['group_name']) ? addslashes($_POST['group_name']) : ''));
		$GroupObj->setVar('group_info',(isset($_POST['group_info']) ? ($_POST['group_info']) : ''));
		$GroupObj->setVar('check_in_date', (isset($_POST['check_in_date'])) ? strtotime($_POST['check_in_date']) : 0);
		$GroupObj->setVar('check_out_date', (isset($_POST['check_out_date'])) ? strtotime($_POST['check_out_date']) : 0);
		//$GroupObj->setVar('apply_start_date', (isset($_POST['apply_start_date'])) ? strtotime($_POST['apply_start_date']) : 0);
		//$GroupObj->setVar('apply_end_date', (isset($_POST['apply_end_date'])) ? strtotime($_POST['apply_end_date']) : 0);
		
		$GroupObj->setVar('apply_start_date', (isset($_POST['apply_start_date'])) ? 
							strtotime($_POST['apply_start_date']['date']) + intval($_POST['apply_start_date']['time']) : 0);
		$GroupObj->setVar('apply_end_date', (isset($_POST['apply_end_date'])) ? strtotime($_POST['apply_end_date']['date']) + intval($_POST['apply_end_date']['time']) : 0);
		
		$GroupObj->setVar('group_price', (isset($_POST['group_price'])) ? round($_POST['group_price'],2) : 0);
		$GroupObj->setVar('group_can_use_coupon', (isset($_POST['group_can_use_coupon'])) ? intval($_POST['group_can_use_coupon'],2) : 0);
		$GroupObj->setVar('group_sented_coupon', (isset($_POST['group_sented_coupon'])) ? round($_POST['group_sented_coupon'],2) : 0);
		$GroupObj->setVar('group_status', (isset($_POST['group_status'])) ? intval($_POST['group_status'],2) : 0);
		$GroupObj->setVar('group_add_time', time());

		$room_counts = array();
		$room_ids = $_POST['room_id'];
		foreach($room_ids as $room_id)
		{
			$room_counts[] = $_POST['room_count_'.$room_id];
		}

		//var_dump($GroupObj);exit;
		$isNew = false;
		if(!$id) 
		{
			$isNew = true;
			$GroupObj->setNew();
		}
		if ($GroupObj->isNew()) {
			$redirect_msg = '添加成功';
			$redirect_to = 'martin.group.php';
		} else {
			$redirect_msg = '修改成功';
			$redirect_to = 'martin.group.php';
		}
		
		if(!is_array($room_ids) || empty($room_ids))
		{
			redirect_header('javascript:history.go(-1);', 2, '房间添加失败<br>没有选择房间');
			exit();
		}

		if(!$group_id = $group_handler->insert($GroupObj))
		{
			redirect_header('javascript:history.go(-1);', 2, '操作失败');
			exit();
		}

		//$group_id = $id > 0 ? $id : $GroupObj->group_id();

		//var_dump($group_id);
		if($group_id > 0)
		{
			if(!$group_handler->InsertGroupRoom($group_id,$room_ids,$room_counts,$isNew))
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
			xoops_confirm(array('op' => 'del', 'id' => $GroupObj->group_id(), 'confirm' => 1, 'name' => $GroupObj->group_name()), '?action=del', "删除 '" . $GroupObj->group_name() . "'. <br /> <br /> 确定删除该团购吗?", _DELETE);
		}else{
			if($group_handler->delete($GroupObj))
			{
				$redirect_msg = "删除成功";
				$redirect_to = "martin.group.php";
			}else{
				$redirect_msg = "删除失败";
				$redirect_to = "javascript:history.go(-1);";
			}
			redirect_header($redirect_to,2,$redirect_msg);
		}
		break;
	case "list":
		martin_collapsableBar('createtable', 'createtableicon', " 团购列表", '团购列表');
		CreateButton();
		$Status = array('<div style="background-color:#FF0000">编辑中</div>','<div style="background-color:#00FF00">已发布</div>');
		$GroupObjs = $group_handler->getGroups($xoopsModuleConfig['perpage'], $start, 0);
		$Cout = $group_handler->getCount();
		include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
		$pagenav = new XoopsPageNav($Cout, $xoopsModuleConfig['perpage'], $start, 'start');
		$pavStr = '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

		// Creating the objects for top categories
		echo $pavStr."<table width='100%' cellspacing=1 cellpadding=10 border=0 class = outer>";
		echo "<tr>";
		echo "<td class='bg3' align='left'><b>ID</b></td>";
		echo "<td class='bg3' align='left'><b>标题</b></td>";
		echo "<td class='bg3' align='left'><b>入住时间</b></td>";
		echo "<td class='bg3' align='left'><b>退房时间</b></td>";
		echo "<td class='bg3' align='left'><b>团购开始时间</b></td>";
		echo "<td class='bg3' align='left'><b>团购结束时间</b></td>";
		echo "<td class='bg3' align='left'><b>团购价格</b></td>";
		echo "<td class='bg3' align='left'><b>曾送现金卷</b></td>";
		echo "<td class='bg3' align='left'><b>公开状态</b></td>";
		echo "<td width='60' class='bg3' align='center'><b>操作</b></td>";
		echo "</tr>";
		if (count($GroupObjs) > 0) {
			foreach ( $GroupObjs as $key => $thiscat) {
				$StatusStr = time() < $thiscat->apply_end_date() ? '<div style="background-color: rgb(0, 255, 0);">%s</div>' : '<div style="background-color: rgb(255, 0, 0);">%s</div>';
				$modify = "<a href='?action=add&id=" . $thiscat->group_id() ."'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif'/></a>";
				$delete = "<a href='?action=del&id=" . $thiscat->group_id() ."'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif'/></a>";
				echo "<tr><td class='even' align='left'>".$thiscat->group_id() . "</td>";
				echo "<td class='even' align='left' width=50><a href='../group.php/group-".$thiscat->group_id().$xoopsModuleConfig['hotel_static_prefix']."'>".$thiscat->group_name() . "</a></td>";
				echo "<td class='even' align='left'>".date('Y-m-d',$thiscat->check_in_date()) . "</td>";
				echo "<td class='even' align='left'>".date('Y-m-d',$thiscat->check_out_date()) . "</td>";
				echo "<td class='even' align='left'>".date('Y-m-d H:i:s',$thiscat->apply_start_date()) . "</td>";
				echo "<td class='even' align='left'>".sprintf($StatusStr,date('Y-m-d H:i:s',$thiscat->apply_end_date())) . "</td>";
				echo "<td class='even' align='left'>".$thiscat->group_price() . "</td>";
				echo "<td class='even' align='left'>".$thiscat->group_sented_coupon() . "</td>";
				echo "<td class='even' align='left'>".$Status[$thiscat->group_status()] . "</td>";
				echo "<td class='even' align='center'>  $modify $delete </td></tr>";			
			}
		} else {
			echo "<tr>";
			echo "<td class='head' align='center' colspan= '10'>" . MARTIN_IS_NUll . "</td>";
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
	$arr = array('addservicetype'=>array('url'=>'martin.group.php?action=add','value'=>'添加团购'),
					'servicetypelist'=>array('url'=>'martin.group.php?action=list','value'=>'团购列表'),);
	Create_button($arr);
}

//底部
include "martin.footer.php";
?>
