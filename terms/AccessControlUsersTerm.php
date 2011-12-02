<?php

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
