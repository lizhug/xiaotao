<?php

//公用系统函数库

class PublicAction extends GlobalAction {

    /*****************************************验证码****************************/
    public function verifyCode(){
      
        // 导入Image类库
        import("ORG.Util.Image");
        Image::buildImageVerify();
    }

    //用户反馈
    public function feedbackSubmit() {
        $uid = 0;
        $email = 0;
        
        if (session('uid')) {
            $uid = session('uid');
        }
        
        if (session('email')) {
            $email = session('email');
        }
        $data = array(
            "id" => "",
            "uid" => $uid,
            "email" => $email,
            "content" => $_POST['content'],
            "ctime" => time()
         );

        $tbFeedback = M("feedback");
        $tbFeedback->add($data);	
        $data['ctime'] = date('Y-m-d h:i:s', $data['ctime']);
        $this->ajaxReturn($data,'',1); 
    }
    
    public function pageNotFound() {
        @header("http/1.1 404 not found");
        @header("status: 404 not found");
        $this->display();
    }  
}
?>
