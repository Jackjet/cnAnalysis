<?php
/**
*发布内容 view
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
?><?php if($oid == 1):?>
<!-- conent part begin -->
	<div class="content">
		<div class="main">

			<div class="layout" id="layout6">
				<h2><i></i><span>上传文件</span></h2>
					<?php echo CHtml::form($this->createUrl('NewFile'),'post',array('enctype'=>'multipart/form-data')); ?>
						<div class="article-title">
							<div class="form-group">
								<label for="title"><span>*</span>文章标题：</label>
								<input type="text" name="title" size="30" value="" id="title" />
							</div>
							<div class="form-group">
								<label for="from"><span>*</span>来源：</label><input type="text" id="from" name="from" />
							</div>
							<div class="form-group">
								<label for="fromaddress"><span>*</span>来源地址：</label><input type="text" id="fromlink" name="fromlink" />
							</div>
							<div class="form-group">
								<label for="author"><span>*</span>原作者：</label><input type="text" id="author" name="author" />
							</div>
							<div class="form-group">
								<label for="editor"><span>*</span>编辑者：</label><input type="text" id="editor" name="editor" />
							</div>
							<div class="form-group">
								<label for="yqtype">类型：</label>
								<select name="typeid" id="typeid">
								<?php
								$this->anviewop->buildTypeDrop($typetree);
								?>
								</select>
								<script type="text/javascript" src="<?php echo $JsURL;?>/views.js"></script>
							</div>
							<div class="form-group">
								<label for="upfile">上传文件：</label>
								<input type="file" name="upfile" id="upfile" />
							</div>
							<div class="form-group">
							
								<input type="submit" value="立即发布" />

							</div>
						</div>
					<?php echo CHtml::endForm(); ?>
			</div>

		</div>
	</div>
	<!-- content part end -->
<?php endif;?>
<?php if($oid == 2):?>
	<!-- conent part begin -->
	<div class="content">
		<div class="main">

			<div class="layout" id="layout7">
				<h2><i></i><span>发布文章</span></h2>
				<form name="example" method="post" action="<?php echo $this->createUrl('NewArticle');?>">
					<div class="article-title">
						<div class="form-group">
							<label for="title"><span>*</span>文章标题：</label>
							<input type="text" name="title" size="30" id="title" />
						</div>
						<div class="form-group">
							<label for="from"><span>*</span>来源：</label>
							<input type="text" id="from" name="from" />
						</div>
						<div class="form-group">
							<label for="fromaddress"><span>*</span>来源地址：</label>
							<input type="text" id="fromlink" name="fromlink" />
						</div>
						<div class="form-group">
							<label for="author"><span>*</span>原作者：</label>
							<input type="text" id="author" name="author" />
						</div>
						<div class="form-group">
							<label for="editor"><span>*</span>编辑者：</label>
							<input type="text" id="editor" name="editor" />
						</div>
						<div class="form-group">
							<label for="yqtype">类型：</label>
							<select name="typeid" id="typeid">
							<?php
							$this->anviewop->buildTypeDrop($typetree);
							?>
							</select>
							<script type="text/javascript" src="<?php echo $JsURL;?>/views.js"></script>
						</div>
					</div>
					<textarea id="editor_id" name="content" class="textarea-editor">
					</textarea>
					<br />
					<div class="publish-button">
						<input type="submit" name="publish" id="publish" class="button-primary" value="立即发布" accesskey="p">
					</div>
				</form>
			</div>

		</div>
	</div>
	<!-- content part end -->
<?php endif;?>