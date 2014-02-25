<?php
/**
*@description 主页控制器，提供主页的所有服务。URL=http://jelcore.oicp.net:7080/cnAnalysis/index.php/homeAn
*@author JelCore
*@version 2014-02-08
*/
class HomeAnController extends Controller
{
	//声明模型类
	private $artop = null;//文章操作模型
	private $usrop = null;//用户操作模型
	private $searchop = null;//搜索数据操作模型
	private $typeop   = null;//类别支持模型

	public  $anviewop  = null;//公共视图渲染对象
	public  $homeviewop    = null;//文章阅读视图数据操作模型

	//默认动作
	public $defaultAction = 'Home';

	/*主页布局文件*/
	public $layout = 'homepage';


	/*定义页面需要的URL*/
	public $TheRegisterURL = "";  //注册动作
	public $TheLoginURL    = "";  //登录动作
    public $TheUserhomeURL = "";  //用户中心
	
	public $TheMoreLink1 = ""; //语情动态更多链接
	public $TheMoreLink2 = ""; //语言生活动态更多链接
	public $TheMoreLink3 = ""; //语言事件与活动更多链接
	public $TheMoreLink4 = ""; //语言应用更多链接


	//side bar
    /*热门搜索关键词*/
    public $hotsearchkw = null;
    /*搜索的类别*/
    public $types       = null;
    public $typetree    = null;
	// 初始化参数
	private function InitializeCAn( )
	{
		/*初始化链接*/
		$this->TheRegisterURL = $this->createUrl('Register');  
		$this->TheLoginURL    = $this->createUrl('Login');
        $this->TheUserhomeURL = $this->createUrl("Userhome");
		$this->TheMoreLink1   = $this->createUrl('DoMore',array('atid'=> YQNEWS));  
		$this->TheMoreLink2   = $this->createUrl('DoMore',array('atid'=> YQLFNEWS));   
		$this->TheMoreLink3   = $this->createUrl('DoMore',array('atid'=> YQEVENT));  
		$this->TheMoreLink4   = $this->createUrl('DoMore',array('atid'=> YQAPP));

		/*初始化modle*/
		$this->artop = new Articleop();
		$this->usrop = new User();
		$this->searchop = new AnSearchop();
		$this->homeviewop = new HomeAnViewop();
		$this->typeop     = new ArtTypeop();
		$this->anviewop   = new AnViewop();

		$this->hotsearchkw = $this->searchop->getKeyWords();
		$this->types       = $this->searchop->getSearchTypes();
		$this->typetree    = $this->typeop->GetTypesTree();

	}



    public function actionUserhome(){
    	$this->layout = "userhome";
        $this -> InitializeCAn();
        $this->render("userhome");
    }

	public function actionHome()
	{//默认主页的index动作
		$this->InitializeCAn();//初始化参数

		$HomePosts = $this->artop->GetHomePosts();
		$this->render('home',array('homeposts' => $HomePosts));
	}

	public function actionLogin()
	{//默认主页的Login动作
		$this->InitializeCAn();//初始化参数
		$this->render('login');
		
	}
	public function actionRegister()
	{//默认主页的Register动作
		$this->InitializeCAn();//初始化参数
		$this->render('register');
	}

	/**
	*@description 更多文章功能的列表页面
	*
	*/
	public function actionDoMore()
	{
		$this->InitializeCAn();//初始化参数

		$TheTypeID = (int)Yii::app()->request->getParam('atid',1);//获取指定的分类，未设置的设为分类1
		$posts   = array();//特定类别下的所有文章
		$posts = $this->artop->GetMorePosts($TheTypeID);//指定文章类型，查询为无限制和每页显示条数
		$morelink = $this->GetMoreLink($TheTypeID);
		
		$this->render('articlelist',array('posts' => $posts['posts'],
			'pages'=> $posts['pager'],'typeid' => $TheTypeID,'morelink' => $morelink,'typetree' => $this->typeop->GetTypesTree($TheTypeID)));
	}

	/**
	*@description 文章阅读页面
	*URL:http://localhost:7080/cnAnalysis/index.php/homeAn/ReadPost/pid/16
	*/
	public function actionReadPost()
	{
		$this->InitializeCAn();//初始化参数
		$ThePostID = (int)Yii::app()->request->getParam('pid');//获取指定文章ID
		if($ThePostID != null)
		{
			//TODO:使得文章点击次数+1
			$thepost  = $this->artop->GetPostByPID($ThePostID);
			if($thepost == null)
			{
				//TODO: error show
				exit;
			}
			$pointtmp = $thepost->point;
			$pointtmp++;
			$thepost->point = $pointtmp;
			$thepost->save();

			$thepost  = $this->artop->GetPostByPID($ThePostID);      //得到这篇文章

			$typename = $this->artop->GetTypeName($thepost->typeid); //得到这边文章

			$morelink = $this->GetMoreLink($thepost->typeid); //得到这篇文章同分类下更多文章的链接

			$username = $this->usrop->GetUNameByUID($thepost->uid);

			$this->render('article',array('thepost' => $thepost,'typename' => $typename,'morelink' => $morelink,'username' => $username));

		}	
		else
		{

		}
	}

	/**
	*登出成功的页面动作
	*
	*/
	public function actionLogoutSucc()
	{
		$lasturl = urldecode(Yii::app()->request->getParam('lasturl'));
		$randcode = Yii::app()->request->getParam('rncd');
		$session = Yii::app()->session;
		if(isset($session['logoutrand']) && $session['logoutrand'] == $randcode)
		{
			unset($session['logoutrand']);
			$this->render('logoutsucc',array('lasturl'=>$lasturl));
		}
		else
		{
			exit('Access Forbidden !');
		}
		
	}


























	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}






// ---------------------------------------------------------------------工具函数---------------------------------------------------------------------------
	/**
	*@description 根据type id 获得 morelink
	*
	*/
	private function GetMoreLink($TheTypeID = 2)
	{
		$morelink = "";
		switch($TheTypeID)
		{
			case YQNEWS: $morelink = $this->TheMoreLink1;
			break;
			case YQLFNEWS: $morelink = $this->TheMoreLink2;
			break;
			case YQEVENT: $morelink = $this->TheMoreLink3;
			break;
			case YQAPP: $morelink = $this->TheMoreLink4;
			break;
		}
		return $morelink;
	}


	
	// -----------------------------------------------------------
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			/*验证码功能类相关设置*/
			'captcha'=>array(
                'class'=>'JCaptchaAction',
                'backColor'=>0xf4f4f4,
                'padding'=>0,
                'height'=>30,
                'maxLength'=>5,/*最大长度*/
                'minLength'=>4,/*最小长度*/
            )
		);
	}
	
}