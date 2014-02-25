<?php
/**
*@description 各页面公用侧边栏文件
*@package JelCore
*@revised 2013-12-19
*/
//定义资源引用路径
$ImagesURL = Yii::app()->request->baseUrl . "/images";  //图片资源
$JsURL     = Yii::app()->request->baseUrl . "/js";      //Js脚本
$CssURL    = Yii::app()->request->baseUrl . "/css";	    //css样式表
$HomeURL   = Yii::app()->homeUrl;		    			//主页URL
?>
<!-- aside part html -->
		<aside>
			<div class="advanced">
				<h2>高级搜索</h2>
				<form>
					<p>标题：<input type="text" name="title" id="high-search-title" /></p>
					<p>内容：<input type="text" name="content" id="high-search-content" /></p>
					<p>
						分类：
						<select name="yq" id="high-search-cat">
							<option value="*">不限</option>
							<?php
								$this->anviewop->buildTypeDrop($this->typetree);
							?>
						</select>
						<script type="text/javascript" src="<?php echo $JsURL;?>/views.js"></script>
					</p>
					<p>来源：<input type="text" name="title" id="high-search-from" /></p>
					<!-- <p>日期：<input type="text" name="title" /></p> -->
					<p><a href="javascript:void(0);" class="advanced-search" id="high-search">搜索</a></p>
				</form>
			</div>
			<div class="pop">
				<h2>热门文章</h2>
				<ul>
					<?php
					foreach($this->hotsearchkw as $key => $onekw)
					{
						?>
						<li>
							<a title="点击:<?php echo $onekw->point;?>" href="<?php echo $onekw->link;?>">
							<?php echo mb_strimwidth($onekw->title, 0, Yii::app()->params['HotPostTitleCount'] * 2,'...',"utf-8");?>
							</a>
						</li>
						<?php
					}
					?>
				</ul>
			</div>
		</aside>