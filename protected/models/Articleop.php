<?php
/**
*@description The Model for Article operation 
*@author JelCore
*@revised 2014-01-08
*/
?><?php
class Articleop
{
	/*私有属性*/
	private $db_article   = null;//文章数据库对象
	private $db_updateart = null;//solr服务DB操作对象
	private $usersop      = null;//用户操作对象
	private $typeop       = null;//文章分类操作对象
	private $backtool     = null;//后台工具对象
	private $pagecount    = 6;   //分页中每一页的文章行数

	private $backpagecount = 6;//后台文章审核页面每一页的文章行数

	/*构造和析构器*/
	public function __construct(  )
	{
		$this->db_article = new Article();
		$this->usersop    = new Usersop();
		$this->backtool   = new BackTool();
		$this->db_updateart = new Updatearticles();
		$this->typeop       = new ArtTypeop();
 	}


/*******************************************************公开方法***********************************************/
	/**
	*@description 根据type id 获得 type name
	*@var Integer $typeid 分类的ID
	*@return String $typename 分类名
	*/
	public function GetTypeName($typeid=1)
	{
		$typename = an_articletype::model()->find('typeid=:thetype', array(':thetype'=>$typeid))->typename;
		return $typename; 
	}

	/**
	*@description 根据type id 获得 某个分类下的articles;$limit限制显示的个数
	*@var Integer $typeid 分类id;Integer $limit 限制的条数;Integer $pagecount 每页显示条数
	*@return Array 结果数组，包括文章(CRecordSet)和分页数据(CPagination) 
	*/
	public function GetPostsByTID($typeid=1,$limit=10,$pagecount = 4)
	{
		$posts  = array();
		$posts_ = array();
		$result = array();


		/*定制查询*/
		$criteria = new CDbCriteria();
		$criteria->order = 'uploadtime desc';          	//按上传时间字段来排序
		$criteria->addInCondition('typeid', $this->typeop->GetCPTIDs($typeid));   //设定查询条件指定类别，查询所有子分类
		$criteria->addCondition("isallow=:isno");       //设定查询条件指定通过审核的文章
		$criteria->params[':isno'] = 1;
		$criteria->limit = $limit;    					//取$limit条数据，如果$limit小于0，则不作处理

		$count = Article::model()->count($criteria);    //count() 函数计算数组中的单元数目或对象中的属性个数
		/*分页处理*/
		$pager = new CPagination($count);			    //分页类
		$pager->pageSize = $pagecount;                  //每页显示的行数
		$pager->applyLimit($criteria);
		$posts = Article::model()->findAll($criteria);	//查询所有的数据
		/*分别存储分页数据和文章数据*/
		$result['posts'] = $posts;
		$result['pager'] = $pager;
		return $result; 
	}

	/**
	*@description 获得首页的简略文章列表
	*
	*/
	public function GetHomePosts()
	{
		$showposts = Yii::app()->params['HomeShowPosts'];
		$querylimit = Yii::app()->params['HomeQueryPosts'];
		$qynews_     = $this->GetPostsByTID(YQNEWS,$querylimit,$showposts);
		$qylifenews_ = $this->GetPostsByTID(YQLFNEWS,$querylimit,$showposts);
		$qyevent_    = $this->GetPostsByTID(YQEVENT,$querylimit,$showposts);
		$qyapp_      = $this->GetPostsByTID(YQAPP,$querylimit,$showposts);
		$AllPost = array(
							YQNEWS     => $qynews_['posts'],
							YQLFNEWS   => $qylifenews_['posts'],
							YQEVENT    => $qyevent_['posts'],
							YQAPP      => $qyapp_['posts']
						);
		return $AllPost;

	}

	/**
	*获取某分类的更多文章
	*@param String $tid 分类的ID
	*@return array $posts 结果数组，包括文章(CRecordSet)和分页数据(CPagination)
	*/
	public function GetMorePosts($tid)
	{
		$showposts = Yii::app()->params['DoMoreShowPosts'];
		$querylimit = Yii::app()->params['DoMoreQueryPosts'];
		$posts = array('posts' => array(),'pager' => null);
		$pss   = $this->GetPostsByTID($tid,$querylimit,$showposts);
		foreach($pss['posts'] as $key => $onepost)
		{
			$posts['posts'][] = array(
										'aid'        => $onepost->aid,
										'uid'        => $onepost->uid,
										'title'      => mb_strimwidth($onepost->title,0, Yii::app()->params['PTAbsCountPag'],'...','utf-8'),
										'from'       => $onepost->from,
										'fromlink'   => $onepost->fromlink,
										'author'     => $onepost->author,
										'uploadtime' => $onepost->uploadtime,
										'editor'     => $this->usersop->GetNameByID($onepost->uid),
										'content'    => $onepost->content,
										'type'       => $this->GetTypeName($onepost->typeid),
										'link'       => $onepost->link,
										'point'      => $onepost->point
			  						 );
		}
		$posts['pager'] = $pss['pager'];
		return $posts;
	}

	/**
	*@description 根据article id 获得 the article
	*@var Integer $pid 文章的id
	*@return CRecordSet $thepost 特定文章的结果集
	*/
	public function GetPostByPID($pid=1)
	{
		$thepost = an_article::model()->find('aid=:pid', array(':pid'=>$pid));
		if($thepost->isallow != 1)
		{
			return null;
		}
		return $thepost; 
	}

/**********************************************************************************************************************************/	
	/**
	*检查文章是否存在
	*@param String $aid  文章ID
	*@return boolean true if exists ; false if not 
	*/
	public function checkArticle($aid)
	{
		return Article::model()->exists('aid=:aid',array(':aid' => $aid));
	}

	/**
	*获取特定文章信息
	*@return CActiveRecordset $art
	*
	*/
	public function GetArticle($aid)
	{
		if(!$this->checkArticle($aid)) return null;
		$art = Article::model()->findByPk($aid);
		return $art;
	}
/**********************************************************************************************************************************/

	/**
	*获取需要编辑的文章
	*@var String $verifyed 待处理文章审核（审核和未审核;Integer $limit 限制显示的条数
	*@return Array 结果数组，包括文章(CRecordSet)和分页数据(CPagination) 
	*/
	public function GetPostsForEdit($verifyed=1,$limit=10)
	{
		$posts  = array();//结果集
		$posts_ = array();//处理后的结果
		$result = array();


		/*分页处理*/

		$criteria = new CDbCriteria();
		$criteria->order = 'uploadtime desc';          	//按上传时间字段来排序
		$criteria->addCondition("isallow=:isverified"); //设定查询条件指定类别
		$criteria->params[':isverified'] = $verifyed;
		$criteria->limit = $limit;    					//取$limit条数据，如果$limit小于0，则不作处理

		$count = Article::model()->count($criteria);    //count() 函数计算数组中的单元数目或对象中的属性个数

		$pager = new CPagination($count);			    //分页类
		$pager->pageSize = Yii::app()->params['BackShowEditPosts'];        //每页显示的行数
		$pager->applyLimit($criteria);
		$posts = Article::model()->findAll($criteria);	//查询所有的数据
		foreach ($posts as $key => $onepost) 
		{
			$posts_[] = array(
								'aid'        => $onepost->aid,
								'uid'        => $onepost->uid,
								'title'      => mb_strimwidth($onepost->title,0, Yii::app()->params['PTAbsCountPag'],'...','utf-8'),
								'from'       => $onepost->from,
								'fromlink'   => $onepost->fromlink,
								'author'     => $onepost->author,
								'uploadtime' => $onepost->uploadtime,
								'editor'     => $this->usersop->GetNameByID($onepost->uid),
								'content'    => $onepost->content,
								'type'       => $this->GetTypeName($onepost->typeid),
								'link'       => $onepost->link,
								'point'      => $onepost->point
							 );
		}


		/*分别存储文章数据和分页数据*/
		$result['posts'] = $posts_;
		$result['pager'] = $pager;
		return $result; 
	}

	/**
	*获取普通用户个人需要编辑的文章
	*@var Integer $uid 用户ID ; String $verifyed 待处理文章审核（审核和未审核;Integer $limit 限制显示的条数
	*@return Array 结果数组，包括文章(CRecordSet)和分页数据(CPagination) 
	*/
	public function GetMyPostsForEdit($uid,$verifyed=1,$limit=10)
	{
		$posts  = array();//结果集
		$posts_ = array();//处理后的结果
		$result = array();


		/*分页处理*/

		$criteria = new CDbCriteria();
		$criteria->order = 'uploadtime desc';          	//按上传时间字段来排序
		
		$criteria->addCondition("isallow=:isverified"); //设定查询条件指定是否审核
		$criteria->params[':isverified'] = $verifyed;

		$criteria->addCondition("uid=:uid");            //设定查询条件指定用户
		$criteria->params[':uid'] = $uid;




		$criteria->limit = $limit;    					//取$limit条数据，如果$limit小于0，则不作处理

		$count = Article::model()->count($criteria);    //count() 函数计算数组中的单元数目或对象中的属性个数

		$pager = new CPagination($count);			    //分页类
		$pager->pageSize = Yii::app()->params['BackShowEditPosts'];        //每页显示的行数
		$pager->applyLimit($criteria);
		$posts = Article::model()->findAll($criteria);	//查询所有的数据
		foreach ($posts as $key => $onepost) 
		{
			$posts_[] = array(
								'aid'        => $onepost->aid,
								'uid'        => $onepost->uid,
								'title'      => $onepost->title,
								'from'       => $onepost->from,
								'fromlink'   => $onepost->fromlink,
								'author'     => $onepost->author,
								'uploadtime' => $onepost->uploadtime,
								'editor'     => $this->usersop->GetNameByID($onepost->uid),
								'content'    => $onepost->content,
								'type'       => $this->GetTypeName($onepost->typeid),
								'link'       => $onepost->link,
								'point'      => $onepost->point
							 );
		}


		/*分别存储文章数据和分页数据*/
		$result['posts'] = $posts_;
		$result['pager'] = $pager;
		return $result; 
	}





	/**
	*获取待编辑的某篇文章
	*@var Integer $aid 文章的ID
	*@return Array $thepost_ 处理完毕的文章数据
	*/
	public function GetThePostForEdit($aid)
	{
		$thepost  = null;
		$thepost_ = array();

		
		if(an_article::model()->count('aid=:pid', array(':pid'=>$aid)) == 1)
		{
			$thepost = an_article::model()->find('aid=:pid', array(':pid'=>$aid));
			$thepost_ = array(
								'aid'        => $thepost->aid,
								'uid'        => $thepost->uid,
								'title'      => $thepost->title,
								'from'       => $thepost->from,
								'fromlink'   => $thepost->fromlink,
								'author'     => $thepost->author,
								'isallow'    => $thepost->isallow,
								'uploadtime' => $thepost->uploadtime,
								'editor'     => $this->usersop->GetNameByID($thepost->uid),
								'content'    => $thepost->content,
								'type'       => $thepost->typeid,
								'link'       => $thepost->link,
								'point'      => $thepost->point
							 );
		}
		else 
		{
			return null;
		}
		return $thepost_;
	}

	/**
	*新建文章预处理
	*@var CRequest $thepost 文章数据请求，必要字段必须不为空
	*@return boolean true 新建成功;false 新建失败
	*/
	public function newArticle($thepost)
	{
		$newAID = 0;
		$editorID = $this->backtool->getUID();
		if($editorID === false)
		{
			return false;
		}
		if(!$this->checkValid($thepost->getParam('title')))
		{
			return false;
		}		
		if(!$this->checkValid($thepost->getParam('from')))
		{
			return false;
		}
		if(!$this->checkValid($thepost->getParam('fromlink')))
		{
			return false;
		}
		if(!$this->checkValid($thepost->getParam('typeid')))
		{
			return false;
		}

		$criteria=new CDbCriteria;
		$criteria->select='max(aid) AS maxColumn';
		$row = $this->db_article->model()->find($criteria);
		$newAID = $row->maxColumn;
		$newAID++;
		$newpost = array(
							'aid'   	 => $newAID,
							'uid'   	 => $editorID,
							'title' 	 => $thepost->getParam('title'),
							'from'  	 => $thepost->getParam('from'),
							'fromlink'   => $thepost->getParam('fromlink'),
							'author'     => $thepost->getParam('author'),
							'uploadtime' => date('Y-m-d H:i:s'),
							'content' 	 => $thepost->getParam('content'),
							'typeid'  	 => $thepost->getParam('typeid'),
							'link'    	 => Yii::app()->createUrl('homeAn/ReadPost',array('pid'=>$newAID))
						);
		$this->InsertArticle($newpost);
		return true;

	}

/*************************************************私有方法**********************************************************/
	/**
	*检查某个分类是否满足父子关系
	*@param String $tid_c 待检查分类ID;String $tid_p 匹配目标
	*@return boolean true 如果满足;false 不满足
	*/
	private function checkTypeRel($tid_c,$tid_p)
	{
		if($tid_c == $tid_p) return true;//自己满足自己
		else
		{
			$tid_np = 0;
			$tid_np = $this->typeop->GetParentType($tid_c);//获取分类的父类
			while($tid_np != 0)
			{
				if($tid_np == $tid_c) return true;//本身无父类
				if($tid_np == $tid_p) return true;//匹配到
				$tid_np = $this->typeop->GetParentType($tid_c);//获取分类的父类
			}
			return false;//最终没有匹配到
		}
	}



	/**
	*将新建的文章写入数据库
	*@var Array $theArt 文章数据数组
	*/
	private function InsertArticle($theArt)
	{
		$this->db_article->aid = $theArt['aid'];                      //填充文章表AR对象
		$this->db_article->uid = $theArt['uid'];
		$this->db_article->title = $theArt['title'];
		$this->db_article->from = $theArt['from'];
		$this->db_article->fromlink = $theArt['fromlink'];
		$this->db_article->author  = $theArt['author'];
		$this->db_article->isallow = 0;
		$this->db_article->uploadtime = $theArt['uploadtime'];
		$this->db_article->content = $theArt['content'];
		$this->db_article->typeid = $theArt['typeid'];
		$this->db_article->link = $theArt['link'];
		$this->db_article->point = 0;



		$this->db_updateart->aid = $theArt['aid'];						//填充Solr更新文章表AR对象
		$this->db_updateart->issucc = 0;

		$this->db_updateart->save();									//进行保存
		$this->db_article->save();
		

	}

	/**
	*检测变量有效性
	*@var mixed $thevar
	*@return boolean true if valid else false
	*/
	private function checkValid($thevar)
	{
		if($thevar === null) return false;
		if($thevar === "") return false;
		return true;
	}
}
?>