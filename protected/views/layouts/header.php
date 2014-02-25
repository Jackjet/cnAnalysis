<?php
/**
*@description 各页面通用页首布局文件
*@package JelCore
*@revised 2013-12-18
*/
//定义资源引用路径
$ImagesURL = Yii::app()->request->baseUrl . "/images";  //图片资源
$JsURL     = Yii::app()->request->baseUrl . "/js";      //Js脚本
$CssURL    = Yii::app()->request->baseUrl . "/css";	    //css样式表
$HomeURL   = Yii::app()->homeUrl;		    			//主页URL
?><!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>动态预料库</title>
	<meta name="keywords" content=""/>
	<meta  name="description" content="" />
	<meta name="author" content="新思路团队,网酷工作室,c1avie" />
	<link rel="shortcut icon" href="<?php echo $ImagesURL;?>/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="<?php echo $CssURL;?>/style.css" />
	<script type="text/javascript">
	var getLoginURL = "<?php echo Yii::app()->createUrl('Users');?>";
	</script>
	<script type="text/javascript" src="<?php echo $JsURL;?>/html5.js"></script>
    <script type="text/javascript" src="<?php echo $JsURL;?>/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="<?php echo $JsURL;?>/solr.js"></script>
    <script type="text/javascript" src="<?php echo $JsURL;?>/validator.js"></script>

</head>
<body id="body">
<!-- top part html -->
<div class="top">
	<div class="top-inner">
		<span class="fl">
			<a href="<?php echo $HomeURL;?>">首页</a>
		</span>
		<span class="fr" id="reglogin">
			<a href="<?php echo $this->TheLoginURL;?>">登录</a>
			<a href="<?php echo $this->TheRegisterURL;?>">注册</a>
		</span>
	</div>
</div>
<div class="wrapper">
	<!-- header part html -->
	<header>
		<div class="logo">
			<a href="<?php echo $HomeURL;?>">
				<img src="<?php echo $ImagesURL;?>/logo.png" width="186" height="78" title="动态语料库" alt="动态语料库" />
			</a>
		</div>
		<h1>动态语料库</h1>
		<div class="search">
			<input type="text" class="search-text" id="global-search" value="在此输入您想检索的内容" />
			<input type="button" class="search-button" />
		</div>
	</header>
