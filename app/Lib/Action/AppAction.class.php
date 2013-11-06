<?php

//移动端接口类
class AppAction {
	
	// 获得所有一级类别
	public function getType() {
		$tbType = M("type");
		$data = $tbType->where("pid = 0 AND isdel = 0")->field("type_id, type, pid")->select();
		return json_encode($data);
	}

	//返回所有二级类别
	public function getSubType() {
		$tbType = M("type");
		$data = $tbType->where("pid != 0 AND isdel = 0")->field("type_id, type, pid")->select();
		return json_encode($data);
	}

	//根据id获取商品详情
	public function getDetail() {
		$id = intval($_GET['pid']);			//获取物品pub_id

		$tbPub = M("pub");

		$data = $tbPub->where("pub_id = $id")->find();				//获取物品

		$data["image_data"] = $this->formatImageData($data["image_data"]);		//图片格式转换

		return json_encode($data);
	}

	//根据物品id获取发布人信息
	public function getUserDataFromDeal() {
		$id = intval($_GET['pid']);

		$tbPub = M("pub");
		$userIdArray = $tbPub->where("pub_id = $id")->field("uid")->find();
		
		$uid = $userIdArray['uid'];						//用户的uid

		$tbUser = M("user");
		$userData = $tbUser->where("uid = $uid")->field("uid, xiaoplus, email, uname, head, sex, birth, admin_level, province, city, area, ctime, isdel")->find();
		
		return $userData;
	}

	//发布物品
	public function insertDeal(){

        $_POST["ctime"] = time();
        $_POST["uid"] = session("uid");
        
        $cookieData = json_decode($_COOKIE['xp_area'], true);
        
        $_POST['province'] = $cookieData['proId'];
      
        $_POST["image_data"] = json_encode($_POST["image_data"]);

        if ($_POST["image_data"] == "null"){
            $_POST["image_data"] = json_encode(array("data/uploads/default.png"));
        }

        $tbPubVerify = D("pub");
        if ($tbPubVerify->create($_POST, 1)){
            $pubId = $tbPubVerify->add();
            return $pubId;
        } else {
            return false;
        }
    }

    //ajax方式获取物品
    public function getDataAjax() {

            $start = $_GET['p'];
            $areaId = session("areaId") || 1924;
            $typeId = isset($_GET['type']) ? intval(htmlentities($_GET['type'])) : 0;
            $subTypeId = isset($_GET['sub']) ? intval(htmlentities($_GET['sub'])) : 0;
            $schoolId = session("schoolId");
            $lowPrice = isset($_GET['low']) ? intval(htmlentities($_GET['low'])) : 0;
            $highPrice = isset($_GET['high']) ? intval(htmlentities($_GET['high'])) : 20000;
            $order = isset($_GET['order']) ? intval(htmlentities($_GET['order'])) : 0;
            $itmType = isset($_GET['buy']) ? intval(htmlentities($_GET['buy'])) : 0;
            $saleType = isset($_GET['by']) ? intval(htmlentities($_GET['by'])) : 0;
           
            $tbPub = M("pub");

            $strSql = "city = $areaId";

            //大类
            if ($typeId != 0){
                $strSql .= " AND type = $typeId";               
            }

            //小类
            if ($subTypeId != 0){
                $strSql .= " AND sub_type = $subTypeId";           
            } 

            //学校
            if (isset($_COOKIE['xt_school_only']) || $schoolId != 0) {
                if ($_COOKIE['xt_school_only'] == 1) {
                    $strSql .= " AND school = $schoolId";  
                }
            }

            //交易类型 0 全部  1 出售  2 求购
            if ($itmType == 1) {
                $strSql .= " AND isbuy = 0";
            } else if ($itmType == 2) {
                $strSql .= " AND isbuy = 1";
            }

             $strSql .= " AND price BETWEEN $lowPrice AND $highPrice AND isdel = 0";

             //排序 0  默认时间    1  时间   2 价格
            if ($order == 2) {
                $strSql .= " ORDER BY CONVERT(price,SIGNED) DESC";
            } else {
                $strSql .= " ORDER BY ctime DESC";
            }

           
            $tbSchool = M("school");

            $pubData = $tbPub->where($strSql)->limit($start * 30, 30)->select();

            for($i = 0; $i != count($pubData); $i++){
                 $tmp = split("\"", $pubData[$i]["image_data"]);
                 //$pubData[$i]["image_data"] = $tmp[1];
				 //改成缩略图输出
				 $splitTmp = split("/", $tmp[1]);
				 $splitTmpLength = count($splitTmp) - 1;
				 
				 //验证是不是原图片
				 if (!preg_match("/default/i", $splitTmp[$splitTmpLength])) {
					 $lastStr = array_pop($splitTmp);
					 array_push($splitTmp, "m_".$lastStr);         
				 }
				 
				 
				 $pubData[$i]["image_data"] = implode("/", $splitTmp);
				 
				 $pubData[$i]['content'] = strip_tags($pubData[$i]['content']);
				 
                 $pubData[$i]['ctime'] = date("m月d日", $pubData[$i]['ctime']);
                 $pubData[$i]['school'] = getSchool($pubData[$i]['school']);

                 $k = 1;
                 foreach($tmp as $j){
                     if ($j == ","){
                         $k++;
                     }
                 }
        
                 $pubData[$i]["sum"] = $k;
            }

            return json_encode($pubData);
    }


    // 物品图片上传
    public function _upload() {
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
       
        $upload->maxSize = 3292200;				//设置上传文件大小
		$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');				//设置上传文件类型
        $upload->savePath = $this->getFolder();				//设置附件上传目录
        $upload->thumb = true;							//设置需要生成缩略图，仅对图像文件有效
        $upload->imageClassPath = 'ORG.Util.Image';				// 设置引用图片类库包路径
        $upload->thumbPrefix = 'm_';  //生产1张缩略图

        $upload->thumbMaxWidth = '400,100';				//设置缩略图最大宽度
		$upload->thumbMaxHeight = '400,100';				//设置缩略图最大高度
        $upload->saveRule = uniqid;						//设置上传文件规则
        
        //删除原图
        $upload->thumbRemoveOrigin = false;

        if (!$upload->upload()) {
            $this->error($upload->getErrorMsg());				//捕获上传异常
        } else {
            $uploadList = $upload->getUploadFileInfo();				//取得成功上传的文件信息
            import("ORG.Util.Image");   
            //给m_缩略图添加水印, Image::water('原文件名','水印图片地址')   
            //Image::water($uploadList[0]['savepath'].$uploadList[0]['savename'], 'Data/uploads/water.png'); 
        }

        return json_encode($uploadList[0]);
    }


	//物品图片的处理函数、把数组的存储方式转化为正常的格式
	protected function formatImageData($data) {

	    //这边单独的图片一栏
        $tmp = explode("\"", $data);
        $tmpArray = Array();
        for ($i = 0; $i != count($tmp); $i++){
            if (strlen($tmp[$i]) > 3){
                $tmpArray[] =  $tmp[$i];
            }
        }

		for ($i = count($tmpArray) - 1; $i >= 0 ; $i--) {
			$imgTmp = $tmpArray[$i];

		 	if (!preg_match("/default/i", $imgTmp)) {
		 		$splitTmp = explode("/", $imgTmp);
		 		$splitTmpLength = count($splitTmp) - 1;
				$lastStr = array_pop($splitTmp);
				array_push($splitTmp, "m_".$lastStr);         
		 	}	

		 	$tmpArray[$i] = implode("/", $splitTmp);	
		}
		 return $tmpArray;

	}
}

?>