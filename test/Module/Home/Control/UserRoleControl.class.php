<?php
class UserRoleControl extends Control
{
	public function select($userID)
	{
		$user = new \Yurun\Auth\User($userID);
		var_dump('list:', $user->selectRoles());
		var_dump('tree:', $user->selectRoles(\Yurun\Auth\Consts\ListFormat::TREE));
	}

	/**
	 * 赋予用户角色
	 * @param integer $userID 用户ID
	 * @param integer $roleID 角色ID
	 * @return void
	 */
	public function add($userID, $roleID)
	{
		$user = new \Yurun\Auth\User($userID);
		if($user->addRole($roleID))
		{
			echo 'success';
		}
		else
		{
			echo $user->error;
		}
	}

	/**
	 * 保存用户角色
	 * @param integer $userID 用户ID
	 * @param integer $roleIDs 角色ID们
	 * @return void
	 */
	public function save($userID, $roleIDs)
	{
		$user = new \Yurun\Auth\User($userID);
		if($user->saveRole(explode(',', $roleIDs)))
		{
			echo 'success';
		}
		else
		{
			echo $user->error;
		}
	}

	/**
	 * 移除角色权限
	 * @param integer $userID 用户ID
	 * @param integer $roleID 角色ID
	 * @return void
	 */
	public function delete($userID, $roleID)
	{
		$user = new \Yurun\Auth\User($userID);
		if($user->deleteRole($roleID))
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
	 * @param integer $roleID 角色ID
	 * @return void
	 */
	public function has($userID, $roleID)
	{
		$user = new \Yurun\Auth\User($userID);
		if($user->hasRole($roleID))
		{
			echo 'has';
		}
		else
		{
			echo 'none';
		}
	}
}