<?php
/**
*@description 主页view文件
*@package JelCore
*@revised 2013-12-19
*/
//定义资源引用路径
$ImagesURL = Yii::app()->request->baseUrl . "/images";  //图片资源
$JsURL     = Yii::app()->request->baseUrl . "/js";      //Js脚本
$CssURL    = Yii::app()->request->baseUrl . "/css";	    //css样式表
$HomeURL   = Yii::app()->homeUrl;		    			//主页URL
$Morelink1 = $this->TheMoreLink1;
$Morelink2 = $this->TheMoreLink2;
$Morelink3 = $this->TheMoreLink3;
$Morelink4 = $this->TheMoreLink4;
?>

<!-- main part html -->
<section class="main">
<div class="line">
	<h2 class="home">语情动态<span><a href="<?php echo $Morelink1;?>">更多</a></span></h2>
	<ul>
		<?php
		foreach($homeposts[YQNEWS] as $key => $onepost)
		{
			?>
				<li>
					<a href="<?php echo $onepost->link;?>"><?php echo mb_strimwidth($onepost->title,0, Yii::app()->params['PTAbsCountPag'],'...','utf-8');?></a>
					<span><?php echo $onepost->uploadtime;?></span>
				</li>
			<?php
		}
		if(count($homeposts[YQNEWS]) === 0)
			{
				?>
				<li>
					<span>没有更多数据...</span>
				</li>
				<?php
			} 
		?>
		
	</ul>
</div>
<div class="line">
	<h2 class="home">语言生活动态<span><a href="<?php echo $Morelink2;?>">更多</a></span></h2>
	<ul>
		<?php
		foreach($homeposts[YQLFNEWS] as $key => $onepost)
		{
			?>
				<li>
					<a href="<?php echo $onepost->link;?>"><?php echo mb_strimwidth($onepost->title,0, Yii::app()->params['PTAbsCountPag'],'...','utf-8');?></a>
					<span><?php echo $onepost->uploadtime;?></span>
				</li>
			<?php
		}
		 	if(count($homeposts[YQLFNEWS]) === 0)
			{
				?>
				<li>
					<span>没有更多数据...</span>
				</li>
				<?php
			}
		?>
	</ul>
</div>
<div class="line">
	<h2 class="home">语言事件与活动<span><a href="<?php echo $Morelink3;?>">更多</a></span></h2>
	<ul>
		<?php
		foreach($homeposts[YQEVENT] as $key => $onepost)
		{
			?>
				<li>
					<a href="<?php echo $onepost->link;?>"><?php echo mb_strimwidth($onepost->title,0, Yii::app()->params['PTAbsCountPag'],'...','utf-8');?></a>
					<span><?php echo $onepost->uploadtime;?></span>
				</li>
			<?php
		}
			if(count($homeposts[YQEVENT]) === 0)
			{
				?>
				<li>
					<span>没有更多数据...</span>
				</li>
				<?php
			} 
		?>
	</ul>
</div>
<div class="line">
	<h2 class="home">语言应用<span><a href="<?php echo $Morelink4;?>">更多</a></span></h2>
	<ul>
		<?php
		foreach($homeposts[YQAPP] as $key => $onepost)
		{
			?>
				<li>
					<a href="<?php echo $onepost->link;?>"><?php echo mb_strimwidth($onepost->title,0, Yii::app()->params['PTAbsCountPag'],'...','utf-8');?></a>
					<span><?php echo $onepost->uploadtime;?></span>
				</li>
			<?php
		}
			if(count($homeposts[YQAPP]) === 0)
			{
				?>
				<li>
					<span>没有更多数据...</span>
				</li>
				<?php
			} 
		?>
	</ul>
</div>
</section>