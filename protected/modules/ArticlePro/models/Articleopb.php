<?php
/**
*@description The Model for Article operation backstage
*@author JelCore
*@revised 2013-12-30
*/
?><?php
class Articleopb
{
	/*私有属性*/
	private $db_article   = null;//文章数据库对象
	private $usersop      = null;//用户操作对象
	private $fileopb      = null;//文件操作对象
	private $db_updateart = null;//solr全文搜索检测表操作文章模型
	private $dopasstype   = array();//审核类型

	private $pregStr     = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png|\.bmp|\.jpeg]))[\'|\"].*?[\/]?>/i";
	/*构造和析构器*/
	public function __construct()
	{
		$this->db_article = new Article();
		$this->usersop    = new Usersop();
		$this->db_updateart = new Updatearticles();
		$this->dopasstype['dopass']  = 1;
		$this->dopasstype['donopass'] = 0;
		$this->fileopb = new Filesopb();
 	}


/*******************************************************公开方法***********************************************/

	/**
	*更新文章
	*@var array $info 文章数据
	*@return boolean true if succeed ; String $errorcode if failed
	*/
	public function updateArticle($info)
	{
		if(isset($info['aid']))
		{
			
			
			if(Article::model()->count('aid=:aid',array(':aid'=>$info['aid'])) != 1) return "无法找到文章！";
			$this->db_article = Article::model()->find('aid=:aid',array(':aid'=>$info['aid']));
			if(!$this->checkValid($info['title'])) return "标题必须为有效值！";
			$this->db_article->title = $info['title'];
			if(!$this->checkValid($info['from'])) return "文章来源必须为有效值！";
			$this->db_article->from = $info['from'];
			if(!$this->checkValid($info['fromlink'])) return "文章来源地址必须为有效值！";
			$this->db_article->fromlink = $info['fromlink'];
			if(!$this->checkValid($info['author'])) return "原作者必须为有效值！";
			$this->db_article->author = $info['author'];
			if(!$this->checkValid($info['typeid'])) return "分类必须为有效值！";
			$this->db_article->typeid = $info['typeid'];
			if(!$this->checkValid($info['content'])) return "文章内容必须为有效值！";
			$this->db_article->content = $info['content'];
			$this->db_article->modifiedtime = date('Y-m-d H:i:s');

			if(Updatearticles::model()->count('aid=:aid',array(':aid'=>$info['aid'])) !== 1)
			{
				$this->db_updateart->aid = $info['aid'];
				$this->db_updateart->issucc = 0;

				$this->db_updateart->save();
			}
			else
			{
				$this->db_updateart = Updatearticles::model()->find('aid=:aid',array(':aid'=>$info['aid']));
				$this->db_updateart->issucc = 0;

				$this->db_updateart->save();
			}
			
			$this->db_article->save();

			return true;

		}
		else
		{
			return $errorcode = "The Post ID Invalid !";
		}
	}




	/**
	*文章审核设置
	*@var array $info 需要的数据
	*@return boolean true if succeed ;String $errorcode if failed 
	*
	*/
	public function doVerify($info)
	{
		if($this->checkValid($info['aid']))
		{
			if($this->checkValid($info['dotype']))
			{
				if($info['dotype'] === 'dopass' || $info['dotype'] === 'donopass')
				{
					if(Article::model()->count('aid=:aid',array(':aid'=>$info['aid'])) == 1)
					{
						$this->db_article = Article::model()->find('aid=:aid',array(':aid'=>$info['aid']));
						$this->db_article->isallow = $this->dopasstype[$info['dotype']];//根据要求进行审核通过与否的操作

						$this->db_article->save();
						return true;
					}
					else
					{
						return $errorcode = "The Post ID Not Found !";
					}
				}
				else
				{
					return $errorcode = "The Operation Type Invalid !";
				}				
			}
			else
			{
				return $errorcode = "The Operation Type Invalid !";
			}
		}
		else
		{
			return $errorcode = "The Post ID Invalid !";
		}
	}

	/**
	*删除文章
	*@var array $info 文章数据
	*@return boolean $ret['artdel'] and $ret['filedel'] true if succeed ;String $res['errorcode'] if failed
	*/
	public function deleteArticle($info)
	{
		$ret = array('errorcode'=>'','successcode' => '','artdel' => false , 'filedel' => false);//返回状态数组
		if($this->checkValid($info['aid']))
		{
			if(Article::model()->count('aid=:aid',array(':aid'=>$info['aid'])) == 1)
			{
				
				$this->db_article = Article::model()->findByPk($info['aid']);


				preg_match_all($this->pregStr, $this->db_article->content, $matches);
				foreach($matches[1] as $key => $onematch)               //删除文章中的图片
				{
					$onematch = $_SERVER['DOCUMENT_ROOT'] . $onematch;  //文章中图片的路径转化
					if(file_exists($onematch))							//保证文件夹有操作权限		
					{
	   					unlink($onematch);                          	//删除图片
	  				}
				}
				$fdo = $this->fileopb->deleteFiles($info['aid']);       //删除文章所有附件
				if($fdo !== true && $fdo !== false)
				{
					$ret['errorcode'] = $fdo;
					return $ret;
				}
				if($fdo)
				{
					$ret['filedel'] = true;
					$ret['successcode'] = '文章中的附件已经成功删除！';
				}
				
				$this->db_article->delete();//删除DB中的文章

				$this->db_updateart = Updatearticles::model()->find('aid=:aid',array(':aid'=>$info['aid']));
				if($this->db_updateart)
				{
					$udid = $this->db_updateart->udid;
					$res = Updatearticles::model()->findByPk($udid);
					if($res) $res->delete();//删除DB中的SOLR更新记录
				}
				$ret['artdel'] = true;
				return $ret;
				
			}
			else
			{
				 $ret['errorcode'] = "The Post ID Not Found !";
				 return $ret;
			}
			
		}
		else
		{
			 $ret['errorcode'] = "The Post ID Invalid !";
			 return $ret;
		}
		
	}













	/**
	*检测数据有效性
	*@var mixed $info 待检测数据
	*@return boolean true if valid;false if invalid
	*/
	private function checkValid($info)
	{
		if(!isset($info)) return false;
		if(empty($info))  return false;
		return true;
	}

	
}
?>