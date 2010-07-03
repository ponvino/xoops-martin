<?php
/**
* $Id: group.php,v 1.42 2007/02/04 15:01:40 malanciault Exp $
* Module:martin 
* Licence: GNU
*/

if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

include_once XOOPS_ROOT_PATH.'/modules/martin/include/common.php';

class MartinGroup extends XoopsObject
{

	function MartinGroup()
	{
		$this->initVar("group_id", XOBJ_DTYPE_INT, null, false);
		$this->initVar("group_name", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("group_info", XOBJ_DTYPE_TXTAREA, null, false);
		$this->initVar("check_in_date", XOBJ_DTYPE_INT, null, false);
		$this->initVar("check_out_date", XOBJ_DTYPE_INT, null, false);
		$this->initVar("apply_start_date", XOBJ_DTYPE_INT, null, false);
		$this->initVar("apply_end_date", XOBJ_DTYPE_INT, null, false);
		$this->initVar("group_price", XOBJ_DTYPE_INT, null, false);
		$this->initVar("group_can_use_coupon", XOBJ_DTYPE_INT, null, false);
		$this->initVar("group_sented_coupon", XOBJ_DTYPE_INT, null, false);
		$this->initVar("group_status", XOBJ_DTYPE_INT, null, false);
		$this->initVar("group_add_time", XOBJ_DTYPE_INT, null, false);
	}

	function group_id()
	{
		return $this->getVar("group_id");
	}

	function group_name($format = 'S')
	{
		return $this->getVar("group_name",$format);
	}

	function group_info($format = 'edit')
	{
		return $this->getVar("group_info",$format);
	}

	function check_in_date()
	{
		return $this->getVar("check_in_date");
	}
	
	function check_out_date()
	{
		return $this->getVar("check_out_date");
	}
	
	function apply_start_date()
	{
		return $this->getVar("apply_start_date");
	}
	
	function apply_end_date()
	{
		return $this->getVar("apply_end_date");
	}
	
	function group_price()
	{
		return $this->getVar("group_price");
	}
	
	function group_can_use_coupon()
	{
		return $this->getVar("group_can_use_coupon");
	}
	
	function group_sented_coupon()
	{
		return $this->getVar("group_sented_coupon");
	}
	
	function group_status()
	{
		return $this->getVar("group_status");
	}
	
	function group_add_time()
	{
		return $this->getVar("group_add_time");
	}

}

/**
 * @method: groupHandler
 * @license http://www.blags.org/
 * @created:2010年05月21日 20时40分
 * @copyright 1997-2010 The Martin Group
 * @author Martin <china.codehome@gmail.com> 
 * */
class MartinGroupHandler extends XoopsObjectHandler
{
	
	/**
	* create a new hotel city
	* @param bool $isNew flag the new objects as "new"?
	* @return object group
	*/
	function &create($isNew = true)
	{
		$group = new MartinGroup();
		if ($isNew) {
			$group->setNew();
		}
		return $group;
	}

	/**
	* retrieve a hotel city
	*
	* @param int $id groupid of the group
	* @return mixed reference to the {@link group} object, FALSE if failed
	*/
	function &get($id)
	{
		if (intval($id) <= 0) {
			return false;
		}

		$criteria = new CriteriaCompo(new Criteria('group_id', $id));
		$criteria->setLimit(1);
        $obj_array = $this->getObjects($criteria);
        if (count($obj_array) != 1) {
            $obj = $this->create();
            return $obj;
        }
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
	function &getGroups($limit=0, $start=0, $sort='group_add_time', $order='DESC', $id_as_key = true)
	{
		$criteria = new CriteriaCompo();

		$criteria->setSort($sort);
		$criteria->setOrder($order);

		$criteria->setStart($start);
		$criteria->setLimit($limit);
		return $this->getObjects($criteria, $id_as_key);
	}

	/**
	* insert a new group in the database
	*
	* @param object $group reference to the {@link group} object
	* @param bool $force
	* @return bool FALSE if failed, TRUE if already present and unchanged or successful
	*/
	function insert(&$group, $force = false)
	{

		if (strtolower(get_class($group)) != 'martingroup') {
			return false;
		}

		if (!$group->cleanVars()) {
			return false;
		}

		foreach ($group->cleanVars as $k => $v) {
			${$k} = $v;
		}

		if ($group->isNew()) {
			$sql = sprintf("INSERT INTO %s (
								group_id,
								group_name,
								group_info,
								check_in_date,
								check_out_date,
								apply_start_date,
								apply_end_date,
								group_price,
								group_can_use_coupon,
								group_sented_coupon,
								group_status,
								group_add_time
							) VALUES (
								NULL,
								%s,%s,%u,%u,%u,%u,%u,%u,%u,%u,%u
							)",
								$this->db->prefix('martin_group'),
								$this->db->quoteString($group_name),
								$this->db->quoteString($group_info),
								$check_in_date,
								$check_out_date,
								$apply_start_date,
								$apply_end_date,
								$group_price,
								$group_can_use_coupon,
								$group_sented_coupon,
								$group_status,$group_add_time
								);
		} else {
			$sql = sprintf("UPDATE %s SET
								group_name = %s,
								group_info = %s,
								check_in_date = %u,
								check_out_date = %u,
								apply_start_date = %u,
								apply_end_date = %u,
								group_price = %u,
								group_can_use_coupon = %u,
								group_sented_coupon = %u,
								group_status = %u
							WHERE group_id = %u",
								$this->db->prefix('martin_group'),
								$this->db->quoteString($group_name),
								$this->db->quoteString($group_info),
								$check_in_date,
								$check_out_date,
								$apply_start_date,
								$apply_end_date,
								$group_price,
								$group_can_use_coupon,
								$group_sented_coupon,
								$group_status,
								$group_id
							);
		}
		//echo $sql;exit;
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}

		return $group_id > 0 ? $group_id : $this->db->getInsertId();

	}

	/**
	 * @删除一个城市
	 * @method:delete(group_id)
	 * @license http://www.blags.org/
	 * @created:2010年05月21日 20时40分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function delete(&$group, $force = false)
	{

		if (strtolower(get_class($group)) != 'martingroup') {
			return false;
		}
		
		$sql = "DELETE FROM ".$this->db->prefix("martin_group")." WHERE group_id = " .$group->group_id();
	
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		
		$sql = "DELETE FROM ".$this->db->prefix("martin_group_room")." WHERE group_id = ".$group->group_id();
		
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
		$sql = 'DELETE FROM '.$this->db->prefix('martin_group');
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
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('martin_group');
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
		$sql = 'SELECT * FROM '.$this->db->prefix('martin_group');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
			if ($criteria->getSort() != '') {
				$sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
			}
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$sql .= " order by  apply_start_date DESC , group_id DESC ";
		//echo "<br />" . $sql . "<br />";
		$result = $this->db->query($sql, $limit, $start);

		if (!$result) {
			return $ret;
		}

		$theObjects = array();

		while ($myrow = $this->db->fetchArray($result)) {
			$group = new MartinGroup();
			$group->assignVars($myrow);
			$theObjects[$myrow['group_id']] =& $group;
			//var_dump($group);
			unset($group);
		}
		//var_dump($theObjects);

		foreach ($theObjects as $theObject) {

			if (!$id_as_key) {
				$ret[] =& $theObject;
			} else {
				$ret[$theObject->group_id()] =& $theObject;
			}
			unset($theObject);
		}

		return $ret;
	}

	/**
	 * @get room list 
	 * @license http://www.blags.org/
	 * @created:2010年06月03日 20时05分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function getRoomList($group_id)
	{
		global $xoopsDB;
		if(empty($group_id)) return false;
		$sql = "SELECT gr.room_id,gr.room_count,r.room_name FROM " .$xoopsDB->prefix("martin_group_room")." gr
			left join ".$xoopsDB->prefix("martin_room")." r ON r.room_id = gr.room_id 
			WHERE group_id = ".$group_id;
		$result = $xoopsDB->query($sql);
		$rows = array();
		while($row = $xoopsDB->fetchArray($result))
		{
			$rows[] = $row;
		}
		return $rows;
	}

	function InsertGroupRoom($group_id,$room_ids,$room_counts,$isNew)
	{
		global $xoopsDB;
		if(!$group_id || !is_array($room_ids)) 
		{
			// delete data
			$sql = "delete FROM ".$xoopsDB->prefix("martin_group")." WHERE group_id = ".$group_id;
			if($group_id > 0 ) $xoopsDB->query($sql);
			return false;
		}
		$dsql = 'delete FROM '.$xoopsDB->prefix("martin_group_room")." WHERE group_id = $group_id";
		$xoopsDB->query($dsql);
		
		$sql = "insert INTO ".$xoopsDB->prefix("martin_group_room")." (group_id,room_id,room_count) VALUES ";
		foreach($room_ids as $key => $room_id)
		{
			$room_count = $room_counts[$key];
			$sql .= $prefix . "($group_id,$room_id,$room_count)";
			$prefix = ",";
		}
		//echo $sql;
		return $xoopsDB->query($sql);
	}

	/**
	 * @get room by hotel
	 * @license http://www.blags.org/
	 * @created:2010年06月03日 20时05分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function GetRoomListByHotel($hotel_id)
	{
		global $xoopsDB;
		$sql = "SELECT room_id,room_name FROM ".$xoopsDB->prefix("martin_room");
		$sql .= $hotel_id > 0 ? " WHERE hotel_id = ".$hotel_id : " ";
		$result = $xoopsDB->query($sql);
		$rows = array();
		while($row = $xoopsDB->fetchArray($result))
		{
			$rows[$row['room_id']] = $row['room_name'];
		}
		return $rows;
	}

	/**
	 * @get top group list
	 * @license http://www.blags.org/
	 * @created:2010年06月20日 13时09分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function GetGroupList($limit = 6)
	{
		global $xoopsDB;
		$sql = 'SELECT * FROM '.$xoopsDB->prefix('martin_group').' WHERE group_status = 1 AND apply_end_date > '.time().' order by apply_end_date , group_id DESC limit '.$limit;
		return $this->GetRows($sql);
	}

	/**
	 * @get Group rooms
	 * @license http://www.blags.org/
	 * @created:2010年06月20日 13时09分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function GetGroupRooms($group_id)
	{
		global $xoopsDB;
		if(!$group_id) return $group_id;
		$sql = 'SELECT gr.*,r.*,rt.room_type_info,h.* FROM '.$xoopsDB->prefix("martin_group_room").' gr ';
		$sql .= ' INNER JOIN '.$xoopsDB->prefix('martin_room').' r ON ( r.room_id = gr.room_id ) ';
		$sql .= ' INNER JOIN '.$xoopsDB->prefix('martin_room_type').' rt ON ( r.room_type_id = rt.room_type_id ) ';
		$sql .= ' INNER JOIN '.$xoopsDB->prefix('martin_hotel').' h ON ( r.hotel_id = h.hotel_id ) ';
		$sql .= ' WHERE gr.group_id = '.$group_id;
		//echo $sql;
		return $this->GetRows($sql);
	}

	/**
	 * @add user join group
	 * @method:
	 * @license http://www.blags.org/
	 * @created:2010年06月22日 20时19分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function AddUserGroup($Data)
	{
		global $xoopsDB;
		if(!is_array($Data) || empty($Data)) return $Data;
		$sql = 'INSERT INTO '.$xoopsDB->prefix('martin_group_join').' (%s) VALUES (%s) ';
		foreach($Data as $key => $value)
		{
			$keys .= $prefix . $key;
			$values .= $prefix . $value;
			$prefix = ',';
		}
		$sql = sprintf($sql,$keys,$values);
		//echo $sql;
		$xoopsDB->query($sql);
		return $xoopsDB->getInsertId();	
	}

	/**
	 * @get group join list 
	 * @method:
	 * @license http://www.blags.org/
	 * @created:2010年06月22日 20时19分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function getGroupJoinList($group_id)
	{
		if(!$group_id) return false;
		global $xoopsDB;
		$sql = 'SELECT j.*,u.uname FROM '.$xoopsDB->prefix('martin_group_join').' j ';
		$sql .= 'INNER JOIN '.$xoopsDB->prefix('users').' u ON (u.uid = j.uid) ';
		$sql .= 'WHERE j.group_id = '.$group_id.' ';
		$sql .= 'ORDER BY j.join_id DESC ';
		return $this->GetRows($sql);
	}

	/**
	 * @check group join exist
	 * @license http://www.blags.org/
	 * @created:2010年06月22日 20时19分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function CheckJoinExist($Data)
	{
		global $xoopsDB;
		$sql = ' SELECT * FROM '.$xoopsDB->prefix('martin_group_join') . " WHERE uid = {$Data['uid']} 
			AND group_id = {$Data['group_id']} ";
		$rows = $this->GetRows($sql);
		return is_array($rows) && !empty($rows);
	}

}

?>
