<?php
class UserRuleControl extends Control
{
	public function select($userID)
	{
		$user = new \Yurun\Auth\User($userID);
		var_dump('list:', $user->selectRules());
		var_dump('tree:', $user->selectRules(\Yurun\Auth\Consts\ListFormat::TREE));
	}

	/**
	 * 赋予用户权限
	 * @param integer $userID 用户ID
	 * @param integer $ruleID 权限ID
	 * @return void
	 */
	public function add($userID, $ruleID)
	{
		$user = new \Yurun\Auth\User($userID);
		if($user->addRule($ruleID))
		{
			echo 'success';
		}
		else
		{
			echo 'error';
		}
	}

	/**
	 * 保存用户权限
	 * @param integer $userID 用户ID
	 * @param integer $ruleIDs 权限ID们
	 * @return void
	 */
	public function save($userID, $ruleIDs)
	{
		$user = new \Yurun\Auth\User($userID);
		$user->saveRule(explode(',', $ruleIDs));
	}

	/**
	 * 移除权限权限
	 * @param integer $userID 用户ID
	 * @param integer $ruleID 权限ID
	 * @return void
	 */
	public function delete($userID, $ruleID)
	{
		$user = new \Yurun\Auth\User($userID);
		if($user->deleteRule($ruleID))
		{
			echo 'success';
		}
		else
		{
			echo 'error';
		}
	}

	/**
	 * 是否拥有权限
	 * @param integer $userID 用户ID
	 * @param integer $ruleID 权限ID
	 * @return void
	 */
	public function has($userID, $ruleID)
	{
		$user = new \Yurun\Auth\User($userID);
		if($user->hasRule($ruleID))
		{
			echo 'has';
		}
		else
		{
			echo 'none';
		}
	}
}