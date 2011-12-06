<?php

/**
 *  @author Weavora Team <hello@weavora.com>
 * 	@link http://weavora.com
 * 	@copyright Copyright (c) 2011 Weavora LLC
 */
class AccessControlUsersTerm extends AccessControlTerm
{

	public function match()
	{
		if (empty($this->params))
			return true;

		$user = Yii::app()->getUser();

		foreach ($this->params as $u) {
			if ($u === '*')
				return true;
			else if ($u === '?' && $user->getIsGuest())
				return true;
			else if ($u === '@' && !$user->getIsGuest())
				return true;
			else if (!strcasecmp($u, $user->getName()))
				return true;
		}
		return false;
	}

}
