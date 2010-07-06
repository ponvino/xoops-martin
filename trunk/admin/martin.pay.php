<?php
include "header.php";
/*
 * 处理
 **/

//头部
include "martin.header.php";

martin_adminMenu( 0 , "订房后台 > 支付方式配置");
global $xoopsModuleConfig;
$line_pays = getModuleArray('line_pays','line_pays',true);
$online_pays = getModuleArray('online_pays','online_pays',true);

$action = isset($_POST['action']) ? $_POST['action'] : @$_GET['action'];
$action = empty($action) ? 'list' : $action;
$key = isset($_POST['key']) ? $_POST['key'] : @$_GET['key'];

$config_path = MARTIN_ROOT_PATH . "pay/$key/config/";
$ini_file = $config_path."ini.php";
$config_file = $config_path."config.php";

switch($action)
{
	case "list":
		martin_collapsableBar('createtable', 'createtableicon', "支付方式", '');
		if(is_array($online_pays))
		{
			echo "<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
			echo "<tr>";
			echo "<td class='bg3' align='left'><b>支付key</b></td>";
			echo "<td class='bg3' align='left'><b>支付名称</b></td>";
			echo "<td width='60' class='bg3' align='center'><b>操作</b></td>";
			echo "</tr>";
			foreach($online_pays as $key => $value)
			{
				$modify = "<a href='?action=modify&amp;key=" . $key ."'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/edit.gif'/></a>";
				echo "<tr><td class='even' align='left'>". $key  . "</td>";
				echo "<td class='even' align='left'>".$value . "</td>";
				echo "<td class='even' align='center'> $modify $delete </td></tr>";
			}
			echo "</table>\n";
		}
		martin_close_collapsable('createtable', 'createtableicon');
	break;
	case "modify":
		$alipay = array();
		if(file_exists($config_file) && is_readable($config_file))
		{
			include $config_file;
		}else if(file_exists($ini_file)){
			include $ini_file;
		}else{
			redirect_header( 'javascript:history.go(-1);' , 2, '暂时还没有此支付方式.' ) ;
		}
		martin_collapsableBar('createtable', 'createtableicon', "支付方式配置", '');
			echo "<form name='op' id='op' action='?action=save' method='post' onsubmit='return xoopsFormValidate_op();' enctype='multipart/form-data'><table width='100%' class='outer' cellspacing='1'><tbody><tr><th colspan='2'>酒店服务</th></tr>";
			foreach($$key as $k => $value)
			{
				echo "<tr valign='top' align='left'><td class='head'>$k</td><td class='even'><input type='text' name='config[$k]' size='45' value='$value' /></td></tr>";
			}
			echo "<tr valign='top' align='left'><td class='head'></td><td class='even'><input type='submit' class='formButton' name=''  id='' value='修改' onclick=\"this.form.elements.op.value='addcategory'\" /><input type='reset' class='formButton' name=''  id='' value='清空' /><input type='button' class='formButton' name=''  id='' value='cancel' onclick='history.go(-1)' /></td></tr></tbody></table><input type='hidden' name='key' id='key' value='$key' /></form></div>";
		martin_close_collapsable('createtable', 'createtableicon');

	break;
	case "save":
		$config = ($_POST['config']);
		$fileStr = "<?php \n";
		foreach($config as $k => $v)
		{
			$fileStr .= '$'.$key."['$k'] = '$v';\n";
		}
		$fileStr .= "?>";
		//var_dump($fileStr);exit;
		if(!is_writable($config_path))
		{
				xoops_error($config_path.' 不可写，请设置成777权限.');exit();
		}
		if(!file_exists($config_file))
		{
			$config_handler = fopen($config_file,'w+');
			fclose($config_handler);
			file_put_contents($config_file,$fileStr);
			chmod($config_file,0777);
		}else if(file_exists($config_file) && is_writable($config_file)){
			file_put_contents($config_file,$fileStr);
			chmod($config_file,0777);
			//file_put_contents($config_file,$fileStr);
		}
		redirect_header('martin.pay.php',2,'修改成功.');
	break;
	default:
		redirect_header( XOOPS_URL , 2, '非法访问.' ) ;
	break;
}



include "martin.footer.php";
