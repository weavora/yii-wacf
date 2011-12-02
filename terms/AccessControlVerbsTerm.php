<?php

class AccessControlVerbsTerm extends AccessControlTerm
{

	public function match()
	{
		$verb = Yii::app()->getRequest()->getRequestType();
		return empty($this->params) || in_array(strtolower($verb), $this->params);
	}

}