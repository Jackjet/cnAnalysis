<?php
/**
*@description 后台控制器，提提供后台的所有服务。URL=http://jelcore.oicp.net:7080/cnAnalysis/index.php/backAn
*@author JelCore
*@version 2014-01-18
*/

class BackAnController extends Controller
{

	/*工具对象*/
	private $backtool = null;//后台工具对象
	private $artop    = null;//文章操作对象
	private $typeop   = null;//分类维护操作对象
	private $antop    = null;//爬虫操作对象
	private $usersop  = null;//用户操作对象
	private $userauth = null;//用户授权操作对象
	private $groupsop = null;//用户组操作对象
	private $filesop      = null;//上传文件操作对象
	public  $backanviewop = null;//后台视图页面渲染工具对象
	public  $anviewop     = null;//公共视图渲染工具对象
	/*参数*/
	private $querylimit = 0;//查询限制
	/*默认动作*/
	public $defaultAction='ResManage';//资源管理

	/*后台页面布局文件*/
	public $layout = 'backpage';

	/*当前登录的用户信息*/
	public $uid = "";	  //用户id
	public $usernick = "";//用户昵称
	public $userrole = "";//用户角色
	//public $userImg  = "";//用户头像

	/*客户端请求对象和session对象*/
	private $request;
	private $session;

	/*定义页面需要的URL*/
	public $TheNonVeriArtURL      = "";  //未审核文章
	public $TheVerifyedArtURL     = "";  //已审核文章
	public $TheVerifyedArtURLMy   = "";  //普通用户自己的已审核文章
	public $TheNonVeriArtURLMy    = "";  //普通用户自己的已审核文章

    public $TheEditUsersURL       = "";  //编辑用户
    public $TheAddUsersURL        = "";  //添加用户
    public $TheDeleUsersURL		  = "";  //删除用户
    public $TheUpFilesURL         = "";  //上传文件
    public $ThePubArtURL		  = "";  //发布文章
    public $TheAuthManURL         = "";  //权限管理
    public $TheCatManURL          = "";  //分类维护
    public $TheCrawlerURL         = "";  //爬虫管理


    /***************************************private methods********************************/

    // 初始化参数 函数
    private function InitializeCAn()
    {
    	$this->request = Yii::app()->request;
    	$this->session = Yii::app()->session;
    	$this->backtool = new BackTool();
    	$this->artop    = new Articleop();
    	$this->typeop   = new ArtTypeop();
    	$this->antop    = new Anantop();
    	$this->usersop  = new Usersop();
    	$this->userauth = new UsersAuth();
    	$this->groupsop = new Groupsop();
    	$this->filesop  = new Filesop();
    	$this->backanviewop = new BackAnViewop();
    	$this->anviewop     = new AnViewop();

    	$this->querylimit = Yii::app()->params['BackShowQueryLimit'];

    	$this->TheNonVeriArtURL    = $this->createUrl('ResManage',array('tid'=>'NonVerifyedArticles'));
    	$this->TheVerifyedArtURL   = $this->createUrl('ResManage',array('tid'=>'VerifyedArticles'));
    	$this->TheVerifyedArtURLMy = $this->createUrl('MyRes',array('tid'=>'VerifyedArticles'));
    	$this->TheNonVeriArtURLMy  = $this->createUrl('MyRes',array('tid'=>'NonVerifyedArticles'));
    	$this->TheEditUsersURL     = $this->createUrl('UsersManage',array('tid'=>'EditUsersBefore'));
    	$this->TheAddUsersURL      = $this->createUrl('UsersManage',array('tid'=>'AddUsers'));
    	$this->TheDeleUsersURL     = $this->createUrl('UsersManage',array('tid'=>'DeleteUsers'));
    	$this->TheAuthManURL       = $this->createUrl('UsersManage',array('tid'=>'AuthManage'));
    	$this->TheUpFilesURL       = $this->createUrl('Publish',array('tid'=>'UploadFiles'));
    	$this->ThePubArtURL        = $this->createUrl('Publish',array('tid'=>'PublishArticles'));
    	$this->TheCatManURL        = $this->createUrl('CatManage');
    	$this->TheCrawlerURL       = $this->createUrl('Crawler');

    	$this->uid = $this->backtool->getUID();
    	$this->userrole = $this->backtool->getNowUserRole();

    	if($this->uid === null || $this->userrole === false)
    	{
    		$this->render('error',array('errorCode'=>'用户授权失败！请重新登录！'));
    		exit();
    	}
    	$info = array();
    	if($this->uid)
    	{
    		$info = (new User())->getInfo(array("uid"=>$this->uid));
    		$this->usernick = $info['name'];
    	}
    	
    }
    /**************************************private methods end**********************************************************************/



    /**************************************actions**********************************************************************************/

    /*************************************页面显示的actions*************************************************************************/
    /**
    *资源管理类的动作
    *@version 2014-01-07
    */
	public function actionResManage()
	{
		$this->InitializeCAn();
		$optype = $this->request->getParam('tid');
		$theposts = array();
		if($optype === 'NonVerifyedArticles')  //默认页面
		{
			$theposts = $this->artop->GetPostsForEdit(0,$this->querylimit);    //拉取未审核文章
			$this->render('resmanage',array('oid'=>1,'posts'=>$theposts['posts'],'pages'=>$theposts['pager'],'userrole'=>$this->userrole));
		}
		else if($optype === 'VerifyedArticles' || $optype === null || $optype === "")
		{
			$theposts = $this->artop->GetPostsForEdit(1,$this->querylimit);    //拉取已审核文章
			$this->render('resmanage',array('oid'=>2,'posts'=>$theposts['posts'],'pages'=>$theposts['pager'],'userrole'=>$this->userrole));
		}
		else if($optype === 'DoEdit')
		{
			$pid = $this->request->getParam('aid');
			if($pid === null || $pid === "")
			{
				$this->render('error',array('errorCode'=>'未定义的操作！请使用菜单栏提供的功能链接！'));
				exit();
			}
			$thepost = $this->artop->GetThePostForEdit($pid);

			$oid = 4;
			if($thepost['isallow'] == "1")
			{
				$oid = 3;
			}
			$this->render('resmanage',array('oid'=>$oid,'thepost'=>$thepost,
				'typetree' => $this->typeop->GetTypesTree(),
				'userrole'=>$this->userrole));
		}
		else
		{
			$this->render('error',array('errorCode'=>'未定义的操作！请使用菜单栏提供的功能链接！'));
		}

		
	}

	/**
    *普通用户自己文章处理的动作
    *@version 2014-01-08
    */
	public function actionMyRes()
	{
		$this->InitializeCAn();
		$optype = $this->request->getParam('tid');
		$uid = $this->backtool->getUID();
		$theposts = array();
		if($optype === 'NonVerifyedArticles' || $optype === null || $optype === "")  //默认页面
		{
			$theposts = $this->artop->GetMyPostsForEdit($uid,0,$this->querylimit);    //拉取未审核文章
			$this->render('resmanage',array('oid'=>1,'posts'=>$theposts['posts'],'pages'=>$theposts['pager'],'userrole'=>$this->userrole));
		}
		else if($optype === 'VerifyedArticles' )
		{
			$theposts = $this->artop->GetMyPostsForEdit($uid,1,$this->querylimit);    //拉取已审核文章
			$this->render('resmanage',array('oid'=>2,'posts'=>$theposts['posts'],'pages'=>$theposts['pager'],'userrole'=>$this->userrole));
		}
		else if($optype === 'DoEdit')
		{
			$pid = $this->request->getParam('aid');
			if($pid === null || $pid === "")
			{
				$this->render('error',array('errorCode'=>'未定义的操作！请使用菜单栏提供的功能链接！'));
				exit();
			}
			$thepost = $this->artop->GetThePostForEdit($pid);

			$oid = 4;
			if($thepost['isallow'] == "1")
			{
				$oid = 3;
			}
			$this->render('resmanage',array('oid'=>$oid,'thepost'=>$thepost,'userrole'=>$this->userrole,'typetree' => $this->typeop->GetTypesTree()));
		}
		else
		{
			$this->render('error',array('errorCode'=>'未定义的操作！请使用菜单栏提供的功能链接！'));
		}
	}

	/**
	*用户管理类的动作
	*
	*/
	public function actionUsersManage()
	{
		$this->InitializeCAn();
		$optype = $this->request->getParam('tid');
		if($optype === 'EditUsersBefore')       //用户检索
		{
			$successInfo = $this->request->getParam('sucif');
			if($successInfo !== null)
			$this->render('usersmanage',array('oid'=>1,'userrole'=>$this->userrole,
				'searchdata' => $this->backtool->getSearchData('searchdata'),
				'searchtype' => $this->backtool->getSearchData('searchtype'),
				'successInfo' => urldecode($successInfo)
				));
			else
				$this->render('usersmanage',array('oid'=>1,'userrole'=>$this->userrole,
				'searchdata' => $this->backtool->getSearchData('searchdata'),
				'searchtype' => $this->backtool->getSearchData('searchtype')
				));
		}
		else if($optype === 'EditFindUsers')        //用户检索结果
		{
			$successInfo = $this->request->getParam('sucif');
			$search = $this->prepareSearch();
			$type = $search['type'];
			$condition = $search['condition'];
			$users = $this->usersop->FindUsers($type,$condition);
			if($successInfo !== null)
			$this->render('usersmanage',array('oid'=>2,'users'=>$users['users'],
				'pages'=>$users['pager'],
				'userrole'=>$this->userrole,
				'successInfo' => urldecode($successInfo)
				));
			else
				$this->render('usersmanage',array('oid'=>2,'users'=>$users['users'],
				'pages'=>$users['pager'],
				'userrole'=>$this->userrole
				));
		}
		else if($optype === 'EditUsersAfter')   		//对检索到的用户编辑
		{

			$uid = $this->request->getParam('uid');
			if($uid === null || $uid === "")
			{
				$this->render('error',array('errorCode'=>'提交的表单为空！请检查！'));
				exit();
			}

			$theuser = $this->usersop->GetUserByID($uid);
			$this->render('usersmanage',array('oid'=>3,'theuser'=>$theuser,'userrole'=>$this->userrole));
		}
		else if($optype === 'AddUsers')
		{
			$this->render('usersmanage',array('oid'=>4,'userrole'=>$this->userrole));
		}
		else if ($optype === 'DeleteUsers') 
		{
			$this->render('usersmanage',array('oid'=>5,'userrole'=>$this->userrole,'searchdata' => $this->backtool->getSearchData('searchdata'),
				'searchtype' => $this->backtool->getSearchData('searchtype')));
		}
		else if ($optype === 'DeleteFindUsers') 
		{
			$search = $this->prepareSearch();
			$type = $search['type'];
			$condition = $search['condition'];
			$users = $this->usersop->FindUsers($type,$condition,'delete');  //获取用户用户群删除操作
			$this->render('usersmanage',array('oid'=>6,'userrole'=>$this->userrole,'users'=>$users['users'],'pages'=>$users['pager']));
		}
		else if($optype === 'AuthManage')
		{
			$this->render('usersmanage',array('oid'=>7,'userrole'=>$this->userrole,'searchdata' => $this->backtool->getSearchData('searchdata'),
				'searchtype' => $this->backtool->getSearchData('searchtype')));
		}
		else if($optype === 'AuthManageFindUsers')
		{
			$search = $this->prepareSearch();
			$type = $search['type'];
			$condition = $search['condition'];
			$users = $this->usersop->FindUsers($type,$condition);
			$groups = $this->groupsop->getGroups();
			$this->render('usersmanage',array('oid'=>8,'userrole'=>$this->userrole,'users'=>$users['users'],'pages'=>$users['pager'],'groups'=>$groups));
		}
		else 
		{
			$this->render('error',array('errorCode'=>'未定义的操作！请使用菜单栏提供的功能链接！'));
		}
	}

	/**
	*发布内容类的动作
	*
	*/
	public function actionPublish()
	{
		$this->InitializeCAn();
		$optype = $this->request->getParam('tid');
		if($optype === 'UploadFiles')
		{
			$this->render('publish',array('oid'=>1,'typetree' => $this->typeop->GetTypesTree()));
		}
		else if($optype === 'PublishArticles')
		{
			$this->render('publish',array('oid'=>2,'userrole'=>$this->userrole,'typetree' => $this->typeop->GetTypesTree()));
		}
		else
		{
			$this->render('error',array('errorCode'=>'未定义的操作！请使用菜单栏提供的功能链接！'));
		}
	}


	/**
	*分类维护的动作
	*
	*
	*/
	public function actionCatManage()
	{
		$this->InitializeCAn();
		$typetree = $this->typeop->GetTypesTree();
		$this->render('catmanage',array('typetree'=>$typetree));
	}

	/**
	*
	*
	*/
	public function actionCrawler()
	{
		$this->InitializeCAn();
		$antmiss = $this->antop->getAntMiss();
		$this->render('crawler',array('antmiss'=>$antmiss['miss'],'pages'=>$antmiss['pager']));
	}
	/********************************************************************************************************************************/
	/********************************************数据交换和处理的actions**************************************************************/
	/**
	*响应新建文章资源的动作
	*
	*
	*/
	public function actionNewArticle()
	{
		$this->InitializeCAn();
		$result = $this->artop->newArticle($this->request);
		if(!$result)
		{

			$this->render('error',array('errorCode'=>'新建文章失败！请检查必填项！'));
			
		}
		else
		{	

			if($this->backtool->checkUserAccess(RADMINJ) || $this->backtool->checkUserAccess(RSYSJ))
			{
				$successInfo = "发布成功！请将其审核通过！";
			}
			else
			{
				$successInfo = "发布成功！请申请管理员审核！";
			}

			$uid = $this->backtool->getUID();
			$theposts = array();
			$theposts = $this->artop->GetMyPostsForEdit($uid,0,$this->querylimit);    //拉取未审核文章
			$this->render('resmanage',array('oid'=>1,'posts'=>$theposts['posts'],
				'pages'=>$theposts['pager'],'userrole'=>$this->userrole,
				'successInfo' => $successInfo
				));
		}

	}


	/**
	*响应新建用户的动作
	*
	*/
	public function actionNewUser()
	{
		$this->InitializeCAn();
		$info = array();
		$info['username'] = $this->request->getParam('username');
		$info['email']	  = $this->request->getParam('email');
		$info['passwd']   = $this->request->getParam('passwd');
		$info['repasswd'] = $this->request->getParam('repasswd');
		$result = $this->usersop->NewUser($info);
		if($result === true)
		{
			$this->render('usersmanage',array('oid'=>4,'userrole'=>$this->userrole,
				'successInfo' => "添加用户成功！"
				));
		}
		else
		{
			$this->render('error',array('errorCode'=>$result));
		}
	}


	/**
	*响应更新用户信息的动作
	*
	*
	*/
	public function actionUpdateUser()
	{
		$this->InitializeCAn();
		$info = array();
		$info['uid'] = $this->request->getParam('theuid');
		$info['name'] = $this->request->getParam('name');
		$info['phone'] = $this->request->getParam('phone');
		$info['address'] = $this->request->getParam('address');
		$result = $this->usersop->UpdateUser($info);

		$search = $this->backtool->getSearchData('searchdata');
		$type = $this->backtool->getSearchData('searchtype');

		
		if($result === true)
		{

			if($search !== null && $type !== null)							          //提取历史搜索返回操作前的界面
			{
				$TheUrl = $this->createUrl('UsersManage',array('tid'=>'EditFindUsers','sucif'=>urlencode('编辑用户成功！')));
				$this->redirect($TheUrl);
			}
			else
			{
				$TheUrl = $this->createUrl('UsersManage',array('tid'=>'EditUsersBefore','sucif'=>urlencode('编辑用户成功！')));
				$this->redirect($TheUrl);
			}


		}
		else
		{
			$this->render('error',array('errorCode'=>$result));
		}
	}

	/**
	*响应删除用户的动作
	*
	*/
	public function actionNewFile()
	{
		$this->InitializeCAn();
		$info = array(
							'title' 	 => $this->request->getParam('title'),
							'from'  	 => $this->request->getParam('from'),
							'fromlink'   => $this->request->getParam('fromlink'),
							'author'     => $this->request->getParam('author'),
							'uploadtime' => date('Y-m-d H:i:s'),
							'typeid'  	 => $this->request->getParam('typeid')
						);
		$newfiles = CUploadedFile::getInstanceByName('upfile');
		$result = $this->filesop->doUpload($newfiles,$info);
		if($result === true)
		{
			$uid = $this->backtool->getUID();
			$theposts = array();
			$theposts = $this->artop->GetMyPostsForEdit($uid,0,$this->querylimit);    //拉取未审核文章
			$this->render('resmanage',array('oid'=>1,'posts'=>$theposts['posts'],
				'pages'=>$theposts['pager'],'userrole'=>$this->userrole,
				'successInfo' => "文件发布成功！请审核通过或者申请管理员审核通过！"
				));
		}
		else
		{
			$this->render('error',array('errorCode'=>$result));
		}
	}



	/**************************************************************其他actions************************************************************/
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		$this->InitializeCAn();
		Yii::app()->user->logout();
		 if ($this->userauth->checkCookieJ("uid") && $this->userauth->checkCookieJ("username"))
         {
            $this->userauth->destroyCookieJ('uid');
            $this->userauth->destroyCookieJ('username');
         }
         else
         {
             $this->session->clear();
             $this->session->destroy();
         }
		$this->redirect(Yii::app()->homeUrl);
	}
	/*************************************actions end*************************************/
    /************************************private method ************************************/
    /**
    *处理用户管理查询的记录
    *@return array $result['condition'] and $result['type']
    */
    private function prepareSearch()
    {
    	$result = array();

    	$condition = $this->backtool->getSearchData('searchdata');
		$type = $this->backtool->getSearchData('searchtype');

		$typec = "username";                		//检索的字段
		$conditionc = $this->request->getParam('username');
		if($conditionc === null || $conditionc === "")              //判断是否输入了username或者email中的一个或两个
		{
			$conditionc = $this->request->getParam('email');

			if($conditionc === null || $conditionc === "")
			{
				if($type === null || $condition === null)
				{
					$this->render('error',array('errorCode'=>'输入不能为空！请至少输入用户名或邮箱中的一个'));
					exit();
				}
				$result['type'] = $type;
				$result['condition'] = $condition;
				$this->backtool->saveSearchData('searchdata',$condition);				//记录用户搜索历史
				$this->backtool->saveSearchData('searchtype',$type);
				return $result;
			}

			$typec = "email";
		}

		if($type === null || $condition === null)
		{
			$result['type'] = $typec;
			$result['condition'] = $conditionc;
			$this->backtool->saveSearchData('searchdata',$conditionc);				//记录用户搜索历史
			$this->backtool->saveSearchData('searchtype',$typec);
			return $result;
		}
		else
		{
			if($typec != $type) $type = $typec;
			if($conditionc != $condition) $condition = $conditionc;

			$result['type'] = $type;
			$result['condition'] = $condition;
			$this->backtool->saveSearchData('searchdata',$condition);				//记录用户搜索历史
			$this->backtool->saveSearchData('searchtype',$type);
			return $result;
		}
    }






































	// Uncomment the following methods and override them if needed
	
	public function filters()
	{
		 return array(
		 'accessControl', // perform access control for CRUD operations
		 );
	}
	 
	public function accessRules()
	{ 
		 return array(
						 array('allow',
								 'actions'=>array('ResManage','UpdateUser',
								 				  'UsersManage','Publish',
								 				  'Logout','NewArticle','NewFile','MyRes',
								 				  'NewUser','CatManage','Crawler'
								 				  ),//指派特定可执的动作
								 'roles'=>array('1'),             		//此处绑定了users的可执行的group为1系统管理员
							  ),
						 array('allow',
								 'actions'=>array('ResManage','UpdateUser',
												 'UsersManage','Publish',
												 'Logout','NewArticle','NewFile','MyRes',
												 'NewUser'
												 ),//指派特定可执的动作
								 'roles'=>array('2'),             	  //此处绑定了users的可执行的group为2资源管理员

							  ),
						  array('allow',
								 'actions'=>array('Publish','Logout','NewArticle','NewFile','MyRes'),//指派特定可执的动作
								 'roles'=>array('3'),             	  //此处绑定了users的可执行的group为3普通用户
								 
							  ),
						 array('deny',  // deny all users
								 'users'=>array('*'),
								 ),
		 			 );
	}

	
	/*
	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}