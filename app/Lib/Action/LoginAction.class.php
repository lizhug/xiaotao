<?php



class LoginAction extends GlobalAction {

    //显示登录页面

    public function index(){
        $this->display();
    }

    public function getLogin(){
        date_default_timezone_set(PRC);
        $user = $_POST["user"];
        $pwd =  $_POST['pwd'];
        $autoLogin = $_POST['autoLogin'];
         //验证帐号和密码的规范性
        $isMail = preg_match("/^[a-zA-Z0-9-._]{1,50}@[a-zA-Z0-9-]{1,65}.(com|net|org|info|biz|([a-z]{2,3}.[a-z]{2}))$/i", $user);
        $isXiaoPlus = preg_match("/[1-9][0-9]{4,10}/", $user);
        $isPwd = preg_match("/^[a-z0-9_-]{6,18}$/i", $pwd);
        //其中一者不符合规范
        if (!$isPwd || (!$isMail && !$isXiaoPlus)){
            $this->ajaxReturn($returnData);
        }
        $this->ajaxReturn($this->_login($user, $pwd, $autoLogin));
    }

    protected function _login($user, $pwd, $autoLogin) {   
        $pwd = md5($pwd);   //md5加密
        $isMail = preg_match("/^[a-zA-Z0-9-._]{1,50}@[a-zA-Z0-9-]{1,65}.(com|net|org|info|biz|([a-z]{2,3}.[a-z]{2}))$/i", $user);
        //实例化数据库 xt_user
        $tbUser = M("user");
        $isFit = $isMail ? $tbUser->where("email = '$user' AND password = '$pwd' AND isdel = 0")->find()
                : $tbUser->where("xiaoplus = '$user' AND password = '$pwd' AND isdel = 0")->find();
        if ($isFit){
            $returnData["isFit"] = true;
            $this->setUserSession($isFit, $autoLogin);
            $returnData["msg"] = "登陆成功";
            $this->setLoginRecord($isFit["uid"]);
        } else {
            $returnData["isFit"] = false;
            $returnData["msg"] = "登陆失败";
        }
        return $returnData;
    }

    //登陆后记录信息
    protected function setLoginRecord($uid){
        //实例化数据库 xt_login_record
        $tbLoginRecord = M("login_record");
        import('ORG.Net.SinaIpLocation');// 导入IpLocation类
        $Ip = new SinaIpLocation(); // 实例化类 参数表示IP地址库文件
        $tmpData["uid"] = $uid;
        $tmpData["ip"] = $Ip->getIp();
        $tmpData['area'] = $Ip->getCountry()." ".$Ip->getProvince()." ".$Ip->getCity(); // 获取某个IP地址所在的位置
        $tmpData["ctime"] = time();
        $tbLoginRecord->add($tmpData);
    }


    //记录用户登录成功后的session信息和生命周期
    protected function setUserSession ($isFit, $autoLogin){
            session("xiaoplus", $isFit["xiaoplus"]);                        //xiaoplus账号
            session("uname", $isFit["uname"]);                                 //用户名
            session("uid", $isFit["uid"]);                                  //用户 uid
            session("email", $isFit["email"]);
            session("user-img", $isFit["head"]);
            session("admin_level", $isFit["admin_level"]);

            //用户设置自动登录的时候就设置cookie，周期为7天
            if ($autoLogin == "true"){
                $lifetime = 24 * 3600 * 7;
                setcookie(session_name(), session_id(), time() + $lifetime, "/",  C("COOKIE_DOMAIN"));
            }
    }

    //登录地址
    public function login($type = null){
        empty($type) && $this->error('参数错误');
        //加载ThinkOauth类并实例化一个对象
        import("ORG.ThinkSDK.ThinkOauth");
        $sns  = ThinkOauth::getInstance($type);
        //跳转到授权页面

        redirect($sns->getRequestCodeURL());
    }
	
    public function addUserInfo() {		
        //从session中获取内容
        $user_info = session("wb_user_info");
        $token = session("wb_token");

        $email = $_POST['email'];
        $password = md5($_POST['password']);
        //实例化数据库
        $tbUser = M("user");
        $returnData['flag'] = false;
        $msg = $tbUser->where("email = '$email'")->find();
        if (!!$msg){
            $returnData['msg'] = "该邮箱已经被注册!请重新输入一个邮箱!";
            $this->ajaxReturn($returnData);
        }
        
        $xiaoplus = $this->getXiaoplusId();
        $this->getFolder($xiaoplus);

        $headImg = $this->saveSNSHeadImg($user_info['head'], $xiaoplus);

        $tmpData = array("uname" => $user_info['nick'], "email" => $email, "password" => $password,
                "ctime"=>time(), "xiaoplus"=>$xiaoplus,
                 "head"=>$headImg, "is_active"=>1);

        $uid = $tbUser->add($tmpData);

        //用户的第三方TOKEN
        $tbUserOpen = M("user_open");

        $tbUserOpen->add(array("uid"=>$uid, "open_id"=>$token['openid'], "token"=>$token["access_token"], "from"=>$user_info['type']));
		
            //用户属性的表单
            $tbUserProfile = M("user_profile");
            $data["qq"] = "";
            $data["college"] = session("schoolId");
            $data["major"] = "";
            $data["year"] = "2010";
            $tbUserProfile->add(array("uid" => $uid, "data" =>  json_encode($data)));
            session("xiaoplus", $xiaoplus);                        //xiaoplus账号
            session("uname", $user_info['nick']);                                 //用户名
            session("uid", $uid);
            session("user-img", $headImg);
            session("weibo_access_token", $token["access_token"]);
            session("admin_level", $isFit["admin_level"]);
            session("wb_token", null);
            session("wb_user_info", null);
            $lifetime = 24 * 3600 * 7;
            setcookie(session_name(), session_id(), time() + $lifetime, "/",  C("COOKIE_DOMAIN"));
            if ($this->sendNotifyMail(array("email"=>$email, "uname" => $user_info['nick']))) {
                $returnData['flag']  = true;
                $returnData['msg'] = "注册成功!";
                $this->ajaxReturn($returnData);
            } else {
                $returnData['flag']  = true;
                $returnData['msg'] = "邮件发送失败!";
                $this->ajaxReturn($returnData);
            }   
	}
	
	/* 填写邮箱的页面 */
	public function addInfo() {
            $this->display();
	}
        
	//发送绑定成功后的邮件
	protected function sendNotifyMail($userInfo){
        Vendor("phpMail.phpmailer");
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
        $mail->Subject = "注册成功 | 校PLUS"; //邮件标题
        
        $this->assign("uname", $userInfo['uname']);
        $content = $this->fetch("notifyEmail"); 
       
        $mail->Body = $content; //邮件内容
       // $mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
        if(!$mail->Send()){
            //$this->ajaxReturn($mail->ErrorInfo);
            return false;
        } 
            return true;
    }
	
    //授权回调地址

    public function callback($type = null, $code = null){

        (empty($type) || empty($code)) && $this->error('参数错误');



        //加载ThinkOauth类并实例化一个对象

        import("ORG.ThinkSDK.ThinkOauth");

        $sns  = ThinkOauth::getInstance($type);


	   // var_dump($sns);
        //腾讯微博需传递的额外参数

        $extend = null;

        if($type == 'tencent'){
            $extend = array('openid' => $this->_get('openid'), 'openkey' => $this->_get('openkey'));
        }
        //请妥善保管这里获取到的Token信息，方便以后API调用

        //调用方法，实例化SDK对象的时候直接作为构造函数的第二个参数传入

        //如： $qq = ThinkOauth::getInstance('qq', $token);

        $token = $sns->getAccessToken($code , $extend);

        //获取当前登录用户信息

        if(is_array($token)){

            $sina_id = $token["openid"];

            $user_info = A('Type', 'Event')->$type($token);

            $tbUserOpen = M("user_open");

            $userdata = $tbUserOpen->where("open_id = '$sina_id'")->find();

            $tbUser = M("user");

           if (!!$userdata) {
		$uid = $userdata["uid"];

                $updateDate = array('access_token' => $token["access_token"]);   

                $tbUserOpen->where("uid = $uid")->save($updateData);            //更新access_token

                //user表更新数据
                $userInfo = $tbUser->where("uid = $uid")->find();

                $xiaoplus = $userInfo['xiaoplus'];

                //更新头像和微博用户名
                $headImg = $this->saveSNSHeadImg($user_info['head'], $xiaoplus);

                //要更新的数据
                $tmpData = array("uname" => $user_info['nick'], "head"=>$headImg);
	
                $uid = $tbUser->where("uid = $uid")->save($tmpData);

                session("xiaoplus", $xiaoplus);                        //xiaoplus账号

                session("uname", $userInfo["uname"]);                                 //用户名

                session("uid", $userInfo["uid"]);

                session("user-img", $headImg);

                session("weibo_access_token", $token["access_token"]);
				
	        session("admin_level", $isFit["admin_level"]);
               
                $lifetime = 24 * 3600 * 7;

                setcookie(session_name(), session_id(), time() + $lifetime, "/",  C("COOKIE_DOMAIN"));

                $this->redirect("Index/index");
            } else {
                //新用户需要重新定向
                session("wb_token", $token);
                session("wb_user_info", $user_info);
                $this->redirect("addInfo");
            }
        }
    }

    protected function saveSNSHeadImg($url, $xiaoplus) {
        $savePath = "data/user/".$xiaoplus."/";
        if (!file_exists($savePath)) {        
        //检查是否有该文件夹，如果没有就创建，并给予最高权限        
            mkdir("$path", 0777);        
        }        

        $filename = md5(date("YmdHis")).".jpg";

        ob_start(); 
        readfile($url); 
        $imgHead = ob_get_contents(); 
        ob_end_clean(); 
        
        $size = strlen($img);

        $fp2=@fopen($savePath.$filename, "a"); 
        fwrite($fp2, $imgHead); 
        fclose($fp2); 

        return $savePath.$filename; 
    }

    //产生xiaoplus账号
    protected  function getXiaoplusId (){
       $tbUser = M("user");
       $xiaoPlus = "";
       do {
           //验证帐号是否存在
           $xiaoPlus = rand(100000, 99999999);
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