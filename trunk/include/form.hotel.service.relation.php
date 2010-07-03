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

class form_hotel_service_relation extends XoopsThemeForm
{

	function form_hotel_service_relation(&$Relation,&$serviceList,&$hotelList)
	{
		$this->Obj = & $Relation;
		$this->serviceList = & $serviceList;
		$this->hotelList = & $hotelList;
		$this->XoopsThemeForm( '酒店酒店关联', "op", xoops_getenv('PHP_SELF') . "?action=hotelsave&hotel_id={$Relation['hotel_id']}&service_id={$Relation['service_id']}" );
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
		$Relation = & $this->Obj;

		$HotelElement = new XoopsFormSelect('酒店名称', 'hotel_id', $Relation['hotel_id'] , 1 );
		$HotelElement->addOptionArray($this->hotelList);
		$this->addElement($HotelElement , true);

		$ServiceElement = new XoopsFormSelect('服务名称', 'service_id', $Relation['service_id'] , 1 );
		$ServiceElement->addOptionArray($this->serviceList);
		$this->addElement($ServiceElement , true);

		$this->addElement( new XoopsFormText('服务价格', 'service_extra_price', 11, 11, $Relation['service_extra_price']), true);
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
		if ( !$this->Obj ) {
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
