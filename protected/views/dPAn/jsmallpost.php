<?php
/**
*@description 文章简略信息列表JSON视图
*@author JelCore
*@revised 2013-12-02
*@var $this Controller 
*/
?><?php
$theArray = array();//待处理数组
$theJson  = "";//生成的JSON

//使用urlencode和urldecode处理中文乱码
if(empty($this->SmallPosts))//判断是否为空
{
	foreach($this->SmallPosts as $key => $onepost)
	{

		 $theArray[urlencode($onepost->Type)][] = array(
														'title' => urlencode($onepost->Title),
														'date'  => urlencode($onepost->Date),
														'link'  => urlencode($onepost->Link)
													 );
	}
	$theJson = json_encode($theArray);
	echo urldecode($theJson);
}
else
{
}
?>