<?php
/**
*类别维护数据操作模型
*@author JelCore
*@version 2014-01-30
*/
?><?php
class ArtTypeopb
{
	/*private var*/
	private $db_arttype = null;//文章类别DB对象
	private $db_arttypetax = null;//文章类别关系DB对象

	private $subtids = null;

	public function __construct()
	{
		$this->db_arttype = new Articletype();
		$this->db_arttypetax = new Articletypetax();

		$this->subtids = array();
	}


	/**
	*对类别进行的编辑操作
	*@param String $tid 类别ID ;String $tname
	*@return boolean true if success;String $berror-the error code  if failed  
	*/
	public function doEditType($tid,$tname)
	{
		if(!$this->checkValid($tid) || !$this->checkValid($tname)) return $berror = "无效的输入！！";
		else
		{
			if(Articletype::model()->exists('typeid=:tid', array(':tid'=>$tid)))
			{
				$this->db_arttype = Articletype::model()->find('typeid=:tid', array(':tid'=>$tid));
				$this->db_arttype->typename = $tname;

				if(!$this->db_arttype->save())return $berror = "DB保存出错！请联系管理员！错误位置：modules/ArtTypePro/models/ArtTypeopb.php #40";

				return true;
			}
			else
			{
				return $berror = "请求的类别ID不存在！";
			}
		}
	}


	/**
	*添加新的类别
	*@param String $newtid 新的类别ID;String $newtname 新类别名称;String $ptid 新类别的父类别
	*@return boolean true if success;String $berror-the error code if failed
	*/
	public function doAddNewType($newtid,$newtname,$ptid)
	{
		if(!$this->checkValid($newtid) || !$this->checkValid($newtname) || !$this->checkValid($ptid)) return $berror = "无效的输入！！";
		else
		{
			if(!Articletype::model()->exists('typeid=:newtid',array(':newtid'=>$newtid)))
			{
				$this->db_arttype->typeid = $newtid;
				$this->db_arttype->typename = $newtname;

				if(!$this->db_arttype->save())return $berror = "DB保存出错！请联系管理员！错误位置：modules/ArtTypePro/models/ArtTypeopb.php #67";

				$this->db_arttypetax->typeid = $newtid;
				$this->db_arttypetax->parentid = $ptid;

				if(!$this->db_arttypetax->save())return $berror = "DB保存出错！请联系管理员！错误位置：modules/ArtTypePro/models/ArtTypeopb.php #72";
				return true;
			}
			else
			{
				return $berror = "重复的分类ID";
			}
		}
	}



	/**
	*删除类别；若删除的类别有子孙分类，则将子孙分类传递给双亲分类
	*@param String $tid 要删除的；类别ID;String $ptid 要删除的类别的双亲类别ID
	*@return boolean true if success;String $berror-error code if failed
	*/
	public function doDeleteType($tid,$ptid)
	{
		if(!$this->checkValid($tid) || !$this->checkValid($ptid)) return $berror = "无效的输入！！";
		else
		{
			if($tid == YQNEWS || $tid == YQLFNEWS || $tid == YQEVENT || $tid == YQAPP) return $berror = "基本大类别不可删除！！";
			$ret = $this->getPostsNum($tid);
			$pnum = $ret['postsnum'];
			if($pnum > 0) return $berror = "该分类下文章数大于0，不可删除！";
			if(!Articletype::model()->exists('typeid=:tid',array(':tid'=>$tid))) return $berror = "不存在的类别ID！";
			else
			{
				$this->db_arttype = Articletype::model()->findByPk($tid);
				if(Articletypetax::model()->count('parentid=:tid',array(':tid'=>$tid)) == 0)//该类别没有子孙分类
				{
					if($this->db_arttype->delete())
					{
						$this->db_arttypetax = Articletypetax::model()->find('typeid=:tid',array(':tid'=>$tid));
						$DB = Articletypetax::model()->findByPk($this->db_arttypetax->taxid);
						if($DB->delete())
							return true;
						else
							return $berror = "DB删除出错！请联系管理员！错误位置：modules/ArtTypePro/models/ArtTypeopb.php #111";
					}
					else
						return $berror = "DB删除出错！请联系管理员！错误位置：modules/ArtTypePro/models/ArtTypeopb.php #114";
				}
				else//有子孙类别进行类别传递
				{
					$this->db_arttypetax = Articletypetax::model()->findAll('parentid=:tid',array(':tid'=>$tid));

					foreach($this->db_arttypetax as $key => $onetax)
					{
						$onetax->parentid = $ptid;
						if(!$onetax->save()) return $berror = "DB保存出错！请联系管理员！错误位置：modules/ArtTypePro/models/ArtTypeopb.php #123";
					}

					if($this->db_arttype->delete())
					{
						$this->db_arttypetax = Articletypetax::model()->find('typeid=:tid',array(':tid'=>$tid));
						$DB = Articletypetax::model()->findByPk($this->db_arttypetax->taxid);
						if($DB->delete())
							return true;
						else
							return $berror = "DB删除出错！请联系管理员！错误位置：modules/ArtTypePro/models/ArtTypeopb.php #133";
					}
					else
						return $berror = "DB删除出错！请联系管理员！错误位置：modules/ArtTypePro/models/ArtTypeopb.php #136";
				}
				

			}
		}
		
	}
/***********************************************************************************************************************/
	/**
	*获取某分类下的文章数
	*@param String $tid 类别ID
	*@return mixed array of (Integer 'postsnum'-posts' number, 'sign' (boolean true if success ; String error code if failed )) 
	*/
	public function getPostsNum($tid)
	{
		$result = array('postsnum'=>0,'sign'=>'');
		if(!Articletype::model()->exists('typeid=:tid',array(':tid'=>$tid)))
		{
			$result['sign'] = "不存在的或未生成的类别ID！";
			return $result;
		}
		else
		{
			$this->db_arttype = Articletype::model()->find('typeid=:tid',array(':tid'=>$tid));
			$postscount = 0;
			$subtids = $this->getSubTypeIDs($tid);
			if(count($subtids) == 0)
			{
				$postscount = Article::model()->count('typeid=:tid',array(':tid'=>$tid));
			}
			else
			{
				$postscount = Article::model()->count('typeid=:tid',array(':tid'=>$tid));
				foreach($subtids as $key => $onetid)
				{
					$pcount =  Article::model()->count('typeid=:tid',array(':tid'=>$onetid));
					$postscount = $postscount + $pcount;
				}
			}
			$this->db_arttype->typecount = $postscount;
			if(!$this->db_arttype->save())return $berror = "DB保存出错！请联系管理员！错误位置：modules/ArtTypePro/models/ArtTypeopb.php #178";

			$result['postsnum'] = $postscount;
			$result['sign'] = true;
			return $result;
		}

	}

	/**
	*获取有效的新的类别ID
	*
	*@return Integer $newtypeid if success;String $berror if failed
	*
	*/
	public function getNewTypeID()
	{
		$criteria=new CDbCriteria;
		$criteria->select='max(typeid) AS maxColumn';
		$row = $this->db_arttype->model()->find($criteria);
		$newTID = $row->maxColumn;
		$newTID++;
		return $newTID;
	}




	/**
	*有效性检测函数
	*@param $var 待检测的变量
	*@return boolean true if valid;false if invalid
	*/
	private function checkValid($var)
	{
		$sign = false;
		if($var === null)
		{
			return $sign;
		}
		else if($var === '' || $var === "")
		{
			return $sign;
		}
		else
		{
			return !$sign;
		}
	}

	/**
	*获取某分类的所有子分类
	*@param String $tid 分类ID
	*@return array of Integer $tid 分类下的所有子分类
	*/
	private function getSubTypeIDs($tid)
	{
		$IDs = array();
		if(Articletypetax::model()->count('parentid=:tid',array(':tid'=>$tid)) == 0)//没有子节点
		{ 
			return array();
		}
		else//有子节点继续递归
		{
			$subTypes = Articletypetax::model()->findAll('parentid=:tid',array(':tid'=>$tid));
			foreach($subTypes as $key => $onetype)
			{
				$IDs[] = "$onetype->typeid";
				$IDs = array_merge($IDs,$this->getSubTypeIDs($onetype->typeid));
			}
			return $IDs;
		}
		
	}
}
?>