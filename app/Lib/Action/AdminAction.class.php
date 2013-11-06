<?php

class AdminAction extends GlobalAction {

	public function index() {
		// 管理员身份验证
        if (session("admin_level") != 1){
        	$this->redirect("Admin:error");
        }
        AdminAction::monthRegister();
		$this->display();
	}
    /*---------------------------------------------获得当月注册用户数-----------------------------*/
    public function monthRegister() {
        $tbUser = M("user");
        $userData = $tbUser->order("ctime desc")->field("ctime")->select();
        $month = date_format($Think.now, '%m');
        $monthReg = 0;
        for($i = 0; $i != count($userData); $i++) {
            if (date_format($userData[$i]['ctime'], '%m')==$month){
                $monthReg ++;
            }
        }
        $this->assign("monthReg", $monthReg);
    }

    /*---------------------------------------------用户列表和发布信息列表-----------------------------*/
    public function userList() {
        // 管理员身份验证
        if (session("admin_level") != 1){
            $this->redirect("Admin:error");
        }
        
        $tbUser = M("user");

        import('ORG.Util.Page');

        $count = $tbUser->count();

        $Page = new Page($count, 30);

        $userData = $tbUser->order("ctime desc")->field("uid,uname,email,password,city,area,isdel,admin_level")->limit($Page->firstRow.','.$Page->listRows)->select();

        $show = $Page->show();

        $this->assign("page", $show);


        $this->assign("userData", $userData);
        
        AdminAction::monthRegister();

        $this->display();
    }

    public function pubList() {
        // 管理员身份验证
        if (session("admin_level") != 1){
            $this->redirect("Admin:error");
        }
        
        $tbPub = M("pub");

        //  ->field("pub_id, xt_user.uid, title, xt_user.uname, price, is_complete, xt_pub.isdel")->limit($start*30, 30)->select();

        import('ORG.Util.Page');

        $count = $tbPub->count();
        
        $Page = new Page($count, 30);

        //$pubData = $tbPub->order("ctime DESC")->limit($Page->firstRow.','.$Page->listRows)->select();

        $pubData = $tbPub->join("LEFT JOIN xt_user on xt_user.uid = xt_pub.uid")->order("xt_pub.ctime DESC")->field("pub_id, xt_pub.uid, title, uname, price, is_complete, xt_pub.isdel")->limit($Page->firstRow.','.$Page->listRows)->select();
        
        $show = $Page->show();

        $this->assign("page", $show);

        $this->assign("pubData", $pubData);

        AdminAction::monthRegister();
        $this->display();
    }

    /*---------------------------------------------网站关键字和描述-----------------------------*/
    public function webConfig() {
        // 管理员身份验证
        if (session("admin_level") != 1){
            $this->redirect("Admin:error");
        }

        $tbWebConfig = M("web_config");
        $webconfigData = $tbWebConfig->where("id = 1")->select();
        $webconfigData[0]['ctime'] = date('Y-m-d h-i-s', $webconfigData[0]['ctime']);
        $this->assign("web_config", $webconfigData[0]);
        AdminAction::monthRegister();
        $this->display();

    }
    /*---------------------------------------------更新用户信息和发布信息-----------------------------*/

	public function updateUser() {
		// 管理员身份验证
        if (session("admin_level") != 1){
        	$this->redirect("Admin:error");
        }
       
        $data = array(
	        "uname" => $_POST['uname'],
	        "password" => $_POST['password'],
            "email" => $_POST['email'],
            "admin_level" => $_POST['admin_level'],
	        "city" => $_REQUEST['city'],
	        "area" => $_REQUEST['area']
        );

        $condition['uid'] = $_POST['uid'];
	
        $tbUser = M('user');
        $result = $tbUser->where($condition)->data($data)->save();
        if (!$result) {
                $this->ajaxReturn("","",0);
        }

        $this->ajaxReturn("","",1);
	}

	public function updatePub() {
		// 管理员身份验证
        if (session("admin_level") != 1){
        	$this->redirect("Admin:error");
        }

        $data = array(
	        "uid" => $_REQUEST['uid'],
	        "title" => $_REQUEST['title'],
	        "price" => $_REQUEST['price'],
	        "is_complete" => $_REQUEST['is_complete'],
        );

        $condition['pub_id'] = $_REQUEST['pub_id'];

        $tbPub = M('pub');
        $result = $tbPub->where($condition)->data($data)->save();
        if ($result) {
                $this->ajaxReturn("","",0);
        }
        $this->ajaxReturn("","",1);
	}

    public function updateWebConfig() {
        // 管理员身份验证
        if (session("admin_level") != 1){
            $this->redirect("Admin:error");
        }

        $data = array(
            "uid" => session('uid'),
            "description" => $_POST['description'],
            "keywords" => $_POST['keywords'],
            "ctime" => time());
        
        $tbWebConfig = M('web_config');
        $result = $tbWebConfig->where('id = 1')->data($data)->save();
        if (!$result) {
            $this->ajaxReturn(false);
        }
        $this->ajaxReturn(true);
    }
    /*---------------------------------------------删除用户或者发布-----------------------------*/
	public function deleteUser() {
		// 管理员身份验证
        if (session("admin_level") != 1){
        	$this->redirect("Admin:error");
        }

        //$data['isdel'] = $_REQUEST['isdel'];
        $uid = $_REQUEST['uid'];

        $tbUser = M("user");

        $userData = $tbUser->where("uid = $uid")->field("isdel")->find();

        $data['isdel'] = ($userData['isdel'] == 0 ? 1 : 0);

        $result = $tbUser->where("uid = $uid")->data($data)->save();
        if ($result) {
                $this->ajaxReturn("","",0);
        }
        $this->ajaxReturn("","",1);
	}

	public function deletePub() {
        // 管理员身份验证
        if (session("admin_level") != 1){
                $this->redirect("Admin:error");
        }

        //$data['isdel'] = $_REQUEST['isdel'];
        $condition['pub_id'] = $_REQUEST['pub_id'];

        $tbPub = M("pub");

        $pubData = $tbPub->where($condition)->field("isdel")->select();
        $data['isdel'] = ($pubData[0]['isdel'] == 0 ? 1 : 0);

        $result = $tbPub->where($condition)->data($data)->save();
        if ($result) {
                $this->ajaxReturn("","",0);
        }
        $this->ajaxReturn("","",1);
	}

    /*---------------------------------------------查看用户反馈信息-----------------------------*/
    public function feedback() {
        // 管理员身份验证
        if (session("admin_level") != 1){
                $this->redirect("Admin:error");
        }

        $tbFeedback = M("feedback");

        import('ORG.Util.Page');

        //获取未读反馈信息

        $read_count = $tbFeedback->order("ctime DESC")->where("isdel = 1")->count();
        
        $read_Page = new Page($read_count, 30);
    
        $read_show = $read_Page->show();

        $readFeedbackData = $tbFeedback->order("ctime DESC")->where("isdel = 1")->limit($read_Page->firstRow.','.$read_Page->listRows)->select();

        for ($i = 0; $i != count($readFeedbackData); $i++) {
            $readFeedbackData[$i]['ctime'] = date('Y-m-d h:i:s', $readFeedbackData[$i]['ctime']);
        }

        $this->assign("read_page", $read_show);

        $this->assign("read_feedback", $readFeedbackData);

        //获取已读反馈信息
        $unread_count = $tbFeedback->order("ctime DESC")->where("isdel = 0")->count();
        
        $unread_Page = new Page($unread_count, 30);
    
        $unread_show = $unread_Page->show();

        $unreadFeedbackData = $tbFeedback->order("ctime DESC")->where("isdel = 0")->limit($unread_Page->firstRow.','.$unread_Page->listRows)->select();

        for ($i = 0; $i != count($unreadFeedbackData); $i++) {
            $unreadFeedbackData[$i]['ctime'] = date('Y-m-d h:i:s', $unreadFeedbackData[$i]['ctime']);
        }

        $this->assign("unread_page", $unread_show);

        $this->assign("unread_feedback", $unreadFeedbackData);

        AdminAction::monthRegister();
        $this->display();
    }
    /*---------------------------------------------更改反馈信息的状态-----------------------------*/
    public function updateFeedback() {
        // 管理员身份验证
        if (session("admin_level") != 1){
                $this->redirect("Admin:error");
        }

        $condition['id'] = $_POST['id'];

        $tbFeedback = M("feedback");

        $feedbackData = $tbFeedback->where($condition)->find();

        $data['isdel'] = $feedbackData['isdel'] == 1 ? 0 : 1;

        $result = $tbFeedback->where($condition)->data($data)->save();

        if (!$result) {
            $this->ajaxReturn(0);
        }

        $this->ajaxReturn(1);
    }


    /*---------------------------------------------邮件回复提交反馈的用户-----------------------------*/
    public function emailFeedback() {
        // 管理员身份验证
        if (session("admin_level") != 1){
                $this->redirect("Admin:error");
        }

        $content = $_POST['content'];

        $condition['email'] = $_POST['email'];

        $tbUser = M("user");

        $userInfo = $tbUser->where($condition)->select();

        if ($userInfo[0]['email']) {

            Vendor("phpMail.phpmailer");

            $mail = new PHPMailer();

            $mail->IsSMTP();

            $mail->CharSet = "utf-8";

            //$mail->Port = 465;

            $mail->Host = C("EMAIL_ADDR"); // 您的企业邮局域名
            $mail->SMTPAuth = true; // 启用SMTP验证功能
            $mail->Username = C("EMAIL_USER"); // 邮局用户名(请填写完整的email地址)
            $mail->Password = C("EMAIL_PWD"); // 邮局密码
            $mail->From =  C("EMAIL_ADDR"); //邮件发送者email地址
            $mail->FromName = C("FROM_NAME");   //来源名字

            $mail->Subject = "校PLUS 反馈意见回复";
            $mail->AddAddress($userInfo[0]["email"], $userInfo[0]['uname']);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")

            $mail->IsHTML(true); // set email format to HTML //是否使用HTML格式

            $str1 = file_get_contents("app/Tpl/Admin/mailFeedbackContent.html");

            $str1 = str_replace("STATIC_User_Name", $userInfo[0]['uname'], $str1);

            $str1 = str_replace("STATIC_content", $content, $str1);

            $mail->Body = $str1; //邮件内容

            if(!$mail->Send()){

                $this->ajaxReturn($mail->ErrorInfo);

                $this->ajaxReturn(0);

            } else {

                $tbFeedback = M('feedback');

                $data['isreply'] = 1;

                $feedbackId = $_POST['feedback_id'];

                $tbFeedback->where("id = $feedbackId")->data($data)->save();

                $this->ajaxReturn(1);


            }

        }

    }


    /*---------------------------------------------学校和专业列表-----------------------------*/
    public function schoolList() {
        // 管理员身份验证
        if (session("admin_level") != 1){
                $this->redirect("Admin:error");
        }

        //提供城市信息
        $tbArea = M('area');
        $areaData = $tbArea->where('pid = 0')->select();

        $this->assign("area", $areaData);

        //获得学校信息
        $tbSchool = M('school');
        $schoolData = $tbSchool->where('sid = 0')->field('school_id, school, area_id')->select();

        $this->assign("schoolData", $schoolData);

        AdminAction::monthRegister();
        $this->display();
    }

    public function majorList() {
        // 管理员身份验证
        if (session("admin_level") != 1){
                $this->redirect("Admin:error");
        }

        $tbMajor = M("school");

        $majorData = $tbMajor->query('SELECT s1.school_id AS "major_id", s1.sid AS "school_id", s1.school AS "major", s2.school AS "school", s2.area_id FROM xt_school AS s1, xt_school AS s2 where s1.sid = s2.school_id');
        $schoolList = $tbMajor->where("sid = 0")->select();

        $this->assign("majorData", $majorData);
        $this->assign("schoolList", $schoolList);
        AdminAction::monthRegister();
        $this->display();
    }

    /*---------------------------------------------添加和删除 学校-----------------------------*/
    public function addSchool() {
        // 管理员身份验证
        if (session("admin_level") != 1){
                $this->redirect("Admin:error");
        }

        $tbSchool = M('school');
        $data = array(
            "school_id" => "",
            "school" => $_POST['school'],
            "area_id" => $_POST['area_id'],
            "sid" => 0);
        $school = $_POST['school'];
        $judge = $tbSchool->where("school = '" . $school . "\'")->count();

        if ($judge) {
            $this->ajaxReturn(0);
        }

        $result = $tbSchool->data($data)->add();

        $this->ajaxReturn(1);
    }

    public function deleteSchool() {
        // 管理员身份验证
        if (session("admin_level") != 1){
                $this->redirect("Admin:error");
        }

        $tbSchool = M('school');
        $data['school_id'] = $_POST['id'];

        $result = $tbSchool->where($data)->delete();
        if (!$result) {
            $this->ajaxReturn(0);
        }
        $this->ajaxReturn(1);
    }
    
    /*---------------------------------------------添加和删除专业-----------------------------*/
    public function addMajor() {
        // 管理员身份验证
        if (session("admin_level") != 1){
                $this->redirect("Admin:error");
        }

        $tbSchool = M('school');
        $data = array(
            "school_id" => "",
            "school" => $_POST['major'],
            "sid" => $_POST['school_id']);

        $result = $tbSchool->data($data)->add();

        if (!$result) {
            $this->ajaxReturn(0);
        }
        $this->ajaxReturn(1);
    }

    public function deleteMajor() {
        // 管理员身份验证
        if (session("admin_level") != 1){
                $this->redirect("Admin:error");
        }

        $tbSchool = M('school');
        $data['school_id'] = $_POST['major_id'];

        $result = $tbSchool->where($data)->delete();
        if (!$result) {
            $this->ajaxReturn(0);
        }
        $this->ajaxReturn(1);
    }

    /*---------------------------------------------修改学校和专业-----------------------------*/
    public function updateSchool() {
        // 管理员身份验证
        if (session("admin_level") != 1){
                $this->redirect("Admin:error");
        }

        $tbSchool = M('school');
        $data = array(
            "school" => $_POST['school'],
            "sid" => 0,
            "area_id" => $_POST['area_id']);
        $condition['school_id'] = $_POST['school_id'];

        $result = $tbSchool->where($condition)->data($data)->save();

        $judge = $tbSchool->where($condition)->select();
        if (!$result && count($judge) == 0) {
            $this->ajaxReturn(0);
        }
        $this->ajaxReturn(1);
    }

    public function updateMajor() {
        // 管理员身份验证
        if (session("admin_level") != 1){
                $this->redirect("Admin:error");
        }

        $tbSchool = M('school');
        $data = array(
            "school" => $_POST['major']);
        $condition['school_id'] = $_POST['major_id'];
        $result = $tbSchool->where($condition)->data($data)->save();

        $judge = $tbSchool->where($condition)->select();
        if (!$result && count($judge) == 0) {
            $this->ajaxReturn(0);
        }
        $this->ajaxReturn(1);
    }

}

?>