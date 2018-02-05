<?php
class RoleControl extends Control
{
	/**
	 * 新增角色
	 * @param integer $parentID 权限父级ID
	 * @param string $name 角色名称
	 * @return void
	 */
	public function add($parentID = 0, $name)
	{
		$roleModel = new \Yurun\Auth\Model\RoleModel;
		$id = $roleModel->add(array(
			'name'		=>	$name,
			'parent_id'	=>	$parentID,
		));
		if($id > 0)
		{
			echo 'success,ruleID:', $id;
		}
		else
		{
			echo $roleModel->error;
		}
	}

	/**
	 * 修改角色
	 * @param integer $id 角色ID
	 * @param string $name 角色名称
	 * @param integer $parentID 权限父级ID
	 * @return void
	 */
	public function update($id, $name = null, $parentID = null)
	{
		$roleModel = new \Yurun\Auth\Model\RoleModel;
		$data = array();
		if(null !== $name)
		{
			$data['name'] = $name;
		}
		if(null !== $parentID)
		{
			$data['parent_id'] = $parentID;
		}
		$result = $roleModel->wherePk($id)->edit($data);
		if($result)
		{
			echo 'success';
		}
		else
		{
			echo $roleModel->error;
		}
	}

	/**
	 * 删除角色
	 * @param integer $id 角色ID
	 * @return void
	 */
	public function delete($id)
	{
		$roleModel = new \Yurun\Auth\Model\RoleModel;
		$result = $roleModel->wherePk($id)->delete();
		if($result)
		{
			echo 'success';
		}
		else
		{
			echo $roleModel->error;
		}
	}
}