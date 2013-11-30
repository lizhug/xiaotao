<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends GlobalAction {
    public function index(){
    	$this->getFreeTen();
    	$this->getBuyTen();
    	$this->getLastestTen();
        $this->display();
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
	//物品图片的处理函数、处理缩略图
	protected function formatImageData(& $pubData) {
            for($i = 0; $i != count($pubData); $i++){
                $tmp = json_decode($pubData[$i]["image_data"], true);           
                
                //如果第一张图不是默认的图 就在图最前面添加m_
                $splitTmp = explode("/", $tmp[0]);
                $splitTmpLength = count($splitTmp) - 1;
                if (!preg_match("/default/i", $splitTmp[$splitTmpLength])) {
                        $lastStr = array_pop($splitTmp);
                        array_push($splitTmp, "m_".$lastStr);         
                }
                
                $pubData[$i]["image_data"] = implode("/", $splitTmp);
            }
	}
}


?>