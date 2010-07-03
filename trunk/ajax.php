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


	default:
		redirect_header(XOOPS_URL,2,'非法闯入.');	
	break;
}
