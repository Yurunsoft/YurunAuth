<?php
namespace Yurun\Auth;

class Helper
{
	/**
	 * 二维数组去重
	 * @param array $array
	 * @return array
	 */
	public function uniqueArray($array)
	{
		return \array_values(\array_map('unserialize', \array_unique(\array_map('serialize', $array))));
	}

	/**
	 * 将列表中每项的某字段作为键值放进数组
	 * @param array $list 
	 * @param string $fieldName 
	 * @return array 
	 */
	public function parseArrayFieldToKey($list, $fieldName)
	{
		$result = array();
		foreach($list as $item)
		{
			$result[$item[$fieldName]] = $item;
		}
		return $result;
	}
}