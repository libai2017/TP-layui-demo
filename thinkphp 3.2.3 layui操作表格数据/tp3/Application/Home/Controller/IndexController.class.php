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