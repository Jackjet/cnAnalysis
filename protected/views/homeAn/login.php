<?php
/**
*@description 登录页面view文件
*@package JelCore
*@revised 2013-12-19
*/
//定义资源引用路径
$ImagesURL = Yii::app()->request->baseUrl . "/images";  //图片资源
$JsURL     = Yii::app()->request->baseUrl . "/js";      //Js脚本
$CssURL    = Yii::app()->request->baseUrl . "/css";	    //css样式表
$HomeURL   = Yii::app()->homeUrl;		    			//主页URL
?>
<!-- main part html -->
<section class="main">
	<form action="">
		<div class="login-title">
			<h3 class="login-reg">登录</h3>
		</div>
		<div id="error"></div>
		<div class="form-group">
			<label for="account"><span>*</span>用户名或邮箱：</label>
			<input type="text" name="account" id="account" />
			<div class="form-group-error" id="account-error"></div>
		</div>
		<div class="form-group">
			<label><span>*</span>密码：</label>
			<input type="password" name="password" id="password" />
			<div class="form-group-error" id="pass-error"></div>
		</div>
		<div class="form-group">
			<label><span>*</span>验证码：</label>
			<input type="text" name="vcode" id="vcode" />
			<script type="text/javascript">
			/**
			*@description 解决验证码刷新页面不更新的问题即给验证码绑定click
			*@author JelCore
			*@revised 2013-12-30
			*/
			$(document).ready(function(){
			    var img = new Image;
			    img.onload=function(){
			        $('#vcode-img').trigger('click');
			    }
			    img.src = $('#vcode-img').attr('src');
			});
			</script>
 			<?php $this->widget('CCaptcha',
			 			array(
			 				'showRefreshButton'=>false,
			 				'clickableImage'=>true,
			 				'imageOptions'=>array(
			 										'id' => 'vcode-img',
										 			'alt'=>'点击图片更换一张',
										 			'title'=>'点击图片更换一张',
										 			'style'=>'cursor:pointer'
										 			)
		 					)
	 			); 
			?>
			<div class="form-group-error" id="vcode-error"></div>
		</div>
		<div class="form-group">
			<input type="checkbox" id="checkUserName" />记住密码
			<!-- <a href="#" class="forget-pass">忘记密码？</a> -->
		</div>

		<div class="form-group">
			<input type="button" value="立即登录" id="login" />
		</div>
	</form>
</section>
<!-- aside part html -->