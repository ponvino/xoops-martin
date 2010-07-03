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

martin_adminMenu( 4 , "订房后台 > 酒店促销");

$promotion_handler =& xoops_getmodulehandler('hotelpromotion', MARTIN_DIRNAME,true);
$hotelservice_handler =& xoops_getmodulehandler('hotelservice', MARTIN_DIRNAME,true);

//$HotelServiceObj = $hotelservice_handler->create();
$PromotionObj = $id > 0 ? $promotion_handler->get($id) : $promotion_handler->create();

switch($action)
{
	case "add":
		include MARTIN_ROOT_PATH.'include/form.hotel.promotion.php';
		martin_collapsableBar('createtable', 'createtableicon', "添加促销", '添加促销');
		CreateButton();
		//Create_button(array('addcity'=>array('url'=>'mconfirmartin.hotel.city.php?action=add','value'=>'添加城市')));	
		$form = & new form_hotel_promotion($PromotionObj,$hotelservice_handler->GetHotelList());
		
		$form->display();
		martin_close_collapsable('createtable', 'createtableicon');
		break;
	case "save":
		//var_dump(($_POST['group_info']));exit;
		$PromotionObj->setVar('promotion_id',$id);
		$PromotionObj->setVar('hotel_id',intval($_POST['hotel_id']));
		$PromotionObj->setVar('promotion_description',(isset($_POST['promotion_description']) ? ($_POST['promotion_description']) : ''));
		$PromotionObj->setVar('promotion_start_date', (isset($_POST['promotion_start_date'])) ? strtotime($_POST['promotion_start_date']) : 0);
		$PromotionObj->setVar('promotion_end_date', (isset($_POST['promotion_end_date'])) ? strtotime($_POST['promotion_end_date']) : 0);
		$PromotionObj->setVar('promotion_add_time', time());

		//var_dump($PromotionObj);exit;
		$isNew = false;
		if(!$id) 
		{
			$isNew = true;
			$PromotionObj->setNew();
		}
		if ($PromotionObj->isNew()) {
			$redirect_msg = '添加成功';
			$redirect_to = 'martin.hotel.promotion.php';
		} else {
			$redirect_msg = '修改成功';
			$redirect_to = 'martin.hotel.promotion.php';
		}
		
		if(!$promotion_handler->insert($PromotionObj))
		{
			redirect_header('javascript:history.go(-1);', 2, '操作失败');
			exit();
		}

		//$promotion_id = $id > 0 ? $id : $PromotionObj->promotion_id();

		redirect_header($redirect_to, 2, $redirect_msg);
		break;
	case "del":	
		if(!$confirm)
		{
			xoops_confirm(array('op' => 'del', 'id' => $PromotionObj->promotion_id(), 'confirm' => 1, 'name' => $PromotionObj->hotel_name()), '?action=del', "删除 '" . $PromotionObj->hotel_name() . "'. <br /> <br /> 确定删除该促销吗?", _DELETE);
		}else{
			if($promotion_handler->delete($PromotionObj))
			{
				$redirect_msg = "删除成功";
				$redirect_to = "martin.hotel.promotion.php";
			}else{
				$redirect_msg = "删除失败";
				$redirect_to = "javascript:history.go(-1);";
			}
			redirect_header($redirect_to,2,$redirect_msg);
		}
		break;
	case "list":
		martin_collapsableBar('createtable', 'createtableicon', " 促销列表", '促销列表');
		CreateButton();
		$Status = array('<div style="background-color:#FF0000">编辑中</div>','<div style="background-color:#00FF00">已发布</div>');
		
		$Cout = $promotion_handler->getCount();
		$PromotionObjs = $promotion_handler->getPromotions($xoopsModuleConfig['perpage'], $start, 0);
		
		include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
		$pagenav = new XoopsPageNav($Cout, $xoopsModuleConfig['perpage'], $start, 'start');
		$pavStr = '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

		// Creating the objects for top categories
		echo $pavStr."<table width='100%' cellspacing=1 cellpadding=5 border=0 class = outer>";
		echo "<tr>";
		echo "<td class='bg3' align='left'><b>ID</b></td>";
		echo "<td class='bg3' align='left'><b>酒店</b></td>";
		echo "<td class='bg3' align='left'><b>促销开始时间</b></td>";
		echo "<td class='bg3' align='left'><b>促销结束时间</b></td>";
		echo "<td width='60' class='bg3' align='center'><b>操作</b></td>";
		echo "</tr>";
		if (count($PromotionObjs) > 0) {
			foreach ( $PromotionObjs as $key => $thiscat) {
				$modify = "<a href='?action=add&id=" . $thiscat->promotion_id() ."'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif'/></a>";
				$delete = "<a href='?action=del&id=" . $thiscat->promotion_id() ."'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif'/></a>";
				echo "<tr><td class='even' align='left'>".$thiscat->promotion_id() . "</td>";
				echo "<td class='even' align='left'>".$thiscat->hotel_name() . "</td>";
				echo "<td class='even' align='left'>".date('Y-m-d',$thiscat->promotion_start_date()) . "</td>";
				echo "<td class='even' align='left'>".date('Y-m-d',$thiscat->promotion_end_date()) . "</td>";
				echo "<td class='even' align='center'>  $modify $delete </td></tr>";			
			}
		} else {
			echo "<tr>";
			echo "<td class='head' align='center' colspan= '5'>" . MARTIN_IS_NUll . "</td>";
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
	$arr = array('addservicetype'=>array('url'=>'martin.hotel.promotion.php?action=add','value'=>'添加促销'),
					'servicetypelist'=>array('url'=>'martin.hotel.promotion.php?action=list','value'=>'促销列表'),);
	Create_button($arr);
}

//底部
include "martin.footer.php";
?>
