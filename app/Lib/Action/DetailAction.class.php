<?php

class DetailAction extends GlobalAction {
    public function index (){
        $id = isset($_GET["id"]) ? intval(addslashes($_GET["id"])) : false;
		
        if (!$id) {
            $this->display("Public:pageNotFound");       //404
            return;
        }
        
         //记录访问该物品的用户
        $this->pub_glance_logs();
        
        $tbPub = M("pub");
        $tbPub->where("pub_id = '$id' AND isdel = 0")->setInc("scan");              //自增
		
        //获取物品信息
        $pubData = $tbPub->where("pub_id = $id AND isdel = 0")->find();
        $uid = $pubData['uid'];
        //把手机号码生成图片
        $pubData['phone'] = $this->stringToImage($pubData['phone']);
        $pubData['phone'] = base64_encode($pubData['phone']);

        //获取该物品的用户信息
        $tbUser = M("user");
        $userData = $tbUser->where("uid = $uid")->find();
        $this->assign('userData', $userData);
      
        //图片json转化为数据
        $pubData["image_data"] = json_decode($pubData["image_data"], true);
        $this->assign("data", $pubData);
        $this->assign("image", $pubData["image_data"]);

        if (!!session("uid")){
            $uid = session("uid");
            $tbStore = M("store");
            $pid = $pubData['pub_id'];
            $isDel = $tbStore->where("uid = '$uid' AND pid = '$pid'")->find();

            if (!!$isDel){
                $this->assign("status", $tbStore->isdel);
            } else {
                $this->assign("status", 1);
            }
        }
        $this->display();
    }

    //把文本转换为图片
    private function stringToImage($string){
        ob_start();
        import('ORG.Util.ThinkImage');          //引入图片处理库
        $img = new ThinkImage(THINKIMAGE_GD, "static\img\intl_detail\btn\phone.png");
        $img->text($string, "static/font/huawen.ttf", 18, '#FF0000', THINKIMAGE_WATER_CENTER);
        $img->save('');
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    //记录访问该detail的用户
    public function pub_glance_logs() {
        $uid = session("uid") ? session('uid') : 0;
        $id = intval($_GET['id']);
        
        $tbPubLogs = M("pub_glance_logs");
        $tbPubLogs->add(array("uid" => $uid, "pid" => $id, "ctime" => time()));
    }

    public function itmSave(){
         //获取session
        $returnData["isFit"] = false;

        if (!session("uid")){
            $returnData["msg"] = "请您先登录在进行收藏操作!";
            $this->ajaxReturn($returnData);
        }
        $uid = session("uid");
        $pid = $_POST["id"];

        $tbStore = M("store");

        $dataFlag = $tbStore->where("uid = '$uid' AND pid = '$pid'")->find();
        
        if ($dataFlag){
            if ($tbStore->isdel == 0) {
                $returnData["msg"] = "亲,您已经收藏过了!";
                $this->ajaxReturn($returnData);
            } else {
                $tbStore->isdel = 0;
               
                $tbStore->ctime = time();
                $tbStore->save();
                $returnData["isFit"] = true;
                $returnData["msg"] = "收藏成功";
                $this->ajaxReturn($returnData);
            }
        }

        $tbStore->add(array("uid"=>$uid, "pid"=>$_POST["id"], "ctime"=>time(), "isdel"=>0));

        if (!!$tbStore){
            $returnData["isFit"] = true;
            $returnData["msg"] = "收藏成功";
        } else {
            $returnData["msg"] = "收藏失败";
        }
        $this->ajaxReturn($returnData);
    }
    public function itmCancel(){
         //获取session

        $returnData["isFit"] = false;

        if (!session("uid")){
            $returnData["msg"] = "请您先登录在进行取消收藏操作!";
            $this->ajaxReturn($returnData);
        }

        $uid = session("uid");
        $pid = $_POST["id"];
        $tbStore = M("store");

        $tbStore->where("uid = '$uid' AND pid = '$pid'")->find();

        if (!!$tbStore){

            if ($tbStore->isdel == 0){
                 $tbStore->isdel = 1;
                 $tbStore->save();

                 $returnData["isFit"] = true;
                 $returnData["msg"] = "取消收藏成功";

            } else {
                $returnData["msg"] = "您还未收藏该物品";
            }
        } else {
            $returnData["msg"] = "您还未收藏该物品";
        }
        $this->ajaxReturn($returnData);
    }
	
    public function deleteDetail() {
        $pid = $_POST['pid'];
        $tbPub = M("pub");
        $data['isdel'] = 1;
        $result = $tbPub->where("pub_id = $pid")->save($data);
        $this->ajaxReturn($result);
    }
}
?>
