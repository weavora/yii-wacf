<?php

class AccessControlControllersTerm extends AccessControlTerm
{

	public function match()
	{
		return empty($this->params) || in_array(strtolower(Yii::app()->controller->getId()), $this->params);
	}

}
