<?php
/**
 * @hotel search object
 * @license http://www.blags.org/
 * @created:2010年06月27日 14时08分
 * @copyright 1997-2010 The Martin Group
 * @author Martin <china.codehome@gmail.com> 
 * */
if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

class MartinSearch extends XoopsObject
{
	public function MartinSearch()
	{
		/*$this->initVar("city_id", XOBJ_DTYPE_INT, null, false);
		$this->initVar("hotel_star", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("hotel_name", XOBJ_DTYPE_TXTBOX, null, false, 255);*/
	}

}

class MartinSearchHandler extends XoopsObjectHandler
{

	public function create()
	{
		return new MartinSearch();
	}	
	
	/**
	 * @get rows 
	 * @license http://www.blags.org/
	 * @created:2010年06月20日 13时09分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function GetRows($sql , $key = null )
	{
		global $xoopsDB;
		$result = $xoopsDB->query($sql);
		$rows = array();
		while($row = $xoopsDB->fetchArray($result))
		{
			if(is_null($key))
			{
				$rows[] = $row;
			}else{
				$rows[$row[$key]] = $row;
			}
		}
		return $rows;
	}

	/**
	 * @search hotels
	 * @license http://www.blags.org/
	 * @created:2010年06月27日 19时57分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	public function Search($Data)
	{
		global $hotel_handler,$xoopsModuleConfig;
		//var_dump($xoopsModuleConfig);
		
		$rows = array();
		$dateTime = strtotime(date('Y-m-d'));
		foreach($Data as $key => $value)
		{
			${$key} = $value;
		}
		$city_ids = $this->GetCityIds($hotel_address);
		//var_dump($Data);
		$sql = "SELECT h.*,hc.city_name as hotel_city FROM ".$this->db->prefix("martin_hotel")." h ";
		$sql .= "INNER JOIN ".$this->db->prefix("martin_room")." r ON (h.hotel_id = r.hotel_id) ";
		$sql .= "INNER JOIN ".$this->db->prefix("martin_room_price")." rp ON (r.room_id = rp.room_id) ";
		$sql .= "INNER JOIN ".$this->db->prefix("martin_hotel_city")." hc ON (h.hotel_city = hc.city_id) ";
		$sql .= " WHERE 1 = 1 ";
		$sql .= (empty($hotel_address) || empty($hotel_ids)) ? "" : $city_ids." IN h.hotel_city_id ";
		$sql .= empty($hotel_name) ? "" : "AND h.hotel_name LIKE '%$hotel_name%' ";
		$sql .= $city_id > 0 ? "AND h.hotel_city IN (SELECT city_id FROM ".$this->db->prefix("martin_hotel_city")." WHERE city_parentid = $city_id ) " : "";
		$sql .= $hotel_star > 0 ? "AND h.hotel_star = $hotel_star " : "";
		$sql .= (is_array($price) && $price[0] > 0 && $price[1] > 0) ? "AND rp.room_price >= {$price[0]} AND rp.room_price <= {$price[1]} " : "";
		$sql .= (is_array($check_date) && $check_date[0] > 0 && $check_date[1] > 0) ? "AND rp.room_date >= {$check_date[0]} AND rp.room_date <= {$check_date[1]} " : "";
		$sql .= "GROUP BY h.hotel_id  ";
		$sql .= (empty($order) || empty($by)) ? " ORDER BY h.hotel_rank DESC , h.hotel_id DESC " : " ORDER BY $order $by ,h.hotel_rank DESC ";
		$rows['count'] = $this->GetCount(str_replace("h.*","count(h.hotel_id) as count",$sql));
		$sql .= "LIMIT $start,{$xoopsModuleConfig['perpage']}";
		//echo $sql;
		
		$result = $this->db->query($sql);
		$this->hotel_ids = & $hotel_ids;
		$cityList = & $hotel_handler->getCityList();
		while($row = $this->db->fetchArray($result))
		{
			$hotel_ids[] = $row['hotel_id'];
			$city_ids = explode(',',$row['hotel_city_id']);
			foreach($city_ids as $id)
			{
				$city_name[] = $cityList[$id];
			}
			$row['city_name'] = implode('、',$city_name);
			$row['hotel_image'] = unserialize($row['hotel_image']);
			$row['hotel_google'] = unserialize(unserialize($row['hotel_google']));
			//var_dump($row['hotel_google']);
			$rows[] = $row;
			unset($city_name);
		}
		//$rows = $this->GetRows($sql,'hotel_id');
		return $rows;
	}

	/**
	 * @get search count
	 * @license http://www.blags.org/
	 * @created:2010年06月27日 19时57分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	public function GetCount($sql)
	{
		if(empty($sql)) return $sql;
		$count = 0;
		$result = $this->db->query($sql);
		while($this->db->fetchArray($result))
		{
			$count++;
		}
		return $count;
	}

	/**
	 * @get city ids
	 * @license http://www.blags.org/
	 * @created:2010年06月27日 14时08分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	public function GetCityIds($city_name)
	{
		global $xoopsDB;
		$sql = "SELECT city_id FROM ".$xoopsDB->prefix("martin_hotel_city")." WHERE city_name LIKE '%$city_name%'";
		$result = $xoopsDB->query($sql);
		while($city_id = $xoopsDB->fetchArray($result))
		{
			$city_ids[] = $city_id['city_id'];
		}
		return is_array($city_ids) ? implode(',',$city_ids) : null;
	}
	
	/**
	 * get hotel rooms
	 * @access public
	 * @return void
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * @created time :2010-06-25 15:27:34
	 * */
	function GethotelRooms($room_date)
	{
		if(empty($this->hotel_ids)) return $this->hotel_ids;
		$sql = "SELECT r.*,rt.room_type_info,rp.room_is_totay_special,rp.room_date,
			GROUP_CONCAT(room_price) as room_prices,GROUP_CONCAT(room_date) as room_dates,
			round(avg(rp.room_price),2) as room_price,round(avg(rp.room_advisory_range_small),2) as room_advisory_range_small,
			round(avg(rp.room_advisory_range_max),2) as room_advisory_range_max,round(avg(rp.room_sented_coupon),2) as room_sented_coupon  	
			FROM ".$this->db->prefix("martin_room")
			." r INNER JOIN ".$this->db->prefix("martin_room_type")." rt ON (r.room_type_id = rt.room_type_id) 
			INNER JOIN ".$this->db->prefix("martin_room_price")." rp ON (rp.room_id = r.room_id)
			WHERE r.hotel_id IN (".implode(",",$this->hotel_ids).") ";
		$sql .= ($room_date[0] > 0 && $room_date[1] > 0)? "AND rp.room_date >= {$room_date[0]} AND rp.room_price <= {$room_date[1]} " : " ";
		$sql .= "GROUP BY r.room_id";
		$rows = array();
		$result = $this->db->query($sql);
		while($row = $this->db->fetchArray($result))
		{
			$room_dates = array();
			$row['room_prices'] = explode(",",$row['room_prices']);
			$row['room_dates'] = explode(",",$row['room_dates']);
			foreach($row['room_prices'] as $key => $room_price)
			{
				$d = $row['room_dates'][$key];
				if($d >= $room_date[0] && $d < $room_date[1]) $room_prices[] = array('date'=>date('Y-m-d',$d),'price'=>$room_price);
			}
			unset($row['room_prices'],$row['room_dates']);
			$row['room_prices'] = $room_prices;
			$row['room_date'] = date('Y-m-d',$row['room_date']);
			$rows[$row['hotel_id']][] = $row;
			unset($row,$room_prices);
		}
		return $rows;
	}

	/**
	 * @get city name 
	 * @method:
	 * @license http://www.blags.org/
	 * @created:2010年06月27日 19时57分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	public function GetCityName($city_id)
	{
		if(empty($city_id)) return $city_id;
		$sql = "SELECT city_name FROM ".$this->db->prefix("martin_hotel_city")." WHERE city_id = $city_id";
		$row = $this->db->fetchRow($this->db->query($sql));
		return isset($row[0]) ? $row[0] : null;
	}

}
?>
