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

class form_hotel_city extends XoopsThemeForm
{

	function form_hotel_city(&$HotelCityObj)
	{
		$this->Obj = & $HotelCityObj;
		$this->XoopsThemeForm( '酒店城市', "op", xoops_getenv('PHP_SELF') . "?action=save" );
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
		global $xoopsDB;
		$mytree = new XoopsTree( $xoopsDB -> prefix( "martin_hotel_city" ), "city_id", "city_parentid" );
		// Parent Category
		ob_start();
		$mytree -> makeMySelBox( "city_name", "", $this->Obj->city_parentid(), 1, 'city_parentid' );
		//makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
		$this -> addElement( new XoopsFormLabel("父类", ob_get_contents() ) );
		ob_end_clean();
		// City Name
		$this->addElement( new XoopsFormText('名称', 'city_name', 50, 255, $this->Obj->city_name()), true);
		$this->addElement( new XoopsFormHidden( 'id', $this->Obj->city_id() ) );

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
		if ( !$this->Obj->city_id() ) {
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
