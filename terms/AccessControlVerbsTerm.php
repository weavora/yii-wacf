<?php

/**
 *  @author Weavora Team <hello@weavora.com>
 * 	@link http://weavora.com
 * 	@copyright Copyright (c) 2011 Weavora LLC
 */
class AccessControlVerbsTerm extends AccessControlTerm
{

	public function match()
	{
		$verb = Yii::app()->getRequest()->getRequestType();
		return empty($this->params) || in_array(strtolower($verb), $this->params);
	}

}