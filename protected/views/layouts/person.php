<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>动态预料库</title>
	<meta name="keywords" content=""/>
	<meta  name="description" content="" />
	<meta name="author" content="新思路团队,网酷工作室,c1avie" />
	<link rel="shortcut icon" href="images/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />

	<script type="text/javascript" src="js/html5.js"></script>
	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
</head>
<body>
<!-- top part html -->
<div class="top">
	<div class="top-inner">
		<span class="fl">
			<a href="#">首页</a>
		</span>
		<span class="fr">
			<a href="login.html">登录</a>
			<a href="register.html">注册</a>
		</span>
	</div>
</div>
<div class="wrapper">
	<!-- header part html -->
	<header>
		<div class="logo">
			<a href="#">
				<img src="images/logo.png" width="186" height="78" title="动态语料库" alt="动态语料库" />
			</a>
		</div>
		<h1>动态语料库</h1>
		<div class="search">
			<form action="">
				<input type="text" class="search-text" />
				<input type="button" class="search-button" />
			</form>
		</div>
	</header>
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
					c1avie
				</div>
				<div class="form-group">
					<label>邮箱：</label>
					429465251@qq.com
				</div>
				<div class="form-group">
					<label>姓名：</label>
					牛赞
				</div>
				<div class="form-group">
					<label>联系电话：</label>
					13260585792
				</div>
				<div class="form-group">
					<label>地址：</label>
					中南民族大学北区学生公寓
				</div>
				<p id="layout-infor">为了保证您更好的使用本网站<br />请您完善您的个人资料</p>
			</div>
			<div class="layout">
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
					<label>原始密码：</label>
					<input type="text" name="oldpassword" id="oldpassword" />
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
	<!-- footer part html -->
	<footer>
		<div class="link">
			<ul>
				<li>友情链接：</li>
				<li><a href="#">中南民族大学</a></li>
				<li><a href="#">中南民族大学</a></li>
				<li><a href="#">中南民族大学</a></li>
				<li><a href="#">中南民族大学</a></li>
				<li><a href="#">中南民族大学</a></li>
			</ul>
		</div>
		<p class="copyright">版权所有 &copy; 2007-2011 鄂ICP备05003346号 共青团中南民族大学委员会 技术支持：<a href="http://www.new-thread.com/">网酷工作室</a></p>
		<p class="address">地址：武汉市洪山区民院路708号 邮编：430074</p>
	</footer>
</div>

<script src="js/validator.js"></script>

</body>
</html>