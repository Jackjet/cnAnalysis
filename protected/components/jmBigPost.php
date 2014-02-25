<?php
/**
*@description 文章详细内容JSON类
*@author JelCore
*@revised 2013-12-01
*
*
*/
?><?php
class jmBigPost
{
	//数据域
	/**
	*TO:数据定义域
	*DO:用于JSON交互
	*
	*
	*/
	private $ID       = "";//文章ID	
	private $Title    = "";//文章标题
	private $Type     = "";//文章类别
	private $Date     = "";//文章发表日期
	private $From     = "";//文章来源
	private $Author   = "";//文章作者
	private $Point    = "";//文章点击量
	private $Context  = "";//文章内容
	private $FromLink = "";//文章来源链接

	private $FileList = array();//文章附件列表

	//构造器和解析器
	/**
	*@param $values 数据数组
	*
	*
	*/
	public function __construct( $values )
	{
		$this->ID       = $values['id'];
		$this->Title    = $values['title'];
		$this->Type     = $values['type'];
		$this->Date     = $values['date'];
		$this->From     = $values['from'];
		$this->Author   = $values['author'];
		$this->Point    = $values['point'];
		$this->Context  = $values['content'];
		$this->FromLink = $values['fromlink'];
		foreach($values['filelist'] as $key => $thefile)
		{
			$this->FileList[$key] = array(
											'name' => $thefile->FileName,
											'link' => $thefile->Link,
											'type' => $thefile->Type
									 	 );
		} 
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