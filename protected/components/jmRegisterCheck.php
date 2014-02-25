<?php
/**
*@description 用户注册即时检测JSON类
*@author JelCore
*@revised 2013-12-02
*@var $this Controller 
*
*/
?><?php
class jmRegisterCheck
{
	//数据域
	/**
	*TO:数据定义域
	*DO:用于JSON交互
	*
	*
	*/	
	private $UserName   = "";//用户名
	private $Email      = "";//电子邮件地址
	

	//构造器和解析器
	public function __construct( $values )
	{

	}
	public function __destruct()
	{

	}
	//PHP魔术方法
	public function __get( $property )
	{
		if( isset( $this->$property ) )
		{
			return $this->$property;
		}
		else
		{
			return NULL;
		}
	}
	public function __set( $property , $value )
	{
		$this->$property = $value;
	}
?>