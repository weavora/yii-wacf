<?php

/**
 * 	@author Weavora Team <hello@weavora.com>
 * 	@link http://weavora.com
 * 	@copyright Copyright (c) 2011 Weavora LLC
 */
class AccessControlCallbackTerm extends AccessControlTerm
{

	public function match()
	{
		return call_user_func($this->params, Yii::app()->getUser());
	}

}
