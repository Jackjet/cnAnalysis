<?php
/**
*热门搜索数据操作model
*@author JelCore
*@version 2014-02-10
*/
?><?php
class AnSearchop
{
	/**/
	private $db_search = null;//搜索DB模型
	private $db_arttype = null;//文章分类DB模型
	private $db_arti    = null;//文章DB模型
	public function __construct()
	{
		$this->db_search = new AnSearch();
		$this->db_arttype = new Articletype();
		$this->db_arti    = new Article();
	}

	/**
	*获取DB中的热门文章
	*@return AR array $keywords 热门文章文章
	*/
	public function getKeyWords()
	{
		$criteria = new CDbCriteria();
		$criteria->select = 'title,point,link';
		$criteria->order = 'point DESC' ;
		$criteria->limit = Yii::app()->params['HotPostShow'];
		$keywords = Article::model()->findAll($criteria);
		return $keywords;
	}

	/**
	*获取搜索类别
	*@return array of CAR $types 
	*
	*/
	public function getSearchTypes()
	{
		$types = Articletype::model()->findAll();
		return $types;
	}
}
?>