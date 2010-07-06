<?php
/**
* $Id: hotel.php,v 1.42 2007/02/04 15:01:40 malanciault Exp $
* Module:martin 
* Licence: GNU
*/

if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

include_once XOOPS_ROOT_PATH.'/modules/martin/include/common.php';

class MartinHotel extends XoopsObject
{

	function MartinHotel()
	{
		$this->initVar("hotel_id", XOBJ_DTYPE_INT, null, false);
		//$this->initVar("hotel_city_id", XOBJ_DTYPE_INT, null, false);
		$this->initVar("hotel_city", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("hotel_city_id", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("hotel_environment", XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar("hotel_rank", XOBJ_DTYPE_INT, null, false);
		$this->initVar("hotel_name", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("hotel_enname", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("hotel_alias", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("hotel_keywords", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("hotel_tags", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("hotel_description", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("hotel_star", XOBJ_DTYPE_INT, null, false);
		$this->initVar("hotel_address", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("hotel_telephone", XOBJ_DTYPE_TXTBOX, null, true, 45);
		$this->initVar("hotel_fax", XOBJ_DTYPE_TXTBOX, null, true, 45);
		$this->initVar("hotel_room_count", XOBJ_DTYPE_INT, null, false);
		$this->initVar("hotel_icon", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("hotel_image", XOBJ_DTYPE_ARRAY, '', true, 1000);
		$this->initVar("hotel_google", XOBJ_DTYPE_ARRAY, '', true, 255);
		$this->initVar("hotel_characteristic", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("hotel_reminded", XOBJ_DTYPE_TXTBOX, null, true, 1000);
		$this->initVar("hotel_facility", XOBJ_DTYPE_TXTBOX, null, true, 1000);
		$this->initVar("hotel_info", XOBJ_DTYPE_TXTAREA, null, false);
		$this->initVar("hotel_status", XOBJ_DTYPE_INT, null, false);
		$this->initVar("hotel_open_time", XOBJ_DTYPE_INT, null, false);
		$this->initVar("hotel_add_time", XOBJ_DTYPE_INT, null, false);
	}

	function hotel_id()
	{
		return $this->getVar("hotel_id");
	}

	function hotel_city()
	{
		return $this->getVar("hotel_city");
	}

	/*function hotel_city_id()
	{
		return $this->getVar("hotel_city_id");
	}*/

	function hotel_city_id($format = 'S')
	{
		return $this->getVar("hotel_city_id",$format);
	}

	function hotel_environment($format = 'S')
	{
		return $this->getVar("hotel_environment",$format);
	}

	function hotel_rank()
	{
		return $this->getVar("hotel_rank");
	}

	function hotel_city_name($format = 'S')
	{
		return $this->getVar("hotel_city_name",$format);
	}

	function hotel_name($format = 'S')
	{
		return $this->getVar("hotel_name",$format);
	}

	function hotel_enname($format = 'S')
	{
		return $this->getVar("hotel_enname",$format);
	}

	function hotel_alias($format = 'S')
	{
		return $this->getVar("hotel_alias",$format);
	}

	function hotel_keywords($format = 'S')
	{
		return $this->getVar("hotel_keywords",$format);
	}

	function hotel_tags($format = 'S')
	{
		return $this->getVar("hotel_tags",$format);
	}

	function hotel_description($format = 'S')
	{
		return $this->getVar("hotel_description",$format);
	}

	function hotel_star()
	{
		return $this->getVar("hotel_star");
	}

	function hotel_address($format = 'S')
	{
		return $this->getVar("hotel_address",$format);
	}

	function hotel_telephone($format = 'S')
	{
		return $this->getVar("hotel_telephone",$format);
	}

	function hotel_fax($format = 'S')
	{
		return $this->getVar("hotel_fax",$format);
	}

	function hotel_room_count()
	{
		return $this->getVar("hotel_room_count");
	}

	function hotel_icon($format = 'S')
	{
		return ($this->getVar("hotel_icon",$format));
	}

	function hotel_image($format = 'S')
	{
		return unserialize($this->getVar("hotel_image",$format));
	}

	function hotel_google($format = 'S')
	{
		return unserialize($this->getVar("hotel_google",$format));
	}

	function hotel_characteristic($format = 'S')
	{
		return $this->getVar("hotel_characteristic",$format);
	}

	function hotel_reminded($format = 'S')
	{
		return $this->getVar("hotel_reminded",$format);
	}

	function hotel_facility($format = 'S')
	{
		return $this->getVar("hotel_facility",$format);
	}

	function hotel_info($format = 'edit')
	{
		return $this->getVar("hotel_info",$format);
	}

	function hotel_status()
	{
		return $this->getVar("hotel_status");
	}

	function hotel_open_time()
	{
		return $this->getVar("hotel_open_time");
	}

	function hotel_add_time()
	{
		return $this->getVar("hotel_add_time");
	}

}

/**
 * @method: HotelCityHandler
 * @license http://www.blags.org/
 * @created:2010年05月21日 20时40分
 * @copyright 1997-2010 The Martin Group
 * @author Martin <china.codehome@gmail.com> 
 * */
class MartinHotelHandler extends XoopsObjectHandler
{
	
	/**
	* create a new hotel city
	* @param bool $isNew flag the new objects as "new"?
	* @return object Hotel
	*/
	function &create($isNew = true)
	{
		$hotel = new MartinHotel();
		if ($isNew) {
			$hotel->setNew();
		}
		return $hotel;
	}

	/**
	* retrieve a hotel city
	*
	* @param int $id hotelcityid of the hotel
	* @return mixed reference to the {@link Hotel} object, FALSE if failed
	*/
	function &get($id)
	{
		if (intval($id) <= 0) {
			return false;
		}

		$criteria = new CriteriaCompo(new Criteria('hotel_id', $id));
		$criteria->setLimit(1);
        $obj_array = $this->getObjects($criteria);
        if (count($obj_array) != 1) {
            $obj = $this->create();
            return $obj;
        }
		$cityList = & self::getCityList();
		$hotel_city = $obj_array[0]->hotel_city();
		$obj_array[0]->setVar('hotel_city',$cityList[$hotel_city]);
		$city_ids = explode(',',$obj_array[0]->hotel_city_id());
		foreach($city_ids as $id)
		{
			$city_name[] = $cityList[$id];
		}
		$obj_array[0]->city_name = implode(',',$city_name);
        return $obj_array[0];
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
	 * @得到列表
	 * @method:
	 * @license http://www.blags.org/
	 * @created:2010年05月23日 14时59分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function &getHotels($limit=0, $start=0, $city_parentid=0, $sort='hotel_id', $order='ASC', $id_as_key = true)
	{
		$criteria = new CriteriaCompo();

		$criteria->setSort($sort);
		$criteria->setOrder($order);

		$criteria->setStart($start);
		$criteria->setLimit($limit);
		return $this->getObjects($criteria, $id_as_key);
	}

	/**
	* insert a new hotel in the database
	*
	* @param object $hotel reference to the {@link Hotel} object
			$city_ids = explode(',',$row['hotel_city_id']);
			foreach($city_ids as $id)
			{
				$city_name[] = $cityList[$id];
			}
	* @param bool $force
	* @return bool FALSE if failed, TRUE if already present and unchanged or successful
	*/
	function insert(&$hotel, $force = false)
	{

		if (strtolower(get_class($hotel)) != 'martinhotel') {
			return false;
		}

		if (!$hotel->cleanVars()) {
			return false;
		}

		foreach ($hotel->cleanVars as $k => $v) {
			${$k} = $v;
		}
		//echo $hotel_room_count;exit;
		
		if ($hotel->isNew()) {
			$sql = sprintf("INSERT INTO %s (
								hotel_id,hotel_city,hotel_city_id , hotel_environment,hotel_rank,hotel_name, hotel_enname,hotel_alias,
								hotel_keywords,hotel_tags,
								hotel_description,	hotel_star,	hotel_address,	hotel_telephone,hotel_fax,hotel_room_count,
								hotel_icon,hotel_image,hotel_google,hotel_characteristic,	
								hotel_reminded, hotel_facility ,hotel_info,	hotel_status,
								hotel_open_time,hotel_add_time
							) VALUES (
								NULL,%u,%s,%s,%u,	%s,	%s,	%s,	%s,	%s , %s , %u , %s , %s,	%s,
								%u,	%s, %s , %s, %s, %s, %s , %s, %u, %u, %u
							)",
								$this->db->prefix('martin_hotel'),
								//$hotel_city_id,
								$hotel_city,
								$this->db->quoteString($hotel_city_id),
								$this->db->quoteString($hotel_environment),
								$hotel_rank,
								$this->db->quoteString($hotel_name),
								$this->db->quoteString($hotel_enname),
								$this->db->quoteString($hotel_alias),
								$this->db->quoteString($hotel_keywords),
								$this->db->quoteString($hotel_tags),
								$this->db->quoteString($hotel_description),
								$hotel_star,
								$this->db->quoteString($hotel_address),
								$this->db->quoteString($hotel_telephone),
								$this->db->quoteString($hotel_fax),
								$hotel_room_count,
								$this->db->quoteString($hotel_icon),
								$this->db->quoteString($hotel_image),
								$this->db->quoteString($hotel_google),
								$this->db->quoteString($hotel_characteristic),
								$this->db->quoteString($hotel_reminded),
								$this->db->quoteString($hotel_facility),
								$this->db->quoteString($hotel_info),
								$hotel_status,
								$hotel_open_time,
								$hotel_add_time
								);
			//echo $sql;exit;
		} else {
			$sql = sprintf("UPDATE %s SET hotel_city = %u,
								hotel_city_id = %s,hotel_environment=%s,hotel_rank = %u,hotel_name = %s, hotel_enname = %s,hotel_alias = %s,
								hotel_keywords = %s,hotel_tags = %s,hotel_description = %s,	hotel_star = %u,hotel_address = %s,	
								hotel_telephone = %s,
								hotel_fax = %s,hotel_room_count = %u,hotel_icon = %s ,hotel_image = %s,hotel_google = %s,
								hotel_characteristic = %s,hotel_reminded = %s, hotel_facility = %s , hotel_info = %s,	hotel_status = %u,
								hotel_open_time = %u,hotel_add_time = %u
							WHERE hotel_id = %u",
								$this->db->prefix('martin_hotel'),
								//$hotel_city_id,
								$hotel_city,
								$this->db->quoteString($hotel_city_id),
								$this->db->quoteString($hotel_environment),
								$hotel_rank,
								$this->db->quoteString($hotel_name),
								$this->db->quoteString($hotel_enname),
								$this->db->quoteString($hotel_alias),
								$this->db->quoteString($hotel_keywords),
								$this->db->quoteString($hotel_tags),
								$this->db->quoteString($hotel_description),
								$hotel_star,
								$this->db->quoteString($hotel_address),
								$this->db->quoteString($hotel_telephone),
								$this->db->quoteString($hotel_fax),
								$hotel_room_count,
								$this->db->quoteString($hotel_icon),
								$this->db->quoteString($hotel_image),
								$this->db->quoteString($hotel_google),
								$this->db->quoteString($hotel_characteristic),
								$this->db->quoteString($hotel_reminded),
								$this->db->quoteString($hotel_facility),
								$this->db->quoteString($hotel_info),
								$hotel_status,
								$hotel_open_time,
								$hotel_add_time,$hotel_id);
		}
		//echo $sql;exit;
		//echo "<br />" . $sql . "<br />";exit;
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			$hotel->setErrors('The query returned an error. ' . $this->db->error());
			return false;
		}
		if ($hotel->isNew()) {
			$hotel->assignVar('hotel_id', $this->db->getInsertId());
		}

		$hotel->assignVar('hotel_id', $hotel_id);
		return true;
	}

	/**
	 * @删除一个酒店
	 * @method:delete(city_id)
	 * @license http://www.blags.org/
	 * @created:2010年05月21日 20时40分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function delete(&$hotel, $force = false)
	{
		if (strtolower(get_class($hotel)) != 'martinhotel') {
			return false;
		}
		/**
		 * delete relations 
		 **/
		//delete images 
		$HotelImages = $hotel->hotel_image();
		if(!empty($HotelImages) && is_array($HotelImages))
		{
			foreach($HotelImages as $HotelImage)
			{
				$file = MARTIN_HOTEL_IMAGE_PATH . $HotelImage['filename'];
				if(file_exists($file) && is_writable($file))
				{
					unlink($file);
				}
			}
		}
		//delete icon
		$full_icon_img = MARTIN_ROOT_PATH . 'images/hotelicon/' . $hotel->hotel_icon();
		if(file_exists($full_icon_img) && is_writable($full_icon_img))
		{
			unlink($full_icon_img);
		}

		//delete tags
		self::deleteTags($hotel);
		
		//delete room group
		$sql = "DELETE FROM ".$this->db->prefix("martin_group_room")." WHERE room_id IN (
			SELECT room_id FROM ".$this->db->prefix("martin_room")." WHERE hotel_id = " . $hotel->hotel_id() . ' ) ';
		$this->db->queryF($sql);
		//delete room group
		$sql = "DELETE FROM ".$this->db->prefix("martin_auction_room")." WHERE room_id IN (
			SELECT room_id FROM ".$this->db->prefix("martin_room")." WHERE hotel_id = " . $hotel->hotel_id() . ' ) ';
		$this->db->queryF($sql);
		//delete group prices
		$sql = "DELETE FROM ".$this->db->prefix("martin_room_price")." WHERE room_id IN (
			SELECT room_id FROM ".$this->db->prefix("martin_room")." WHERE hotel_id = " . $hotel->hotel_id() . ' ) ';
		$this->db->queryF($sql);
		//delete rooms
		$sql = "DELETE FROM ".$this->db->prefix("martin_room")." WHERE hotel_id = " . $hotel->hotel_id();
		$this->db->queryF($sql);
		//delete hotel promotions
		$sql = "DELETE FROM ".$this->db->prefix("martin_hotel_promotions")." WHERE hotel_id = " . $hotel->hotel_id();
		$this->db->queryF($sql);

	
		$sql = "DELETE FROM ".$this->db->prefix("martin_hotel")." WHERE hotel_id = " . $hotel->hotel_id();
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}

		if (!$result) {
			return false;
		}
		return true;
	}

	/**
	* delete hotel cities matching a set of conditions
	*
	* @param object $criteria {@link CriteriaElement}
	* @return bool FALSE if deletion failed
	*/
	function deleteAll($criteria = null)
	{
		$sql = 'DELETE FROM '.$this->db->prefix('martin_hotel');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}

	/**
	* count hotel cities matching a condition
	*
	* @param object $criteria {@link CriteriaElement} to match
	* @return int count of categories
	*/
	function getCount($searchData)
	{
		if(!empty($searchData) && is_array($searchData))
		{
			$where = 'where 1 = 1 ';
			//$where .= !empty($searchData['hotel_city_id']) ? " and h.hotel_city_id = {$searchData['hotel_city_id']} " : " ";
			$where .= !empty($searchData['hotel_city_id']) ? " and h.hotel_city_id LIKE  '%{$searchData['hotel_city_id']}%' " : " ";
			$where .= !empty($searchData['hotel_star']) ? " and h.hotel_star = {$searchData['hotel_star']} " : " ";
			$where .= !empty($searchData['hotel_name']) ? " and h.hotel_name like '%{$searchData['hotel_name']}%' " : " ";
		}
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('martin_hotel')." h $where";
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		//echo $sql;
		$result = $this->db->query($sql);
		if (!$result) {
			return 0;
		}
		list($count) = $this->db->fetchRow($result);
		return $count;
	}

	/**
	 * @得到城市
	 * @license http://www.blags.org/
	 * @created:2010年05月21日 20时40分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.$this->db->prefix('martin_hotel');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
			if ($criteria->getSort() != '') {
				$sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
			}
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		//echo "<br />" . $sql . "<br />";
		$result = $this->db->query($sql, $limit, $start);

		if (!$result) {
			return $ret;
		}

		$theObjects = array();

		while ($myrow = $this->db->fetchArray($result)) {
			$hotel = new MartinHotel();
			$hotel->assignVars($myrow);
			$theObjects[$myrow['hotel_id']] =& $hotel;
			//var_dump($hotel);
			unset($hotel);
		}
		//var_dump($theObjects);

		foreach ($theObjects as $theObject) {

			if (!$id_as_key) {
				$ret[] =& $theObject;
			} else {
				$ret[$theObject->hotel_id()] =& $theObject;
			}
			unset($theObject);
		}

		return $ret;
	}

	/**
	 * @得到酒店列表
	 * @license http://www.blags.org/
	 * @created:2010年05月29日 11时31分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function getHotelList($searchData,$limit=0, $start=0, $sort='hotel_id')
	{
		if(!empty($searchData) && is_array($searchData))
		{
			$where = 'where 1 = 1 ';
			//$where .= !empty($searchData['hotel_city_id']) ? " and h.hotel_city_id = {$searchData['hotel_city_id']} " : " ";
			$where .= !empty($searchData['hotel_city_id']) ? " and h.hotel_city_id LIKE '%{$searchData['hotel_city_id']}%' " : " ";
			$where .= !empty($searchData['hotel_star']) ? " and h.hotel_star = {$searchData['hotel_star']} " : " ";
			$where .= !empty($searchData['hotel_name']) ? " and h.hotel_name like '%{$searchData['hotel_name']}%' " : " ";
		}
		$sql = "SELECT h.* FROM ".$this->db->prefix("martin_hotel")." h ";
			//left join ".$this->db->prefix("martin_hotel_city")." hc ON (hc.city_id IN ( h.hotel_city_id ) ) 
		//$sql .=	"$where	Group BY h.hotel_id order BY h.hotel_rank ,h.hotel_id DESC ";
		$sql .=	"$where	 order BY h.hotel_rank ,h.hotel_id DESC ";
		$query = $this->db->query($sql,$limit,$start);
		$cityList = & self::getCityList();
		while($row = $this->db->fetchArray($query))
		{
			$city_ids = explode(',',$row['hotel_city_id']);
			foreach($city_ids as $id)
			{
				$city_name[] = $cityList[$id];
			}
			$hotel_ids[] = $row['hotel_id'];
			$row['city_name'] = implode('、',$city_name);
			$row['hotel_open_time'] = date('Y-m-d H:i:s',$row['hotel_open_time']);
			$row['hotel_add_time'] = date('Y-m-d H:i:s',$row['hotel_add_time']);
			$nrows[] = $row;
			unset($row,$city_name);
		}
		$this->hotel_ids = & $hotel_ids;
		//echo '<pre>';print_r($nrows);
		return $nrows;
	}

	/**
	 * get hotel rooms
	 * @access public
	 * @return void
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * @created time :2010-06-25 15:27:34
	 * */
	function GethotelRooms()
	{
		if(empty($this->hotel_ids)) return $this->hotel_ids;
		$sql = "SELECT r.*,rt.room_type_info FROM ".$this->db->prefix("martin_room")
			." r INNER JOIN ".$this->db->prefix("martin_room_type")." rt ON (r.room_type_id = rt.room_type_id) 
			WHERE r.hotel_id IN (".implode(",",$this->hotel_ids).") ";
		$rows = array();
		$result = $this->db->query($sql);
		while($row = $this->db->fetchArray($result))
		{
			$rows[$row['hotel_id']][] = $row;
		}
		return $rows;
	}

	/**
	 * @get city list
	 * @method:
	 * @license http://www.blags.org/
	 * @created:2010年06月18日 21时37分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function getCityList($WHERE = null)
	{
		$sql = 'SELECT * FROM '.$this->db->prefix('martin_hotel_city');
		$sql .= !is_null($WHERE) ? ' ' . $WHERE . ' ' : '';
		$result = $this->db->query($sql);
		$rows = array();
		while($row = $this->db->fetchArray($result))
		{
			$rows[$row['city_id']] = $row['city_name'];
		}
		return $rows;
	}

	/**
	 * @method:save rank
	 * @license http://www.blags.org/
	 * @created:2010年05月29日 19时25分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function saveRank($RankData)
	{
		if(!empty($RankData) && is_array($RankData))
		{
			$result = true;
			foreach($RankData as $hotel_id => $hotel_rank)
			{
				$sql = "UPDATE ".$this->db->prefix("martin_hotel")." set hotel_rank = ".intval($hotel_rank)."
					WHERE hotel_id = ".$hotel_id;
				if(!$this->db->query($sql))
				{
					$result = false;
				}
			}
			return $result;
		}
		return false;
	}

	/**
	 * @检测是否存在
	 * @license http://www.blags.org/
	 * @created:2010年05月28日 21时02分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function CheckExist($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix("martin_hotel")." WHERE hotel_id = " . $id;
		return is_array($this->db->fetchArray($this->db->query($sql)));
	}

	/**
	 * @
	 * @method:
	 * @license http://www.blags.org/
	 * @created:2010年06月14日 20时47分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function CheckAliasExist($hotel_alias,$hotel_id)
	{
		if(empty($hotel_alias)) return false;
		$sql = "SELECT count(*) FROM ".$this->db->prefix("martin_hotel")." WHERE hotel_alias = '".$hotel_alias."'";
		list($exist) = $this->db->fetchRow($this->db->query($sql));
		if($hotel_id > 0 && $exist > 1) return true;
		if($hotel_id > 0 && $exist == 1) return false;
		return $exist;
	}

    /**
	 * tag action
     * Update hotel tag links of the hotel
     * 
     * @return 	bool 	true on success
     */
    function updateTags(&$hotel)
    {
		if ($tag_handler = @xoops_getmodulehandler("tag", "tag", true)) {
			$tag_handler->updateByItem($hotel->getVar('hotel_tags'), $hotel->getVar('hotel_id'), MARTIN_DIRNAME);
		}
	    return true;
    }

    /**
     * Delete hotel tags links of the article from database
     * 
     * @return 	bool 	true on success
     */
    function deleteTags(&$hotel)
    {
		if ($tag_handler = @xoops_getmodulehandler("tag", "tag", true)) {
			$tag_handler->updateByItem(array(), $hotel->getVar('hotel_id'), MARTIN_DIRNAME);
		}
        return true;
    }

	/**
	 * @get rank hotel count 
	 * @license http://www.blags.org/
	 * @created:2010年06月20日 13时09分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function getHotelRankCount()
	{
		global $xoopsDB;
		$sql = 'SELECT hotel_star ,count(hotel_id) as count FROM '.$xoopsDB->prefix('martin_hotel').' GROUP BY hotel_star ORDER BY hotel_star  ';
		$result = $xoopsDB->query($sql);
		$rows = array();
		while($row = $xoopsDB->fetchArray($result))
		{
			$rows[$row['hotel_star']] = $row['count'];
		}
		return $rows;
	}

	/**
	 * @get viewed hotels
	 * @license http://www.blags.org/
	 * @created:2010年06月20日 13时09分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function GetViewedHotels($hotel_ids,$limit = 10)
	{
		global $xoopsDB;
		if(empty($hotel_ids) || !is_array($hotel_ids)) return $hotel_ids;
		$sql = 'SELECT hotel_id,hotel_alias,hotel_name FROM '.$xoopsDB->prefix('martin_hotel').' WHERE hotel_id IN ('.implode(',',$hotel_ids).') LIMIT '.$limit;
		$rows = $this->GetRows($sql,'hotel_id');
		$viewedRows = array();
		foreach($hotel_ids as $hotel_id)
		{
			$viewedRows[] = $rows[$hotel_id];
		}
		return $viewedRows;
	}

}

?>
