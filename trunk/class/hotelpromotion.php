<?php
/**
* $Id: promotion.php,v 1.42 2007/02/04 15:01:40 malanciault Exp $
* Module:martin 
* Licence: GNU
*/

if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

include_once XOOPS_ROOT_PATH.'/modules/martin/include/common.php';

class MartinHotelPromotion extends XoopsObject
{

	function MartinHotelPromotion()
	{
		$this->initVar("promotion_id", XOBJ_DTYPE_INT, null, false);
		$this->initVar("hotel_id", XOBJ_DTYPE_INT, null, false);
		$this->initVar("hotel_name", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("promotion_start_date", XOBJ_DTYPE_INT, null, false);
		$this->initVar("promotion_end_date", XOBJ_DTYPE_INT, null, false);
		$this->initVar("promotion_description", XOBJ_DTYPE_TXTAREA, null, false);
		$this->initVar("promotion_add_time", XOBJ_DTYPE_INT, null, false);
	}

	function promotion_id()
	{
		return $this->getVar("promotion_id");
	}

	function hotel_id()
	{
		return $this->getVar("hotel_id");
	}
	
	function hotel_name()
	{
		return $this->getVar("hotel_name");
	}

	function promotion_start_date()
	{
		return $this->getVar("promotion_start_date");
	}
	
	function promotion_end_date()
	{
		return $this->getVar("promotion_end_date");
	}

	function promotion_description($format = 'edit')
	{
		return $this->getVar("promotion_description",$format);
	}
	
	function promotion_add_time()
	{
		return $this->getVar("promotion_add_time");
	}
}

/**
 * @method: MartinHotelPromotionHandler
 * @license http://www.blags.org/
 * @created:2010年05月21日 20时40分
 * @copyright 1997-2010 The Martin promotion
 * @author Martin <china.codehome@gmail.com> 
 * */
class MartinHotelPromotionHandler extends XoopsObjectHandler
{
	
	/**
	* create a new hotel city
	* @param bool $isNew flag the new objects as "new"?
	* @return object promotion
	*/
	function &create($isNew = true)
	{
		$promotion = new MartinHotelPromotion();
		if ($isNew) {
			$promotion->setNew();
		}
		return $promotion;
	}

	/**
	* retrieve a hotel city
	*
	* @param int $id promotionid of the hotel promotion 
	* @return mixed reference to the {@link promotion} object, FALSE if failed
	*/
	function &get($id)
	{
		if (intval($id) <= 0) {
			return false;
		}

		$criteria = new CriteriaCompo(new Criteria('promotion_id', $id));
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
	 * @copyright 1997-2010 The Martin promotion
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function &getPromotions($limit=0, $start=0, $sort='promotion_add_time', $order='DESC', $id_as_key = true)
	{
		$criteria = new CriteriaCompo();

		$criteria->setSort($sort);
		$criteria->setOrder($order);

		$criteria->setStart($start);
		$criteria->setLimit($limit);
		return $this->getObjects($criteria, $id_as_key);
	}

	/**
	* insert a new promotion in the database
	*
	* @param object $promotion reference to the {@link hotel promotion} object
	* @param bool $force
	* @return bool FALSE if failed, TRUE if already present and unchanged or successful
	*/
	function insert(&$promotion, $force = false)
	{

		if (strtolower(get_class($promotion)) != 'martinhotelpromotion') {
			return false;
		}

		if (!$promotion->cleanVars()) {
			return false;
		}

		foreach ($promotion->cleanVars as $k => $v) {
			${$k} = $v;
		}

		if ($promotion->isNew()) {
			$sql = sprintf("INSERT INTO %s (
								promotion_id,
								promotion_description,
								hotel_id,
								promotion_start_date,
								promotion_end_date,
								promotion_add_time
							) VALUES (
								NULL,
								%s,%u,%u,%u,%u
							)",
								$this->db->prefix('martin_hotel_promotions'),
								$this->db->quoteString($promotion_description),
								$hotel_id,
								$promotion_start_date,
								$promotion_end_date,
								$promotion_add_time
								);
		} else {
			$sql = sprintf("UPDATE %s SET
								promotion_description = %s,
								hotel_id = %u,
								promotion_start_date = %u,
								promotion_end_date = %u
							WHERE promotion_id = %u",
								$this->db->prefix('martin_hotel_promotions'),
								$this->db->quoteString($promotion_description),
								$hotel_id,
								$promotion_start_date,
								$promotion_end_date,
								$promotion_id
							);
		}
		//echo $sql;exit;
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}

		return $promotion_id > 0 ? $promotion_id : $this->db->getInsertId();

	}

	/**
	 * @删除一个城市
	 * @method:delete(promotion_id)
	 * @license http://www.blags.org/
	 * @created:2010年05月21日 20时40分
	 * @copyright 1997-2010 The Martin promotion
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function delete(&$promotion, $force = false)
	{

		if (strtolower(get_class($promotion)) != 'martinhotelpromotion') {
			return false;
		}
		
		$sql = "DELETE FROM ".$this->db->prefix("martin_hotel_promotions")." WHERE promotion_id = ".$promotion->promotion_id();
		
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
		$sql = 'DELETE FROM '.$this->db->prefix('martin_hotel_promotions');
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
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('martin_hotel_promotions');
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
	 * @copyright 1997-2010 The Martin promotion
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT p.*,h.hotel_name FROM '.$this->db->prefix('martin_hotel_promotions') . ' p ';
		$sql .= ' left join '.$this->db->prefix('martin_hotel').' h on ( h.hotel_id = p.hotel_id ) ';
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
			if ($criteria->getSort() != '') {
				$sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
			}
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$sql .= " order by p.promotion_id DESC ";
		//echo "<br />" . $sql . "<br />";
		$result = $this->db->query($sql, $limit, $start);

		if (!$result) {
			return $ret;
		}

		$theObjects = array();

		while ($myrow = $this->db->fetchArray($result)) {
			$promotion = new MartinHotelPromotion();
			$promotion->assignVars($myrow);
			$theObjects[$myrow['promotion_id']] =& $promotion;
			//var_dump($promotion);
			unset($promotion);
		}
		//var_dump($theObjects);

		foreach ($theObjects as $theObject) {

			if (!$id_as_key) {
				$ret[] =& $theObject;
			} else {
				$ret[$theObject->promotion_id()] =& $theObject;
			}
			unset($theObject);
		}

		return $ret;
	}

	/**
	 * @get hotel promotion
	 * @license http://www.blags.org/
	 * @created:2010年06月14日 20时47分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	function getHotelPromotion($hotel_id)
	{
		global $xoopsDB;
		if(!$hotel_id > 0) return '';
		$sql = 'SELECT * FROM ' . $xoopsDB->prefix('martin_hotel_promotions') . ' WHERE
			'.time().' BETWEEN  promotion_start_date AND promotion_end_date  AND hotel_id = '.$hotel_id.'  limit 1 ';
		return $xoopsDB->fetchArray($xoopsDB->query($sql));
	} 

}

?>
