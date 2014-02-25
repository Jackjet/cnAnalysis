<?php
/**
*@description 后台各页面通用页首布局文件
*@package JelCore
*@revised 2014-01-08
*/
//定义资源引用路径
$ImagesURL = Yii::app()->request->baseUrl . "/images";  //图片资源
$JsURL     = Yii::app()->request->baseUrl . "/js";      //Js脚本
$CssURL    = Yii::app()->request->baseUrl . "/css";	    //css样式表
$HomeURL   = Yii::app()->homeUrl;		    			//主页URL
$EEditorURL   = Yii::app()->request->baseUrl . "/eeditor"; //K编辑器目录
$userrole = $this->userrole;
?><!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<title>中国语情</title>
	
	<link rel="stylesheet" href="<?php echo $CssURL;?>/back_style.css" />

	<script charset="utf-8" src="<?php echo $EEditorURL;?>/kindeditor.js"></script>
	<script charset="utf-8" src="<?php echo $EEditorURL;?>/lang/zh_CN.js"></script>
	<script charset="utf-8" src="<?php echo $EEditorURL;?>/plugins/code/prettify.js"></script>
	<script>
			var artopsubURL = "<?php echo Yii::app()->createUrl('ArticlePro');?>";//articles ajax 
			var useropsubURL = "<?php echo Yii::app()->createUrl('Users');?>";//users ajax
			var arttypesubURL = "<?php echo Yii::app()->createUrl('ArtTypePro');?>";//articletypes ajax
			var antmisssuburl = "<?php echo Yii::app()->createUrl('AntProc');?>";//ant manage ajax
	</script>
	<script>
        KindEditor.ready(function(K) {
                window.editor = K.create('#editor_id');
        });
	</script>
	<script src="<?php echo $JsURL;?>/jquery-1.11.0.js"></script>
	<script src="<?php echo $JsURL;?>/jquery.blockUI.js"></script>
	<script src="<?php echo $JsURL;?>/back.js"></script>
	<script src="<?php echo $JsURL;?>/tree.js"></script>

</head>
<body>	
<!-- top part begin -->
<div class="top">
	<div class="title">中国语情</div>
	<div class="admin">您好！<?php if($userrole === RSYSJ || $userrole === RADMINJ ):?>管理员:<?php endif;?><?php echo $this->usernick;?><a href="<?php echo $this->createUrl('Logout');?>">[登出]</a></div>
</div>
<!-- top part end -->