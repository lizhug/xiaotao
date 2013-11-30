<?php

class EmptyAction extends GlobalAction {
	public function index() {
		$string = strtolower(str_replace("/", "", __URL__));
		
		/* 调用学校缓存
			在domain/sysu的时候从缓存中获取对应的id而不用从数据库中读取
		*/ 
		//$cache = Cache::getInstance("Memcache", array('expire'=>60));

		$tbSchool = M("school");
		$schoolData = $tbSchool->where("alias = '$string'")->find();
		if ($schoolData) {
			parent::aliasCookie($schoolData['school_id']);
			$this->getFreeTen();
                        $this->getBuyTen();
                        $this->getLastestTen();
			$this->display("Index:index");
		} else {
			$this->_empty();
		}
	}
	 /*
	*   get new top 10
    */
    public function getLastestTen(){
    	$tbPub = M("pub");
	
    	//返回最新前十的物品
	$proId = session("proId");
    	$data = $tbPub->where("isdel = 0 AND isbuy = 0 AND province = $proId")->order("ctime DESC")->limit(10)->field("pub_id, title, image_data, price, ctime")->select();
    	$this->formatImageData($data);
    	$this->assign("lastestTen", $data);
    }

    /*
	*   get 最受浏览最多 top 10
    */
    public function getScanTen(){
    	$tbPub = M("pub");

    	$proId = session("proId");
    	$data = $tbPub->where("isdel = 0 AND province = $proId")->order("scan DESC")->limit(10)->field("pub_id, title, image_data, price, ctime")->select();
    	$this->formatImageData($data);
    	$this->assign("scanTen", $data);
    }

    /*
	*   get 10个免费商品 top 10
    */
    public function getFreeTen(){
    	$tbPub = M("pub");
	$proId = session("proId");
    	$data = $tbPub->where("isdel = 0 AND province = $proId")->where("price = 0")->limit(10)->field("pub_id, title, image_data, price, ctime")->select();
    	$this->formatImageData($data);
    	$this->assign("freeTen", $data);
    }

	/*
	*   get 10个求购商品 top 10
    */
    public function getBuyTen(){
    	$tbPub = M("pub");
	$proId = session("proId");
    	$data = $tbPub->where("isdel = 0 AND isbuy = 1 AND province = $proId")->order("ctime DESC")->limit(10)->field("pub_id, title, image_data, price, ctime")->select();
    	$this->formatImageData($data);
    	$this->assign("buyTen", $data);
    }

	//物品图片的处理函数、把数组的存储方式转化为正常的格式
	protected function formatImageData(& $pubData) {
	  for($i = 0; $i != count($pubData); $i++){
	     $tmp = explode("\"", $pubData[$i]["image_data"]);
	     //$pubData[$i]["image_data"] = $tmp[1];
		 
		 //改成输出缩略图 m开头
		 $splitTmp = explode("/", $tmp[1]);
		 $splitTmpLength = count($splitTmp) - 1;
		 if (!preg_match("/default/i", $splitTmp[$splitTmpLength])) {
			 $lastStr = array_pop($splitTmp);
			 array_push($splitTmp, "m_".$lastStr);         
		 }
		 
		 $pubData[$i]["image_data"] = implode("/", $splitTmp);
	  }
	}


	public function _empty() {
                @header("http/1.1 404 not found");
		@header("status: 404 not found");
		$this->display("Public:pageNotFound");
       }
}

?>