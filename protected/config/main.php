<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

/*定义文章类别常量 此四个大类不可删除*/
define ('YQNEWS',1);  //语情动态
define ('YQLFNEWS',2);//语言生活动态
define ('YQEVENT',3); //语言事件与活动
define ('YQAPP',4);   //语言应用

/*定义三大角色常量*/
//ID类
define('RSYSJ','1');      //系统管理员
define('RADMINJ','2');    //管理员
define('RNORMALJ','3');   //普通用户
//字串类
define('SSYSJ','sys');      //系统管理员
define('SADMINJ','admin');   //管理员
define('SNORMALJ','normal'); //普通用户


//

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'中国语情',
	'defaultController' => 'HomeAn',
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123456',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		

		/*自定义模块*/
        "Users"=>array(),//用户AJAX支持模块
        "Picupload"=>array(),//图片文章上传模块
        'ArticlePro' => array(),//文章处理AJAX支持模块
        'ArtTypePro'=>array(),//文章类别AJAX支持
        'AntProc'=>array(),   //爬虫管理AJAX支持
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'class' => 'WebUser',//定义处理用户权限的类
			'loginUrl' => 'http://localhost:7080/cnAnalysis/index.php/homeAn/Login',//未登录时跳转到的登录页面
			'allowAutoLogin'=>true,
		),
        'urlManager'=>array(
            'urlFormat'=>'path',
            'rules'=>array(
                '<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
            ),
        ),
        'session'=>array(
        			'timeout'=>3600,//session有效期 1一小时
    	),
		// uncomment the following to enable URLs in path-format //URL重写
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName' => false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		/*'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=languagecollect',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'enableParamLogging' => true
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'homeAn/error',
		),
		'log'=>array(
		    'class'=>'CLogRouter',
		    'routes'=>array(

		        array(
		            'class' => 'CFileLogRoute',
		            'levels' =>'error, warning, info',
		            'categories' => 'application.*',//日志文件分类，只有调用时使用了appplication都会默认写入默认文件application.log
		            //'logPath' => '/logs/', //日志文件路径
		            'maxFileSize' => 5120,//日志大小
		            'maxLogFiles' => 20,//保存最大个数，Yii会按时间保留最近20个文件
		        ),
		        array(
		            'class' => 'CFileLogRoute',
		            'levels' => 'error, warning,info',
		            'categories'=> 'db.*',//日志文件分类，db相关
		            'logFile' => 'db.log',//保存数据库操作相关日志
		            //'logPath' => '/logs/', //日志文件路径
		            'maxFileSize' => 5120,//日志大小5M，以kb为单位的
		            'maxLogFiles' => 20,
		        ),
		        array(
		            'class' => 'CFileLogRoute',
		            'levels' => 'trace',
		            'categories'=> 'debug.*',
		            'logFile' => 'debug.log',//保存debug日志
		            //'logPath' => '/logs/',
		            'maxFileSize' => 5120,
		            'maxLogFiles' => 20,
		        ),
		        
		        array(

                    'class'=>'CWebLogRoute',

                    'levels'=>'trace',

                )

		    )
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'FileSavePathPrefixAbs' => $_SERVER['DOCUMENT_ROOT']."/cnAnalysis/uploadfiles/",//配置上传文件绝对存储目录
		'FileSavePathPrefixRel' => 'http://'.$_SERVER['HTTP_HOST'] .'/cnAnalysis/uploadfiles/',//配置上传文件资源URL
		'FileAllowType' => array('doc','pdf','jpg','jpeg','png','gif','xls','ppt','txt','bmp','mp4','flv','mp3','wav','wma','ape','flac'),//支持的文件类型
		'FileAllowSize' => 10*1024*1024,//大小限制10MB 
		'ProjectName'   => 'cnAnalysis',//工程名字，与文件夹对应
		'HomeShowPosts' => 4,//主页分类下最多显示
		'HomeQueryPosts' => 50,//主页分类下每次查询限制
		'DoMoreShowPosts' => 10,//更多页面每页最多显示条数
		'DoMoreQueryPosts' => 100,//主页分类下每次查询限制
		'BackShowEditPosts' => 6,//后台页面编辑文章每页最多显示条数
		'BackShowQueryLimit'=> 100,//后台页面每次查询限制
		'BackShowEditUsers' => 6,//后台页面编辑用户每页最多显示条数
		'BreadCrumbsNavPrefix' => ">>",//面包屑导航的指示符号
		'PTAbsCountNav'           => 16,  //导航文章标题摘要字数(按utf-8中文计算)
		'PTAbsCountPag'           => 38 , //页面标题摘要字数(按utf-8中文计算)
		'HotPostShow'             => 5 ,  //热门文章的显示条数，若为小于0则为不限
		'HotPostTitleCount'       => 14 , //热门文章标题摘要字数(按utf-8中文计算)
		'AntMissQueryLimt'        => -1,  //爬虫任务DB查询限制，-1为不限制
		'AntMissPageCount'        => 6 ,  //爬虫任务列表每页显示条数

	),
);