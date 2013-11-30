<?php

class WeixinAction extends Action{

	public function index() {
		$this->display();
	}
	
	//从微信端搜索的时候返回信息
	public function getWeixinSearchInfo() {
		$keyWords = trim($_GET['keywords']);
		//处理字符串
        $keyWords = addslashes(strip_tags($keyWords));

        $tbPub = M("pub");
        $pubData = $tbPub->where("title LIKE '%$keyWords%' and is_complete = 0")->limit(0, 5)->select();
		
        for($i = 0; $i != count($pubData); $i++){
            $returnInfo .= ($i + 1).". ";
			$returnInfo .= "【".$pubData[$i]['title']."】-";
            $returnInfo .= "【价格:".$pubData[$i]['price']."】-";
			$returnInfo .= "【交易地点:".getSchool($pubData[$i]['school'])."】-";
			$returnInfo .= "【联系人:".$pubData[$i]['saler']."】-";
			$returnInfo .= "【电话/QQ:".$pubData[$i]['phone']."】<br />";
        }
		echo $returnInfo;
	}


}

?>