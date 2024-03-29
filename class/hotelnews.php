<?php
/**
 * @get martin hotel news
 * @license http://www.blags.org/
 * @created:2010年06月29日 20时38分
 * @copyright 1997-2010 The Martin Group
 * @author Martin <china.codehome@gmail.com> 
 * */
class MartinHotelNews extends XoopsObject
{
	
}

/**
 * @get martin hotel news handler
 * @license http://www.blags.org/
 * @created:2010年06月29日 20时38分
 * @copyright 1997-2010 The Martin Group
 * @author Martin <china.codehome@gmail.com> 
 * */
class MartinHotelNewsHandler extends XoopsObjectHandler
{

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
	 * @get hotel news 
	 * @method:
	 * @license http://www.blags.org/
	 * @created:2010年06月29日 20时38分
	 * @copyright 1997-2010 The Martin Group
	 * @author Martin <china.codehome@gmail.com> 
	 * */
	public function GetHotelNews($artids)
	{
		global $xoopsDB;
		if(empty($artids) || !is_array($artids)) return $artids;
		$artids = implode(",",$artids);
		$sql = "SELECT text_id,alias FROM ".$xoopsDB->prefix("news_text")." WHERE art_id IN ($artids)";
		$text_rows = $this->GetRows($sql,'text_id');
		
		$sql = "SELECT art_id,cat_alias,art_title,art_pages FROM ".$xoopsDB->prefix("news_article")."	WHERE art_id IN ($artids) ORDER BY art_time_publish DESC";
		$result = $xoopsDB->query($sql);
		$rows = array();
		while($row = $xoopsDB->fetchArray($result))
		{
			$text_id = unserialize($row['art_pages']);
			$text_id = $text_id[0];
			$url = $text_rows[$text_id]['alias'];
			$url = XOOPS_URL . $row['cat_alias'] . $url;
			$rows[$row['art_id']] = array('url'=>$url,'title'=>$row['art_title']);
		}
		return $rows;
	}
}

