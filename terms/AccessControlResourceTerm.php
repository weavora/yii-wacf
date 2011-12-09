<?php

/**
 * 	@author Weavora Team <hello@weavora.com>
 * 	@link http://weavora.com
 * 	@copyright Copyright (c) 2011 Weavora LLC
 */
class AccessControlResourceTerm extends AccessControlTerm
{

	private $_model = null;

	public function match()
	{
		$ownerId = isset($this->params['ownerField']) ? $this->params['ownerField'] : 'user_id';
		$webUserId = isset($this->params['webUserId']) ? $this->params['webUserId'] : 'id';

		if (!isset(Yii::app()->user->{$webUserId})) {
			return false;
		}

		$userId = Yii::app()->user->{$webUserId};
		$this->_model = $this->_getModelFinder();
		if (!$this->_model || !$this->_model->hasAttribute($ownerId)) {
			return false;
		}

		$resource = $this->_findResource();
		if (!$resource) {
			return false;
		}

		return $resource->{$ownerId} == $userId;
	}

	private function _findResource()
	{
		$params = array();

		if (isset($this->params['params'])) {
			$conditionArr = $this->params['params'];
		} else {
			$conditionArr['id'] = array(
				'id',
				get_class($this->_model) . ".id",
				get_class($this->_model) . "Form.id",
			);
		}

		if (!is_array($conditionArr)) { // 'params' => 'CommentModel.id' case
			$conditionArr = array($conditionArr);
		}
		foreach ($conditionArr as $dbField => $requestField) {
			//find param in request
			if (is_numeric($dbField)) {
				$dbField = $this->_model->getMetaData()->tableSchema->primaryKey;
			}

			$requestFieldValue = $this->_getParamValue($requestField);
			if (!$requestFieldValue || !$requestField) {
				return false;
			}

			$params[$dbField] = $requestFieldValue;
		}
		return $this->_model->findByAttributes($params);
	}

	private function _getParamValue($param)
	{
		if (is_string($param)) {
			$param = explode(',', $param);
		}

		if (!is_array($param)) {
			return null;
		}

		foreach ($param as $paramKey) {
			$resultValue = $this->_getRequestParam($paramKey);
			if (!is_null($resultValue))
				return $resultValue;
		}

		return null;
	}

	private function _getRequestParam($paramKey)
	{
		$paramKeyPortitions = explode('.', $paramKey);
		$request = null;
		foreach ($paramKeyPortitions as $key) {
			if (is_null($request) && $params = Yii::app()->request->getParam($key)) {
				$request = $params;
				continue;
			}
			if (!isset($request[$key])) {
				return null;
			}
			$request = $request[$key];
		}
		return is_scalar($request) ? $request : null;
	}

	private function _getModelFinder()
	{
		$model = null;
		if (isset($this->params['model'])) {
			if (is_string($this->params['model'])) { //name of class
				if (class_exists($this->params['model'])) {
					$className = ucfirst($this->params['model']);
					$model = $className::model();
				}
			} elseif (is_object($this->params['model'])) {//an instance
				if (!($this->params['model'] instanceof CActiveRecord))
					throw new Exception(get_class($this->params['model']) . ' is not an instance of CActiveRecord');
				$model = $this->params['model'];
			}
		} else {
			//get from controller name
			$className = ucfirst(str_replace('Controller', '', get_class(Yii::app()->controller)));
			$model = $className::model();
			if (!$model instanceof CActiveRecord)
				throw new Exception(get_class($this->params['model']) . ' is not an instance of CActiveRecord');
		}
		return $model;
	}

}