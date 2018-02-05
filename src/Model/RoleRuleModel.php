<?php

namespace Yurun\Auth\Model;

/**
 * RoleRuleModel
 */
class RoleRuleModel extends BaseModel
{
	public $table = 'role_rule';

	public function selectRoleRules($roleID)
	{
		if(!is_array($roleID))
		{
			$roleID = [$roleID];
		}
		return $this->field('rule.*')
					->join('', $this->tableName('rule') . ' as rule', $this->tableName() . '.rule_id=rule.id')
					->where(array('role_id'=>array('in', $roleID)))
					->select();
	}
}