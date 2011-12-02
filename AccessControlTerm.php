<?php

abstract class AccessControlTerm extends CComponent
{
	protected $params;

	public function __construct($values)
	{
		$this->params = $values;
	}

	abstract public function match();
}