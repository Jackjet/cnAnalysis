<?php
/**
*@description The Model for Article's type operation 
*@author JelCore
*@revised 2014-01-08
*/
?><?php
class ArtTypeop
{

	/*私有数据域*/
	private $db_articletype = null;//文章类别数据库AR对象
	private $db_artitypetax = null;//文章类别关系数据库AR对象

	/*构造器*/
	public function __construct()
	{
		$this->db_articletype = new Articletype();
		$this->db_artitypetax = new Articletypetax();
	}

	/**
	*获取当前所有的分类提供给文章编辑使用
	*@return CActiveRecordset $types
	*
	*/
	public function GetTypes()
	{
		$types = Articletype::model()->findAll();
		return $types;
	}

	/**
	*获取特定分类
	*@return CActiveRecordset $type
	*
	*/
	public function GetType($tid)
	{
		if(!$this->checkType($tid)) return null;
		$type = Articletype::model()->findByPk($tid);
		return $type;
	}

	/**
	*获取当前分类的直接父类ID
	*@param String $tid 分类的ID
	*@return AR 分类的直接父类
	*/
	public function GetParentType($tid)
	{
		if(!Articletype::model()->exists('typeid=:tid',array(':tid'=>$tid)) || !Articletypetax::model()->exists('typeid=:tid',array(':tid'=>$tid))) exit("文章类别数据库有错误！错误:ArtTypeop:GetParentType");
		else
		{
			$this->db_articletypetax = Articletypetax::model()->find('typeid=:tid',array(':tid'=>$tid));
			if($this->db_articletypetax->parentid == 0) return Articletype::model()->findByPk($tid);//如果没有父类则返回本身
			else
				return Articletype::model()->findByPk($this->db_artitypetax->parentid);//否则返回其直接父类
		}
	}

	/**
	*获取合适的分类ID:support to GetPostsByTID
	*@param $ptid 目标ID
	*@return array of int $pctidarr 包含目标本身以及其所有子孙分类(child and parent itself)
	*/
	public function GetCPTIDs($ptid)
	{
		$pctidarr = array();
		if(!Articletype::model()->exists('typeid=:tid',array(':tid'=>$ptid))) exit('调用错误，该分类不存在！错误:ArtTypeop:getCPTIDs');
		else
		{
			$pctidarr[] = "$ptid";//包含自身
			if(Articletypetax::model()->count('parentid=:ptid',array(':ptid'=>$ptid)) == 0) return $pctidarr;
			else 
			{
				$res = Articletypetax::model()->findAll('parentid=:ptid',array(':ptid'=>$ptid));

				foreach($res as $key => $onetax)
				{
					$pctidarr = array_merge($pctidarr,$this->getCPTIDs($onetax->typeid));
				}
				return $pctidarr;
			}
		}

	}

	/**
	*获取由指定分类到其最高父级分类的路径
	*@param String $tid 分类ID
	*@return array $ppath 一个顺序的分类集数组
	*/
	public function GetPPath($tid)
	{
		$ppath = array();
		$thetype = $this->GetType($tid);
		if($thetype == null) return null;
		$ppath[] = $thetype;
		if(!Articletypetax::model()->exists('typeid=:tid',array(':tid'=>$tid))) return null;
		$this->db_artitypetax = Articletypetax::model()->find('typeid=:tid',array('tid'=>$tid));

		while($this->db_artitypetax->parentid != 0)
		{
			$tid = $this->db_artitypetax->parentid;
			$thetype = $this->GetType($tid);
			if($thetype == null) return null;
			$ppath[] = $thetype;

			if(!Articletypetax::model()->exists('typeid=:tid',array(':tid'=>$tid))) return null;
			$this->db_artitypetax = Articletypetax::model()->find('typeid=:tid',array('tid'=>$tid));
		}
		return array_reverse($ppath,false);//倒序为父级到子级

	}

	/**
	*获取详细的文章类别树数组
	*@param int $targetid 目标ID，默认提取所有主分类
	*@return mixed array $typetree 
	*/
	public function GetTypesTree($targetid=-1)
	{
		$maintid = array();
		if(!Articletype::model()->exists('typeid=:tid',array(':tid' => YQNEWS))  ||
		   !Articletype::model()->exists('typeid=:tid',array(':tid' => YQLFNEWS))||
		   !Articletype::model()->exists('typeid=:tid',array(':tid' => YQEVENT)) ||
		   !Articletype::model()->exists('typeid=:tid',array(':tid' => YQAPP)))
		{
			exit('<h1>主要类别未找到！请联系网站管理员！</h1>');
		}
		else
		{
			$typetree = array();
			$treenode = array();
			if($targetid == -1)
			{
				$maintid = array(YQNEWS,YQLFNEWS,YQEVENT,YQAPP);
				foreach($maintid as $key => $onetid)
				{
					$treenode = array(
									'node' => Articletype::model()->find('typeid=:tid',array(':tid'=> $onetid)),
									'subnodes' => array()
								 );
					$treenode = $this->GetNodeSub($treenode);

					$typetree[] = $treenode;
				}
				return $typetree;
			}
			else
			{
				$maintid = array($targetid);
				foreach($maintid as $key => $onetid)
				{
					$treenode = array(
									'node' => Articletype::model()->find('typeid=:tid',array(':tid'=> $onetid)),
									'subnodes' => array()
								 );
					$treenode = $this->GetNodeSub($treenode);

					$typetree[] = $treenode;
				}
				return $typetree;
			}
		}	
	}


	/**
	*递归获取某分类下的所有子分类(GetTypesTree函数辅助)
	*@var mixed $node 一个节点
	*@return mixed $node 添加子节点后的节点
	*/
	private function GetNodeSub($node)
	{
		$nodeid = $node['node']->typeid;
		$_count = Articletypetax::model()->count('parentid=:parid',array(':parid'=>$nodeid));
		if($_count < 1)//递归结束条件，即该分类下没有子分类
		{
			return $node;
		}
		else
		{
			$subnodes = Articletype::model()->findAllBySql(               //查询当前节点的子节点
			'SELECT * FROM an_articletype WHERE typeid 
			IN (SELECT typeid FROM an_articletypetax WHERE parentid=:parid)',
			array(':parid'=>$nodeid)
																			);
			$treenode = array();


			foreach($subnodes as $key => $onesubnode)//将每个直接子节点添加到当前节点
			{
				$treenode = array(
							'node' => $onesubnode,
							'subnodes' => array()
						 );
				$treenode = $this->GetNodeSub($treenode);//递归处理每个子节点的直接子节点
				$node['subnodes'][] = $treenode;
			}

			return $node;
			
		}
		
	}



	/**
	*检查类别是否存在
	*@param String $tid  类别ID
	*@return boolean true if exists ; false if not 
	*/
	public function checkType($tid)
	{
		return Articletype::model()->exists('typeid=:tid',array(':tid' => $tid));
	}
}
?>