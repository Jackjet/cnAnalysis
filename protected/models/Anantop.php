<?php
/**
*爬虫数据操作模型
*@author JelCore
*@version 2014-02-24 
*/
?><?php
class Anantop
{
	private $db_an = null;//爬虫DB模型

	public function __construct()
	{
		$this->db_an = new AnAnt();
	}

	/**
	*获取爬虫任务列表数据
	*@param String $adds 附加参数
	*@return array of AR $antmiss
	*/
	public function getAntMiss($adds="")
	{
		if($adds == "")
		{
			$limit = Yii::app()->params['AntMissQueryLimt'];
			$pagecount = Yii::app()->params['AntMissPageCount'];

			$antmiss = array();
			/*定制查询*/
			$criteria = new CDbCriteria();
			$criteria->order = 'addtime desc';          	//按上传时间字段来排序
			$criteria->limit = $limit;    					//取$limit条数据，如果$limit小于0，则不作处理
			$count = AnAnt::model()->count($criteria);     //count() 函数计算数组中的单元数目或对象中的属性个数

			/*分页处理*/
			$pager = new CPagination($count);			    //分页类
			$pager->pageSize = $pagecount;                  //每页显示的行数
			$pager->applyLimit($criteria);
			$miss = AnAnt::model()->findAll($criteria);	//查询所有的数据
			/*分别存储分页数据和文章数据*/
			$antmiss['miss'] = $miss;
			$antmiss['pager'] = $pager;
			return $antmiss;
		}
		else if($adds == "all")
		{
			return AnAnt::model()->findAll();
		}
		
	}
}
?>