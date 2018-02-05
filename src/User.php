<?php

namespace Yurun\Auth;

use Yurun\Auth\Consts\ListFormat;

class User
{
	/**
	 * 用户ID
	 * @var int
	 */
	public $userID;

	/**
	 * UserRoleModel
	 * @var Yurun\Auth\Model\UserRoleModel
	 */
	public $userRoleModel;

	/**
	 * UserRuleModel
	 * @var Yurun\Auth\Model\UserRuleModel
	 */
	public $userRuleModel;

	/**
	 * RoleRuleModel
	 * @var Yurun\Auth\Model\RoleRuleModel
	 */
	public $roleRuleModel;

	/**
	 * 错误信息
	 * @var string
	 */
	public $error;

	/**
	 * 当前用户的权限
	 * @var array
	 */
	public $rules;

	/**
	 * 当前用户的角色
	 * @var array
	 */
	public $roles;

	/**
	 * 不触发事件
	 * @var boolean
	 */
	protected $noEvents = false;

	public function __construct($userID)
	{
		$this->userID = $userID;
		$this->userRoleModel = new Model\UserRoleModel;
		$this->userRuleModel = new Model\UserRuleModel;
		$this->roleRuleModel = new Model\RoleRuleModel;
		$this->init();
	}

	protected function init()
	{
		$this->selectRoles();
		$this->selectRules();
		\Yurun\Until\Event::on('YURUN_AUTH_ROLE_RULE_CHANGED', function($params){
			if(isset($this->roles[$params['roleID']]))
			{
				$this->selectRoles();
				$this->selectRules();
			}
		});
	}

	/**
	 * 查询用户的所有角色
	 * @param int $format 查询出来的列表格式，ListFormat::XXX
	 * @return array
	 */
	public function selectRoles($format = ListFormat::LIST)
	{
		$this->error = '';
		$arr1 = $this->userRoleModel->selectUserRoles($this->userID);
		$this->roles = \Yurun\Auth\Helper::parseArrayFieldToKey($arr1, 'id');
		switch($format)
		{
			case ListFormat::LIST:
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
	 * 查询用户拥有的所有权限
	 * @param int $format 查询出来的列表格式，ListFormat::XXX
	 * @return array
	 */
	public function selectRules($format = ListFormat::LIST)
	{
		$this->error = '';
		$arr1 = \Yurun\Auth\Helper::uniqueArray(array_merge(
			$this->userRuleModel->selectUserRules($this->userID),
			$this->roleRuleModel->selectRoleRules(array_column($this->selectRoles(), 'id'))
		));
		$this->rules = \Yurun\Auth\Helper::parseArrayFieldToKey($arr1, 'id');
		switch($format)
		{
			case ListFormat::LIST:
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
	 * 获取用户是否是某个角色
	 * @param int|array<int> $roleID 角色ID或角色ID数组
	 * @return boolean
	 */
	public function hasRole($roleID)
	{
		return isset($this->roles[$roleID]);
	}

	/**
	 * 获取用户是否有某个权限
	 * @param int|array<int> $ruleID 权限ID或权限ID数组
	 * @return boolean
	 */
	public function hasRule($ruleID)
	{
		return isset($this->rules[$ruleID]);
	}

	/**
	 * 赋予用户角色
	 * @param int|array<int> $roleID 角色ID或角色ID数组
	 * @return boolean
	 */
	public function addRole($roleID)
	{
		if($this->hasRole($roleID) ? true : $this->userRoleModel->add(array(
			'user_id'	=>	$this->userID,
			'role_id'	=>	$roleID,
		), \Db::RETURN_INSERT_ID) > 0)
		{
			$this->selectRoles();
			$this->selectRules();
			\Yurun\Until\Event::trigger('YURUN_AUTH_USER_ROLE_CHANGED', [
				'userID'	=>	$this->userID,
				'roleID'	=>	$ruleID,
				'operation'	=>	'add',
			]);
			$this->error = '';
			return true;
		}
		else
		{
			$this->error = '赋予用户角色失败';
			return false;
		}
	}

	/**
	 * 移除用户的角色
	 * @param int|array<int> $roleID 角色ID或角色ID数组
	 * @return boolean
	 */
	public function deleteRole($roleID)
	{
		if($this->userRoleModel->where(array(
			'user_id'	=>	$this->userID,
			'role_id'	=>	$roleID,
		))->delete(null, \Db::RETURN_ROW) > 0)
		{
			$this->selectRoles();
			$this->selectRules();
			\Yurun\Until\Event::trigger('YURUN_AUTH_USER_ROLE_CHANGED', [
				'userID'	=>	$this->userID,
				'roleID'	=>	$ruleID,
				'operation'	=>	'delete',
			]);
			$this->error = '';
			return true;
		}
		else
		{
			$this->error = '移除用户角色失败';
			return false;
		}
	}

	/**
	 * 赋予用户权限
	 * @param int|array<int> $roleID 权限ID或权限ID数组
	 * @return boolean
	 */
	public function addRule($ruleID)
	{
		if($this->hasRule($ruleID) ? true : $this->userRuleModel->add(array(
			'user_id'	=>	$this->userID,
			'rule_id'	=>	$ruleID,
		), \Db::RETURN_INSERT_ID) > 0)
		{
			$this->selectRoles();
			$this->selectRules();
			\Yurun\Until\Event::trigger('YURUN_AUTH_USER_RULE_CHANGED', [
				'userID'	=>	$this->userID,
				'ruleID'	=>	$ruleID,
				'operation'	=>	'add',
			]);
			$this->error = '';
			return true;
		}
		else
		{
			$this->error = '赋予用户权限失败';
			return false;
		}
	}

	/**
	 * 移除用户的权限
	 * @param int|array<int> $ruleID 权限ID或权限ID数组
	 * @return boolean
	 */
	public function deleteRule($ruleID)
	{
		if($this->userRuleModel->where(array(
			'user_id'	=>	$this->userID,
			'rule_id'	=>	$ruleID,
		))->delete(null, \Db::RETURN_ROWS) > 0)
		{
			$this->selectRoles();
			$this->selectRules();
			\Yurun\Until\Event::trigger('YURUN_AUTH_USER_RULE_CHANGED', [
				'userID'	=>	$this->userID,
				'ruleID'	=>	$ruleID,
				'operation'	=>	'delete',
			]);
			$this->error = '';
			return true;
		}
		else
		{
			$this->error = '移除用户角色失败';
			return false;
		}
	}

	/**
	 * 保存用户和角色，与数据库中记录进行比对，自动进行赋予和移除角色关联记录
	 * @param array $roleIDs
	 * @return void
	 */
	public function saveRole($roleIDs)
	{
		$this->noEvents = true;
		$oldRoleIDs = $this->userRoleModel->where(array('user_id'=>$this->userID))->field('role_id')->selectColumn('role_id');
		$addRoleIDs = array_diff($roleIDs, $oldRoleIDs);
		foreach($addRuleIDs as $roleID)
		{
			if(!$this->addRole($roleID))
			{
				$this->noEvents = false;
				return false;
			}
		}
		$removeRoleIDs = array_diff($oldRoleIDs, $roleIDs);
		foreach($removeRoleIDs as $roleID)
		{
			if(!$this->deleteRole($roleID))
			{
				$this->noEvents = false;
				return false;
			}
		}
		$this->noEvents = false;
		$this->selectRoles();
		$this->selectRules();
		\Yurun\Until\Event::trigger('YURUN_AUTH_USER_ROLE_SAVE', [
			'roleIDs'	=>	$roleIDs,
			'userID'	=>	$this->userID,
			'operation'	=>	'save',
		]);
		return true;
	}
	
	/**
	 * 保存用户和权限，与数据库中记录进行比对，自动进行赋予和移除权限关联记录
	 * @param array $ruleIDs
	 * @return void
	 */
	public function saveRule($ruleIDs)
	{
		$this->noEvents = true;
		$oldRuleIDs = $this->userRuleModel->where(array('user_id'=>$this->userID))->field('rule_id')->selectColumn('rule_id');
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
		$this->selectRoles();
		$this->selectRules();
		\Yurun\Until\Event::trigger('YURUN_AUTH_USER_RULE_SAVE', [
			'ruleIDs'	=>	$ruleIDs,
			'userID'	=>	$this->userID,
			'operation'	=>	'save',
		]);
		return true;
	}
}