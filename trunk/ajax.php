<?php
include '../../mainfile.php';
include XOOPS_ROOT_PATH.'/modules/martin/include/common.php';
if(!defined('MODULE_URL')) define('MODULE_URL',XOOPS_URL . '/modules/martin/');

$hotel_handler =& xoops_getmodulehandler("hotel", 'martin');
$room_handler =& xoops_getmodulehandler("room", 'martin');
$service_handler =& xoops_getmodulehandler("hotelservice", 'martin');
$promotion_handler =& xoops_getmodulehandler("hotelpromotion", 'martin');

$action = isset($_GET['action']) ? trim(strtolower($_GET['action'])) : null;
$action = isset($_POST['action']) ? trim(strtolower($_POST['action'])) : $action;

/**
 * ajax 
 * @access public
 * @return void
 * @copyright 1997-2010 The Martin Group
 * @author Martin <china.codehome@gmail.com> 
 * @created time :2010-07-03 15:30:35
 * */
switch($action)
{
	case "saveuser":
		global $xoopsUser;
		$document = isset($_POST['document']) ? intval($_POST['document']) : 0;
		$document_value = isset($_POST['document_value']) ? trim($_POST['document_value']) : '';
		$name = isset($_POST['name']) ? trim($_POST['name']) : '';
		$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
		$telephone = isset($_POST['telephone']) ? trim($_POST['telephone']) : '';
        $member_handler =& xoops_gethandler('member');
        $edituser =& $member_handler->getUser($xoopsUser->uid());
		$edituser->setVar('name',$name);
		$edituser->setVar('document',$document);
		$edituser->setVar('document_value',$document_value);
		$edituser->setVar('phone',$phone);
		$edituser->setVar('telephone',$telephone);        
		if (!$member_handler->insertUser($edituser)) {
            echo _US_PROFUPDATED;
        }
		break;
	default:
		redirect_header(XOOPS_URL,2,'非法闯入.');	
	break;
}
exit();
