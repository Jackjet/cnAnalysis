<?php
/**
*资源管理 view
*@package JelCore 
*@version 2014-01-06
*@var $oid  the operation type
*@var $this BackAnController 
*/
//定义资源引用路径
$ImagesURL = Yii::app()->request->baseUrl . "/images";  //图片资源
$JsURL     = Yii::app()->request->baseUrl . "/js";      //Js脚本
$CssURL    = Yii::app()->request->baseUrl . "/css";	    //css样式表
$HomeURL   = Yii::app()->homeUrl;		    			//主页URL
?>
	<!-- conent part begin -->
	<div class="content">
		<div class="main">
			<div class="layout" id="layout6">
				<h2><i></i><span>爬虫管理</span></h2>
				<table>
					<thead>
						<tr>
							<th scope="col" class="manage-column">任务id</th>
							<th scope="col" class="manage-column">网站</th>
							<th scope="col" class="manage-column">入口url</th>
							<th scope="col" class="manage-column">状态</th>
							<th scope="col" class="manage-column">添加时间</th>
									
						</tr>
					</thead>
					<?php
					foreach($antmiss as $key => $onemiss)
					{
						?>
						<tr>
							<th scope="col" class="manage-column"><?php echo $onemiss->antid;?></th>
							<th scope="col" class="manage-column"><?php echo $onemiss->domain;?></th>
							<th scope="col" class="manage-column"><?php echo $onemiss->url;?></th>
							<th scope="col" class="manage-column"><?php echo $onemiss->status;?></th>
							<th scope="col" class="manage-column"><?php echo $onemiss->addtime;?></th>			
						</tr>
						<?php
					}
					?>
					<tbody>
					<?php 
						
					?>
					</tbody>
				</table>
				<div class="new-mission">
					<h3>新建任务：</h3>
					<div class="form-group">
						<label for="crawlerdomain">选择网站：</label>
						<select name="crawlerdomain" id="crawlerdomain">
							<option value="www.baidu.com">www.baidu.com</option>
							<option value="www.google.com">www.google.com</option>
							<option value="www.facebook.com">www.facebook.com</option>
						</select>
					</div>
					<div class="form-group">
						<label for="crawlerurl">输入入口URL：</label>
						<input type="text" id="crawlerurl" name="crawlerurl" />
					</div>
					<div class="form-group">
						<input type="button" id="addantmiss" value="提交" />
					</div>
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
			</div>
			
		</div>
	</div>
	<!-- content part end -->
