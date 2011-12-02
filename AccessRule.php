<?php

class AccessRule extends CComponent
{

	/**
	 * @var boolean whether this is an 'allow' rule or 'deny' rule.
	 */
	public $allow;

	/**
	 * @var string the error message to be displayed when authorization is denied by this rule.
	 * If not set, a default error message will be displayed.
	 * @since 1.1.1
	 */
	public $message;

	/**
	 * @var array of AccessRullesTerms 
	 */
	public $termsList = array();

	/**
	 * Checks whether the Web user is allowed to perform the specified action.
	 * @param CWebUser $user the user object
	 * @param CController $controller the controller currently being executed
	 * @param CAction $action the action to be performed
	 * @param string $ip the request IP address
	 * @param string $verb the request verb (GET, POST, etc.)
	 * @return integer 1 if the user is allowed, -1 if the user is denied, 0 if the rule does not apply to the user
	 */
	public function isUserAllowed()
	{
		foreach ($this->termsList as $term) {
			if (!$term->match()) {
				return 0;
			}
		}
		return $this->allow ? 1 : -1;
	}

	public function addTerm(AccessControlTerm $term)
	{
		$this->termsList[] = $term;
	}

}