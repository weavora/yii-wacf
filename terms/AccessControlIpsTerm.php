<?php

/**
 *  @author Weavora Team <hello@weavora.com>
 * 	@link http://weavora.com
 * 	@copyright Copyright (c) 2011 Weavora LLC
 */
class AccessControlIpsTerm extends AccessControlTerm
{

	public function match()
	{
		if (empty($this->params))
			return true;

		$ip = Yii::app()->getRequest()->getUserHostAddress();

		foreach ($this->params as $rule) {
			if ($rule === '*' || $rule === $ip || (($pos = strpos($rule, '*')) !== false && !strncmp($ip, $rule, $pos)))
				return true;
		}
		return false;
	}

}