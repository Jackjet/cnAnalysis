<?php
/**
*@description 简单搜索JSON类
*@author JelCore
*@revised 2013-12-02
*@var $this Controller 
*
*/
?><?php
class jmSearchData
{
	//数据域
	/**
	*TO:数据定义域
	*DO:用于JSON交互
	*
	*
	*/	
	private $Context   = "";//搜索内容

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