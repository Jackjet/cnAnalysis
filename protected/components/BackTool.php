<?php
/**
*后台功能实现工具类
*@author JelCore
*@version 2014-01-06
*/
?><?php
class BackTool
{

	/*内置变量*/
	private $session;//会话
	private $cookies;//cookie

	/*构造器*/
	public function __construct()
	{
		$this->session  = Yii::app()->session;
		$this->cookies  = Yii::app()->request->getCookies();
	}

	/********************************************工具方法***********************************************/

	/**************************************用户相关*****************************************************/
	/**
	*获得登录后用户的id
	*@return boolean or String
	*false 如果未定义uid;若成功返回用户的ID
	*/
	public function getUID()
	{

		if($this->cookies['uid'] === null && $this->session['uid'] === null)
		{
			return false;
		}
		
		$uid = "";

		$uid = $this->session['uid'];
		if($uid === null || $uid === "")
		{
			$uid = $this->cookies['uid']->value;
			if($uid === null || $uid === "")
			{
				return false;
			}
		}
		
		return $uid;
	}


	/**
	*检测用户的角色
	*@return String $role
	*/
	public function getNowUserRole()
	{
		$usergroup = Yii::app()->user->group;
		$role = "";
		if($usergroup === null || $usergroup === "")
		{
			return false;
		}
		switch ($usergroup) 
		{
			case '1':
				$role = "1";
				break;
			case '2':
				$role = "2";
				break;
			case '3':
				$role = "3";
				break;
			default:
				return false;
				break;
		}
		return $role;
	}

	/**
	*检测用户的权限是否满足系统管理员或者管理员
	*@var String $role 要检测的其是否具有角色
	*/
	public function checkUserAccess($role=RADMINJ)
	{
		return Yii::app()->user->checkAccess($role);
	}


	/****************************************************数据相关********************************************************/

	/**
	*记录用户的历史检索信息
	*@var String $search 检索的内容;$cname存储的索引
	*@return boolean true if succeed;false if $search is not valid
	*/
	public function saveSearchData($cname,$search)
	{
		if(isset($search) && isset($cname))
		{
			$cookie = new CHttpCookie($cname,$search);
			$cookie->expire = time() + 60*60*24;  		//有限期1天
			Yii::app()->request->cookies[$cname] = $cookie;
			return true;
		}
		else
		{
			return false;
		}
		
	}

	/**
	*读取用户的历史检索缓存
	*@var $cname 存储的索引
	*@return String $search;null if the value doesn't exist
	*/
	public function getSearchData($cname)
	{
		$cookies = Yii::app()->request->getCookies();
		if(isset($cookies[$cname]))
		return $cookies[$cname]->value;
		else
		return null;
	}

	/*******************************************************借鉴代码*******************************************************/
	/**
	*Validate an email address.
	*Provide email address (raw input)
	*Returns true if the email address has the email 
	*address format and the domain exists.
	*/
	public function validEmail($email)
	{
	   $isValid = true;
	   $atIndex = strrpos($email, "@");
	   if (is_bool($atIndex) && !$atIndex)
	   {
	      $isValid = false;
	   }
	   else
	   {
	      $domain = substr($email, $atIndex+1);
	      $local = substr($email, 0, $atIndex);
	      $localLen = strlen($local);
	      $domainLen = strlen($domain);
	      if ($localLen < 1 || $localLen > 64)
	      {
	         // local part length exceeded
	         $isValid = false;
	      }
	      else if ($domainLen < 1 || $domainLen > 255)
	      {
	         // domain part length exceeded
	         $isValid = false;
	      }
	      else if ($local[0] == '.' || $local[$localLen-1] == '.')
	      {
	         // local part starts or ends with '.'
	         $isValid = false;
	      }
	      else if (preg_match('/\\.\\./', $local))
	      {
	         // local part has two consecutive dots
	         $isValid = false;
	      }
	      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
	      {
	         // character not valid in domain part
	         $isValid = false;
	      }
	      else if (preg_match('/\\.\\./', $domain))
	      {
	         // domain part has two consecutive dots
	         $isValid = false;
	      }
	      else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
	                 str_replace("\\\\","",$local)))
	      {
	         // character not valid in local part unless 
	         // local part is quoted
	         if (!preg_match('/^"(\\\\"|[^"])+"$/',
	             str_replace("\\\\","",$local)))
	         {
	            $isValid = false;
	         }
	      }
	      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
	      {
	         // domain not found in DNS
	         $isValid = false;
	      }
	   }
	   return $isValid;
	}
}	
?>