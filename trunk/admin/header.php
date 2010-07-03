<?php
include_once "../../../mainfile.php";

if (!defined("SMARTSECTION_NOCPFUNC")) {
	include_once '../../../include/cp_header.php';
}

include_once XOOPS_ROOT_PATH . "/class/xoopsmodule.php";
include_once XOOPS_ROOT_PATH . "/class/xoopstree.php";
include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

include XOOPS_ROOT_PATH.'/modules/martin/include/common.php';

$imagearray = array(
	'editimg' => "<img src='". MARTIN_IMAGES_URL ."/button_edit.png' alt='编辑' align='middle' />",
    'deleteimg' => "<img src='". MARTIN_IMAGES_URL ."/button_delete.png' alt='删除' align='middle' />",
    'online' => "<img src='". MARTIN_IMAGES_URL ."/on.png' alt='' align='正常' />",
    'offline' => "<img src='". MARTIN_IMAGES_URL ."/off.png' alt='' alt='禁用' />",
	);

$myts = &MyTextSanitizer::getInstance();
?>
