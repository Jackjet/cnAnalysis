<?php
/**
*@description 首页home view的布局文件
*@author JelCore
*@revised 2013-12-18
**/
?><?php echo $this->renderPartial('/layouts/header'); ?>
<section class="content">
	<?php echo $content;?>
	<?php
    echo $this->renderPartial('/layouts/sidebar');
    ?>
</section>
<?php echo $this->renderPartial('/layouts/footer'); ?> 