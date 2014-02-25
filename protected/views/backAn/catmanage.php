<?php
/**
*类别 view
*@package JelCore 
*@version 2014-01-18
*@var $oid  the operation type
*@var $this BackAnController 
*/
//定义资源引用路径
$ImagesURL = Yii::app()->request->baseUrl . "/images";  //图片资源
$JsURL     = Yii::app()->request->baseUrl . "/js";      //Js脚本
$CssURL    = Yii::app()->request->baseUrl . "/css";	    //css样式表
$HomeURL   = Yii::app()->homeUrl;		    			//主页URL

$yqnews = null;
$yqlfnews = null;
$yqevent = null;
$yqapp = null;
//数组简化
foreach($typetree as $key => $treenode)
{
  if($treenode['node']->typeid == YQNEWS) {$yqnews = $treenode; continue;}
  if($treenode['node']->typeid == YQLFNEWS) {$yqlfnews = $treenode; continue;}
  if($treenode['node']->typeid == YQEVENT) {$yqevent = $treenode; continue;}
  if($treenode['node']->typeid == YQAPP) {$yqapp = $treenode; continue; }
}


/********************************************************************************************************************/
?>
<!-- conent part begin -->
  <div class="content">
    <div class="main">

      <div class="layout" id="layout6">
        <h2><i></i><span>资源类别</span></h2>

<div id="TreeList">

    <div class="ParentNode hidden" id="<?php echo YQNEWS; ?>" title="loading...">
      <div class="title" ><?php echo $yqnews['node']->typename;?></div>
      <div class="editBT"></div>
      <div class="editArea"><span>[编辑]</span><span>[添加下级类别]</span></div>
    </div>
    <div class="Row" style="display:none">
      <?php
      if(count($yqnews['subnodes']) > 0)//是否有子分类
      {
        foreach($yqnews['subnodes'] as $key => $treenode)
        {
            $this->backanviewop->buidTreeLeaves($treenode);
        }
      }      
      ?>
    </div>



    <div class="ParentNode hidden" id="<?php echo YQLFNEWS; ?>" title="loading..." >
      <div class="title" ><?php echo $yqlfnews['node']->typename;?></div>
      <div class="editBT"></div>
      <div class="editArea"><span>[编辑]</span><span>[添加下级类别]</span></div>
    </div>
    <div class="Row" style="display:none">
      <?php
      if(count($yqlfnews['subnodes']) > 0)
      {
        foreach($yqlfnews['subnodes'] as $key => $treenode)
        {
            $this->backanviewop->buidTreeLeaves($treenode);
        }
      }      
      ?>
    </div>


    <div class="ParentNode hidden" id="<?php echo YQEVENT; ?>" title="loading..." >
      <div class="title" ><?php echo $yqevent['node']->typename;?></div>
      <div class="editBT"></div>
      <div class="editArea"><span>[编辑]</span><span>[添加下级类别]</span></div>
    </div>
    <div class="Row" style="display:none">
      <?php
      if(count($yqevent['subnodes']) > 0)
      {
        foreach($yqevent['subnodes'] as $key => $treenode)
        {
            $this->backanviewop->buidTreeLeaves($treenode);
        }
      }      
      ?>
    </div>



     <div class="ParentNode hidden" id="<?php echo YQAPP; ?>" title="loading..." >
      <div class="title" ><?php echo $yqapp['node']->typename;?></div>
      <div class="editBT"></div>
      <div class="editArea"><span>[编辑]</span><span>[添加下级类别]</span></div>
    </div>
    <div class="Row" style="display:none">
      <?php
      if(count($yqapp['subnodes']) > 0)
      {
        foreach($yqapp['subnodes'] as $key => $treenode)
        {
            $this->backanviewop->buidTreeLeaves($treenode);
        }
      }      
      ?>
    </div>
  </div>

</div>
</div>
</div>