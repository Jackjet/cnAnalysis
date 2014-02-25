<?php
/**
*@description 文章简略信息JSON类
*@author JelCore
*@revised 2013-12-02
*@var $this Controller 
*
*/
?><?php
class jmSmallPost
{
	//数据域
	/**
	*TO:数据定义域
	*DO:用于JSON交互
	*
	*
	*/	
	private $Title = "";//文章标题
	private $Date  = "";//文章发表日期
	private $Link  = "";//文章链接
	private $Type  = "";//文章类别，为最小子类
	//构造器和解析器
	/**
	*@param $values 传入的值的数组
	*
	*/
	public function __construct( $values )
	{
		$this->Title = $values['title'];
		$this->Date  = $values['date'];
		$this->Link  = $values['link'];
		$this->Type  = $values['type'];
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
}
?>