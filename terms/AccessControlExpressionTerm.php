<?php

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
