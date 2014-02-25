<?php
/**
*@description The Model for Group operation 
*@author JelCore
*@revised 2014-01-10
*/
?><?php
class Groupsop
{
	/*private object*/
	private $db_groups = null;



	/*construct*/

	public function __construct()
	{
		$this->db_groups = new Groups();
	}




	/*public method*/

	/**
	*get the groups
	*@return AR Array $groups
	*
	*/
	public function getGroups()
	{
		$groups = Groups::model()->findAll();
		return $groups;
	}
}
?>