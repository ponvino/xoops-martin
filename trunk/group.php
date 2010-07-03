<?php
include '../../mainfile.php';
include XOOPS_ROOT_PATH.'/modules/martin/include/common.php';

global $xoopsUser;

if(!$xoopsUser) redirect_header(XOOPS_URL.'/user.php',3,'您还没登录.');

$group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : $id;
$group_id = isset($_POST['group_id']) ? intval($_POST['group_id']) : $group_id;
if(!$group_id) redirect_header(XOOPS_URL,3,'非法访问.');

$group_handler =& xoops_getmodulehandler("group", 'martin');
$hotel_handler =& xoops_getmodulehandler("hotel", 'martin');
$group_obj = $group_handler->get($group_id);

//判断是否存在
if(!$group_obj->group_id()) redirect_header(XOOPS_URL,3,'非法访问.');
//是否结束
if($group_obj->apply_end_date() < time()) redirect_header(XOOPS_URL,3,'该团购已经结束.');

//参加团购
$uid = $xoopsUser->getVar('uid');
$action = isset($_GET['action']) ? trim($_GET['action']) : null;
$room_number = isset($_POST['room_number']) ? intval($_POST['room_number']) : 1;
if($action == 'save')
{
	$data = array('uid'=>$uid,'group_id'=>$group_id,'room_number'=>$room_number,'join_time'=>time());
	if($group_handler->CheckJoinExist($data)){
		redirect_header('javascript:history.go(-1);',2,'您已经参加过了.');
	}else if($group_handler->AddUserGroup($data))
	{
		redirect_header(XOOPS_URL.'/modules/martin/group.php/group-'.$group_id.$xoopsModuleConfig['hotel_static_prefix'],2,'提交成功.');
	}else{
		redirect_header('javascript:history.go(-1);',2,'提交失败.');
	}
	exit();
}

$group_data = array();
foreach($group_obj->vars as $key => $var)
{
	$group_data[$key] = $group_obj->$key();
}

$rooms = $group_handler->GetGroupRooms($group_id);

$xoopsOption["template_main"] = "martin_group.html";

include XOOPS_ROOT_PATH.'/header.php';
include XOOPS_ROOT_PATH.'/modules/martin/HotelSearchLeft.php';

$xoopsOption['xoops_pagetitle'] =   $group_obj->group_name(). ' - 团购 - '.$xoopsConfig['sitename'];

$xoopsTpl -> assign("xoops_pagetitle", $xoopsOption["xoops_pagetitle"]);
$xoopsTpl -> assign("module_url", XOOPS_URL . '/modules/martin/');
$xoopsTpl -> assign('group_id',$group_id);
$xoopsTpl -> assign('group',$group_data);
$xoopsTpl -> assign('rooms',$rooms);
$xoopsTpl -> assign('hotel_static_prefix',$xoopsModuleConfig['hotel_static_prefix']);

include XOOPS_ROOT_PATH.'/footer.php';
?>
