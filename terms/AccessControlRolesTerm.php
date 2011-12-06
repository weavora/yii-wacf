<?php

/**
 *  @author Weavora Team <hello@weavora.com>
 * 	@link http://weavora.com
 * 	@copyright Copyright (c) 2011 Weavora LLC
 */
class AccessControlRolesTerm extends AccessControlTerm
{

	public function match()
	{
		if (empty($this->params))
			return true;

		$user = Yii::app()->getUser();

		foreach ($this->params as $role) {
			if ($user->checkAccess($role))
				return true;
		}
		return false;
	}

}
