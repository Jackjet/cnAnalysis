<?php
/**
*后台页面左边侧边栏
*@package JelCore
*@var $this BackAnController 
*/
//定义资源引用路径
$ImagesURL = Yii::app()->request->baseUrl . "/images";  //图片资源
$JsURL     = Yii::app()->request->baseUrl . "/js";      //Js脚本
$CssURL    = Yii::app()->request->baseUrl . "/css";	    //css样式表
$HomeURL   = Yii::app()->homeUrl;		    			//主页URL
$userrole = $this->userrole;
?><!-- aside part begin -->
<div class="aside">
	<h1><a href="<?php echo $HomeURL;?>">网站主页</a></h1>
	<dl>
	<?php if($userrole === RSYSJ || $userrole === RADMINJ)://权限控制显示?>
		<dt class="resource"><a href="javascript:void(0)">资源审核</a></dt>
		<dd>
			<ul>
				<li><a href="<?php echo $this->TheVerifyedArtURL;?>">已审核</a></li>
				<li><a href="<?php echo $this->TheNonVeriArtURL;?>">未审核</a></li>
			</ul>
		</dd>
	<?php endif;?>
	<?php if($userrole === RNORMALJ)://普通用户个人的文章列表?>
		<dt class="resource"><a href="javascript:void(0)">资源审核</a></dt>
		<dd>
			<ul>
				<li><a href="<?php echo $this->TheVerifyedArtURLMy;?>">已审核</a></li>
				<li><a href="<?php echo $this->TheNonVeriArtURLMy;?>">未审核</a></li>
			</ul>
		</dd>
	<?php endif;?>


	<?php if($userrole === RSYSJ || $userrole === RADMINJ )://系统管理员、资源管理员可见?>
		<dt class="user"><a href="javascript:void(0)">用户管理</a></dt>
		<dd>
			<ul>
				<li><a href="<?php echo $this->TheEditUsersURL;?>">编辑用户</a></li>
				<li><a href="<?php echo $this->TheAddUsersURL;?>">添加用户</a></li>
				<li><a href="<?php echo $this->TheDeleUsersURL;?>">删除用户</a></li>
			</ul>
		</dd>
	<?php endif;?>



	<?php if($userrole === RSYSJ || $userrole === RADMINJ || $userrole === RNORMALJ )://系统管理员、资源管理员和普通用户都可见?>
		<dt class="publish"><a href="javascript:void(0)">资源管理</a></dt>
		<dd>
			<ul>
				<li><a href="<?php echo $this->TheUpFilesURL;?>">上传文件</a></li>
				<li><a href="<?php echo $this->ThePubArtURL;?>">发布文章</a></li>
			</ul>
		</dd>
	<?php endif;?>


	<?php if($userrole === RSYSJ)://只有系统管理员才可见的操作?>
		<dt class="authority"><a href="javascript:void(0)">系统管理</a></dt>
		<dd>
			<ul>
				<li><a href="<?php echo $this->TheAuthManURL;?>">权限分配</a></li>
				<li><a href="<?php echo $this->TheCatManURL;?>">资源类别</a></li>
				<li><a href="<?php echo $this->TheCrawlerURL;?>">爬虫管理</a></li>
			</ul>
		</dd>
	<?php endif;?>
	</dl>
</div>
<!-- aside part end -->