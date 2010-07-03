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

class form_hotel extends XoopsThemeForm
{

	function form_hotel(&$HotelObj,&$HotelCityObj)
	{
		global $Ranks;
		$this->Ranks = & $Ranks;
		$this->Obj = & $HotelObj;
		$this->CityObj = & $HotelCityObj;
		$this->XoopsThemeForm( '酒店信息', "op", xoops_getenv('PHP_SELF') . "?action=save" );
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
		global $xoopsDB,$xoopsModuleConfig;
		
		//编辑器
		include_once XOOPS_ROOT_PATH."/modules/martin/class/xoopsformloader.php";
		include_once MARTIN_ROOT_PATH . '/include/formdatetime.php';

		$this->google_api = $xoopsModuleConfig['google_api'];

		$mytree = new XoopsTree( $xoopsDB -> prefix( "martin_hotel_city" ), "city_id", "city_parentid" );
		// Parent Category
		ob_start();
		$mytree -> makeMySelBox( "city_name", "", $this->CityObj->city_parentid(), 1, 'hotel_city_id' );
		//makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
		$this -> addElement( new XoopsFormLabel("区域", ob_get_contents() ) );
		ob_end_clean();
		// City Name
		$this->addElement( new XoopsFormText('酒店排序', 'hotel_rank', 11, 11, $this->Obj->hotel_rank()), true);

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
		
		$this->addElement( new XoopsFormText('酒店 FAX', 'hotel_keywords', 50, 255, $this->Obj->hotel_keywords()), true);
		
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
		//$this->addElement($google , true);
		
		//酒店图片
		$Img = new XoopsFormElementTray('酒店图片'); 
		$Img->addElement(new XoopsFormLabel("", $this->Swfupload() ));

		$this->addElement($Img);
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

		//$this->addElement(new XoopsFormEditor("酒店详细信息", $editor, $editor_configs, false, $onfailure = null) , true);
		$this->addElement(new XoopsFormHidden("hotel_info", $hotel_info) , true );	
		
		$this->addElement( new XoopsFormRadioYN("酒店编辑状态", 'hotel_status', $this->Obj->hotel_status(), '已发布', '编辑中') , true);
		$this->addElement( new MartinFormDateTime("酒店发布时间", 'hotel_open_time', $size = 15, $this->Obj->hotel_open_time() ) ,true);
		
		$this->addElement( new XoopsFormHidden( 'hotel_id', $this->Obj->hotel_id() ));
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
		if ( !$this->CityObj->city_id() ) {
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
		$str = '<div id="gmap" style="width: 640px; height: 320px;"></div>';
		$str .= '<style type="text/css"> 
			@import url("http://www.google.com/uds/css/gsearch.css");
			@import url("http://www.google.com/uds/solutions/localsearch/gmlocalsearch.css");
			</style> 
		<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key='.$this->google_api.'" type="text/javascript"></script> 
		<script type="text/javascript"> 
		//<![CDATA[
		//得到坐标
		var lat = document.getElementById("GmapLatitude").value;
		lat = lat == "" ? 35.86166 : lat;
		var lng = document.getElementById("GmapLongitude").value;
		lng = lng == "" ? 104.195397 : lng;

		function initialize() {
		  if (GBrowserIsCompatible()) {
			var map = new GMap2(document.getElementById("gmap"),{ size: new GSize(800,400) } );
			map.setCenter(new GLatLng(lat,lng), 3);
			var customUI = map.getDefaultUI();
			// Remove MapType.G_HYBRID_MAP
			customUI.maptypes.hybrid = false;
			map.setUI(customUI);
			//搜索
			map.enableGoogleBar();
			GEvent.addListener(map,"click", function(overlay,data) {
				document.getElementById("GmapLatitude").value = data.lat();
				document.getElementById("GmapLongitude").value = data.lng();
			});

			//锚点
			//得到数据信息
			var hotel_name = ["'.$this->Obj->hotel_name().'"];
			var message = ["'.$this->Obj->hotel_description().'"];
			hotel_name = hotel_name == "" ? ["酒店名称"] : hotel_name;
			message = message == "" ? ["酒店描述"] : message;
			
			function createMarker(latlng, number) {
			  var marker = new GMarker(latlng);
			  marker.value = number;
			  //点击显示
			  GEvent.addListener(marker,"click", function() {
				var myHtml = "<b><font color=\"blue\">" + hotel_name[number] + "</font></b><br/>" + message[number];
				map.openInfoWindowHtml(latlng, myHtml);
			  });
			  return marker;
			}
		 
			/*var bounds = map.getBounds();
			var southWest = bounds.getSouthWest();
			var northEast = bounds.getNorthEast();
			var lngSpan = northEast.lng() - southWest.lng();
			var latSpan = northEast.lat() - southWest.lat();*/
			for (var i = 0; i < 1; i++) {
				var latlng = new GLatLng(lat,lng);
				map.addOverlay(createMarker(latlng, i));
			}
		  }
		}
		//window.onunload = GUnload();
		window.onload = function(){initialize();};
		//Event.observe(window, "load",initialize);
		google.setOnLoadCallback(initialize);
		//]]>
		</script>  ';
		return $str;
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
		session_start();
		$_SESSION["file_info"] = array();

		$hotel_image = $this->Obj->hotel_image();
		$swf = '
		<link href="../javascript/swfupload/css/default.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="../javascript/swfupload/swfupload.js"></script>
		<script type="text/javascript" src="../javascript/swfupload/swfupload.swfobject.js"></script>
		<script type="text/javascript" src="../javascript/swfupload/fileprogress.js"></script>
		<script type="text/javascript" src="../javascript/swfupload/handlers.js"></script>
		<script type="text/javascript">
		var swfu;
		SWFUpload.onload = function () {
			var settings = {
				flash_url : "../javascript/swfupload/swfupload.swf",
				flash9_url : "../javascript/swfupload/swfupload_fp9.swf",
				upload_url: "upload.php",
				post_params: {
					"PHPSESSID" : "'.session_id().'"
				},
				file_size_limit : "100 MB",
				file_types : "*.jpg;*.JPG;*.gif;*.GIF;*.jpeg;*.JPEG;*.png;*.PNG",
				file_types_description : "All Files",
				file_upload_limit : 0,
				//file_queue_limit : 0,
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel",
					showUpload 	   : "ShowTmp"
				},
				debug: true,

				// Button Settings
				button_image_url : "../javascript/swfupload/images/button.png",
				button_placeholder_id : "spanButtonPlaceholder",
				button_width: 61,
				button_height: 22,
				//button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,

				// The event handler functions are defined in handlers.js
				swfupload_loaded_handler : swfUploadLoaded,
				//file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : queueComplete,	// Queue plugin event
				
				// SWFObject settings
				minimum_flash_version : "9.0.28",
				swfupload_pre_load_handler : swfUploadPreLoad,
				swfupload_load_failed_handler : swfUploadLoadFailed
			};
			swfu = new SWFUpload(settings);
		}
		</script>			
				<div id="divSWFUploadUI">
					<div id="ShowTmp"></div>
					<div class="fieldset  flash" id="fsUploadProgress">
					<span class="legend">图片上传</span>
					</div>
					<p id="divStatus">0 Files Uploaded</p>
					<p>
						<span id="spanButtonPlaceholder"></span>
						<input id="btnCancel" type="button" value="Cancel All Uploads" disabled="disabled" style="margin-left: 2px; height: 22px; font-size: 8pt;" />
						<br />
					</p>
				</div>
				<noscript>
					<div style="background-color: #FFFF66; border-top: solid 4px 