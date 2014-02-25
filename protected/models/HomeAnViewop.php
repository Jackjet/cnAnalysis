<?php
/**
*文章阅读视图页面操作模型
*@author JelCore
*@version 2014-02-13
*/
?><?php
class HomeAnViewop
{

	private $artiop  = null;//文章操作模型
	private $typeop  = null;//文章类别操作模型
	private $HomeURL = "";

	private $breadcrumbsprefix  = "";//导航的箭头

	public function __construct()
	{
		$this->HomeURL = Yii::app()->homeUrl;
		$this->typeop  = new ArtTypeop();
		$this->artiop  = new Articleop();
		$this->breadcrumbsprefix = Yii::app()->params['BreadCrumbsNavPrefix']; 
	}

	/**
	*打印面包屑导航
	*@param String $page 面包屑类型:有 post 和 type 两种;String $id 相应的文章或者分类ID 
	*/
	public function getBreadCrumbs($page,$id)
	{
		$nav = " <a href=\"{$this->HomeURL}\">首页</a> " . $this->breadcrumbsprefix;
		$link = "";
		if($page == "type")
		{
			$ppath = $this->typeop->GetPPath($id);
			if($ppath == null) {echo "<h1>类别不存在！！可能已经被删除！</h1>"; exit();}
			foreach($ppath as $key => $onetype)
			{
				$link .= $this->getTypeUrl($onetype) . $this->breadcrumbsprefix;
			}
			echo $nav . $link;
		}
		if($page == "post")
		{
			$arti = $this->artiop->GetArticle($id);
			if($arti == null) echo "<h1>文章不存在！可能已经被删除！</h1>";

			$ppath = $this->typeop->GetPPath($arti->typeid);
			if($ppath == null) {echo "<h1>类别不存在！！可能已经被删除！</h1>"; exit();}
			foreach($ppath as $key => $onetype)
			{
				$link .= $this->getTypeUrl($onetype) . $this->breadcrumbsprefix;
			}
			echo $nav . $link ."正文 >> " . $this->getArtUrl($arti) . " >> ";
		}
	}

	/**
	*获取类别URL
	*@param AR $type 类别
	*@return String $typeURL HTML的URL文本 
	*/
	private function getTypeUrl($type)
	{
		$typeURL = " <a href=\"" . 
		Yii::app()->createUrl('HomeAn/DoMore',array('atid'=>$type->typeid)) . 
		"\">{$type->typename}</a> ";
		return $typeURL;
	}

	/**
	*获取文章URL
	*@param  AR $arti 文章
	*@return String $artURL HTML的URL文本 
	*/
	private function getArtUrl($arti)
	{
		$newtitle = mb_strimwidth($arti->title, 0, Yii::app()->params['PTAbsCountNav'] * 2,'...',"utf-8");
		$artURL = " <a href=\"" . 
		Yii::app()->createUrl('HomeAn/ReadPost',array('pid'=>$arti->aid)) . 
		"\">$newtitle</a> ";
		return $artURL;
	}
}
?>