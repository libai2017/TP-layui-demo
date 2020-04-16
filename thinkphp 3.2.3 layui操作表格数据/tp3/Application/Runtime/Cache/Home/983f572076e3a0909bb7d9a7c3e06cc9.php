<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$d): $mod = ($i % 2 );++$i;?><p><?php echo ($d["id"]); ?>----<?php echo ($d["name"]); ?>----<?php echo ($d["age"]); ?></p><?php endforeach; endif; else: echo "" ;endif; ?>
</body>
</html>