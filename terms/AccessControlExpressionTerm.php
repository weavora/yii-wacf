<?php

/**
 * 	@author Weavora Team <hello@weavora.com>
 * 	@link http://weavora.com
 * 	@copyright Copyright (c) 2011 Weavora LLC
 */
class AccessControlExpressionTerm extends AccessControlTerm
{

	public function match()
	{
		if ($this->params === null)
			return true;
		else
			return $this->evaluateExpression($this->params, array('user' => Yii::app()->getUser()));
	}

}
