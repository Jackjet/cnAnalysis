<?php
/**
*后台视图渲染模型
*@author JelCore
*@version 2014-02-22
*
*/
?><?php
class BackAnViewop
{
	public function __construct()
	{

	}

	/**
	*分类维护页面类别树的递归构造(辅助函数)
	*@param mixed Array $treenode 树的所有节点
	*
	*/
	public function buidTreeLeaves($treenode)
	{
	    if(count($treenode['subnodes']) == 0)
	    {
	        ?>
	        <div class="ChildNode" id="<?php echo $treenode['node']->typeid;?>" title="loading...">
	            <div class="title"><?php echo $treenode['node']->typename;?></div>
	            <div class="editBT"></div>
	            <div class="editArea"><span>[编辑]</span><span>[添加下级类别]</span><span>[添加同级类别]</span><span>[删除]</span></div>
	        </div>
	        <?php
	    }
	    else
	    {
	        ?>
	        <div class="ParentNode" id="<?php echo $treenode['node']->typeid;?>" title="loading...">
	            <div class="title"><?php echo $treenode['node']->typename;?></div>
	            <div class="editBT"></div>
	            <div class="editArea"><span>[编辑]</span><span>[添加下级类别]</span><span>[添加同级类别]</span><span>[删除]</span></div>
	        </div>
	        <div class="Row" style="display:none">
	        <?php
	        foreach($treenode['subnodes'] as $key => $onenode)
	        {
	            $this->buidTreeLeaves($onenode);
	        }
	        ?>
	        </div>
	        <?php
	    }
	}
}
?>