<?php
/**
*@description 文章阅读view文件
*@package JelCore
*@revised 2013-12-20
*/
//定义资源引用路径
$ImagesURL = Yii::app()->request->baseUrl . "/images";  //图片资源
$JsURL     = Yii::app()->request->baseUrl . "/js";      //Js脚本
$CssURL    = Yii::app()->request->baseUrl . "/css";	    //css样式表		   
?>
<!-- main part html -->
<section class="main">
	<h4><?php $this->homeviewop->getBreadCrumbs('post',$thepost->aid);?></h4>
	<article>
		<h1><?php echo $thepost->title;?></h1>
		<h3>
			<span>来源：<a href="<?php echo $thepost->fromlink;?>"><?php echo $thepost->from;?></a></span>
			<span>上传者：<?php echo $username;?></span>
			<span>发布日期：<?php echo $thepost->uploadtime;?></span>
			<span>点击：<?php echo $thepost->point;?></span>
		</h3>
		<div>
			<?php echo $thepost->content;?>
		</div>
	</article>
</section>