<?php

/**
 * 	@author Weavora Team <hello@weavora.com>
 * 	@link http://weavora.com
 * 	@copyright Copyright (c) 2011 Weavora LLC
 */
class AccessControlActionsTerm extends AccessControlTerm
{

	public function match()
	{
		return empty($this->params) || in_array(strtolower(Yii::app()->controller->action->getId()), $this->params);
	}

}