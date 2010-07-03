<?php
/**
 * @城市表单
 * @license http://www.blags.org/
 * @created:2010年05月20日 23时52分
 * @copyright 1997-2010 The Martin Group
 * @author Martin <china.codehome@gmail.com> 
 * */
if ( !defined( 'XOOPS_ROOT_PATH' ) )	return;

include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

class form_hotel_promotion extends XoopsThemeForm
{

	function form_hotel_promotion(&$HotelPromotionObj,&$HotelList)
	{
		$this->Obj = & $HotelPromotionObj;
		$this->HotelList = & $HotelList;
		$this->XoopsThemeForm( '促销', "op", xoops_getenv('PHP_SELF') . "?action=save" );
		$this->setExtra('enctype="multipart/form-data"');

		$this->createElements();
		$this->createButtons();	
	}

	/**
	 * created elements
	 * @license http://www.blags.org/
	 * @created:2010年05月21日 20时40分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function createElements()
	{
		//include_once MARTIN_ROOT_PATH . '/include/formdatetime.php';
		include_once XOOPS_ROOT_PATH."/modules/martin/class/xoopsformloader.php";
		
		$js = '<script type=\'text/javascript\'>
			jQuery.noConflict(); 
			</script>';
		$RoomElement = new XoopsFormSelect($js.'酒店', 'hotel_id', $this->HotelList , 0 , false );
		$RoomElement->addOptionArray($this->HotelList);
		$this->addElement($RoomElement,false);
		
		$this->addElement( new XoopsFormTextDateSelect("开始时间", 'promotion_start_date', $size = 15, $this->Obj->promotion_start_date(),false ) ,true);
		
		$this->addElement( new XoopsFormTextDateSelect("结束时间", 'promotion_end_date', $size = 15, $this->Obj->promotion_end_date(),false ) ,true);

		$editor = 'tinymce';
		$editor_configs = array();
		$editor_configs["name"] ="promotion_description";
		$editor_configs["value"] = $this->Obj->promotion_description();
		$editor_configs["rows"] = empty($xoopsModuleConfig["editor_rows"])? 35 : $xoopsModuleConfig["editor_rows"];
		$editor_configs["cols"] = empty($xoopsModuleConfig["editor_cols"])? 60 : $xoopsModuleConfig["editor_cols"];
		$editor_configs["width"] = empty($xoopsModuleConfig["editor_width"])? "100%" : $xoopsModuleConfig["editor_width"];
		$editor_configs["height"] = empty($xoopsModuleConfig["editor_height"])? "400px" : $xoopsModuleConfig["editor_height"];
		$this->addElement(new XoopsFormEditor("促销详细信息", $editor, $editor_configs, false, $onfailure = null) , false);
		
		$this->addElement( new XoopsFormHidden( 'id', $this->Obj->promotion_id() ) );
	}
	
	/**
	 * @创建按钮
	 * @license http://www.blags.org/
	 * @created:2010年05月20日 23时52分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function createButtons() {
		$button_tray = new XoopsFormElementTray('', '');	
		// No ID for category -- then it's new category, button says 'Create'
		if ( !$this->Obj->promotion_id() ) {
			$butt_create = new XoopsFormButton('', '', '提交', 'submit');
			$butt_create->setExtra('onclick="this.form.elements.op.value=\'addcategory\'"');
			$button_tray->addElement($butt_create);
			
			$butt_clear = new XoopsFormButton('', '', '清空', 'reset');
			$button_tray->addElement($butt_clear);
			
			$butt_cancel = new XoopsFormButton('', '', 'cancel', 'button');
			$butt_cancel->setExtra('onclick="history.go(-1)"');
			$button_tray->addElement($butt_cancel);
			
			$this->addElement($button_tray);
		} else {
			// button says 'Update'
			$butt_create = new XoopsFormButton('', '', '修改', 'submit');
			$butt_create->setExtra('onclick="this.form.elements.op.value=\'addcategory\'"');
			$button_tray->addElement($butt_create);

			$butt_clear = new XoopsFormButton('', '', '清空', 'reset');
			$button_tray->addElement($butt_clear);
			
			$butt_cancel = new XoopsFormButton('', '', 'cancel', 'button');
			$butt_cancel->setExtra('onclick="history.go(-1)"');
			$button_tray->addElement($butt_cancel);
	
			$this->addElement($button_tray);
		}
	}
}
?>
