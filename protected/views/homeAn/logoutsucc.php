<?php
/**
*登出成功视图
*
*/
?>
<h4><?php
echo "登出成功！即将返回上次的页面！";
?></h4>
<script type="text/javascript">
	setTimeout("window.location.href = '<?php echo $lasturl;?>'",3000);
</script>