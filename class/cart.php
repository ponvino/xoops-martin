<?php
/**
 * @
 * @method:
 * @license http://www.blags.org/
 * @created:2010年07月03日 21时12分
 * @copyright 1997-2010 The Martin Group
 * @author Martin <china.codehome@gmail.com> 
 * */
class MartinCart extends XoopsObject
{

	function MartinCart()
	{
		$this->initVar("order_id", XOBJ_DTYPE_INT, null, false);
		$this->initVar("order_type", XOBJ_DTYPE_INT, null, false);
		$this->initVar("order_mode", XOBJ_DTYPE_INT, null, false);
		$this->initVar("order_uid", XOBJ_DTYPE_INT, null, false);
		$this->initVar("order_pay_method", XOBJ_DTYPE_INT, null, false);
		$this->initVar("order_status", XOBJ_DTYPE_INT, null, false);
		$this->initVar("order_total_price", XOBJ_DTYPE_INT, null, false);
		$this->initVar("order_pay_money", XOBJ_DTYPE_INT, null, false);
		$this->initVar("order_coupon", XOBJ_DTYPE_INT, null, false);
		$this->initVar("order_sented_coupon", XOBJ_DTYPE_INT, null, false);
		$this->initVar("order_real_name", XOBJ_DTYPE_TXTBOX, null, true, 45);
		$this->initVar("order_document_type", XOBJ_DTYPE_INT, null, false);
		$this->initVar("order_document", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("order_telephone", XOBJ_DTYPE_TXTBOX, null, true, 45);
		$this->initVar("order_phone", XOBJ_DTYPE_TXTBOX, null, true, 45);
		$this->initVar("order_extra_persons", XOBJ_DTYPE_TXTAREA, null, false);
		$this->initVar("order_note", XOBJ_DTYPE_TXTAREA, null, false);
		$this->initVar("order_status_time", XOBJ_DTYPE_INT, null, false);
		$this->initVar("order_submit_time", XOBJ_DTYPE_INT, null, false);
	}

}

/**
 * @martin cart handler
 * @method:
 * @license http://www.blags.org/
 * @created:2010年07月03日 21时12分
 * @copyright 1997-2010 The Martin Group
 * @author Martin <china.codehome@gmail.com> 
 * */
class MartinCartHandler extends XoopsObjectHandler
{
	
	/**
	 * @create cart object
	 * @license http://www.blags.org/
	 * @created:2010年07月04日 12时59分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function &create()
	{
		$obj =& new MartinCart;
		return $obj;
	}

	/**
	 * @save cart 
	 * @license http://www.blags.org/
	 * @created:2010年07月04日 12时59分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function saveCart($cart,$force = false)
	{
		if (strtolower(get_class($cart)) != 'martincart') {
			return false;
		}

		if (!$cart->cleanVars()) {
			return false;
		}

		foreach ($cart->cleanVars as $k => $v) {
			${$k} = $v;
		}

		$sql = sprintf("INSERT INTO %s (
							order_id,
							order_type,
							order_mode,
							order_uid,
							order_status,
							order_pay_method,
							order_total_price,
							order_pay_money,
							order_coupon,
							order_sented_coupon,
							order_real_name,
							order_document_type,
							order_document,
							order_telephone,
							order_phone,
							order_extra_persons,
							order_note,
							order_status_time,
							order_submit_time
						) VALUES (
							NULL,
							%u,
							%u,
							%u,
							%u,
							%u,
							%u,
							%u,
							%u,
							%u,
							%s,
							%u,
							%s,
							%s,
							%s,
							%s,
							%s,
							%u,
							%u
						)",
							$this->db->prefix('martin_order'),
							($order_type),
							($order_mode),
							($order_uid),
							($order_status),
							($order_pay_method),
							($order_total_price),
							($order_pay_money),
							($order_coupon),
							($order_sented_coupon),
							$this->db->quoteString($order_real_name),
							($order_document_type),
							$this->db->quoteString($order_document),
							$this->db->quoteString($order_telephone),
							$this->db->quoteString($order_phone),
							$this->db->quoteString($order_extra_persons),
							$this->db->quoteString($order_note),
							($order_status_time),
							$order_submit_time
							);
		//echo $sql;exit;
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}

		return $this->db->getInsertId();
	}

	/**
	 * @Insert order room
	 * @license http://www.blags.org/
	 * @created:2010年07月04日 12时59分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function InsertOrderRoom($order_id,$room_id , $room_date_count)
	{
		global $xoopsDB;
		$result = true;
		if(!$order_id || !$room_id || !$room_date_count) return false;
		$sql = "INSERT INTO ".$xoopsDB->prefix("martin_order_room")." (order_id,room_id,room_date,room_count) VALUES ";
		if(is_array($room_id))
		{
			foreach($room_id as $key => $id)
			{
				$prefix = '';
				foreach($room_date_count as $k => $v)
				{
					$sql .= $prefix."($order_id,$id,$k,$v)";
					$prefix = ',';
				}
				//echo $sql;exit;
				if(!$xoopsDB->queryF($sql)) $result = false;
			}
		}
		return $result;
	}

	/**
	 * @insert order service
	 * @method:
	 * @license http://www.blags.org/
	 * @created:2010年07月04日 12时59分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function InsertOrderService($order_id,$service_date_count)
	{
		global $xoopsDB;
		$result = true;
		if(!$order_id || !$service_date_count ) return false;
		$sql = "INSERT INTO ".$xoopsDB->prefix("martin_order_service")." (order_id,service_id,service_date,service_count) VALUES ";
		if(is_array($service_date_count))
		{
			$prefix = '';
			foreach($service_date_count as $k => $v)
			{
				$sql .= $prefix."($order_id,$k,0,$v)";
				$prefix = ',';
			}
			//echo $sql;exit;
			if(!$xoopsDB->queryF($sql)) $result = false;
		}
		return $result;
	}

}


