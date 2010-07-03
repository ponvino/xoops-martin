<?php
/**
 * @酒店表单
 * @license http://www.blags.org/
 * @created:2010年05月20日 23时52分
 * @copyright 1997-2010 The Martin Group
 * @author Martin <china.codehome@gmail.com> 
 * */
if ( !defined( 'XOOPS_ROOT_PATH' ) )	return;

include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

class form_order extends XoopsThemeForm
{

	function form_order(&$OrderObj)
	{
		//array
		$this->OrderType = getModuleArray('order_type','order_type',true);
		$this->OrderMode = getModuleArray('order_mode','order_mode',true);
		$this->OrderPayMethod = getModuleArray('order_pay_method','order_pay_method',true);
		$this->OrderStatus = getModuleArray('order_status','order_status',true);
		$this->OrderDocumentType = getModuleArray('order_document_type','order_document_type',true);
		
		//print_r($this->OrderStatus);exit;

		$this->Obj = & $OrderObj;
		$this->XoopsThemeForm( '订单信息', "op", xoops_getenv('PHP_SELF') . "?action=save" );
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
		//var_dump($this->Obj);exit;
		//编辑器
		include_once XOOPS_ROOT_PATH."/modules/martin/class/xoopsformloader.php";
		include_once MARTIN_ROOT_PATH . '/include/formdatetime.php';
		
		$Order = new XoopsFormElementTray('订单ID');
		$Order->addElement(new XoopsFormElementTray($this->Obj->order_id()));
		$this->addElement($Order,false);

		$order_type = new XoopsFormElementTray('预定方式');
		$orderIniType = $this->Obj->order_type();
		$order_type->addElement(new XoopsFormElementTray($this->OrderType[$orderIniType]));
		$this->addElement($order_type,false);
		
		$order_status = new XoopsFormElementTray('订单状态');
		$StatusElement = new XoopsFormSelect('当前状态:'.$this->OrderStatus[$this->Obj->order_status()].'<br>', 
					'order_status', $this->Obj->order_status() , 1 );
		$StatusElement->addOptionArray($this->OrderStatus);
		$order_status->addElement($StatusElement,false);
		
		if($qrooms = $this->Obj->qrooms )
		{
			$orderqrooms = '<br><br>';
			foreach($qrooms as $room)
			{
				$orderqrooms .=  $orderqroomsPrefix .
				'酒店名称 : <a href="martin.hotel.php?action=add&id='.$room['hotel_id'].'">' . $room['hotel_name'] . '</a> &nbsp;';
				$orderqrooms .= '房间名称 : <a href="martin.room.php?action=add&id='.$room['room_id'].'">' . $room['room_name'] . '</a> &nbsp;';
				$orderqrooms .= '房间数量 : <b>' . $room['room_count'] . '</b>&nbsp;';
				$orderqrooms .= '订房时间 : <b>' . date('Y-m-d',$room['room_date']) . '</b>&nbsp;';
				$orderqrooms .= '价格设定 : <input type="text" name="room_price['.$room['room_id'].'-'.$room['room_date'].']" value= ' . $room['room_price'] . '>&nbsp;';
				$orderqroomsPrefix = '<br><br>';
			}
		}
		
		//$orderqrooms .= '<br><br><input type="button" value="保存价钱">';
		
		$order_status->addElement(new XoopsFormElementTray($orderqrooms));
		
		$this->addElement($order_status,false);
		
		$order_pay_method = new XoopsFormElementTray('订单支付方式');
		$order_pay_method->addElement(new XoopsFormElementTray($this->OrderPayMethod[$this->Obj->order_pay_method()]));
		$this->addElement($order_pay_method,false);
		
		$order_total_price = new XoopsFormElementTray('订单总金额');
		$order_total_price->addElement(new XoopsFormElementTray($this->Obj->order_total_price()));
		$this->addElement($order_total_price,false);
		
		$order_pay_money = new XoopsFormElementTray('实际支付金额');
		$order_pay_money->addElement(new XoopsFormElementTray($this->Obj->order_pay_money()));
		$this->addElement($order_pay_money,false);
		
		$order_coupon = new XoopsFormElementTray('现金卷支付金额');
		$order_coupon->addElement(new XoopsFormElementTray($this->Obj->order_coupon()));
		$this->addElement($order_coupon,false);
		
		$order_sented_coupon = new XoopsFormElementTray('赠送金额');
		$order_sented_coupon->addElement(new XoopsFormElementTray($this->Obj->order_sented_coupon()));
		$this->addElement($order_sented_coupon,false);
		
		$order_mode = new XoopsFormElementTray('订单模式');
		$order_mode->addElement(new XoopsFormElementTray('<a href='.XOOPS_URL.'/userinfo.php?uid='.$this->Obj->order_uid().'>'.$this->Obj->uname().'</a>'));
		$this->addElement($order_mode,false);
		
		$order_real_name = new XoopsFormElementTray('真实姓名');
		$order_real_name->addElement(new XoopsFormElementTray($this->Obj->order_real_name()));
		$this->addElement($order_real_name,false);
		
		$order_document_type = new XoopsFormElementTray('证件类型');
		$order_document_type->addElement(new XoopsFormElementTray($this->OrderDocumentType[$this->Obj->order_document_type()]));
		$this->addElement($order_document_type,false);
		
		$order_document = new XoopsFormElementTray('证件');
		$order_document->addElement(new XoopsFormElementTray($this->Obj->order_document()));
		$this->addElement($order_document,false);
		
		$order_phone = new XoopsFormElementTray('手机');
		$order_phone->addElement(new XoopsFormElementTray($this->Obj->order_phone()));
		$this->addElement($order_phone,false);
		
		$order_telephone = new XoopsFormElementTray('电话');
		$order_telephone->addElement(new XoopsFormElementTray($this->Obj->order_telephone()));
		$this->addElement($order_telephone,false);
		
		$extraPesons = $this->Obj->order_extra_persons();
		if(is_array($extraPesons))
		{
			var_dump($extraPesons);
		}
		$order_extra_persons = new XoopsFormElementTray('附带人员信息');
		$order_extra_persons->addElement(new XoopsFormElementTray($$extraPesons));
		$this->addElement($order_extra_persons,false);
		
		$order_note = new XoopsFormElementTray('订单备注');
		$order_note->addElement(new XoopsFormElementTray($this->Obj->order_note()));
		$this->addElement($order_note,false);
		
		$order_status_time = new XoopsFormElementTray('订单最后修改时间');
		$order_status_time->addElement(new XoopsFormElementTray(date('Y-m-d H:i:s',$this->Obj->order_status_time())));
		$this->addElement($order_status_time,false);
		
		$order_submit_time = new XoopsFormElementTray('订单提交时间');
		$order_submit_time->addElement(new XoopsFormElementTray(date('Y-m-d H:i;s',$this->Obj->order_submit_time())));
		$this->addElement($order_submit_time,false);
		
		$orderrooms = '';
		if($rooms = $this->Obj->rooms)
		{
			foreach($rooms as $room)
			{
				$orderrooms .=  $orderroomsPrefix .
				'酒店名称 : <a href="martin.hotel.php?action=add&id='.$room['hotel_id'].'">' . $room['hotel_name'] . '</a> &nbsp;';
				$orderrooms .= '房间名称 : <a href="martin.room.php?action=add&id='.$room['room_id'].'">' . $room['room_name'] . '</a> &nbsp;';
				$orderrooms .= '房间数量 : <b>' . $room['room_count'] . '</b>&nbsp;';
				$orderrooms .= '订房时间 : <b>' . date('Y-m-d',$room['room_date']) . '</b>&nbsp;';
				$orderrooms .= '价格 : <b>' . $room['room_price'] . '</b>&nbsp;';
				$orderroomsPrefix = '<br>';
			}
		}
		$order_rooms = new XoopsFormElementTray('订单房间信息');
		$order_rooms->addElement(new XoopsFormElementTray($orderrooms));
		$this->addElement($order_rooms,false);
		

		/*$this->addElement( new XoopsFormText('酒店排序', 'hotel_rank', 11, 11, $this->Obj->hotel_rank()), true);

		$this->addElement( new XoopsFormText('酒店名称', 'hotel_name', 50, 255, $this->Obj->hotel_name()), true);

		$this->addElement( new XoopsFormText('酒店英文名称', 'hotel_enname', 50, 255, $this->Obj->hotel_enname()), true);

		$this->addElement( new XoopsFormText('酒店别名', 'hotel_alias', 50, 255, $this->Obj->hotel_alias()), true);

		$this->addElement( new XoopsFormText('酒店关键字(SEO)', 'hotel_keywords', 50, 255, $this->Obj->hotel_keywords()), true);
		
		$this->addElement( new XoopsFormTextArea('酒店描述(SEO)', 'hotel_description', $this->Obj->hotel_description()) , true);
		
		//hotel star
		$rankElement = new XoopsFormSelect('酒店星级', 'hotel_star', $this->Obj->hotel_star() , 1 );
		$rankElement->addOptionArray($this->Ranks);
		$this->addElement($rankElement , true);

		$this->addElement( new XoopsFormText('酒店地址', 'hotel_address', 50, 255, $this->Obj->hotel_address()), true);
		
		$this->addElement( new XoopsFormText('酒店电话', 'hotel_telephone', 50, 255, $this->Obj->hotel_telephone()), true);
		
		$this->addElement( new XoopsFormText('酒店 FAX', 'hotel_fax', 50, 255, $this->Obj->hotel_fax()), true);
		
		$this->addElement( new XoopsFormText('酒店特色', 'hotel_characteristic', 50, 255, $this->Obj->hotel_characteristic()), true);
		
		$this->addElement( new XoopsFormText('酒店房间数', 'hotel_room_count', 11, 11, $this->Obj->hotel_room_count()), true);
		
		//$this->addElement( new XoopsFormText('酒店房图片', 'hotel_image', 50, 255, $this->Obj->hotel_image()), true);
		
		//特殊处理
		//酒店地图
		$Coordinate = $this->Obj->hotel_google();
		$google = new XoopsFormElementTray('google 地图'); 
		$google->addElement(new XoopsFormText('纬度', 'GmapLatitude', 25, 25, $Coordinate[0]), true);
		$google->addElement(new XoopsFormText('经度', 'GmapLongitude', 25, 25, $Coordinate[1]), true);
		$google->addElement(new XoopsFormLabel("<br><br><font style='background-color:#2F5376;color:#FFFFFF;padding:2px;vertical-align:middle;'>google map:</font><br>", $this->googleMap($Coordinate) ));
		
		//酒店图片
		$Img = new XoopsFormElementTray('酒店图片'); 
		$Img->addElement(new XoopsFormLabel("", $this->Swfupload() ));

		$this->addElement($Img);
		$this->addElement($google , true);
		//特殊处理
		
		//编辑器 酒店详细信息
		$this->addElement( new XoopsFormTextArea('酒店特别提醒', 'hotel_reminded', $this->Obj->hotel_reminded()) , true);
		$editor = 'tinymce';
		$hotel_info = $this->Obj->hotel_info();
		$editor_configs = array();
		$editor_configs["name"] ="hotel_info";
		$editor_configs["value"] = $hotel_info;
		$editor_configs["rows"] = empty($xoopsModuleConfig["editor_rows"])? 35 : $xoopsModuleConfig["editor_rows"];
		$editor_configs["cols"] = empty($xoopsModuleConfig["editor_cols"])? 60 : $xoopsModuleConfig["editor_cols"];
		$editor_configs["width"] = empty($xoopsModuleConfig["editor_width"])? "100%" : $xoopsModuleConfig["editor_width"];
		$editor_configs["height"] = empty($xoopsModuleConfig["editor_height"])? "400px" : $xoopsModuleConfig["editor_height"];

		$this->addElement(new XoopsFormEditor("酒店详细信息", $editor, $editor_configs, false, $onfailure = null) , false);
		//$this->addElement(new XoopsFormHidden("hotel_info", $hotel_info) , true );	
		
		$this->addElement( new XoopsFormRadioYN("酒店编辑状态", 'hotel_status', $this->Obj->hotel_status(), '已发布', '编辑中') , true);
		$this->addElement( new MartinFormDateTime("酒店发布时间", 'hotel_open_time', $size = 15, $this->Obj->hotel_open_time() ) ,true);*/
		
		$this->addElement( new XoopsFormHidden( 'id', $this->Obj->order_id() ));
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
		if ( !$this->Obj->order_id() ) {
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

	/**
	 * @google 地图
	 * @license http://www.blags.org/
	 * @created:2010年05月24日 19时55分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function googleMap($Coordinate)
	{

	}

	/**
	 * swf 多图片上传
	 * @license http://www.blags.org/
	 * @created:2010年05月24日 19时55分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function Swfupload()
	{	

	}
}
?>
