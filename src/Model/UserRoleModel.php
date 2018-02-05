<?php

namespace Yurun\Auth\Model;

/**
 * UserRoleModel
 */
class UserRoleModel extends BaseModel
{
	public $table = 'user_role';

	public function selectUserRoles($userID)
	{
		if(!is_array($userID))
		{
			$userID = [$userID];
		}
		return $this->field('role.*')
					->join('', $this->tableName('role') . ' as role', $this->tableName() . '.role_id=role.id')
					->where(array('user_id'=>array('in', $userID)))
					->select();
	}
}