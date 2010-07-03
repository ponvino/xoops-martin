<?php
/**
 * @method:日历类
 * @license http://www.blags.org/
 * @created:2010年05月25日 22时33分
 * @copyright 1997-2010 The Martin Group
 * @author Martin <china.codehome@gmail.com> 
 * */
if (!defined("XOOPS_ROOT_PATH")) {
die("XOOPS root path not defined");
}

/**
 * @method: 日期
 * @license http://www.blags.org/
 * @created:2010年05月25日 22时33分
 * @copyright 1997-2010 The Martin Group
 * @author Martin <china.codehome@gmail.com> 
 * */
class MartinFormDateTime extends XoopsFormElementTray
{

	function MartinFormDateTime($caption, $name, $size = 15, $value=0,$is_time = true)
	{
		$this->XoopsFormElementTray($caption, '&nbsp;');
		$value = intval($value);
		$value = ($value > 0) ? $value : time();
		$datetime = getDate($value);
		$this->addElement(new XoopsFormTextDateSelect('', $name.'[date]', $size, $value));
		$timearray = array();
		for ($i = 0; $i < 24; $i++) {
			for ($j = 0; $j < 60; $j = $j + 10) {
				$key = ($i * 3600) + ($j * 60);
				$timearray[$key] = ($j != 0) ? $i.':'.$j : $i.':0'.$j;
			}
		}
		ksort($timearray);
		$timeselect = new XoopsFormSelect('', $name.'[time]', $datetime['hours'] * 3600 + 600 * floor($datetime['minutes'] / 10));
		$timeselect->addOptionArray($timearray);
		$this->addElement($timeselect);
	}
}

?>
