<?php
// 本类由系统自动生成，仅供测试用途
class PubAction extends GlobalAction {
    public function index(){
        if (!session("uid")){
            $this->display("Public:login_notify_error");
            return;
        }
        $uid = session("uid");
        $tbUser = M("user");
        if (!$tbUser->where("uid = '$uid'")->getField("is_active")) {
            $this->display("Pub:alert");
            return;
        }

        //实例化xt_type数据库
        $tbType = M("type");
        $typeData = $tbType->where("pid = 0")->select();
        $this->assign('typeData', $typeData);
     
        $this->display();  
    }
    
    public function getSubType(){
        $typeId = isset($_POST["typeId"]) ? $_POST["typeId"] : 1;

        //实例化数据库
        $tbType = M("type");
        $subTypeData = $tbType->where("pid = '$typeId'")->select();
        $this->ajaxReturn($subTypeData);
    }
    /**
     * 按照日期自动创建存储文件夹
     * @return string
     */
    protected function getFolder()
    {
        $pathStr = "data/uploads/";
        if ( strrchr( $pathStr , "/" ) != "/" ) {
            $pathStr .= "/";
        }
        $pathStr .= date( "Ymd" );
        if ( !file_exists( $pathStr ) ) {
            if ( !mkdir( $pathStr , 0777 , true ) ) {
                return false;
            }
        }
        return $pathStr."/";
    }

    public function itmImgUpload() {
        if (!session("uid")){
            $this->error("请您登陆后再访问个人信息页面");
        }
        if (empty($_FILES)) {
            $this->error("您没有上传图片文件!");
        }
        $this->_upload();
    }

    // 文件上传
    protected function _upload() {        
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        $upload->maxSize = 4292200;
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg, bmp');          //设置上传文件类型
        $upload->savePath = $this->getFolder();         //设置附件上传目录          
        $upload->thumb = true;               //设置需要生成缩略图，仅对图像文件有效
        $upload->imageClassPath = 'ORG.Util.Image';          // 设置引用图片类库包路径
        $upload->thumbPrefix = 'm_';  //生产1张缩略图
        $upload->thumbMaxWidth = '400,100';             //设置缩略图最大宽度
        $upload->thumbMaxHeight = '400,100';            //设置缩略图最大高度
        $upload->saveRule = uniqid;                 //设置上传文件规则
        $upload->thumbRemoveOrigin = false;             //删除原图
        if (!$upload->upload()) {
            $this->error($upload->getErrorMsg());               //捕获上传异常
        } else {
            $uploadList = $upload->getUploadFileInfo();                 //取得成功上传的文件信息
            $imgPath = $uploadList[0]['savepath'].$uploadList[0]['savename'];
            import('ORG.Util.ThinkImage');          //引入图片处理库
            $img = new ThinkImage(THINKIMAGE_GD, $imgPath);
            $img->water("style/img/common/icon/water.png", THINKIMAGE_WATER_SOUTHEAST);
            $img->save($uploadList[0]['savepath'].$uploadList[0]['savename']);
        }
        $this->ajaxReturn($uploadList[0]);
    }

    public function setPubInsert(){
        C('TOKEN_ON',false);
        $_POST["ctime"] = time();
        $_POST["uid"] = session("uid");
        
        $cookieData = json_decode($_COOKIE['xp_area'], true);
        
        $_POST['province'] = $cookieData['proId'];
        $_POST["image_data"] = json_encode($_POST["image_data"]);

        if ($_POST["image_data"] == "null"){
            $_POST["image_data"] = json_encode(array(C("DEFAULT_ITEM_BG")));
        }

        $tbPubVerify = D("pub");
        if ($tbPubVerify->create($_POST, 1)){
            $pubId = $tbPubVerify->add();
            $this->ajaxReturn($pubId);
        } else {
            $this->ajaxReturn("您填写的信息有错误");
        }
    }

    //-----------------------------------修改我的发布信息----------------------------
    public function change() {
        if (!session("uid")){
            $this->display("Public:login_notify_error");
            return;
        }

        $id = isset($_GET['id']) ? $_GET['id'] : false;

        $condition = array(
            'pub_id' => $id,
            'uid' => session('uid')
        );

        $tbPub = M('pub');
        $pubData = $tbPub->where($condition)->find();

        if(!count($pubData)){
            $this->display("change_notify_error");
            return;
        }

        //根据大类获取小类
        $typeId = $pubData['type'];
        $tbType = M("type");
        $subType = $tbType->where("pid = $typeId")->select();
        $this->assign("subTypeList", $subType);

        //这边单独的图片一栏/输出图片
        $tmp = split("\"", $pubData["image_data"]);
        $tmpArray = Array();
        for ($i = 0; $i != count($tmp); $i++){
            if (strlen($tmp[$i]) > 3){
                $tmpArray[] =  $tmp[$i];
            }
        }

        $pubData["image_data"] = $tmpArray[0];
        $this->assign("image", $tmpArray);
             
        //输出内容
        $this->assign('pubData', $pubData);
        $this->display();
    }

    public function updatePub() {
        if (!session("uid")){
            return;
        }

        $condition = array(
            'pub_id' => $_POST['pub_id'],
            'uid' => session('uid')
        );
        
        //获取地址
       
        $data = array(
            'title' => $_POST['title'],
            'sub_type' => $_POST['sub_type'],
            'price' => $_POST['price'],
            'phone' => $_POST['phone'],
            'saler' => $_POST['saler'],
            'by' => $_POST['by'],
	    'school' => session("schoolId"),
            'isbuy' => $_POST['isbuy'],
            'ctime' => time(),
            'content' => $_POST['content']
        );
        $tbPub = M('pub');
        $result = $tbPub->where($condition)->data($data)->save();
        $this->ajaxReturn($result);
    }
}
?>