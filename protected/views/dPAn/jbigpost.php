<?php
/**
*@description 文章详细内容JSON视图
*@author JelCore
*@revised 2013-12-03
*@var $this Controller 
*/
?><?php
$thePost 	  = array();//待处理文章
$theFileArray = array();//待处理文件数组
$theJson  	  = "";		//生成的JSON

//var_dump($this->TheBigPost);exit();
//使用urlencode和urldecode处理中文乱码
if(empty($this->TheBigPost))//判断是否为空
{
	foreach($this->TheBigPost->FileList as $key => $onefile)
	{

		 $theFileArray[] = array(
									'filename' => urlencode($onefile['name']),
									'link'     => urlencode($onefile['link']),
									'type'     => urlencode($onefile['type'])
							 	);
	}
	$thePost = array(
						'id'       => urlencode($this->TheBigPost->ID),
						'title'    => urlencode($this->TheBigPost->Title),
						'date'     => urlencode($this->TheBigPost->Date),
						'from'     => urlencode($this->TheBigPost->From),
						'author'   => urlencode($this->TheBigPost->Author),
						'point'    => urlencode($this->TheBigPost->Point),
						'context'  => urlencode($this->TheBigPost->Context),
						'fromlink' => urlencode($this->TheBigPost->FromLink),
						'filelist' => $theFileArray
					);
	
	$theJson = json_encode($thePost);
	echo urldecode($theJson);
}
else
{
	echo "数据为空！！";
}
?>