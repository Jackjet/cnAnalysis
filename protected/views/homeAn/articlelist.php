<?php
/**
*@description 文章列表页view文件
*@package JelCore
*@revised 2013-12-20
*@var $this controller
*/
//定义资源引用路径
$ImagesURL = Yii::app()->request->baseUrl . "/images";  //图片资源
$JsURL     = Yii::app()->request->baseUrl . "/js";      //Js脚本
$CssURL    = Yii::app()->request->baseUrl . "/css";	    //css样式表
?>
<!-- main part html -->
<section class="main">
	<h4>
		<?php $this->homeviewop->getBreadCrumbs('type',$typeid);?>
		<select class="list-select" id="artlistsel">
			<?php
			$this->anviewop->buildTypeDrop($typetree,"","list");
			?>
		</select>
		<script type="text/javascript" src="<?php echo $JsURL;?>/views.js"></script>
	</h4>
	<div class="line">
		<ul>
		<?php 
			foreach($posts as $key => $thepost)
			{
				?>
				<li>
					<a href="<?php echo $thepost['link'];?>"><?php echo $thepost['title'];?></a>
					<span><?php echo $thepost['uploadtime'];?></span>
				</li>
				<?php
			}
			if(count($posts) === 0)
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
	<div class="paging">
		<?php
		$this->widget('CLinkPager',array(
											'header'=>'',
											'firstPageLabel'=>'首页', //指定回到首页的文本显示下面的类似，
											'lastPageLabel' =>'末页', //原本YII默认CSS设置隐藏首页和末页显示，这里已经作了修改。见/framework/web/pagers/pager.css
											'prevPageLabel' =>'上一页',
											'nextPageLabel' =>'下一页',
											'pages'=>$pages,
											'maxButtonCount'=>13,
										)
					);
		?>
	</div>
</section>