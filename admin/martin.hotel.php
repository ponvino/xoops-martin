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
$hotel_city_id = isset($_GET['hotel_city_id']) ? intval($_GET['hotel_city_id']) : 0;

$searchData = array('hotel_city_id'=>intval($_GET['hotel_city_id']),
					'hotel_star'=>intval($_GET['hotel_star']),'hotel_name'=>trim($_GET['hotel_name']));
//确认删除
$confirm = (isset($_POST['confirm'])) ? $_POST['confirm'] : 0;
//parameter 参数

//模块配置
$Ranks = GetRanks($xoopsModuleConfig);
//酒店
$hotel_handler =& xoops_getmodulehandler('hotel', MARTIN_DIRNAME,true);
//城市
$hotelcity_handler =& xoops_getmodulehandler('hotelcity', MARTIN_DIRNAME,true);
//是否存在
if($id > 0 && !$hotel_handler->CheckExist($id)) redirect_header(XOOPS_URL,3,'非法访问');
$HotelCityObj = $hotelcity_handler->create();
//酒店
$HotelObj = $id > 0 ? $hotel_handler->get($id) : $hotel_handler->create();



$TmpFilePath = '../images/hotel/tmp/';
$FilePath = '../images/hotel/';
$FileType = array('.jpg','.bmp','.png','.gif','.jpeg');

martin_adminMenu( 2 , "订房后台 > 酒店管理");

switch($action){
	case "add":
		include MARTIN_ROOT_PATH.'include/form.hotel.php';
		martin_collapsableBar('createtable', 'createtableicon', "添加酒店", '添加酒店');
		//Create_button(array('addcity'=>array('url'=>'mconfirmartin.hotel.city.php?action=add','value'=>'添加城市')));	
		$form = & new form_hotel($HotelObj,$HotelCityObj);
		
		$form->display();
		martin_close_collapsable('createtable', 'createtableicon');	
		break;
	case "save":
		$alias_url = str_replace(' ','-',addslashes($_POST['hotel_alias']));
		$alias_url = ($hotel_handler->CheckAliasExist($alias_url,$id)) ? $alias_url . '-' . rand(10000,100000) : $alias_url ;
		
		$hotel_city_id = implode(',',$_POST['hotel_city_id']);

		$HotelObj->setVar('hotel_id',$id);
		$HotelObj->setVar('hotel_city',(isset($_POST['hotel_city'])) ? intval($_POST['hotel_city']) : 0);
		$HotelObj->setVar('hotel_city_id', $hotel_city_id);
		$HotelObj->setVar('hotel_environment',(isset($_POST['hotel_environment'])) ? addslashes($_POST['hotel_environment']) : '');
		$HotelObj->setVar('hotel_rank',(isset($_POST['hotel_rank'])) ? intval($_POST['hotel_rank']) : 0);
		$HotelObj->setVar('hotel_name',(isset($_POST['hotel_name'])) ? addslashes($_POST['hotel_name']) : '');
		$HotelObj->setVar('hotel_enname',(isset($_POST['hotel_enname'])) ? addslashes($_POST['hotel_enname']) : '');
		$HotelObj->setVar('hotel_alias',(isset($_POST['hotel_alias'])) ? $alias_url : '');
		$HotelObj->setVar('hotel_keywords',(isset($_POST['hotel_keywords'])) ? addslashes($_POST['hotel_keywords']) : '');
		$HotelObj->setVar('hotel_tags',(isset($_POST['hotel_tags'])) ? addslashes($_POST['hotel_tags']) : '');
		$HotelObj->setVar('hotel_description',(isset($_POST['hotel_description'])) ? addslashes($_POST['hotel_description']) : '');
		$HotelObj->setVar('hotel_star',(isset($_POST['hotel_star'])) ? intval($_POST['hotel_star']) : 0);
		$HotelObj->setVar('hotel_address',(isset($_POST['hotel_address'])) ? addslashes($_POST['hotel_address']) : '');
		$HotelObj->setVar('hotel_telephone',(isset($_POST['hotel_telephone'])) ? addslashes($_POST['hotel_telephone']) : '');
		$HotelObj->setVar('hotel_fax',(isset($_POST['hotel_fax'])) ? addslashes($_POST['hotel_fax']) : '');
		$HotelObj->setVar('hotel_room_count',(isset($_POST['hotel_room_count'])) ? intval($_POST['hotel_room_count']) : 0);

		//file upload
		$hotel_icon = isset($_POST['hotel_icon_old']) ? $_POST['hotel_icon_old'] : null;
		
		include XOOPS_ROOT_PATH.'/class/uploader.php';
		
		if(!empty($_FILES['hotel_icon']['tmp_name']))
		{
			$path = MARTIN_ROOT_PATH.'/images/hotelicon/';
			$FileTypeUpload = array("image/jpg", "image/png", "image/gif", "image/jpeg");
			$uploader = new XoopsMediaUploader($path,$FileTypeUpload,2048*1024);
			if ( $uploader->fetchMedia( $_POST["xoops_upload_file"][0]) ) {
				$uploader->ext = strtolower(ltrim(strrchr($uploader->getMediaName(), '.'), '.'));
				$SaveFileName = time().rand(1000,10000).".".$uploader->ext;
				$uploader->setTargetFileName($SaveFileName);
				if ( !$uploader->upload() ){
					xoops_error($uploader->getErrors());exit();
				}elseif ( file_exists( $uploader->getSavedDestination() )){
					//delete images
					if(!empty($hotel_icon)) unlink(MARTIN_ROOT_PATH.'/images/hotelicon/'.$hotel_icon);
					$hotel_icon = $uploader->getSavedFileName();
				}
			}else{
				xoops_error($uploader->getErrors());
			}
		}

		//echo $hotel_icon;exit;
	
		$hotel_icon = empty($hotel_icon) ? 'hotel.jpg' : $hotel_icon;
		$HotelObj->setVar('hotel_icon',$hotel_icon);
		
		//得到图片
		$images = array();
		if(!empty($_POST['FileData']) && is_array($_POST['FileData']))
		{
			foreach($_POST['FileData'] as $key => $Value)
			{
				if($id>0 && file_exists($FilePath.$key))
				{
					$images[] = array( 'filename' => $key , 'alt' => $Value);
					continue;
				}
				foreach($FileType as $Prefix)
				{
					$TmpFileName = $TmpFilePath . $key . $Prefix;
					if(file_exists( $TmpFileName ))
					{
						$FileName = time() . rand(1000,10000) . $Prefix ;
						$images[] = array( 'filename' => $FileName , 'alt' => $Value);
						copy( $TmpFileName , $FilePath . $FileName );break;
					}
				}
			}
		}
		//clear dir
		deldir($TmpFilePath);
		
		$HotelObj->setVar('hotel_image',serialize($images));
		$HotelObj->setVar('hotel_google',serialize(array($_POST['GmapLatitude'],$_POST['GmapLongitude'])));
		$HotelObj->setVar('hotel_characteristic',(isset($_POST['hotel_characteristic'])) ? addslashes($_POST['hotel_characteristic']) : '');
		$HotelObj->setVar('hotel_reminded',(isset($_POST['hotel_reminded'])) ? addslashes($_POST['hotel_reminded']) : '');
		$HotelObj->setVar('hotel_facility',(isset($_POST['hotel_facility'])) ? addslashes($_POST['hotel_facility']) : '');
		$HotelObj->setVar('hotel_info',(isset($_POST['hotel_info'])) ? ($_POST['hotel_info']) : '');
		$HotelObj->setVar('hotel_status',(isset($_POST['hotel_status'])) ? intval($_POST['hotel_status']) : 0);
		//$HotelObj->setVar('hotel_open_time',strtotime(trim($_POST['hotel_open_time']['date'])) + intval(trim($_POST['hotel_open_time']['time'])) );
		$HotelObj->setVar('hotel_open_time',strtotime(trim($_POST['hotel_open_time'])));
		$HotelObj->setVar('hotel_add_time',time());
		
		//var_dump($HotelObj);
		//var_dump($_POST);
		if(!$id) $HotelObj->setNew();
		
		if ($HotelObj->isNew()) {
			$redirect_msg = '添加成功';
			$redirect_to = 'martin.hotel.php';
		} else {
			$redirect_msg = '修改成功';
			$redirect_to = 'martin.hotel.php';
		}
		if(!$hotel_handler->insert($HotelObj))
		{
			if($HotelObj->_errors) xoops_error($HotelObj->error);
			redirect_header('javascript:history.go(-1);', 2, '操作失败');
			exit();
		}
		
		$hotel_id = $HotelObj->getVar('hotel_id');
		$hotel_tags = $HotelObj->getVar('hotel_tags');
		// hotel tag
		if($hotel_id > 0 && !empty($hotel_tags) )
		{
			$hotel_handler->updateTags($HotelObj);
		}

		redirect_header($redirect_to, 2, $redirect_msg);
		break;
	/*case "upload":
			include MARTIN_ROOT_PATH . "admin/upload.php";
		break;
	case "showtmpimg":
			include MARTIN_ROOT_PATH . "admin/thumbnail.php";
		break;*/
	case "saverank":
		$RankData = $_POST['Ranks'];
		$savemsg = '保存失败';
		if($hotel_handler->saveRank($RankData))
		{
			$savemsg = '保存成功';
		}
		redirect_header('martin.hotel.php',2,$savemsg);
		break;
	case "deleteimg":
		$HotelImgPath = MARTIN_ROOT_PATH .'images/hotel/';
		$HotelImgName = isset($_POST['img']) ? $_POST['img'] : $_GET['img'];
		$FullImg = $HotelImgPath . $HotelImgName;
		if(file_exists($FullImg) && is_writable($FullImg))
		{
			unlink($FullImg);
		}
		break;
	case "del":
		if(!$confirm)
		{
			xoops_confirm(array('op' => 'del', 'id' => $id, 'confirm' => 1, 'name' => $HotelObj->hotel_name()), '?action=del', "删除 '" . $HotelObj->hotel_name() . "'. <br /> <br /> 确定删除该酒店,已经酒店下的客房吗?", _DELETE);
		}else{
			if($hotel_handler->delete($HotelObj))
			{
				$redirect_msg = "删除成功";
				$redirect_to = "martin.hotel.php";
			}else{
				$redirect_msg = "删除失败";
				$redirect_to = "javascript:history.go(-1);";
			}
			redirect_header($redirect_to,2,$redirect_msg);
		}

		break;
	case "list":
		martin_collapsableBar('createtable', 'createtableicon', " 酒店列表", '酒店列表');
		Create_button(array('addhotel'=>array('url'=>'martin.hotel.php?action=add','value'=>'添加酒店')));	
		
		$HotelObjs = $hotel_handler->getHotelList($searchData,$xoopsModuleConfig['perpage'], $start);
		//print_r($hotel_handler->hotel_ids);
		$hotelRooms = $hotel_handler->GethotelRooms();

		//分页
		$HotelCout = $hotel_handler->getCount($searchData);
		include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
		$pagenav = new XoopsPageNav($HotelCout, $xoopsModuleConfig['perpage'], $start, 'start',"hotel_city_id={$searchData['hotel_city_id']}&hotel_star={$searchData['hotel_star']}&hotel_name={$searchData['hotel_name']}&start");
		$pavStr = '<div style="text-align:left;">' . $pagenav->renderNav() . '</div>';
		
		$StarStr = "<option value='0'>----</option>";
		foreach($Ranks as $key => $Rank)
		{
			$selected = $key == $_GET['hotel_star'] ? ' selected' : '';
			$StarStr .= "<option value='$key' $selected>$Rank</option>";
		}
		// Creating the objects for top categories
		echo "$pavStr<table width='100%' cellspacing=1 cellpadding=9 border=0 class = outer>";
		echo "<tr><td class='bg3' align='right'>
			<form action='' method='get'>
			酒店区域:{$hotelcity_handler->getTree('hotel_city_id',$_GET['hotel_city_id'])}
			酒店星级:<select name='hotel_star'>$StarStr</select>
			酒店名称:<input type='text' name='hotel_name' value='{$_GET['hotel_name']}'>
			<input type='submit' value='搜索'>
			</form>
			</td></tr>";
		echo "</table>";
		echo "<form action='martin.hotel.php?action=saverank' method='post'><div align='right'><input type='submit' value='保存排名'.</div><table width='100%' cellspacing=1 cellpadding=9 border=0 class = outer>";
		echo "<td class='bg3' align='left'><b>酒店名称</b></td>";
		echo "<td class='bg3' align='left'><b>房型</b></td>";
		echo "<td class='bg3' align='left'><b>酒店区域</b></td>";
		echo "<td class='bg3' align='left'><b>酒店星级</b></td>";
		echo "<td class='bg3' align='left'><b>酒店电话</b></td>";
		echo "<td class='bg3' align='left'><b>酒店传真</b></td>";
		echo "<td class='bg3' align='left'><b>房间数量</b></td>";
		echo "<td class='bg3' align='left'><b>酒店状态</b></td>";
		echo "<td class='bg3' align='left'><b>酒店排序</b><br></td>";
		echo "<td width='150' class='bg3' align='center'><b>操作</b></td>";
		echo "</tr>";
		$Status = array('<div style="background-color:#FF0000">编辑中</div>','<div style="background-color:#00FF00">已发布</div>');
		if ($HotelCout > 0) {
			foreach($HotelObjs as $hotel)
			{
				$add = "<a href='martin.hotel.service.php?action=addhotel&hotel_id=" . $hotel['hotel_id'] ."' title='添加酒店服务'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/add.jpg'/></a>";
				$addroom = "<a href='martin.room.php?action=add&hotel_id=" . $hotel['hotel_id'] ."' title='添加酒店客房'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/addroom.jpg'/></a>";
				$modify = "<a href='?action=add&id=" . $hotel['hotel_id'] ."'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif'/></a>";
				$delete = "<a href='?action=del&id=" . $hotel['hotel_id']  ."'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/delete.gif'/></a>";
				$hotel_url = XOOPS_URL . '/hotel-' . $hotel['hotel_alias'] . $xoopsModuleConfig['hotel_static_prefix'] ;
				echo '<tr>';
				echo "<td class='even' align='lefet'><a href='$hotel_url'>{$hotel['hotel_name']}</a></td>";
				echo "<td class='even' align='lefet'><a href='martin.room.php?action=add&amp;hotel_id={$hotel['hotel_id']}'><img src='../images/icon/add_btn_icon.gif' title='新增房型'></a></td>";
				echo "<td class='even' align='lefet'>{$hotel['city_name']}</td>";
				echo "<td class='even' align='lefet'>{$Ranks[$hotel['hotel_star']]}</td>";
				echo "<td class='even' align='lefet'>{$hotel['hotel_telephone']}</td>";
				echo "<td class='even' align='lefet'>{$hotel['hotel_fax']}</td>";
				echo "<td class='even' align='lefet'>{$hotel['hotel_room_count']}</td>";
				echo "<td class='even' align='lefet'>{$Status[$hotel['hotel_status']]}</td>";
				echo "<td class='even' align='lefet'><input type='text' name='Ranks[{$hotel['hotel_id']}]' size=5 value='{$hotel['hotel_rank']}'></td>";
				echo "<td class='even' align='center'> $addroom &nbsp; $add &nbsp; $modify &nbsp; $delete </td>";
				echo '</tr>';
				$rooms = isset($hotelRooms[$hotel['hotel_id']]) ? $hotelRooms[$hotel['hotel_id']] : null;
				if(is_array($rooms))
				{
					foreach($rooms as $room)
					{
						echo '<tr>';
						echo "<td class='even' align='lefet'></td>";
						echo "<td class='even' align='lefet'><a href='martin.room.php?action=add&amp;id={$room['room_id']}'>{$room['room_type_info']}</a></td>";
						echo "<td class='even' align='lefet'>面积:{$room['room_area']}</td>";
						echo "<td class='even' align='lefet'>楼层:{$room['room_floor']}</td>";
						echo "<td class='even' align='lefet'>{$Status[$room['room_status']]}</td>";
						echo "<td class='even' align='lefet'><a href='martin.room.php?action=addprice&amp;room_id={$room['room_id']}'>价格管理</a></td>";
						echo "<td class='even' align='lefet'></td>";
						echo "<td class='even' align='lefet'></td>";
						echo "<td class='even' align='lefet'></td>";
						echo "<td class='even' align='center'></td>";
						echo '</tr>';
					}
				}
			}
		} else {
			echo "<tr>";
			echo "<td class='head' align='center' colspan= '9'>" . MARTIN_IS_NUll . "</td>";
			echo "</tr>";
			$categoryid = '0';
		}
		echo "</table></form>\n";
		echo "$pavStr<br />";
		martin_close_collapsable('createtable', 'createtableicon');
		echo "<br>";

		break;

		martin_close_collapsable('createtable', 'createtableicon');
		echo "<br>";
		break;
	default:
		redirect_header( XOOPS_URL , 2, '非法访问.' ) ;
	break;
}

//底部
include "martin.footer.php";
?>
