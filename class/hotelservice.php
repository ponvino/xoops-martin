<?php
/**
* $Id: hotelservice.php,v 1.42 2007/02/04 15:01:40 malanciault Exp $
* Module:martin 
* Licence: GNU
*/

if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

include_once XOOPS_ROOT_PATH.'/modules/martin/include/common.php';

class MartinHotelService extends XoopsObject
{

	function MartinHotelservice()
	{
		$this->initVar("service_id", XOBJ_DTYPE_INT, null, false);
		$this->initVar("service_type_id", XOBJ_DTYPE_INT, null, false);
		$this->initVar("service_type_name", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("service_unit", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("service_name", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("service_instruction", XOBJ_DTYPE_TXTAREA, null, false);
	}

	function service_id()
	{
		return $this->getVar("service_id");
	}

	function service_type_id()
	{
		return $this->getVar("service_type_id");
	}

	function service_type_name()
	{
		return $this->getVar("service_type_name");
	}

	function service_unit($format = 'S')
	{
		return $this->getVar("service_unit",$format);
	}

	function service_name($format = 'S')
	{
		return $this->getVar("service_name",$format);
	}

	function service_instruction($format = 'S')
	{
		return $this->getVar("service_instruction",$format);
	}
}

/**
 * @method: hotelserviceHandler
 * @license http://www.blags.org/
 * @created:2010年05月21日 20时40分
 * @copyright 1997-2010 The Martin Group
 * @author Martin <china.codehome@gmail.com> 
 * */
class MartinHotelServiceHandler extends XoopsObjectHandler
{
	
	/**
	* create a new hotel city
	* @param bool $isNew flag the new objects as "new"?
	* @return object hotelservice
	*/
	function &create($isNew = true)
	{
		$hotelservice = new MartinHotelService();
		if ($isNew) {
			$hotelservice->setNew();
		}
		return $hotelservice;
	}

	/**
	* retrieve a hotel city
	*
	* @param int $id hotelserviceid of the hotelservice
	* @return mixed reference to the {@link hotelservice} object, FALSE if failed
	*/
	function &get($id)
	{
		if (intval($id) <= 0) {
			return false;
		}

		$criteria = new CriteriaCompo(new Criteria('service_id', $id));
		$criteria->setLimit(1);
        $obj_array = $this->getObjects($criteria);
        if (count($obj_array) != 1) {
            $obj = $this->create();
            return $obj;
        }
        return $obj_array[0];
	}

	/**
	 * @得到列表
	 * @method:
	 * @license http://www.blags.org/
	 * @created:2010年05月23日 14时59分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function &getHotelServices($limit=0, $start=0, $sort='service_id', $order='ASC', $id_as_key = true)
	{
		$criteria = new CriteriaCompo();

		$criteria->setSort($sort);
		$criteria->setOrder($order);

		$criteria->setStart($start);
		$criteria->setLimit($limit);
		return $this->getObjects($criteria, $id_as_key);
	}

	/**
	* insert a new hotelservice in the database
	*
	* @param object $hotelservice reference to the {@link hotelservice} object
	* @param bool $force
	* @return bool FALSE if failed, TRUE if already present and unchanged or successful
	*/
	function insert(&$hotelservice, $force = false)
	{

		if (strtolower(get_class($hotelservice)) != 'martinhotelservice') {
			return false;
		}

		if (!$hotelservice->cleanVars()) {
			return false;
		}

		foreach ($hotelservice->cleanVars as $k => $v) {
			${$k} = $v;
		}

		if ($hotelservice->isNew()) {
			$sql = sprintf("INSERT INTO %s (
								service_id,
								service_type_id,
								service_unit,
								service_name,
								service_instruction
							) VALUES (
								NULL,
								%u,
								%s,
								%s,
								%s
							)",
								$this->db->prefix('martin_hotel_service'),
								$service_type_id,
								$this->db->quoteString($service_unit),
								$this->db->quoteString($service_name),
								$this->db->quoteString($service_instruction)
								);
		} else {
			$sql = sprintf("UPDATE %s SET
								service_type_id = %u,
								service_unit = %s,
								service_name = %s,
								service_instruction = %s
							WHERE service_id = %u",
							$this->db->prefix('martin_hotel_service'),
							$service_type_id,
							$this->db->quoteString($service_unit),
							$this->db->quoteString($service_name),
							$this->db->quoteString($service_instruction),
							$service_id);
		}
		//echo "<br />" . $sql . "<br />";
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			$hotelservice->setErrors('The query returned an error. ' . $this->db->error());
			return false;
		}
		if ($hotelservice->isNew()) {
			$hotelservice->assignVar('service_id', $this->db->getInsertId());
		}

		$hotelservice->assignVar('service_id', $service_id);
		return true;
	}

	/**
	 * @删除一个城市
	 * @method:delete(service_id)
	 * @license http://www.blags.org/
	 * @created:2010年05月21日 20时40分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function delete(&$hotelservice, $force = false)
	{

		if (strtolower(get_class($hotelservice)) != 'martinhotelservice') {
			return false;
		}

		$sql = "DELETE FROM ".$this->db->prefix("martin_hotel_service")." WHERE service_id = ".$hotelservice->service_id();
	
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
		$sql = 'DELETE FROM '.$this->db->prefix('martin_hotel_service');
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
	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('martin_hotel_service');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		$result = $this->db->query($sql);
		if (!$result) {
			return 0;
		}
		list($count) = $this->db->fetchRow($result);
		return $count;
	}

	/**
	 * @get objects
	 * @license http://www.blags.org/
	 * @created:2010年05月21日 20时40分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT s.*,st.service_type_name FROM '.$this->db->prefix('martin_hotel_service')." s left join ".$this->db->prefix("martin_hotel_service_type")." st ON (s.service_type_id = st.service_type_id ) ";
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
			$hotelservice = new MartinHotelService();
			$hotelservice->assignVars($myrow);
			$theObjects[$myrow['service_id']] =& $hotelservice;
			//var_dump($hotelservice);
			unset($hotelservice);
		}
		//var_dump($theObjects);

		foreach ($theObjects as $theObject) {

			if (!$id_as_key) {
				$ret[] =& $theObject;
			} else {
				$ret[$theObject->service_id()] =& $theObject;
			}
			unset($theObject);
		}

		return $ret;
	}

	/**
	 * @get hotel service list
	 * @license http://www.blags.org/
	 * @created:2010年05月30日 20时48分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function getHotelServiceRelations($pageSize , $Limit)
	{
		$rows = array();
		$sql = "SELECT s.service_name,sr.*,h.hotel_name FROM ".$this->db->prefix("martin_hotel_service_relation")." sr 
			left join ".$this->db->prefix("martin_hotel_service")." s ON (s.service_id = sr.service_id) 
			left join ".$this->db->prefix("martin_hotel")." h ON (sr.hotel_id = h.hotel_id) order BY hotel_id DESC 
			limit $Limit,$pageSize";
		$result = $this->db->query($sql);
		while($row = $this->db->fetchArray($result))
		{
			$rows[] = $row;
		}
		return $rows;
	}

	/**
	 * @get relation 
	 * @license http://www.blags.org/
	 * @created:2010年05月30日 20时48分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function getHotelServiceRelation($hotel_id , $service_id)
	{
		if(!is_numeric($hotel_id) || !is_numeric($service_id)) return false;
		$sql = "SELECT s.service_name,sr.*,h.hotel_name FROM ".$this->db->prefix("martin_hotel_service_relation")." sr 
			left join ".$this->db->prefix("martin_hotel_service")." s ON (s.service_id = sr.service_id) 
			left join ".$this->db->prefix("martin_hotel")." h ON (sr.hotel_id = h.hotel_id) WHERE sr.hotel_id = $hotel_id and sr.service_id = $service_id ";
		$result = $this->db->query($sql);
		return $this->db->fetchArray($result);
	}

	/**
	 * delete hotel service relation
	 * @license http://www.blags.org/
	 * @created:2010年05月30日 20时48分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function DeleteServiceRelation($hotel_id,$service_id)
	{
		if(!is_numeric($hotel_id) || !is_numeric($service_id)) return false;
		$sql = "delete FROM ".$this->db->prefix("martin_hotel_service_relation")." 
			WHERE hotel_id = $hotel_id and service_id = $service_id";
		return $this->db->queryF($sql);
	}

	/**
	 * @Insert Relation
	 * @license http://www.blags.org/
	 * @created:2010年05月30日 20时48分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function InsertRelation($RelationData,$IsOld = false)
	{
		if(empty($RelationData) || !is_array($RelationData)) return false;
		foreach($RelationData as $key => $value)
		{
			${$key} = $value;
		}
		if(!$IsOld && $this->CheckRelationExist($hotel_id,$service_id)) return false;

		if(!$IsOld)
		{
			$sql = "insert INTO ".$this->db->prefix('martin_hotel_service_relation')." (`hotel_id`,`service_id`,`service_extra_price`) VALUES ($hotel_id,$service_id,$service_extra_price) ";
		}else{
			$sql = "UPDATE ".$this->db->prefix("martin_hotel_service_relation")." SET service_extra_price = $service_extra_price WHERE hotel_id = $hotel_id and service_id = $service_id";
		}
		//echo $sql;exit;	
		return $this->db->queryF($sql);
	}

	/**
	 * 检测是否存在
	 * @license http://www.blags.org/
	 * @created:2010年05月30日 20时48分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function CheckRelationExist($hotel_id,$service_id)
	{
		if(!is_numeric($hotel_id) || !is_numeric($service_id)) return false;
		$sql = "SELECT * FROM ".$this->db->prefix("martin_hotel_service_relation")." WHERE hotel_id = $hotel_id and service_id = $service_id";
		return is_array($this->db->fetchArray($this->db->query($sql)));
	}

	/**
	 * @get hotel list
	 * @license http://www.blags.org/
	 * @created:2010年05月30日 20时48分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function getHotelList($hotel_id = 0)
	{
		$rows = array();
		$sql = "SELECT hotel_id ,hotel_name FROM ".$this->db->prefix("martin_hotel");
		$sql .= $hotel_id > 0 ? " WHERE hotel_id = $hotel_id" : "" ;
		$sql .= " order BY hotel_rank ,hotel_id DESC ";
		$result = $this->db->query($sql);
		while($row = $this->db->fetchArray($result))
		{
			$rows[$row['hotel_id']] = $row['hotel_name'];
		}
		return $rows;
	}

	/**
	 * @get service list
	 * @license http://www.blags.org/
	 * @created:2010年05月30日 20时48分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function getServiceList($service_id = 0)
	{
		$rows = array();
		$sql = "SELECT service_id ,service_name FROM ".$this->db->prefix("martin_hotel_service");
		$sql .= $hotel_id > 0 ? " WHERE service_id = $service_id" : "" ;
		$result = $this->db->query($sql);
		while($row = $this->db->fetchArray($result))
		{
			$rows[$row['service_id']] = $row['service_name'];
		}
		return $rows;
	}
	
	/**
	 * @get relation count
	 * @license http://www.blags.org/
	 * @created:2010年05月30日 20时48分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function GetRelationCount()
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('martin_hotel_service_relation');
		$result = $this->db->query($sql);
		if (!$result) {
			return 0;
		}
		list($count) = $this->db->fetchRow($result);
		return $count;
	}

	/**
	 * @get hotel service
	 * @license http://www.blags.org/
	 * @created:2010年06月16日 22时31分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function getHotelService($hotel_id)
	{
		global $xoopsDB,$xoopsModule;
		$DBPrefx = $xoopsDB->prefix . '_' . $xoopsModule->getVar('dirname');
		if(empty($hotel_id)) return false;
		$sql = 'SELECT st.service_type_name,s.service_id,s.service_unit,s.service_name,
			s.service_instruction,sr.service_extra_price FROM ';
		$sql .= $DBPrefx . "_hotel_service s INNER JOIN " . $DBPrefx . "_hotel_service_relation sr ON ( sr.service_id = s.service_id ) ";
		$sql .= "INNER JOIN {$DBPrefx}_hotel_service_type st ON (s.service_type_id = st.service_type_id) WHERE sr.hotel_id = ".$hotel_id;
		//echo $sql;
		$rows = array();
		$result = $xoopsDB->query($sql);
		while($row = $xoopsDB->fetchArray($result))
		{
			//$row['service_extra_price'] = round($row['service_extra_price'],2);
			$rows[] = $row;
		}
		return $rows;
	}

}

?>
