<?php
echo $this->renderPartial('/layouts/header');
?>
    <section class="content">
        <?php echo $content;?>
        <?php
        //echo $this->renderPartial('/layouts/sidebar');
        ?>
    </section>
<?php echo $this->renderPartial('/layouts/footer'); ?>