<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="/test/tp3/Public/bootstrap/css/bootstrap.css">
    <script src="/test/tp3/Public/js/jquery-3.3.1.min.js"></script>
    <script src="/test/tp3/Public/bootstrap/js/bootstrap.js"></script>
</head>
<body>

    <div style="width: 600px;margin: 20px auto;">
        <table class="table">
            <caption>详细信息表</caption>
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>name</th>
                    <th>age</th>
                    <th colspan="2" style="text-align: center">操作</th>
                </tr>
            </thead>
            <tbody>
            <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$d): $mod = ($i % 2 );++$i;?><tr>
                    <th scope="row"><?php echo ($d["id"]); ?></th>
                    <td id="<?php echo ($d["id"]); ?>"><?php echo ($d["id"]); ?></td>
                    <td name="<?php echo ($d["name"]); ?>"><?php echo ($d["name"]); ?></td>
                    <td age="<?php echo ($d["age"]); ?>"><?php echo ($d["age"]); ?></td>
                    <td><button type="button" class="btn btn-primary btn-sm edit" data-toggle="modal" data-target="#myModalEdit" >编辑</button></td>
                    <td><button type="button" class="btn btn-primary btn-sm delete" data-toggle="modal" data-target="#myModalDelete" >删除</button></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    </div>



    <div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabelDelete">是否删除此条信息？</h4>
                </div>
                <div class="modal-body">
                    <span style="margin-left: 20px">id:</span><span id="Delete-id"></span>
                    <span style="margin-left: 20px">name:</span><span id="Delete-name"></span>
                    <span style="margin-left: 20px">age:</span><span id="Delete-age"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="delete">确定</button>
                </div>
            </div>
        </div>
    </div>



    <script>
        $(".edit").click(function () {
            $("#Delete-age").html($(this).parent().prev().attr("age"));
            $("#Delete-name").html($(this).parent().prev().prev().attr("name"));
            $("#Delete-id").html($(this).parent().prev().prev().prev().attr("id"));
        })
    </script>
</body>
</html>