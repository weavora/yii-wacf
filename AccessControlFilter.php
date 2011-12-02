<?php

class AccessControlFilter extends CFilter
{

	/**
	 * @var string the error message to be displayed when authorization fails.
	 * This property can be overridden by individual access rule via {@link CAccessRule::message}.
	 * If this property is not set, a default error message will be displayed.
	 * @since 1.1.1
	 */
	public $message;
	private $_rules = array();

	/**
	 * @return array list of access rules.
	 */
	public function getRules()
	{
		return $this->_rules;
	}

	/**
	 * @param array $rules list of access rules.
	 */
	public function setRules($rules)
	{
		foreach ($rules as $rule) {
			if (is_array($rule) && isset($rule[0])) {
				$r = new AccessRule();
				$r->allow = $rule[0] === 'allow';
				foreach (array_slice($rule, 1) as $name => $value) {
					if ($name === 'message') {
						$r->$name = $value;
					} else {
						$className = 'AccessControl' . ucfirst($name) . 'Term';
						if (!class_exists($className)) {
							throw new Exception("Class not found"); //@todo need throw other exception
						}
						if (is_array($value)) {
							$value = array_map('strtolower', $value);
						}
						$r->addTerm(new $className($value));
					}
				}
				$this->_rules[] = $r;
			}
		}
	}

	/**
	 * Performs the pre-action filtering.
	 * @param CFilterChain $filterChain the filter chain that the filter is on.
	 * @return boolean whether the filtering process should continue and the action
	 * should be executed.
	 */
	protected function preFilter($filterChain)
	{
		$app = Yii::app();
		$user = $app->getUser();

		foreach ($this->getRules() as $rule) {
			if (($allow = $rule->isUserAllowed()) > 0) { // allowed
				break;
			} else if ($allow < 0) { // denied
				$this->accessDenied($user, $this->resolveErrorMessage($rule));
				return false;
			}
		}
		$event = new CEvent($this);
		$this->onAfterAccessFilterSuccess($event);

		return true;
	}

	/**
	 * Resolves the error message to be displayed.
	 * This method will check {@link message} and {@link AccessRule::message} to see
	 * what error message should be displayed.
	 * @param AccessRule $rule the access rule
	 * @return string the error message
	 * @since 1.1.1
	 */
	protected function resolveErrorMessage($rule)
	{
		if ($rule->message !== null)
			return $rule->message;
		else if ($this->message !== null)
			return $this->message;
		else
			return Yii::t('yii', 'You are not authorized to perform this action.');
	}

	/**
	 * Denies the access of the user.
	 * This method is invoked when access check fails.
	 * @param IWebUser $user the current user
	 * @param string $message the error message to be displayed
	 * @since 1.0.5
	 */
	protected function accessDenied($user, $message)
	{
		$event = new CEvent($this);
		$this->onAfterAccessFilterFail($event);
		if (!$event->handled) {
			if ($user->getIsGuest())
				$user->loginRequired();
			else
				throw new CHttpException(403, $message);
		}
	}

	public function onAfterAccessFilterFail($event)
	{
		return $this->raiseEvent('onAfterAccessFilterFail', $event);
	}

	public function onAfterAccessFilterSuccess($event)
	{
		$this->raiseEvent('onAfterAccessFilterSuccess', $event);
	}

}