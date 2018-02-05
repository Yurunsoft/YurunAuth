<?php
class RuleControl extends Control
{
	/**
	 * 新增权限
	 * @param integer $parentID 权限父级ID
	 * @param string $name 权限名称
	 * @return void
	 */
	public function add($parentID = 0, $name)
	{
		$ruleModel = new \Yurun\Auth\Model\RuleModel;
		$id = $ruleModel->add(array(
			'name'		=>	$name,
			'parent_id'	=>	$parentID,
		));
		if($id > 0)
		{
			echo 'success,ruleID:', $id;
		}
		else
		{
			echo $ruleModel->error;
		}
	}

	/**
	 * 修改权限
	 * @param integer $id 权限ID
	 * @param string $name 权限名称
	 * @param integer $parentID 权限父级ID
	 * @return void
	 */
	public function update($id, $name = null, $parentID = null)
	{
		$ruleModel = new \Yurun\Auth\Model\RuleModel;
		$data = array();
		if(null !== $name)
		{
			$data['name'] = $name;
		}
		if(null !== $parentID)
		{
			$data['parent_id'] = $parentID;
		}
		$result = $ruleModel->wherePk($id)->edit($data);
		if($result)
		{
			echo 'success';
		}
		else
		{
			echo $ruleModel->error;
		}
	}

	/**
	 * 修改权限
	 * @param integer $id 权限ID
	 * @return void
	 */
	public function delete($id)
	{
		$ruleModel = new \Yurun\Auth\Model\RuleModel;
		$result = $ruleModel->wherePk($id)->delete();
		if($result)
		{
			echo 'success';
		}
		else
		{
			echo $ruleModel->error;
		}
	}
}