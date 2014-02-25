<?php
/**
*@description 文章附件信息JSON类
*@author JelCore
*@revised 2013-12-02
*@var $this Controller 
*
*/
?><?php
class jmFileAttachment
{
	//数据域
	/**
	*TO:数据定义域
	*DO:用于JSON交互
	*
	*
	*/	
	private $FileName  = "";//文件名
	private $Link      = "";//下载地址
	private $Type      = "";//文件类型


	//构造器和解析器
	/**
	*@param $values 数据数组
	*
	*/
	public function __construct( $values )
	{
		$this->FileName = $values['filename'];
		$this->Link     = $values['link'];
		$this->Type     = $values['type'];
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