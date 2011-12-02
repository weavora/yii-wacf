<?php

class AccessControlCallbackTerm extends AccessControlTerm
{

	public function match()
	{
		return call_user_func($this->params, Yii::app()->getUser());
	}

}
