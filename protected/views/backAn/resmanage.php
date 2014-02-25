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
?><?php 
if($oid == 1)//未审核文章列表
{                              

?>
	<!-- conent part begin -->
	<div class="content">
		<div class="main">
			<div class="layout" id="layout1">
				<h2><i></i><span>未审核文章<?php if(isset($successInfo))echo "(".$successInfo.")";?></span></h2>
				<table>
					<thead>
						<tr>
							<th scope="col" class="manage-column">标题</th>
							<th scope="col" class="manage-column">原作者</th>
							<th scope="col" class="manage-column">分类目录</th>
							<th scope="col" class="manage-column">日期</th>
							<th scope="col" class="manage-column">编辑者</th>
							<?php if($userrole === RSYSJ || $userrole === RADMINJ):?><th scope="col" class="manage-column">操作</th><?php endif;?>					
						</tr>
					</thead>
					<tbody>
					<?php 
						foreach($posts as $key => $thepost)
						{
							?>
							<tr>
								<td><a href="<?php echo $this->createUrl('ResManage',array('tid'=>'DoEdit','aid' => $thepost['aid'])); ?>"><?php echo $thepost['title'];?></a></td>
								<td><?php echo $thepost['author'];?></td>
								<td><?php echo $thepost['type'];?></td>
								<td><?php echo $thepost['uploadtime'];?></td>
								<td><?php echo $thepost['editor'];?></td>
								<?php if($userrole === RSYSJ || $userrole === RADMINJ):?>
									<td>
										<a href="javascript:doPass(<?php echo $thepost['aid'];?>);" class="examine-no" id="dopass<?php echo $thepost['aid'];?>">[审核通过]</a>
										<a href="javascript:doDelete(<?php echo $thepost['aid'];?>);" class="examine-no">[删除]</a>
									</td>
								<?php endif;?>
							</tr>
							<?php
						}
						if(count($posts) === 0)
						{
							?>
							<tr>
								<td>您还没有发布任何文章，请在右侧菜单栏选择“发布文章”操作发布您自己的文章^_^</td>
							</tr>
							<?php
						}
					?>
					</tbody>
				</table>
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
	<?php 
}
?>
<?php if($oid == 2)//已经审核文章列表 
{                                 
	?>
	<!-- conent part begin -->
	<div class="content">
		<div class="main">
			<div class="layout" id="layout1">
				<h2><i></i><span>已审核文章<?php if(isset($successInfo))echo "(".$successInfo.")";?></span></h2>
				<table>
					<thead>
						<tr>
							<th scope="col" class="manage-column">标题</th>
							<th scope="col" class="manage-column">原作者</th>
							<th scope="col" class="manage-column">分类目录</th>
							<th scope="col" class="manage-column">日期</th>
							<th scope="col" class="manage-column">编辑者</th>
							<?php if($userrole === RSYSJ || $userrole === RADMINJ):?><th scope="col" class="manage-column">操作</th><?php endif;?>	
						</tr>
					</thead>
					<tbody>
					<?php 
						foreach($posts as $key => $thepost)
						{
							?>
							<tr>
								<td><a href="<?php echo $this->createUrl('ResManage',array('tid'=>'DoEdit','aid' => $thepost['aid'])); ?>"><?php echo $thepost['title'];?></a></td>
								<td><?php echo $thepost['author'];?></td>
								<td><?php echo $thepost['type'];?></td>
								<td><?php echo $thepost['uploadtime'];?></td>
								<td><?php echo $thepost['editor'];?></td>
								<?php if($userrole === RSYSJ || $userrole === RADMINJ):?>
									<td>
										<a href="javascript:doNoPass(<?php echo $thepost['aid'];?>);" class="examine-no" id="donopass<?php echo $thepost['aid'];?>">[审核不通过]</a>
										<a href="javascript:doDelete(<?php echo $thepost['aid'];?>);" class="examine-no">[删除]</a>
									</td>
								<?php endif;?>
							</tr>
							<?php
						}
						if(count($posts) === 0)
						{
							?>
							<tr>
								<td>您还没有发布任何文章或者您的文章未经过审核，请在右侧菜单栏，
								选择“发布文章”操作，发布您自己的文章或者联系管理员审核您的文章。^_^</td>
							</tr>
							<?php
						}
					?>
					</tbody>
				</table>
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
	<?php 
}
?>
<?php if($oid == 3)//详细文章编辑（已审核 ）
{                                 
	?>
	<!-- conent part begin -->
	<div class="content">
		<div class="main">

			<div class="layout" id="layout7">
				<h2><i></i><span>已审核文章</span></h2>
					<div class="article-title">
						<div class="form-group">
							<label for="title"><span>*</span>文章标题：</label>
							<input type="text" name="title" size="30" value="<?php echo $thepost['title'];?>" id="title"  />
						</div>
						<div class="form-group">
							<label for="from"><span>*</span>来源：</label><input type="text" id="from" value="<?php echo $thepost['from'];?>" name="from" />
						</div>
						<div class="form-group">
							<label for="fromaddress"><span>*</span>来源地址：</label><input type="text" id="fromlink" name="fromlink" value="<?php echo $thepost['fromlink'];?>" />
						</div>
						<div class="form-group">
							<label for="author"><span>*</span>原作者：</label><input type="text" id="author" name="author" value="<?php echo $thepost['author'];?>" />
						</div>
						<div class="form-group">
							<label for="editor"><span>*</span>编辑者：</label><input type="text" id="editor" name="editor" value="<?php echo $thepost['editor'];?>" readonly="true" />
						</div>
						<div class="form-group">
							<label for="yqtype">类别：</label>
							<select name="typeid" id="typeid">
							<?php
							$this->anviewop->buildTypeDrop($typetree,$thepost['type']);
							?>
							</select>
							<script type="text/javascript" src="<?php echo $JsURL;?>/views.js"></script>
						</div>
					</div>
					<input type="hidden" id="aid" value="<?php echo $thepost['aid'];?>">
					<textarea id="editor_id" name="content" class="textarea-editor">
					<?php echo $thepost['content']; ?>
					</textarea>
					<div class="publish-button">
						<input type="button" name="publish" id="publishnew" class="button-primary" value="立即更新" accesskey="p">
						<?php if($userrole === RSYSJ || $userrole === RADMINJ):?>
							<input id="donopass<?php echo $thepost['aid'];?>" type="button" name="judge-no" id="judge-no" class="button-primary" value="设为审核不通过" accesskey="p" onclick="javascript:doNoPass(<?php echo $thepost['aid'];?>);">
							<input type="button" name="judge-no" id="judge-no" class="button-primary" value="删除" accesskey="p" onclick="javascript:doDelete(<?php echo $thepost['aid'];?>);">
						<?php endif;?>
					</div>
			</div>

		</div>
	</div>
	<!-- content part end -->
	<?php 
}
?>
<?php if($oid == 4)//详细文章编辑 （未审核）
{                                 
	?>
	<!-- conent part begin -->
	<div class="content">
		<div class="main">

			<div class="layout" id="layout7">
				<h2><i></i><span>未审核文章</span></h2>
					<div class="article-title">
						<div class="form-group">
							<label for="title"><span>*</span>文章标题：</label>
							<input type="text" name="title" size="30" value="<?php echo $thepost['title'];?>" id="title" autocomplete="off" />
						</div>
						<div class="form-group">
							<label for="from"><span>*</span>来源：</label><input type="text" id="from" value="<?php echo $thepost['from'];?>" name="from" />
						</div>
						<div class="form-group">
							<label for="fromaddress"><span>*</span>来源地址：</label><input type="text" id="fromlink" name="fromlink" value="<?php echo $thepost['fromlink'];?>" />
						</div>
						<div class="form-group">
							<label for="author"><span>*</span>原作者：</label><input type="text" id="author" name="author" value="<?php echo $thepost['author'];?>" />
						</div>
						<div class="form-group">
							<label for="editor"><span>*</span>编辑者：</label><input type="text" id="editor" name="editor" value="<?php echo $thepost['editor'];?>" readonly="true"  />
						</div>
						<div class="form-group">
							<label for="yqtype">类别：</label>
							<select name="typeid" id="typeid">
							<?php
							$this->anviewop->buildTypeDrop($typetree,$thepost['type']);
							?>
							</select>
							<script type="text/javascript" src="<?php echo $JsURL;?>/views.js"></script>
						</div>
					</div>
					<input type="hidden" id="aid" value="<?php echo $thepost['aid'];?>">
					<textarea id="editor_id" name="content" class="textarea-editor">
					<?php echo $thepost['content']; ?>
					</textarea>
					<div class="publish-button">
						<input type="button" name="publish" id="publishnew" class="button-primary" value="立即更新" accesskey="p">
						<?php if($userrole === RSYSJ || $userrole === RADMINJ):?>
							<input  id="dopass<?php echo $thepost['aid'];?>" type="button" name="judge-no"  class="button-primary" value="审核通过" accesskey="p" onclick="javascript:doPass(<?php echo $thepost['aid'];?>);">
							<input type="button" name="judge-no" id="judge-no" class="button-primary" value="删除" accesskey="p" onclick="javascript:doDelete(<?php echo $thepost['aid'];?>);">
						<?php endif;?>
					</div>
			</div>

		</div>
	</div>
	<!-- content part end -->
	<?php 
}
?>