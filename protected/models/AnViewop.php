<?php
/**
*公共视图渲染模型类
*@author JelCore
*@version 2014-02-22
*/
?><?php
class AnViewop
{

	private $typedropdelimiter = '&nbsp;';//类别下拉框的缩进符，HTML实体字符

	public function __construct()
	{

	} 

	/**
	*类别下拉框节点渲染
	*@param mixed array $treenode 所有类别节点;String $delimiter 分隔符;String $chosentid 当前选中的类别ID;String $optype 使用配置
	*@return echo
	*/
	private function buildTypeDropNode($treenode,$delimiter,$chosentid="",$optype="single")
	{
		if($optype == "single")//单独文章页面
		{
			if(count($treenode['subnodes']) == 0)//子节点向后缩进两个空格
		    {
		    	$delimiter = $delimiter.$this->typedropdelimiter;
		        ?>
		        <option value="<?php echo $treenode['node']->typeid;?>" 
		        <?php if($chosentid == $treenode['node']->typeid)
				{echo "selected=\"selected\"";}?>
				>
				<?php echo $delimiter . $treenode['node']->typename;?>
				</option>
		        <?php
		    }
		    else
		    {	//父节点
		    	$delimiter = $delimiter.$this->typedropdelimiter;
		        ?>
				<option value="<?php echo $treenode['node']->typeid;?>" 
		        <?php if($chosentid == $treenode['node']->typeid)
				{echo "selected=\"selected\"";}?>
				>
				<?php echo $delimiter . $treenode['node']->typename;?>
				</option>
		        <?php
		        foreach($treenode['subnodes'] as $key => $onenode)
		        {
		            $this->buildTypeDropNode($onenode,$delimiter,$chosentid);
		        }
		        ?>
		        <?php
		    }
		}
		if($optype == "list")//列表页面
		{
			if(count($treenode['subnodes']) == 0)//子节点向后缩进两个空格
		    {
		    	$delimiter = $delimiter.$this->typedropdelimiter;
		        ?>
		        <option value="<?php echo Yii::app()->createUrl('HomeAn/DoMore',array('atid'=>$treenode['node']->typeid));?>"
		        <?php if($chosentid == $treenode['node']->typeid)
				{echo "selected=\"selected\"";}?>
		        >
				<?php echo $delimiter . $treenode['node']->typename;?>
				</option>
		        <?php
		    }
		    else
		    {	//父节点
		    	$delimiter = $delimiter.$this->typedropdelimiter;
		        ?>
				<option value="<?php echo Yii::app()->createUrl('HomeAn/DoMore',array('atid'=>$treenode['node']->typeid));?>"
				<?php if($chosentid == $treenode['node']->typeid)
				{echo "selected=\"selected\"";}?>
				>
				<?php echo $delimiter . $treenode['node']->typename;?>
				</option>
		        <?php
		        foreach($treenode['subnodes'] as $key => $onenode)
		        {
		            $this->buildTypeDropNode($onenode,$delimiter,$chosentid,$optype);
		        }
		        ?>
		        <?php
		    }
		}	
	}
	/**
	*类别下拉框渲染
	*@param mixed array $typetree 所有类别节点;String $chosentid 当前选中的类别ID;String $optype 使用配置
	*@return echo
	*/
	public function buildTypeDrop($typetree,$chosentid="",$optype="single")
	{
		if($optype == "single")//单独文章页面
		{
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
			?>
			<option value="<?php echo YQNEWS; ?>" 
	        <?php if($chosentid == $yqnews['node']->typeid)
			{echo "selected=\"selected\"";}?>
			>
			<?php echo $yqnews['node']->typename;?>
			</option>
	      	<?php
		      if(count($yqnews['subnodes']) > 0)//是否有子分类
		      {
		        foreach($yqnews['subnodes'] as $key => $treenode)
		        {
		            $this->buildTypeDropNode($treenode,$this->typedropdelimiter,$chosentid,$optype);
		        }
		      }      
		      ?>
		    </div>
			<?php

			?>
			<option value="<?php echo YQLFNEWS; ?>" 
	        <?php if($chosentid == $yqlfnews['node']->typeid)
			{echo "selected=\"selected\"";}?>
			>
			<?php echo $yqlfnews['node']->typename;?>
			</option>
	      	<?php
		      if(count($yqlfnews['subnodes']) > 0)//是否有子分类
		      {
		        foreach($yqlfnews['subnodes'] as $key => $treenode)
		        {
		            $this->buildTypeDropNode($treenode,$this->typedropdelimiter,$chosentid,$optype);
		        }
		      }      
		      ?>
		    </div>
			<?php

		    ?>
			<option value="<?php echo YQEVENT; ?>" 
	        <?php if($chosentid == $yqevent['node']->typeid)
			{echo "selected=\"selected\"";}?>
			>
			<?php echo $yqevent['node']->typename;?>
			</option>
	      	<?php
		      if(count($yqevent['subnodes']) > 0)//是否有子分类
		      {
		        foreach($yqevent['subnodes'] as $key => $treenode)
		        {
		            $this->buildTypeDropNode($treenode,$this->typedropdelimiter,$chosentid,$optype);
		        }
		      }      
		      ?>
		    </div>
			<?php

			?>
			<option value="<?php echo YQAPP; ?>" 
	        <?php if($chosentid == $yqapp['node']->typeid)
			{echo "selected=\"selected\"";}?>
			>
			<?php echo $yqapp['node']->typename;?>
			</option>
	      	<?php
		      if(count($yqapp['subnodes']) > 0)//是否有子分类
		      {
		        foreach($yqapp['subnodes'] as $key => $treenode)
		        {
		            $this->buildTypeDropNode($treenode,$this->typedropdelimiter,$chosentid,$optype);
		        }
		      }      
		      ?>
		    </div>
			<?php
		}
		if($optype == "list")
		{
			$ttree = null;
			//数组简化
			foreach($typetree as $key => $treenode)
			{
				$ttree = $treenode;
			}
			?>
			<option value="<?php echo Yii::app()->createUrl('HomeAn/DoMore',array('atid'=>$ttree['node']->typeid)); ?>"
			<?php if($chosentid == $ttree['node']->typeid)
			{echo "selected=\"selected\"";}?>
			>
			<?php echo $ttree['node']->typename;?>
			</option>
	      	<?php
		      if(count($ttree['subnodes']) > 0)//是否有子分类
		      {
		        foreach($ttree['subnodes'] as $key => $treenode)
		        {
		            $this->buildTypeDropNode($treenode,$this->typedropdelimiter,$chosentid,$optype);
		        }
		      }      
		      ?>
		    </div>
			<?php
		}
		
	}
		
}
?>