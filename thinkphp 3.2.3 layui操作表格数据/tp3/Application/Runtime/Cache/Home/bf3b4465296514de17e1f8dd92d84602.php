<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="/test/tp3/Public/js/jquery-3.3.1.min.js"></script>
</head>
<body>
    <p><input type="text" id="data" placeholder="请输入查询ID"></p>
    <p><input type="submit" onclick="submit()" value="提交"></p>
    <div id="back_data"></div>
</body>

<script>
    function submit(){
        $.ajax({
            url:"<?php echo U('get_data');?>",
            type:"POST",
            data:{id:$("#data").val()},
            success:function (result) {
                var str = "id:"+result.id+" name:"+result.name+" age:"+result.age;
                $("<p>").append(str).appendTo("#back_data");
            }
        })
    }
</script>

</html>