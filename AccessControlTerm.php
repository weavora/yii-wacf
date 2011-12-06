<?php

/**
 * 	@author Weavora Team <hello@weavora.com>
 * 	@link http://weavora.com
 * 	@copyright Copyright (c) 2011 Weavora LLC
 */
abstract class AccessControlTerm extends CComponent
{

	protected $params;

	public function __construct($values)
	{
		$this->params = $values;
	}

	/**
	 * @return boolean $result
	 */
	abstract public function match();
}