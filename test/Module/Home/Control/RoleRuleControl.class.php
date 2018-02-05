<?php
class RoleRuleControl extends Control
{
	public function select($roleID)
	{
		$role = new \Yurun\Auth\Role($roleID);
		var_dump('list:', $role->selectRules());
		var_dump('tree:', $role->selectRules(\Yurun\Auth\Consts\ListFormat::TREE));
	}

	/**
	 * 赋予角色权限
	 * @param integer $roleID 角色ID
	 * @param integer $ruleID 权限ID
	 * @return void
	 */
	public function add($roleID, $ruleID)
	{
		$role = new \Yurun\Auth\Role($roleID);
		if($role->addRule($ruleID))
		{
			echo 'success';
		}
		else
		{
			echo $role->error;
		}
	}

	/**
	 * 保存角色权限
	 * @param integer $roleID 角色ID
	 * @param integer $ruleIDs 权限ID们
	 * @return void
	 */
	public function save($roleID, $ruleIDs)
	{
		$role = new \Yurun\Auth\Role($roleID);
		if($role->saveRule(explode(',', $ruleIDs)))
		{
			echo 'success';
		}
		else
		{
			echo $role->error;
		}
	}

	/**
	 * 移除角色权限
	 * @param integer $roleID 角色ID
	 * @param integer $ruleID 权限ID
	 * @return void
	 */
	public function delete($roleID, $ruleID)
	{
		$role = new \Yurun\Auth\Role($roleID);
		if($role->deleteRule($ruleID))
		{
			echo 'success';
		}
		else
		{
			echo $role->error;
		}
	}

	/**
	 * 是否拥有权限
	 * @param integer $roleID 角色ID
	 * @param integer $ruleID 权限ID
	 * @return void
	 */
	public function has($roleID, $ruleID)
	{
		$role = new \Yurun\Auth\Role($roleID);
		if($role->hasRule($ruleID))
		{
			echo 'has';
		}
		else
		{
			echo 'none';
		}
	}
}