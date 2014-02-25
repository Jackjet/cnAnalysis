<?php
/**
*user authorization control
*@author JelCore
*@version 2014-01-04
*/ 
class WebUser extends CWebUser
{
	/**
	 * Overrides a Yii method that is used for roles in controllers (accessRules).
	 *
	 * @param string $operation Name of the operation required (here, a role).
	 * @param mixed $params (opt) Parameters for this operation, usually the object to access.
	 * @return bool Permission granted?
	 */
	 public function checkAccess($operation, $params=array())
	 {
 	 	if (empty($this->id)) 
 		{
			 // Not identified => no rights

			 return false;
		}
	 	$group = $this->getState('group');
	 	if ($group === '1') 
	 	{
	 		return true; // admin role has access to everything
	 	}
	 	// allow access if the operation request is the current user's role
	 	return ($operation === $group);
	}
}