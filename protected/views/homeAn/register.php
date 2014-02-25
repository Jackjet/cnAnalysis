<?php
/**
*@description 注册页面view文件
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
        <div id="error"></div>
		<div class="login-title">
			<h3 class="login-reg">注册</h3>
		</div>
		<div class="form-group">
			<label for="username"><span>*</span>用户名：</label>
			<input type="text" name="username" id="username" />
			<div class="form-group-error" id="username-error"></div>
		</div>
		<div class="form-group">
			<label><span>*</span>邮箱：</label>
			<input type="text" name="email" id="email" />
			<div class="form-group-error" id="email-error"></div>
		</div>
		<div class="form-group">
			<label><span>*</span>密码：</label>
			<input type="password" name="password" id="password" />
			<div class="form-group-error" id="pass-error"></div>
		</div>
		<div class="form-group">
			<label><span>*</span>重复密码：</label>
			<input type="password" name="repassword" id="repassword" />
			<div class="form-group-error" id="repass-error"></div>
		</div>
		<div class="form-group">
			<label>姓名：</label>
			<input type="text" name="name" id="name" />
			<div class="form-group-error" id="name-error"></div>
		</div>
		<div class="form-group">
			<label>联系电话：</label>
			<input type="text" name="phone" id="phone" />
			<div class="form-group-error" id="phone-error"></div>
		</div>
		<div class="form-group">
			<label>地址：</label>
			<input type="text" name="address" id="address" />
			<div class="form-group-error" id="address-error"></div>
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
			<input type="button" value="立即注册" id="register" />
		</div>
	</form>
</section>