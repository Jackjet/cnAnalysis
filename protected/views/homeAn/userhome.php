<?php
/**
 *@description 用户主页view文件
 *@package JelCore
 *@revised 2014-01-06
 */
//定义资源引用路径
$ImagesURL = Yii::app()->request->baseUrl . "/images";  //图片资源
$JsURL     = Yii::app()->request->baseUrl . "/js";      //Js脚本
$CssURL    = Yii::app()->request->baseUrl . "/css";	    //css样式表
$HomeURL   = Yii::app()->homeUrl;		    			//主页URL

$uid = "";
$uid = Yii::app()->session["uid"];
if($uid === null || $uid === "")
{	
	$cookies = Yii::app()->request->getCookies();
	$uid = $cookies['uid'];
}
if($uid === null || $uid ==="")
{
	echo "您尚未登录"  ;
}

if ($uid !== null || $uid !==""){
    $info = (new User())->getInfo(array("uid"=>$uid));


    echo <<<userhome
	<section class="content person">
		<div class="tag-menu">
		    <ul class="menu">
		        <li><a href="#" class="current">个人信息</a></li>
		        <li><a href="#">修改信息</a></li>
		        <li><a href="#">上传资源</a></li>
		    </ul>
		</div>
		<div class="message">
			<div class="layout">
				<div class="form-group">
					<label for="username">用户名：</label>
userhome;
    echo $info["username"];
    echo <<<userhome
				</div>
				<div class="form-group">
					<label>邮箱：</label>
userhome;
    echo $info["email"];
    echo <<<userhome
				</div>
				<div class="form-group">
					<label>姓名：</label>
userhome;
    echo $info["name"];
    echo <<<userhome
				</div>
				<div class="form-group">
					<label>联系电话：</label>
userhome;
    echo $info["phone"];
    echo <<<userhome
				</div>
				<div class="form-group">
					<label>地址：</label>
userhome;
    echo $info["address"];
    echo <<<userhome
				</div>
				<p id="layout-infor">为了保证您更好的使用本网站<br />请您完善您的个人资料</p>
			</div>
			<div class="layout">
				<div class="form-group">
					<label>姓名：</label>
					<input type="text" name="name" id="name" value="
userhome;
    echo $info["name"];
    echo <<<userhome
"/>
					<div class="form-group-error" id="name-error"></div>
				</div>
				<div class="form-group">
					<label>联系电话：</label>
					<input type="text" name="phone" id="phone" value="
userhome;
    echo $info["phone"];
    echo <<<userhome
"/>
					<div class="form-group-error" id="phone-error"></div>
				</div>
				<div class="form-group">
					<label>地址：</label>
					<input type="text" name="address" id="address" value="
userhome;
    echo $info["address"];
    echo <<<userhome
"/>
					<div class="form-group-error" id="address-error"></div>
				</div>
				<div class="form-group">
					<label>原始密码：</label>
					<input type="password" name="oldpassword" id="oldpassword" />
					<div class="form-group-error" id="oldpass-error"></div>
				</div>
				<div class="form-group">
					<label>新密码：</label>
					<input type="password" name="password" id="password" />
					<div class="form-group-error" id="pass-error"></div>
				</div>
				<div class="form-group">
					<label>重复密码：</label>
					<input type="password" name="repassword" id="repassword" />
					<div class="form-group-error" id="repass-error"></div>
				</div>
				<div class="form-group">
					<input type="button" value="立即修改" id="change" />
				</div>
			</div>
			<div class="layout">
				<input type="file" />
			</div>
		</div>
	</section>
userhome;

}else echo "您尚未登录"  ;
