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

$order_handler =& xoops_getmodulehandler('order', MARTIN_DIRNAME,true);

//hotel city
$hotelcity_handler =& xoops_getmodulehandler('hotelcity', MARTIN_DIRNAME,true);
$HotelCityObj = $hotelcity_handler->create();

//hotel
$hotel_handler =& xoops_getmodulehandler('hotel', MARTIN_DIRNAME,true);

if($id){
	$OrderObj = $order_handler->get($id);
}else{
	$OrderObj = $order_handler->create();
}

martin_adminMenu( 1 , "订房后台 > 订单");

switch($action)
{
	case "edit":
		include MARTIN_ROOT_PATH.'include/form.order.php';
		martin_collapsableBar('createtable', 'createtableicon', "酒店订单修改", '酒店订单修改');
		CreateButton();
		if(!$OrderObj->order_id())
		{
			redirect_header(XOOPS_URL,2,'非法访问.');
		}
		$form = & new form_order($OrderObj);
		
		$form->display();
		martin_close_collapsable('createtable', 'createtableicon');
		break;
	/*case "info":

		break;*/
	case "save":
		$OrderObj->setVar('order_id',$id);
		$OrderObj->setVar('order_status',intval($_POST['order_status']));
		$room_price = $_POST['room_price'];

		if(!$id) $OrderObj->setNew();
		
		if ($OrderObj->isNew()) {
			$redirect_msg = '添加成功';
			$redirect_to = 'martin.order.php';
		} else {
			$redirect_msg = '修改成功';
			$redirect_to = 'martin.order.php';
		}
		if(!$order_handler->updateOrder($OrderObj,$room_price))
		{
			redirect_header('javascript:history.go(-1);', 2, '操作失败');
			exit();
		}
		redirect_header($redirect_to, 2, $redirect_msg);
		break;
	case "del":
		if(!$confirm)
		{
			xoops_confirm(array('op' => 'del', 'id' => $id, 'confirm' => 1, 'name' => ''), '?action=del', "删除 订单'" . $id . "'. <br /> <br /> 确定删除该订单吗?", _DELETE);
		}else{
			if($order_handler->delete($OrderObj))
			{
				$redirect_msg = "删除成功";
				$redirect_to = "martin.order.php";
			}else{
				$redirect_msg = "删除失败";
				$redirect_to = "javascript:history.go(-1);";
			}
			redirect_header($redirect_to,2,$redirect_msg);
		}
		break;
	case "list":
		martin_collapsableBar('createtable', 'createtableicon', " 酒店订单列表", '酒店订单列表');
		CreateButton();	
		
		//searchData 
		$searchData = isset($_POST['s']) ? $_POST['s'] : null;
		$searchData = isset($_GET['s']) ? $_GET['s'] : $searchData;
		
		$hotel_name = isset($_GET['hotel_name']) ? $_GET['hotel_name'] : null;

		//分页
		$Count = $order_handler->getCount($searchData);
		
		include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
		
		$searchStr = '';
		$searchData = array_filter($searchData);
		if(is_array($searchData))
		{
			foreach($searchData as $key => $value)
			{
				$searchStr .= 's['.$key.']' . '=' . $value . '&amp;';
				${$key} = intval($value);
			}
		}

		$pagenav = new XoopsPageNav($Count, $xoopsModuleConfig['perpage'], $start, 'start',$searchStr);
		$pavStr = '<div style="text-align:left;">' . $pagenav->renderNav() . '</div>';
	
		//html 
		$htmlStar = getModuleArray('hotelrank','hotel_star');
		$htmlOrderType = getModuleArray('order_type','s[order_type]',false,($order_type));
		$htmlOrderMode = getModuleArray('order_mode','s[order_mode]',false,($order_mode));
		$htmlOrderPayMethod = getModuleArray('order_pay_method','s[order_pay_method]',false,($order_pay_method));
		$htmlOrderStatus = getModuleArray('order_status','s[order_status]',false,($order_status));
		//array
		$OrderType = getModuleArray('order_type','order_type',true);
		$OrderMode = getModuleArray('order_mode','order_mode',true);
		$OrderPayMethod = getModuleArray('order_pay_method','order_pay_method',true);
		$OrderStatus = getModuleArray('order_status','order_status',true);
		
		$selectedHotel = is_null($hotel_name) ? '' : "\n<option value='{$hotel_id}' selected='selected'>$hotel_name</option>";
		$htmlHotel = "<span id='hotel_name_div'><SELECT name='s[hotel_id]' onchange='hotel_select(this)'><option value='0'>----</option>$selectedHotel</SELECT></span><span id='hotel_name'></span>";
		$Status = array('<div style="background-color:#FF0000">编辑中</div>','<div style="background-color:#00FF00">已发布</div>');
		//$htmlStar = getModuleArray('hotelrank','hotel_star');
		
		$OrderObjs = $Count > 0 ? $order_handler->getOrders($searchData,$xoopsModuleConfig['perpage'], $start, 0) : null;
		// Creating the objects for top categories
		
		echo "$pavStr<table width='100%' cellspacing=1 cellpadding=9 border=0 class = outer>";
		echo "<tr><td class='bg3' align='left'>
			<form action='' id='orderSearch' method='get'>
			酒店区域:{$hotelcity_handler->getTree('hotel_city_id',$_GET['hotel_city_id'])}
			酒店星级:$htmlStar
			酒店名称:$htmlHotel
			预定方式:$htmlOrderType
			支付方式:$htmlOrderPayMethod
			订单类型:$htmlOrderMode
			订单状态:$htmlOrderStatus
			</td></tr><tr><td class='bg3' align='right'>
			<input type='submit' value='搜索'></td>
			</form></tr>";
		echo "</table>";
		echo "<table width='100%' cellspacing=1 cellpadding=14 border=0 class = outer>";
		echo "<td class='bg3' width=10 align='left'><b>ID</b></td>";
		echo "<td class='bg3' align='left'><b>预定方式</b></td>";
		echo "<td class='bg3' align='left'><b>订单模式</b></td>";
		echo "<td class='bg3' align='left'><b>支付方式</b></td>";
		echo "<td class='bg3' align='left'><b>状态</b></td>";
		echo "<td class='bg3' width=30 align='left'><b>总价</b></td>";
		echo "<td class='bg3' align='left'><b>支付金额</b></td>";
		echo "<td class='bg3' align='left'><b>使用现金券</b></td>";
		echo "<td class='bg3' align='left'><b>用户</b></td>";
		//echo "<td class='bg3' align='left'><b>姓名</b></td>";
		echo "<td class='bg3' align='left'><b>电话</b></td>";
		//echo "<td class='bg3' align='left'><b>订单修改时间</b></td>";
		//echo "<td class='bg3' align='left'><b>提交时间</b></td>";
		echo "<td class='bg3' align='center'><b>操作</b></td>";
		echo "</tr>";
		if ($Count > 0) {
			foreach($OrderObjs as $order)
			{
				$modify = "<a href='?action=edit&id=" . $order->order_id()  ."'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif'/></a>";
				$delete = "<a href='?action=del&id=" . $order->order_id()  ."'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif'/></a>";
				echo '<tr>';
				echo "<td class='even' align='left'>
					<a href='?action=edit&amp;id={$order->order_id()}' title='查看详情'>{$order->order_id()}</a></td>";
				echo "<td class='even' align='left'>{$OrderType[$order->order_type()]}</td>";
				echo "<td class='even' align='left'>{$OrderMode[$order->order_mode()]}</td>";
				echo "<td class='even' align='left'>{$OrderPayMethod[$order->order_pay_method()]}</td>";
				echo "<td class='even' align='left'>{$OrderStatus[$order->order_status()]}</td>";
				echo "<td class='even' align='left'>{$order->order_total_price()}</td>";
				echo "<td class='even' align='left'>{$order->order_pay_money()}</td>";
				echo "<td class='even' align='left'>{$order->order_coupon()}</td>";
				echo "<td class='even' align='left'>
					<a href='".XOOPS_URL."/userinfo.php?uid={$order->order_uid()}' title='查看用户信息' target='_blank'>{$order->uname()}</a>&nbsp;({$order->order_real_name()})</td>";
				//echo "<td class='even' align='left'>{$order->order_real_name()}</td>";
				echo "<td class='even' align='left'>{$order->order_phone()}<br>{$order->order_telephone()}</td>";
				//echo "<td class='even' align='left'>".date('Y-m-d H:i:s',$order->order_status_time())."</td>";
				//echo "<td class='even' align='left'>".date('Y-m-d H:i:s',$order->order_submit_time())."</td>";
				echo "<td class='even' align='center'> $modify $delete </td>";
				echo '</tr>';
			}
		} else {
			echo "<tr>";
			echo "<td class='head' align='center' colspan= '14'>" . MARTIN_IS_NUll . "</td>";
			echo "</tr>";
			$categoryid = '0';
		}
		echo "</table></form>\n";
		echo "$pavStr<br />";
		martin_close_collapsable('createtable', 'createtableicon');
		martin_order_list_js();
		echo "<br>";

		break;
	default:
		redirect_header( XOOPS_URL , 2, '非法访问.' ) ;
	break;
}


function CreateButton()
{
	Create_button(array(
					'servicetypelist'=>array('url'=>'martin.order.php?action=list','value'=>'酒店订单列表'),
				));
}


//底部
include "martin.footer.php";
?>
