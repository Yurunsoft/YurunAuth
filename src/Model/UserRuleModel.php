<?php

namespace Yurun\Auth\Model;

/**
 * UserRuleModel
 */
class UserRuleModel extends BaseModel
{
	public $table = 'user_rule';

	public function selectUserRules($userID)
	{
		if(!is_array($userID))
		{
			$userID = [$userID];
		}
		return $this->field('rule.*')
					->join('', $this->tableName('rule') . ' as rule', $this->tableName() . '.rule_id=rule.id')
					->where(array('user_id'=>array('in', $userID)))
					->select();
	}
}