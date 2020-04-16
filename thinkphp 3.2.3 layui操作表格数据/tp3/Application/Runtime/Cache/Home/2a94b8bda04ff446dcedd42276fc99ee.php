<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="/test/tp3/Public/layui/css/layui.css">
    <script src="/test/tp3/Public/layui/layui.js"></script>
    <script src="/test/tp3/Public/js/jquery-3.3.1.min.js"></script>
</head>
<style>
    table td{
        text-align: center;
    }
</style>
<body>
<div class="layui-container" style="margin:25px auto;">

    <div class="layui-row">


        <div class="layui-col-md3">
            <ul class="layui-nav layui-nav-tree" lay-filter="test">
                <li class="layui-nav-item layui-nav-itemed">
                    <a href="javascript:;">导航条</a>
                    <dl class="layui-nav-child">
                        <dd><a href="<?php echo U('home/index/index');?>" class="check_select">全部数据</a></dd>
                        <dd><a style="cursor: pointer;" onclick="loading()">loading</a></dd>
                        <dd><a href="">跳转</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;">解决方案</a>
                    <dl class="layui-nav-child">
                        <dd><a href="">移动模块</a></dd>
                        <dd><a href="">后台模版</a></dd>
                        <dd><a href="">电商平台</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item"><a href="">产品</a></li>
                <li class="layui-nav-item"><a href="">大数据</a></li>
            </ul>
        </div>


        <div class="content layui-col-md9">
            <table class="layui-table">
                <caption>详细信息表</caption>
                <thead>
                <tr>
                    <th style="text-align: center;">ID</th>
                    <th style="text-align: center;">name</th>
                    <th style="text-align: center;">age</th>
                    <th colspan="2" style="text-align: center">操作</th>
                </tr>
                </thead>
                <tbody id="table-body">
                <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$d): $mod = ($i % 2 );++$i;?><tr>
                        <td name="<?php echo ($d["id"]); ?>"><?php echo ($d["id"]); ?></td>
                        <td name="<?php echo ($d["name"]); ?>"><?php echo ($d["name"]); ?></td>
                        <td name="<?php echo ($d["age"]); ?>"><?php echo ($d["age"]); ?></td>
                        <td><button name="<?php echo ($d["id"]); ?>" type="button" class="layui-btn layui-btn-normal layui-btn-sm" onclick="data_edit(this)">编辑</button></td>
                        <td><button name="<?php echo ($d["id"]); ?>" type="button" class="layui-btn layui-btn-danger layui-btn-sm" onclick="data_delete(this)">删除</button></td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
        </div>

    </div>
    <div id="test1" style="text-align: right;margin-right: 30px;"></div>
</div>

<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"] ?>


</body>

<script>



    // layui 分页，ajax获取数据填充到表格
    $(function () {
        $('.check_select').each(function () {
            if ($(this).attr("href") == "<?php echo $_SERVER['REQUEST_URI'] ?>") {
                $(this).addClass("layui-this");
            }
        });

        layui.use('laypage', function(){
            var laypage = layui.laypage;

            //执行一个laypage实例
            laypage.render({
                elem: 'test1',
                count: <?php echo ($count); ?>,//数据总数，从服务端得到
                limit:10,
                jump: function(obj, first){
                    //obj包含了当前分页的所有参数，比如：
                    console.log(obj.curr); //得到当前页，以便向服务端请求对应页的数据。
                    console.log(obj.limit); //得到每页显示的条数

                    //首次不执行
                    if(!first){
                        $.ajax({
                            url: "<?php echo U('home/index/get_page_data');?>",
                            data:{"page":obj.curr-1},
                            type:"POST",
                            success:function (result) {
                                if(result.message == 'success'){
                                    console.log(result.data);
                                    $("#table-body").empty();

                                    for(var i=0; i<result.data.length; i++){
                                        var str = '<tr>\n' +
                                            '<td name="'+  result.data[i].id +'">'+  result.data[i].id +'</td>\n' +
                                            '<td name="'+  result.data[i].name +'">'+  result.data[i].name +'</td>\n' +
                                            '<td name="'+  result.data[i].age +'">'+  result.data[i].age +'</td>\n' +
                                            '<td><button name="'+  result.data[i].id +'" type="button" class="layui-btn layui-btn-normal layui-btn-sm" onclick="data_edit(this)">编辑</button></td>\n' +
                                            '<td><button name="'+  result.data[i].id +'" type="button" class="layui-btn layui-btn-danger layui-btn-sm" onclick="data_delete(this)">删除</button></td>\n' +
                                            '</tr>';
                                        $("#table-body").append(str);
                                    }
                                }else{
                                    layui.msg(result.data);
                                }
                            },
                            error:function (result) {
                                console.log("error");
                            }
                        });
                    }
                }
            });
        });

    });


    // 删除按钮，删除后jquery移除一行数据
    function data_delete(that) {
        var id = $(that).attr("name");
        layui.use('layer', function(){
        layer.confirm('是否确定删除？', {
            btn: ['取消','确定'] //按钮
        }, function(){
            layer.msg("已取消");
        }, function(){
            $.ajax({
                url: "<?php echo U('home/index/data_delete');?>",
                data:{"id":id},
                type:"POST",
                success:function (result) {
                    console.log(result);
                    if(result.message == "success"){
                        $(that).parent().parent().remove();
                    }
                    layer.msg(result.data);
                },
                error:function (result) {
                    console.log(result);
                }
            })

        });
    })
    }

    //注意：导航 依赖 element 模块，否则无法进行功能性操作，渲染左边导航条
    layui.use('element', function () {
        var element = layui.element;
    });


    function data_edit(that){
        layui.use('layer', function(){
            var layer = layui.layer;
            layer.open({
                type: 1,
                area: ['600px', '360px'],
                shadeClose: true, //点击遮罩关闭
                content: '\<\div style="padding:20px;overflow: hidden">    ' +
                '<form class="layui-form" id="update-edit-form">\n' +
                '        <div class="layui-form-item">\n' +
                '            <label class="layui-form-label">ID :</label>\n' +
                '            <div class="layui-input-block">\n' +
                '                <div type="text" autocomplete="off" style="padding-top:8px;line-height:20px;padding: 9px 15px;" id="edit-id1">不能访问</div>\n' +
                '            </div>\n' +
                '        </div>\n' +
                '        <div class="layui-form-item">\n' +
                '            <label class="layui-form-label">name :</label>\n' +
                '            <div class="layui-input-block">\n' +
                '                <input type="text" id="edit-name" name="name" autocomplete="off" class="layui-input">\n' +
                '            </div>\n' +
                '        </div>\n' +
                '        <div class="layui-form-item">\n' +
                '            <label class="layui-form-label">age :</label>\n' +
                '            <div class="layui-input-block" id="permission-radio">\n' +
                '                <input type="text" id="edit-age" name="age" autocomplete="off" class="layui-input">\n' +
                '                <input type="hidden" id="edit-id2" name="id" autocomplete="off" class="layui-input">\n' +
                '            </div>\n' +
                '        </div>\n' +
                '        \n' +
                '        <div class="layui-form-item" style="margin-top:80px;margin-left:270px;">\n' +
                '            <div class="layui-input-block">\n' +
                '                <div class="layui-btn layui-btn-primary layui-layer-close1 layui-layer-close" id="edit-cancel">取消</div>\n' +
                '                <div style="margin-left:90px;" class="layui-btn layui-btn-normal edit-submit">确定</div>\n' +
                '            </div>\n' +
                '        </div>\n' +
                '    </form>'+
                '</div>'
            });

            var id = $(that).parent().parent().children().eq(0).attr("name");
            var name = $(that).parent().parent().children().eq(1).attr("name");
            var age = $(that).parent().parent().children().eq(2).attr("name");

            $("#edit-id1").text(id);
            $("#edit-id2").val(id);
            $("#edit-name").val(name);
            $("#edit-age").val(age);
            $(".layui-layer-title").text("修改个人信息");

            // 为确定按钮绑定事件，这个函数只能定义在这个函数内部，定义在外部就算是动态绑定也不行，
            $(".edit-submit").click(function () {
                console.log("click");

                var edit_name = $("#edit-name").val();
                var edit_age = $("#edit-age").val();
                if(name == edit_name && age==edit_age){
                    layer.msg("没有做修改");
                }else{
                    $.ajax({
                        type: "POST",
                        url: "<?php echo U('update_data');?>",
                        data: $('#update-edit-form').serialize(),
                        success: function (result) {
                            if(result.message == "success"){
                                layui.use('element', function(){
                                    layer.msg(result.data);
                                });
                                $(that).parent().parent().children().eq(1).attr("name",edit_name);
                                $(that).parent().parent().children().eq(1).text(edit_name);
                                $(that).parent().parent().children().eq(2).attr("name",edit_age);
                                $(that).parent().parent().children().eq(2).text(edit_age);
                                setTimeout(function(){$('#edit-cancel').click();},800);
                            }else{
                                layui.use('element', function(){
                                    layer.msg(result.data);
                                })
                            }
                        },
                        error : function() {
                            layui.use('element', function(){
                                layer.msg("不能访问！");
                            })
                        }
                    });
                }
            })
        });
    }


    function loading() {
        layui.use('layer', function(){
            var layer = layui.layer;
            var index = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
        })
    }

</script>
</html>