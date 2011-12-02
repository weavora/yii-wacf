<?php

class AccessControlActionsTerm extends AccessControlTerm
{

	public function match()
	{
		return empty($this->params) || in_array(strtolower(Yii::app()->controller->action->getId()), $this->params);
	}
}