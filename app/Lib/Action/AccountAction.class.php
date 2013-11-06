<?php

class AccountAction extends GlobalAction {
    
/************************************更改头像*******************************************************/
    public function index() {
        $this->redirect("info");
    }
    
    public function header(){
        if (!session("uid")){
            $this->display("Public:login_notify_error");
            return;
        }
        $this->getInitHead();
        $this->display();
    }
	
    //用户头像初始化函数
    private function getInitHead (){
        $uid = session("uid");
        $tbUser = M("user");
        $imgPath = $tbUser->where("uid = '$uid'")->getField("head");            //用户头像初始化
        $this->assign("headInit", $imgPath);                    //相对路径的头像路径
    }

    //头像截取函数
    public function cropImg (){
        if (!session("uid")){
            //$this->display("Public:login_notify_error");
            return;
        }

        $uid = session("uid");
        $selection = $_POST["selection"];
        $imgPath = $_POST["imgPath"];
         
        if ($selection == "" || $imgPath == ""){
            $returnData["msg"] = "上传文件错误，请检查您的文件";
            $this->ajaxReturn($returnData);
        }
        
         import('ORG.Util.ThinkImage');          //引入图片处理库
         $img = new ThinkImage(THINKIMAGE_GD, $imgPath);
         
         $scale = $img->width() / 500;
         $flag = $img->crop($selection["width"] * $scale, $selection["height"] * $scale, $selection["x1"] * $scale, $selection["y1"] * $scale, $selection["width"] * $scale, $selection["height"] * $scale)->save($imgPath);
         
         $tbUser = M("user");
         $tbUser->where("uid = '$uid'")->save(array("head"=> $imgPath));
            
         session("user-img", $imgPath);  //因为用户头像在全站是以session存的 所以得更新
         $this->ajaxReturn($flag);
    }

    //前台上传头像
    public function uploadHeader(){
        if (!session("uid")){
            //$this->error("请您登陆后再访问个人信息页面");
            return;
        }
        
        if (empty($_FILES)) {
            $this->error("您没有上传图片文件!");
        }
        
        $imgInfo = $this->_upload(session("xiaoplus"));
        $this->ajaxReturn($imgInfo);
    }

    // 文件上传
    protected function _upload($xiaoplus) {
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        $upload->maxSize = 3292200;          //设置上传文件大小   3M左右
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg,bmp');      //设置上传文件类型
        $upload->savePath = "data/user/".$xiaoplus."/";             //设置附件上传目录
        $upload->thumb = false;                     //设置需要生成缩略图，仅对图像文件有效
        $upload->imageClassPath = 'ORG.Util.Image';              // 设置引用图片类库包路径
        $upload->saveRule = uniqid;             //设置上传文件规则
        $upload->thumbRemoveOrigin = false;             //删除原图
        
        if (!$upload->upload()) {
            $this->error($upload->getErrorMsg());           //捕获上传异常
        } else {
            $uploadList = $upload->getUploadFileInfo();             //取得成功上传的文件信息
        }
        return $uploadList[0];
    }

/*************************************找回密码****************************************************/

    public function password (){
        if(!!isset($_GET['password_forget'])) {
            $string = htmlspecialchars($_GET['password_forget']);
            $uid = $_GET['u'];
            $tbValidation = M("validation");
            if (!!$tbValidation->where("uid = $uid AND '$string' = code AND is_active = 0")->find()) {
                $tbValidation->is_active = 1;
                $tbValidation->save();
                session("myuid", $uid);
                $this->display("Account:newPassword");
                return;
            }
        }
        $this->display();
    }
    
    //设置新密码的页面
    public function newPassword() {
        $this->display();
    }

    //设置新密码
    public function setNewPwd () {
        $newPwd = $_POST["pwd"];
        $uid = session("myuid");
        $tbUser = M("user");
        if (!!$tbUser->where("uid = '$uid'")->find()){
            $tbUser->password = md5($newPwd);
            $tbUser->save();
            session("myuid", null);
            $returnData["isFit"] = true;
            $returnData["msg"] = "密码修改成功";
            $this->ajaxReturn($returnData);
        } else {
            $returnData["isFit"] = false;
            $returnData["msg"] = "修改密码失败!";
            $this->ajaxReturn($returnData);
        }
    }

    //忘记密码
    public function forgetPwd() {
        $email = $_POST['email'];
        $tbUser = M("user");
        $userData = $tbUser->where("email = '$email'")->find();
        if (!!$userData){
            if ($this->__sentValidMail($userData)){
                $returnData["flag"] = true;
                $returnData["msg"] = "邮件已发送，请及时进入您的邮箱修改密码。如果您未收到邮件，请查看邮件是否在垃圾箱。";
                $this->ajaxReturn($returnData);
            } else {
                $returnData["flag"] = false;
                $returnData["msg"] = "邮件发送失败，请联系管理员.";
                $this->ajaxReturn($returnData);
            }
        } else {
            $returnData["flag"] = false;
            $returnData["msg"] = "该邮箱还未注册，请检查您的输入是否有问题";
            $this->ajaxReturn($returnData);
        }
    }

    protected function __sentValidMail($userInfo){
        Vendor("phpMail.phpmailer");
        $mail = new PHPMailer(); //建立邮件发送类
        $address = $userInfo["email"];
        $verifyCode = md5(time());
        $validData['uid'] = $userInfo['uid'];
        $validData["type"] = "password_forget";
        $validData["is_active"] = 0;
        $validData["code"] = $verifyCode;
        $validData["ctime"] = time();
        $validData["target_url"] = $_SERVER["HTTP_REFERER"]."?password_forget=".$validData["code"]."&u=".$userInfo['uid'];
  
        $mail->IsSMTP(); // 使用SMTP方式发送
        $mail->CharSet = "utf-8";
        $mail->Host = C("EMAIL_ADDR"); // 您的企业邮局域名
        $mail->SMTPAuth = true; // 启用SMTP验证功能
        $mail->Username = C("EMAIL_USER"); // 邮局用户名(请填写完整的email地址)
        $mail->Password = C("EMAIL_PWD"); // 邮局密码
        $mail->From =  C("EMAIL_USER"); //邮件发送者email地址
        $mail->FromName = C("FROM_NAME");   //来源名字
        $mail->AddAddress("$address", $userInfo['uname']);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
        $mail->IsHTML(); // set email format to HTML //是否使用HTML格式
        $mail->Subject = "校PLUS | 账号密码找回"; //邮件标题
        
        $this->assign("target", $validData["target_url"]);
        $this->assign("uname", $userInfo['uname']);
        $content = $this->fetch("findPasswordMail");
        
        $mail->Body = $content;
        if(!$mail->Send()){
           // $this->ajaxReturn($mail->ErrorInfo);
            return false;
        } else {
           //实例化验证数据库  xt_verify
            $tbValidation = M("validation");
            $tbValidation->add($validData);
            return true;
        }

    }



/***********************************************密码修改页面*******************************************************/


    //修改密码的页面display
    public function changePassword (){
        if (!session("uid")){
            $this->display("Public:login_notify_error");
            return;
        }
        $this->display();
    }

    //修改密码

    public function changePwd (){
        if (!session("uid")){
            //$this->error("请您登陆后再访问个人信息页面");
            return;
        }
		
        $uid = session("uid");              //获取用户id
        $returnData["isFit"] = false;

        if (!checkVerifyCode()){
            $returnData["msg"] = "验证码错误";
            $this->ajaxReturn($returnData);
            return;
        }
		
        $oldPwd = md5($_POST["oldPwd"]);
        $newPwd = $_POST["newPwd"];
        $tbUser = M("user");
		
        if (!!$tbUser->where("uid = '$uid' AND password = '$oldPwd'")->find()){
            $tbUser->password = md5($newPwd);
            $tbUser->save();
            $returnData["isFit"] = true;
            $returnData["msg"] = "密码修改成功";
            $this->ajaxReturn($returnData);
        } else {
            $returnData["msg"] = "旧密码错误, 请重新输入!";
            $this->ajaxReturn($returnData);
        }
    }

    /** 个人资料页*/
    public function info (){
        if (!session("uid")){
            $this->display("Public:login_notify_error");
            return;
        }
        
        //输出用户
        $uid = session("uid");
        $tbUser = M("user");
        $userData = $tbUser->where("uid = '$uid'")->find();
        $userData['birth'] = explode("-", $userData['birth']);
        $this->assign("data",$userData);                //用户数据
        
        //输出地点
        $tbArea = M("area");
        $areaData = $tbArea->where("area")->where("pid = 0")->select();
        $this->assign("area", $areaData);
        $tbUserProfile = M("user_profile");
        $profileData = $tbUserProfile->where("uid = $uid")->find();
        $profileData['data'] = json_decode($profileData['data'], true);
        $this->assign("profile", $profileData);
        
        $this->showProvinceList();
        $this->showCityList($userData['province']);
        $this->showAreaList($userData['city']);
      
        $this->display();
    }
    
    //省份列表
    public function showProvinceList(){
        $tbArea = M("area");
        $areaData = $tbArea->where("area")->where("pid = 0")->select();
        $this->assign("area", $areaData);
    }
    
    //城市列表
    public function showCityList($proId) {
        $tbArea = M("area");
        $cityData = $tbArea->where("pid = $proId")->select();
        $this->assign("cityList", $cityData);
    }
    
    
    
    //返回地区列表
    public function showAreaList($cityId) {
        $tbArea = M("area");
        $areaData = $tbArea->where("pid = $cityId")->select();
        $this->assign("areaList", $areaData);
    }
    
    public function getAllArea() {
        $pid = $_REQUEST["pid"];
        $tbArea = M("area");
        $areaData = $tbArea->where("pid = '$pid'")->select();
        $this->ajaxReturn($areaData);
    }

    public function infoSave(){
        if (!session("uid")){
            return;
        }
        $uid = session("uid");
        $returnData["isFit"] = false;
        $_POST["birth"] = $_POST["b-year"]."-". $_POST["b-month"]."-". $_POST["b-day"];
        $uname = addslashes($_POST['uname']);
        $tbUser = M("user");

        if ($tbUser->where("uname = '$uname' AND uid != $uid")->find()) {
            $returnData["msg"] = "用户名已经存在!";
            $this->ajaxReturn($returnData);
        }

        $tbUser->where("uid = $uid")->save($_POST);
        $tbUserProfile = M("user_profile");
        
        $data["qq"] = $_POST["qq"];
        $data["college"] = intval($_POST["college"]);
        $data["year"] = intval($_POST["year"]);
        $tbUserProfile->where("uid = '$uid'")->save(array("data" =>  json_encode($data)));
        $returnData["isFit"] = true;
        $returnData["msg"] = "修改成功";
        $this->ajaxReturn($returnData);
    }
    
    
    //邮箱绑定
    public function bindEmail() {  
        if (!session("uid")) {
           // return;//$this->error();
        }
       
        $uid = session("uid");
        if (isset($_GET['bind_email'])) {
            $string = $_GET["bind_email"];
            $tbValidation = M("validation");
            if (!!$tbValidation->where("code = '$string' and is_active = 0")->find()) {
                $tbValidation->is_active = 1;
                $tbValidation->save();
                $email = session("mail");
                $tbUser = M("user");
                $tbUser->where("uid = $uid")->find();
                $tbUser->email = $email;
                session("mail", null);
                session("email", $email);
                $tbUser->save();
            } 
            $this->redirect("Account:info");
        }
        $mail = $_POST['mail'];
        $tbUser = M("user");
        $returnData['flag'] = false;
        //检查邮箱是否已经被绑定
        if (!!$tbUser->where("email = '$mail'")->find()) {
            $returnData['msg'] = "您的邮箱已经被绑定!";
            $this->ajaxReturn($returnData);
        }
        
        //
        $userInfo = $tbUser->where("uid = $uid")->find();
        session("mail", $mail);
        if ($this->sentBindMail($userInfo)) {
            $returnData['flag'] = true;
            $returnData['msg'] = "验证邮件已发送!请检查您的邮箱!";
        } else {
            $returnData['msg'] = "邮件发送失败, 请检查邮箱是否存在!";
        }
        $this->ajaxReturn($returnData);
    }
    
    protected function sentBindMail($userInfo){
        Vendor("phpMail.phpmailer");
        $uid = $userInfo['uid'];
        $verifyCode = md5($uid.time());
        $validData["type"] = "bind_email";
        $validData["uid"] = $userInfo['uid'];
        $validData["is_active"] = 0;
        $validData["code"] = $verifyCode;
        $validData["ctime"] = time();
        $validData["target_url"] = "http://t.xiaoplus.com/Account/bindEmail?bind_email=".$verifyCode;
        $mail = new PHPMailer(); //建立邮件发送类
        $mail->IsSMTP(); // 使用SMTP方式发送
        $mail->CharSet = "utf-8";
        //$mail->Port = 465;
        $mail->Host = C("EMAIL_ADDR"); // 您的企业邮局域名
        $mail->SMTPAuth = true; // 启用SMTP验证功能
        $mail->Username = C("EMAIL_USER"); // 邮局用户名(请填写完整的email地址)
        $mail->Password = C("EMAIL_PWD"); // 邮局密码
        $mail->From =  C("EMAIL_USER"); //邮件发送者email地址
        $mail->FromName = C("FROM_NAME");   //来源名字
        
        $mail->AddAddress(session("mail"), $userInfo['uname']);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
        $mail->IsHTML(true); // set email format to HTML //是否使用HTML格式
        $mail->Subject = "校PLUS | 邮箱绑定邮件"; //邮件标题

        $this->assign("uname", $userInfo['uname']);
        $this->assign("target", $validData["target_url"]);
        $this->assign("email", session("mail"));
        
        $content = $this->fetch("emailBindContent");

        $mail->Body = $content; //邮件内容
        if(!$mail->Send()){
            //$this->ajaxReturn($mail->ErrorInfo);
            return false;
        } else {
            //实例化验证数据库  xt_verify
            $tbValidation = M("validation");
            $tbValidation->add($validData);
            return true;
        }
    }
   
/*****************************************获取用户发布的信息*******************************/
    public function pub (){
         if (!session("uid")){
            $this->display("Public:login_notify_error");
            return;
        }

        $uid = session("uid");
        $this->getPublist($uid);
        $this->display();
    }







/****************************************获得用户收藏的信息******************************/

    public function store(){
         if (!session("uid")){
            $this->display("Public:login_notify_error");
            return;
        }
        $uid = session("uid");
        $this->getStore($uid);
        $this->display();
    }

    //获取我的收藏
    public function getStore($uid) {
        $tbStore = M();  
        $start = 0;
        if (isset($_POST['p'])) {
            $start = intVal($_POST['p']) * 5;
            $uid = intVal($_POST['uid']);
            $list = $tbStore->table("xt_store s, xt_pub p")->where("s.uid = '$uid' AND s.isdel = 0 AND p.isdel = 0 AND s.pid = p.pub_id")->order('s.ctime DESC')->limit($start, 5)->field("p.pub_id, p.title, p.type, p.sub_type, p.province, p.school, p.ctime")->select();

            for ($i = 0; $i < count($list); $i++){
                $list[$i]['province'] = getArea($list[$i]['province']);
                $list[$i]['school'] = getSchool($list[$i]['school']);
                $list[$i]['type'] = getPubType($list[$i]['type']); 
                $list[$i]['sub_type'] = getPubType($list[$i]['sub_type']);
                $list[$i]['ctime'] = date("Y年m月d日h点s分", $list[$i]['ctime']);
            }
            $this->ajaxReturn($list);
        } else {
            $list = $tbStore->table("xt_store s, xt_pub p")->where("s.uid = '$uid' AND s.isdel = 0 AND p.isdel = 0 AND s.pid = p.pub_id")->order('s.ctime DESC')->limit($start, 5)->field("p.pub_id, p.title, p.type, p.sub_type, p.city, p.school, p.ctime")->select();
            for ($i = 0; $i < count($list); $i++){
                $list[$i]['city'] = getArea($list[$i]['city']);
                $list[$i]['school'] = getSchool($list[$i]['school']);
                $list[$i]['type'] = getPubType($list[$i]['type']); 
                $list[$i]['sub_type'] = getPubType($list[$i]['sub_type']);
                $list[$i]['ctime'] = date("Y年m月d日h点s分", $list[$i]['ctime']);
            }
            $this->assign("data", $list);
        }
    }
    
    //收藏函数
    public function setStore(){
        $returnData["flag"] = false;
        if (!session("uid")){
            $returnData["msg"] = "请登陆后再进行收藏";
            $this->ajaxReturn($returnData);
        }
        $data["uid"] = session("uid");
        $data["pid"] = $_POST["id"];
        $tbStore = M("store");
        if (!!$tbStore->add($data)){
            $returnData["flag"] = true;
            $returnData["msg"] = "收藏成功!";
        } else {
            $returnData["msg"] = "收藏失败";
            $this->ajaxReturn($returnData);
        }
    }

/****************************************获取用户信息*******************************************/

    public function user(){
        if (!isset($_GET["u"])){
            $this->error();
        }
        $xiaoplus = intval(addslashes($_GET["u"]));
        //实例化user数据库
        $tbUser = M("user");
        $returnData = $tbUser->where("xiaoplus = '$xiaoplus'")->find();
        //用户不存在
        if (!$returnData) {
            $this->error();
            return;
        }

        $returnData['school'] = getSchool($returnData['school']);
        $returnData['sex'] = getSex($returnData['sex']);
        
        $uid = $returnData["uid"];
        $tbUserProfile = M("user_profile");
        $data = $tbUserProfile->where("uid = '$uid'")->find();

        $profile = json_decode($data['data'], true);

        $returnData['college'] = $profile['college'];
        $returnData['qq'] = $profile['qq'];
        $returnData['year'] = $profile->year;

        $this->assign("data", $returnData);
        $this->getPublist($uid);                //获取列表
        $this->display();
    }



 /*****************************根据获得的uid查取用户发布的信息*****************/

    public function getPublist($uid){
        $tbPub = M('pub');
        $start = 0;
        if (isset($_POST['p'])) {
            $start = intVal($_POST['p']) * 5;
            $uid = intVal($_POST['uid']);

            $list = $tbPub->where("uid = $uid AND isdel = 0")->order('ctime DESC')->limit($start, 5)->field("pub_id, title, type, sub_type, province, school, ctime, is_complete")->select();
            for ($i = 0; $i < count($list); $i++){
                $list[$i]['province'] = getArea($list[$i]['province']);
                $list[$i]['school'] = getSchool($list[$i]['school']);
                $list[$i]['type'] = getPubType($list[$i]['type']); 
                $list[$i]['sub_type'] = getPubType($list[$i]['sub_type']);
                $list[$i]['ctime'] = date("Y年m月d日h点s分", $list[$i]['ctime']);
            }       
            $this->ajaxReturn($list);
        } else {
            $list = $tbPub->where("uid = $uid AND isdel = 0")->order('ctime DESC')->limit($start, 5)->field("pub_id, title, type, sub_type, province, school, ctime, is_complete")->select();
            for ($i = 0; $i < count($list); $i++){
                $list[$i]['province'] = getArea($list[$i]['province']);
                $list[$i]['school'] = getSchool($list[$i]['school']);
                $list[$i]['type'] = getPubType($list[$i]['type']); 
                $list[$i]['sub_type'] = getPubType($list[$i]['sub_type']);
                $list[$i]['ctime'] = date("Y年m月d日h点s分", $list[$i]['ctime']);
            }        
            $this->assign('publist', $list);
        }
    }
 /*****************************根据获得的pub_id和is_complete来修改该信息的出售状态*****************/
    
    public function pubComplete(){
        if (!session("uid")){
            return;//$this->error("请您登陆后再进行此操作");
        }
        $pub_id = $_POST['pub_id'];
        $is_complete = $_POST['is_complete'];
        $uid = session("uid");
        $tbPub = M("pub");
        $condition = array( 'uid'=>$uid , 'pub_id'=>$pub_id);
        $data = array( 'is_complete'=> $is_complete );
        $result = $tbPub->where($condition)->save($data);
        if(!$result){
            $this->ajaxReturn(array('returnWord'=>'操作出错','returnCode'=>0));
        }
        else{
            $this->ajaxReturn(array('returnWord'=>'操作成功','returnCode'=>1));
        }
    }

      //用户退出
    public function loginout(){
        if (!session("uid")){
            return;
        }
        session_destroy();
        setcookie(session_name(),'',time()-3600);
        $_SESSION = array();
    }
}

?>