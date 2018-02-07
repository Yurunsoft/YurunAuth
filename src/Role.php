<?php

namespace Yurun\Auth;

use \Yurun\Auth\Consts\ListFormat;

class Role
{
	/**
	 * 角色ID
	 * @var int
	 */
	public $roleID;

	/**
	 * RoleModel
	 * @var Yurun\Auth\Model\RoleModel
	 */
	public $roleModel;
	
	/**
	 * RoleRuleModel
	 * @var Yurun\Auth\Model\RoleRuleModel
	 */
	public $roleRuleModel;

	/**
	 * 角色数据
	 * @var array
	 */
	public $data;

	/**
	 * 角色是否存在
	 * @var boolean
	 */
	public $exists;

	/**
	 * 错误信息
	 * @var string
	 */
	public $error;

	/**
	 * 不触发事件
	 * @var boolean
	 */
	protected $noEvents = false;

	public function __construct($roleID)
	{
		$this->roleModel = new Model\RoleModel;
		$this->roleRuleModel = new Model\RoleRuleModel;
		$this->find($roleID);
	}

	/**
	 * 获取角色信息
	 * @param int $roleID 角色ID
	 * @return array
	 */
	public function find($roleID)
	{
		$this->roleID = $roleID;
		$this->data = $this->roleModel->getByPk($roleID);
		$this->exists = isset($this->data['id']);
		if($this->exists)
		{
			$this->error = '';
		}
		else
		{
			$this->error = '角色不存在';
		}
		return $this->data;
	}

	/**
	 * 查询角色拥有的所有权限
	 * @param int $format 查询出来的列表格式，ListFormat::XXX
	 * @return array
	 */
	public function selectRules($format = ListFormat::LIST_ARRAY)
	{
		$this->error = '';
		$arr1 = $this->roleRuleModel->selectRoleRules($this->roleID);
		switch($format)
		{
			case ListFormat::LIST_ARRAY:
				return $arr1;
				break;
			case ListFormat::TREE:
				$arr2 = array();
				// 处理成ID为键名的数组
				foreach($arr1 as $val)
				{
					$arr2[$val['id']] = $val;
				}
				// 节省内存
				unset($arr1, $val);
				// 结果数组
				$result = array();
				// 循环处理关联列表
				foreach($arr2 as $item)
				{
					if(isset($arr2[$item['parent_id']]))
					{
						$arr2[$item['parent_id']]['children'][] = &$arr2[$item['id']];
					}
					else
					{
						$result[] = &$arr2[$item['id']];
					}
				}
				return $result;
				break;
		}
		$this->error = 'format 类型错误';
		return false;
	}

	/**
	 * 获取角色是否有某个权限
	 * @param int|array<int> $ruleID 权限ID或权限ID数组
	 * @return boolean
	 */
	public function hasRule($ruleID)
	{
		return $this->roleRuleModel->where(
			array(
				'role_id'	=>	$this->roleID,
				'rule_id'	=>	$ruleID,
			)
		)->limit(1)->getScalar('id') > 0;
	}
	
	/**
	 * 赋予角色权限
	 * @param int|array<int> $roleID 权限ID或权限ID数组
	 * @return boolean
	 */
	public function addRule($ruleID)
	{
		if($this->hasRule($ruleID) ? true : $this->roleRuleModel->add(array(
			'role_id'	=>	$this->roleID,
			'rule_id'	=>	$ruleID,
		), \Db::RETURN_INSERT_ID) > 0)
		{
			if(!$this->noEvents)
			{
				\Yurun\Until\Event::trigger('YURUN_AUTH_ROLE_RULE_CHANGED', [
					'ruleID'	=>	$ruleID,
					'roleID'	=>	$this->roleID,
					'operation'	=>	'add',
				]);
			}
			$this->error = '';
			return true;
		}
		else
		{
			$this->error = '赋予角色权限失败';
			return false;
		}
	}

	/**
	 * 移除角色的权限
	 * @param int|array<int> $ruleID 权限ID或权限ID数组
	 * @return boolean
	 */
	public function deleteRule($ruleID)
	{
		if($this->roleRuleModel->where(array(
			'role_id'	=>	$this->roleID,
			'rule_id'	=>	$ruleID,
		))->delete(null, \Db::RETURN_ROWS) > 0)
		{
			if(!$this->noEvents)
			{
				\Yurun\Until\Event::trigger('YURUN_AUTH_ROLE_RULE_CHANGED', [
					'ruleID'	=>	$ruleID,
					'roleID'	=>	$this->roleID,
					'operation'	=>	'delete',
				]);
			}
			$this->error = '';
			return true;
		}
		else
		{
			$this->error = '移除角色权限失败';
			return false;
		}
	}

	/**
	 * 保存角色和权限关联，与数据库中记录进行比对，自动进行赋予和移除权限关联记录
	 * @param array $ruleIDs
	 * @return boolean
	 */
	public function saveRule($ruleIDs)
	{
		$this->noEvents = true;
		$oldRuleIDs = $this->roleRuleModel->where(array('role_id'=>$this->roleID))->field('rule_id')->selectColumn('rule_id');
		$addRuleIDs = array_diff($ruleIDs, $oldRuleIDs);
		foreach($addRuleIDs as $ruleID)
		{
			if(!$this->addRule($ruleID))
			{
				$this->noEvents = false;
				return false;
			}
		}
		$removeRuleIDs = array_diff($oldRuleIDs, $ruleIDs);
		foreach($removeRuleIDs as $ruleID)
		{
			if(!$this->deleteRule($ruleID))
			{
				$this->noEvents = false;
				return false;
			}
		}
		$this->noEvents = false;
		\Yurun\Until\Event::trigger('YURUN_AUTH_ROLE_RULE_CHANGED', [
			'ruleIDs'	=>	$ruleIDs,
			'roleID'	=>	$this->roleID,
			'operation'	=>	'save',
		]);
		return true;
	}
}