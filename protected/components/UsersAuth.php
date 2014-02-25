<?php
/**
*User模块工具类
*@author JelCore
*@revised 2014-01-03
*/
?><?php
class UsersAuth
{	


	/*the handler for login authentication*/
	private $userIndentity = null;

	/*the expire of user idnetity*/
	private $expire;//a week is enough

	/**
	*construct 
	*initialized the data
	*
	*/
	public function __construct()
	{
		$this->expire = 60*60*24*7;
	}




	/**
	*keep login state into cookie 
	*@var String $cookiename the name of the cookie
	*@var String $cookievalue the value of the cookie 
	*/
	public function keepCookieJ($cookiename,$cookievalue)
	{
		$cookie = new CHttpCookie($cookiename,$cookievalue);
		$cookie->expire = time() + 60*60*24*7;  //有限期7天
		Yii::app()->request->cookies[$cookiename] = $cookie;
	}

	/**
	*get cookie value
	*@var String $cookiename the name of the cookie 
	*@return  String the cookie value if the cookie  exists
	*@return null if the cookie doesn't exists  
	*/
	public function getCookieJ($cookiename)
	{
		$cookies = Yii::app()->request->getCookies();
		return $cookies[$cookiename]->value;
	}

	/**
	*destroy the cookie 
	*@var String $cookiename
	*/
	public function destroyCookieJ($cookiename)
	{
		$cookie = Yii::app()->request->getCookies();
		unset($cookie[$cookiename]);
	}

	/**
	*check if the cookie  exists
	*@var String $cokiename
	*@return bool true if the cookie exists; false if not
	*/ 
	public function checkCookieJ($cookiename)
	{
		$cookie = Yii::app()->request->getCookies();
		if($cookie === null)
		{
			return false;
		}
		if(isset($cookie[$cookiename]))
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	/**
	*user authentcation 
	*@var String $username username or user's email
	*@var String $password the password for the user
	*@return boolean 
	* true if loading success
	* false if loading failed 
	*/
	public function authloadUser($username,$password)
	{	
		if($username !== null && $password !== null)
		{
			$this->userIndentity = new UsersIdentity($username,$password);
			return true;
		}
		else
			return false;
	}

	/**
	*do the authentication
	*@var String $iskeep ,if it is "true" the authentication if will be saved in cookies ortherwise in session  
	*@return boolean or String
	*true if the authentication sucess ;error Stirng if failed
	*/
	public function doAuthentication($isKeep)
	{
		if($this->userIndentity->authenticate())
		{
			if($isKeep == "true")
			Yii::app()->user->login($this->userIndentity,$this->expire);//allowAutoLogin must be set true in order to use cookie-based authentication
			else
			Yii::app()->user->login($this->userIndentity);
			return true;
		}  
		else  
    	return $this->userIndentity->errorMessage = "Error Authentcation!";
	}
}	
?>