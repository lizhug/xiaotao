<?php

class ActivityAction extends GlobalAction {
	
	public function index() {
		$this->redirect("jdrebate");
	}


	//输出模板
	public function jdrebate() {
		if(isset($_GET["register_active"])) {
            $string = $_GET["register_active"];
            $uid = htmlspecialchars($_GET['u']);
            $tbValidation = M("validation");
            if (!!$tbValidation->where("'$uid' = uid AND code = '$string'")->find()) {
                $tbValidation->is_active = 1;
                $tbValidation->save();
                $tbUser = M('user');
                $tbUser->where("uid = '$uid'")->find();
                $tbUser->is_active = 1;
                $tbUser->save();
            } else {
            	$this->display("Register:active_error");
            	return;	
            }
            
        }

		$this->display();
	}


	//获取购物车页面
	public function getBuyPage() {

		//判断用户是否登录
		if (!session("uid")) {
			$returnData['content'] = $this->fetch("noticeWrap");
			$this->ajaxReturn($returnData);
		} else {
			$returnData['content'] = $this->fetch("buyCar");
			$this->ajaxReturn($returnData);
		}
	}

	//购物车物品提交
	public function saveBuyCar() {
		$jdDB = M("activity_jd_1111");
		$data['uname'] = htmlspecialchars($_POST['userName']);
		$data['url'] = htmlspecialchars($_POST['url']);
		$data['addr'] = htmlspecialchars($_POST['userAddr']);
		$data['phone'] = htmlspecialchars($_POST['userPhone']);
		$data['remark'] = htmlspecialchars($_POST['userAddition']);
		$data['uid'] = session("uid");
		$data['ctime'] = time();
		$data['completed'] = 0;
		$data['isdel'] = 0;
		$data["email"] = session("email");
		$data['watched'] = 0;
		//$this->ajaxReturn($data);
		if ($jdDB->add($data)) {
			$this->ajaxReturn(array("flag" => true, "context" => $this->fetch("buyCarSave")));
		} else {
			$this->ajaxReturn(array("flag" => true, "context" => "提交失败!"));
		}
	}

	//快速登录
	public function quickLogin() {
		$returnData['content'] = $this->fetch("quickLogin");
		$this->ajaxReturn($returnData);
	}

	//快速注册
	public function quickRegister() {
		$returnData['content'] = $this->fetch("quickRegister");
		$this->ajaxReturn($returnData);
	}	

	//查看已购买列表
	public function getAlreadyBuy() {
	
		$jdDB = M("activity_jd_1111");
		$uid = session("uid");

		$data = $jdDB->where("uid = $uid AND isdel = 0")->select();
		$this->assign("myList", $data);
		$returnData['content'] = $this->fetch("alreadyBuy");

		$this->ajaxReturn($returnData);
	}

	//取消订单
	public function deleteItem() {
		$itemId = intval($_POST['itemId']);
		$uid = session("uid");
		$jdDB = M("activity_jd_1111");
		if ($jdDB->where("uid = $uid AND id = $itemId")->save(array("isdel" => 1))) {
			$this->ajaxReturn(array("flag" => true));
		} else {
			$this->ajaxReturn(array("flag" => false));
		}
	}

	//完成订单
	public function completeItem() {
		$itemId = intval($_POST['itemId']);
		$uid = session("uid");
		$jdDB = M("activity_jd_1111");
		if ($jdDB->where("uid = $uid AND id = $itemId")->save(array("completed" => 1))) {
			$this->ajaxReturn(array("flag" => true));
		} else {
			$this->ajaxReturn(array("flag" => false));
		}	
	}

	//后台查看
	public function adminWatch() {
		$jdDB = M("activity_jd_1111");
		$data = $jdDB->order("ctime DESC")->select();
		$this->assign("orderList", $data);
		$this->display();
	} 
}

?>