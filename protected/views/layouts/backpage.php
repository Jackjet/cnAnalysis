<?php
/**
*@description 后台页面 的布局文件
*@author JelCore
*@revised 2014-01-05
**/
?><?php echo $this->renderPartial('/layouts/back_header'); ?>
<div class="wrapper">
	<?php echo $this->renderPartial('/layouts/back_aside');?>
	<?php echo $content;?>
</div>
<?php echo $this->renderPartial('/layouts/back_footer'); ?> 