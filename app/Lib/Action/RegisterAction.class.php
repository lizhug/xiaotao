<?php

class RegisterAction extends GlobalAction {
    public function index (){
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
                $this->display("Register:active_success");
                return;
            } 
            $this->display("Register:active_error");
            return;
        }
        $this->display();
    }
    public function active_error() {
        $this->display();
    }

    public function active_success() {
        $this->display();
    }
    
    //提交注册信息
    public function registerSubmit(){
        $returnData["isFit"] = false;
        //验证码验证
        if (!checkVerifyCode()){
            $returnData["msg"] = "验证码错误";
            $this->ajaxReturn($returnData);
        }
        //获取表单变量
        $userName = $_POST["userName"];
        $mail = $_POST["mail"];
        $pwd = $_POST["pwd"];
        if ($userName == "" || $mail == "" || $pwd == ""){
            $returnData["msg"] = "您填写的信息有错误";
            $this->ajaxReturn($returnData);
        }
        
        //实例化数据库 xt_user 用来查询用户邮箱 和 用户名是否已经被注册
        $tbUser = M("user");
        if (!!$tbUser->where("email = '$mail' OR uname = '$userName'")->find()){
            $returnData["msg"] = "邮箱或者用户名已经被注册";
            $this->ajaxReturn($returnData);
        } else {
            $newXiaoplus = $this->getXiaoplusId();
            $tmpData = array("email" => $mail, "head" => C("DEFAULT_USER_HEADER"), "uname" => $userName, "password" => md5($pwd), "ctime"=>time(), "xiaoplus"=>$newXiaoplus);
            $uid = $tbUser->add($tmpData);
            $tmpData['uid'] = $uid;
            $returnData["isFit"] = true;
            $returnData["email"] = $mail;
            $returnData["id"] = $newXiaoplus;
            $returnData["userName"] = $userName;
            $returnData["msg"] = "注册成功";
            //用户属性的表单
            $tbUserProfile = M("user_profile");
            $data["qq"] = "";
            $data["college"] = session("schoolId");             //注册的时候默认当前页面所在大学
            $data["major"] = "";
            $data["year"] = "2010";
            $tbUserProfile->add(array("uid" => $uid, "data" =>  json_encode($data)));
            session("xiaoplus", $newXiaoplus);                        //xiaoplus账号
            session("uname", $userName);                                 //用户名
            session("uid", $uid);                                  //用户 uid
            session("email", $mail);
            session("user-img",  C("DEFAULT_USER_HEADER"));
            session("admin_level", 0);
            $this->getFolder($newXiaoplus);
            if ($this->sentValidMail($tmpData)) {
                $returnData["isFit"] = true;
                $this->ajaxReturn($returnData);    
            } else {
                 $returnData["isFit"] = false;
                 $returnData["msg"] = "注册成功!邮件发送错误!";
                 $this->ajaxReturn($returnData);
            }
        }
    }

    public function checkMail() {
        $this->assign("uname", addslashes($_GET['uname']));
        $this->assign("xiaoplus", intval($_GET['id']));
        $this->assign("email", addslashes($_GET['email']));
        $this->display();
    }

    public function completeBasicInformation() {
        if (!session("uid")){
            $this->error("请您登陆后再访问个人信息页面");
        }
        //输出用户

        $uid = session("uid");
        $tbUser = M("user");
        $userData = $tbUser->where("uid = '$uid'")->find();
        $userData['birth'] = explode("-", $userData['birth']);
        $this->assign("data",$userData);                    //输出用户数据
        
        //输出省份
        $this->showProvinceList();
        $this->showCityList($userData['province']);
        $this->showAreaList($userData['city']);
        
        $tbUserProfile = M("user_profile");
        $profileData = $tbUserProfile->where("uid = '$uid'")->find();
        $profileData['data'] = json_decode($profileData['data'], true);
        $this->assign("profile", $profileData);
        $this->display();
    }
	
    public function basicInfoSave() {
	if (!session("uid")){
            $this->error("请您登陆后再访问个人信息页面");
        }
       
        $uid = session("uid");
        $tbUser = M("user");
        $_POST["birth"] = $_POST["b-year"]."-". $_POST["b-month"]."-". $_POST["b-day"];
        
        $saveData['province'] = $_POST['province'];
        $saveData['city'] = $_POST['city'];
        $saveData['area'] = $_POST['area'];
        $saveData['birth'] = $_POST['birth'];
        $saveData['sex'] = $_POST['sex'];
         
        $tbUser->where("uid = $uid")->save($saveData);
   
	//调用父类的方法更新cookie
	parent::aliasCookie($_POST['college']);
        
        $tbUserProfile = M("user_profile");
        $data["college"] = $_POST["college"];
        $data["year"] = $_POST["year"];
       
        $tbUserProfile->where("uid = $uid")->save(array("data" =>  json_encode($data)));
        $returnData["isFit"] = true;
        $returnData["msg"] = "修改成功";
        $this->ajaxReturn($_POST);
    }
	
    public function completeUserImage(){
        if (!session("uid")){
            $this->error();
        }
        $this->getInitHead();
        $this->display();
    }

    public function getInitHead (){
        $uid = session("uid");
        $tbUser = M("user");
        $imgPath = $tbUser->where("uid = '$uid'")->getField("head");
        $this->assign("headInit", $imgPath);
    }

    public function showProvinceList(){
        //输出地点
        $tbArea = M("area");
        $areaData = $tbArea->where("area")->where("pid = 0")->select();
        $this->assign("area", $areaData);
    }

    //城市列表
    public function showCityList($proId) {
        $tbArea = M("area");
        $cityData = $tbArea->where("pid = $proId")->select();
        $this->assign("cityList", $cityData);
        return $cityData[0]['area_id'];         //返回第一个城市 用来获取地区
    }
    
    //ajax形式获取地点
    public function getAllArea() {
        $pid = $_REQUEST["pid"];
        $tbArea = M("area");
        $areaData = $tbArea->where("pid = '$pid'")->select();
        $this->ajaxReturn($areaData);
    }
    
    //返回地区列表
    public function showAreaList($cityId) {
        $tbArea = M("area");
        $areaData = $tbArea->where("pid = $cityId")->select();
        $this->assign("areaList", $areaData);
    }

    protected function sentValidMail($userInfo){
        Vendor("phpMail.phpmailer");
        $verifyCode = md5(time());
        $validData["type"] = "register_activate";
        $validData["uid"] = $userInfo['uid'];
        $validData["is_active"] = 0;
        $validData["code"] = $verifyCode;
        $validData["ctime"] = time();
        $validData["target_url"] = $_SERVER["HTTP_REFERER"]."?register_active=".$verifyCode."&u=".$userInfo["uid"];
        $mail = new PHPMailer(); //建立邮件发送类
        $address = $userInfo["email"];
        $mail->IsSMTP(); // 使用SMTP方式发送
        $mail->CharSet = "utf-8";
        //$mail->Port = 465;
        $mail->Host = C("EMAIL_ADDR"); // 您的企业邮局域名
        $mail->SMTPAuth = true; // 启用SMTP验证功能
        $mail->Username = C("EMAIL_USER"); // 邮局用户名(请填写完整的email地址)
        $mail->Password = C("EMAIL_PWD"); // 邮局密码
        $mail->From =  C("EMAIL_USER"); //邮件发送者email地址
        $mail->FromName = C("FROM_NAME");   //来源名字
        $mail->AddAddress("$address", $userInfo['uname']);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")

        $mail->IsHTML(true); // set email format to HTML //是否使用HTML格式

        $mail->Subject = "校PLUS | 账户验证邮件"; //邮件标题
        
        $this->assign("uname", $userInfo['uname']);
        $this->assign("target", $validData["target_url"]);
        $content = $this->fetch("checkMailContent");

        $mail->Body = $content; //邮件内容
        if(!$mail->Send()){
            return false;
        } else {
            //实例化验证数据库  xt_verify
            $tbValidation = M("validation");
            $tbValidation->add($validData);
            return true;
        }
    }

    //产生xiaoplus账号
    protected  function getXiaoplusId (){
       $tbUser = M("user");
       $xiaoPlus = "";
       do {
           //验证帐号是否存在
           $xiaoPlus = rand(1000000, 99999999);
           $isExsit = $tbUser->where("xiaoplus = '$xiaoPlus'")->find();
       } while(!!$isExsit);

       return $xiaoPlus;
    }

    //用户注册成功后立即创建一个用户的文件夹
    protected function getFolder($xiaoplus){
        $pathStr = "data/user/".$xiaoplus;
        if ( strrchr( $pathStr , "/" ) != "/" ) {
            $pathStr .= "/";
        }
        if ( !file_exists( $pathStr ) ) {
            if ( !mkdir( $pathStr , 0777 , true ) ) {
                return false;
            }
        }
        return $pathStr."/";
    }
}

?>

