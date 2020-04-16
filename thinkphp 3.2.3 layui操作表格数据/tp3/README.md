详细可以看：https://www.cnblogs.com/zhengzheng66/articles/12715649.html

 layui 模态框提示
![1](https://img2020.cnblogs.com/blog/1134658/202004/1134658-20200416205728041-647573428.png)

弹出框编辑信息
![2](https://img2020.cnblogs.com/blog/1134658/202004/1134658-20200416205801471-2033748453.png)

操作后提示信息
![3](https://img2020.cnblogs.com/blog/1134658/202004/1134658-20200416205835960-1689982877.png)

thinkPHP 的主要代码，有些东西没完善好，得注意一些，具体不过多解释了，关于layui部分，可以参考layui的官网
提示：layui的模态框不同于bootstrap，其很多内容都是需要渲染之后才有效果的，这一点需要注意。

既然是渲染之后才有的dom，那么我们就需要在其渲染之后为一些按钮绑定所需要的事件，选择其渲染之后出现的 class 或 id 进行查找绑定事件。
```
<?php
namespace Home\Controller;
use Think\Controller;

use Home\Model\TestModel;
use Think\Log;

class IndexController extends Controller {
    public function index(){
        $page = I("get.page");
        if(!$page){
            $page = 0;
        }
        $model = new TestModel();
        $data = $model->limit($page*10,10)->order('id asc')->select();
        $count = $model->where()->count();
        $this->assign("count",$count);
        $this->assign("data",$data);
        $this->display();
    }

    public function get_page_data(){
        if(IS_AJAX){
            $page = I("post.page");
            $model = new TestModel();
            $data = $model->limit($page*10,10)->order('id asc')->select();
            if($data){
                $message = "success";
            }else{
                $message = "fail";
                $data = "获取数据失败";
            }
            $this->ajaxReturn(
                array(
                    "message" => $message,
                    "data" => $data,
                )
            );
        }
    }

    public function update_data(){
        try{
            if(IS_AJAX){
                $id = I("post.id");
                $da['name'] = I("post.name");
                $da['age'] = I("post.age");
                $model = new TestModel();
                $result = $model->where("id=%d",$id)->save($da);
                if($result){
                    $message = "success";
                    $data = "数据更新成功";
                }else{
                    $message = "fail";
                    $data = "数据插入失败";
                }
                $this->ajaxReturn(
                    array(
                        "message" => $message,
                        "data" => $data,
                        "dddd" => $da,
                        'ID'   =>$id,
                    )
                );
            }
        }catch (\Exception $e){
            Log::record("[IndexController@update] error:" . $e->getMessage() . "; in file & line :" . $e->getFile()
                . "[Line:".$e->getLine()."]" );
            $this->ajaxReturn(
                array(
                    "message" => "fail",
                    "data"    => $e->getMessage().";".$e->getFile(),
                )
            );
        }

    }

    public function data_delete(){
        try{
            if(IS_AJAX){
                $id = I("post.id");
                if($id){
                    $model = new TestModel();
                    $result = $model->where("id=%d",$id)->delete();
                    if($result){
                        $message = "success";
                        $data = "删除成功";
                    }else{
                        $message = "fail";
                        $data = "删除失败";
                    }
                    $this->ajaxReturn(
                        array(
                            "message" => $message,
                            "data" => $data,
                        )
                    );
                }
            }
        }catch (\Exception $e){
            Log::record("[IndexController@delete] error:" . $e->getMessage() . "; in file & line :" . $e->getFile()
                . "[Line:".$e->getLine()."]" );
            $this->ajaxReturn(
                array(
                    "message" => "fail",
                    "data"    => $e->getMessage().";".$e->getFile(),
                )
            );
        }

    }

    public function search(){
        $this->assign("this_page", U('get_data'));
        $this->display();
    }

    public function get_data(){
        $id = I("post.id");
        $model = new TestModel();
        $data = $model->where("id=$id")->find();
        $this->ajaxReturn($data);
    }
}
```
HTML主页
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="__PUBLIC__/layui/css/layui.css">
    <script src="__PUBLIC__/layui/layui.js"></script>
    <script src="__PUBLIC__/js/jquery-3.3.1.min.js"></script>
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
                        <dd><a href="{:U('home/index/index')}" class="check_select">全部数据</a></dd>
                        <dd><a style="cur
