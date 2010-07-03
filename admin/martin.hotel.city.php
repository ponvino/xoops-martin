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
$city_parentid = isset($_GET['city_parentid']) ? intval($_GET['city_parentid']) : 0;
//确认删除
$confirm = (isset($_POST['confirm'])) ? $_POST['confirm'] : 0;
//parameter 参数

martin_adminMenu( 5 , "订房后台 > 城市管理");

$hotelcity_handler =& xoops_getmodulehandler('hotelcity', MARTIN_DIRNAME,true);

if($id){
	$HotelCityObj = $hotelcity_handler->get($id);
}else{
	$HotelCityObj = $hotelcity_handler->create();
}
//var_dump($HotelCityObj);
//var_dump($hotelcity_handler);
//var_dump($hotelcity_handler->city_name());

switch($action)
{
	case "add":
		include MARTIN_ROOT_PATH.'include/form.hotel.city.php';
		martin_collapsableBar('createtable', 'createtableicon', "添加酒店城市", '添加酒店城市');
		//Create_button(array('addcity'=>array('url'=>'mconfirmartin.hotel.city.php?action=add','value'=>'添加城市')));	
		$form = & new form_hotel_city($HotelCityObj);
		
		$form->display();
		martin_close_collapsable('createtable', 'createtableicon');
		break;
	
	case "save":
		$city_alias = isset($_POST['city_alias']) ? addslashes($_POST['city_alias']) : '';
		$city_parentid = isset($_POST['city_parentid']) ? intval($_POST['city_parentid']) : 0;
		$city_alias = $city_parentid ? '' : $city_alias;
		$HotelCityObj->setVar('city_id',$id);
		$HotelCityObj->setVar('city_parentid', $city_parentid);
		$HotelCityObj->setVar('city_name', (isset($_POST['city_name'])) ? addslashes($_POST['city_name']) : '');
		$HotelCityObj->setVar('city_alias', $city_alias);
		$HotelCityObj->setVar('city_level',  0);

		if(!$id) $HotelCityObj->setNew();
		
		if ($HotelCityObj->isNew()) {
			$redirect_msg = '添加成功';
			$redirect_to = 'martin.hotel.city.php';
		} else {
			$redirect_msg = '修改成功';
			$redirect_to = 'martin.hotel.city.php';
		}
		if(!$hotelcity_handler->insert($HotelCityObj))
		{
			redirect_header('javascript:history.go(-1);', 2, '操作失败');
			exit();
		}
		redirect_header($redirect_to, 2, $redirect_msg);
		break;
	case "del":	
		if(!$confirm)
		{
			xoops_confirm(array('op' => 'del', 'id' => $HotelCityObj->city_id(), 'confirm' => 1, 'name' => $HotelCityObj->city_name()), '?action=del', "删除 '" . $HotelCityObj->city_name() . "'. <br /> <br /> 确定删除该城市下的行政区和商业区吗?", _DELETE);
		}else{
			if($hotelcity_handler->delete($HotelCityObj))
			{
				$redirect_msg = "删除成功";
				$redirect_to = "martin.hotel.city.php";
			}else{
				$redirect_msg = "删除失败";
				$redirect_to = "javascript:history.go(-1);";
			}
			redirect_header($redirect_to,2,$redirect_msg);
		}
		break;
	case "list":
		martin_collapsableBar('createtable', 'createtableicon', " 酒店城市列表", '酒店城市列表');
		Create_button(array('addcity'=>array('url'=>'martin.hotel.city.php?action=add','value'=>'添加城市')));	
		
		$HoteCityObjs = $hotelcity_handler->getHotelCitys($xoopsModuleConfig['perpage'], $start, 0);

		// Creating the objects for top categories
		echo "<br />\n<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
		echo "<tr>";
		echo "<td class='bg3' align='left'><b>城市名称</b></td>";
		echo "<td width='60' class='bg3' align='center'><b>操作</b></td>";
		echo "</tr>";
		$CityCout = $hotelcity_handler->getCount();
		if (count($HoteCityObjs) > 0) {
			foreach ( $HoteCityObjs as $key => $thiscat) {
				display($thiscat);
			}
		} else {
			echo "<tr>";
			echo "<td class='head' align='center' colspan= '7'>" . MARTIN_IS_NUll . "</td>";
			echo "</tr>";
			$categoryid = '0';
		}
		echo "</table>\n";
		include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
		$pagenav = new XoopsPageNav($CityCout, $xoopsModuleConfig['perpage'], 0, 'start');
		echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
		echo "<br />";
		martin_close_collapsable('createtable', 'createtableicon');
		echo "<br>";

		break;
	default:
		redirect_header( XOOPS_URL , 2, '非法访问.' ) ;
	break;
}

function display($HotelCityObj, $level = 0)
{
	global $xoopsModule, $hotelcity_handler;
	$modify = "<a href='?action=add&id=" . $HotelCityObj->city_id() ."&city_parentid=".$HotelCityObj->city_parentid(). "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif'/></a>";
	$delete = "<a href='?action=del&id=" . $HotelCityObj->city_id() ."&city_parentid=".$HotelCityObj->city_parentid(). "'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif'/></a>";

	$spaces = '';
	for ( $j = 0; $j < $level; $j++ ) {
		$spaces .= '&nbsp;&nbsp;&nbsp;';
	}
	echo "<tr>";
	echo "<td class='even' align='lefet'>" . $spaces . "<a href='?action=add&id=" . $HotelCityObj->city_id() . "'><img src='" . XOOPS_URL . "/modules/smartsection/images/icon/subcat.gif' alt='' />&nbsp;" . $HotelCityObj->city_name() . "</a></td>";
	echo "<td class='even' align='center'> $modify $delete </td>";
	echo "</tr>";
	$subObj = $hotelcity_handler->getHotelCitys(0, 0, $HotelCityObj->city_id());
	if (count($subObj) > 0) {
		$level++;
		foreach ( $subObj as $key => $thiscat ) {
			display($thiscat, $level);
		}
	}
	unset($HotelCityObj);
}

//底部
include "martin.footer.php";
?>
