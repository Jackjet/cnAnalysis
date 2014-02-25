<?php
/**
*@description 各页面公用页尾布局文件
*@package JelCore
*revised 2013-12-18
*
*/
//定义资源引用路径
$ImagesURL =  Yii::app()->request->baseUrl . "/images/";  //图片资源
$JsURL     =  Yii::app()->request->baseUrl . "/js/";      //Js脚本
$CssURL    =  Yii::app()->request->baseUrl . "/css/";	  //css样式表
?>
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
</body>
</html>