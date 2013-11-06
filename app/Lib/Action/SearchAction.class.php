<?php

class SearchAction extends GlobalAction {
    /*
     * 页面初始化函数
     * 需要传入的变量会有：
     * 1、Search传过来的参数
     * 2、选择的城市的area_id
     * 3、选择的大类的type_id
     */
    public function index() {

        /*
         * 当只有一个参数的时候为 大类的编号
         * 当有两个参数的时候  第一个为大类的编号 
         * 当有三个参数的时候  第一个为大类的编号  第二个为小类的编号
         * 当有五个参数的时候  第一个为大类的编号  第二个为小类的编号 第三个为价格的下限   第四个为价格的上限
         */

        //系统默认初始化的数据
        // guang zhou  sun-Yet sun
    
        //定位学校、和城市
        if (!session("proId")) {     
            $cookieData = json_decode($_COOKIE['xp_area'], true);
            session("proId", $cookieData['proId']);                
            session("schoolId", $cookieData['schoolId']);                    //中大
        }
        
        if (isset($_GET["search"])){
            $this->getSearchResult();
        } else {
            $this->getData();
        }
      
        //获取数据并向模板输出
        $this->getSider(); 
        $this->getType();
        $this->getSubType();

        $this->getPriceList();
        $this->display(); 
    }

    //输出侧边栏浏览量前10名
    protected function getSider (){
        $tbPub = M("pub");
        $data = $tbPub->where("isdel = 0")->order("scan DESC")->limit(10)->select();

        for($i = 0; $i < count($data); $i++){
             $tmp = json_decode($data[$i]["image_data"], true);
             //$data[$i]["image_data"] = $tmp[1];
             $splitTmp = explode("/", $tmp[0]);
             $splitTmpLength = count($splitTmp) - 1;
             if (!preg_match("/default/i", $splitTmp[$splitTmpLength])) {
                 $lastStr = array_pop($splitTmp);
                 array_push($splitTmp, "m_".$lastStr);         
             }
             
             $data[$i]["image_data"] = implode("/", $splitTmp);
        }
       
        $this->assign("sider", $data);
    }

    //根据标题搜索、而不是根据内容搜索
    protected  function getSearchResult(){

        //处理字符串
        $keyWords = addslashes(strip_tags($_GET["search"]));
        $tbPub = M("pub");
        $pubData = array();

        // 仅对中文采用检索算法
        if (preg_match("/([\x81-\xfe][\x40-\xfe])/", $str, $match)) {
            $root = C('ROOT');
            require_once($root.'/Common/search/class.xtsearch.php');
            $xtSearch = new XTSearch();
            $result = $xtSearch->query($keyWords);


            foreach ($result['matches'] as $attr) {
                $item = $tbPub->where(array('pub_id'=>$attr['id']))->limit('0,30')->order("ctime DESC")->find();    
                if ($item != NULL)
                    array_push($pubData, $item);
            }
        }
        else {
            $pubData = $tbPub->where("title LIKE '%$keyWords%' and isdel = 0")->limit(0, 30)->order("ctime DESC")->select();
        }

        for($i = 0; $i != count($pubData); $i++){
             $tmp = json_decode($pubData[$i]["image_data"], true);
            
             $splitTmp = split("/", $tmp[0]);
             $splitTmpLength = count($splitTmp) - 1;
             if (!preg_match("/default/i", $splitTmp[$splitTmpLength])) {
                 $lastStr = array_pop($splitTmp);
                 array_push($splitTmp, "m_".$lastStr);         
             }
             
             $pubData[$i]["image_data"] = implode("/", $splitTmp);	 
             $pubData[$i]['content'] = strip_tags($pubData[$i]['content']);
             $pubData[$i]['school'] = getSchool($pubData[$i]['school']);
             
             $k = 1;
             foreach($tmp as $j){
                 if ($j == ","){
                     $k++;
                 }
             }
             $pubData[$i]["sum"] = $k;
        }	
        $this->assign("goods", $pubData);
    }

    //获取所有的大类
    protected function getType (){
        $tbType = M("type");
        $typeData = $tbType->where("pid = 0")->select();
        $this->assign('typeData', $typeData);
    }

    //如果根据传进来的大类选择相应的小类 默认大类为 0 全部
    protected function getSubType(){
        $typeId = isset($_GET['type']) ? intval($_GET['type']) : 0;             //强制类型转换

        if ($typeId != 0){
             $tbType = M("type");
             $subTypeData = $tbType->where("pid = '$typeId'")->select();
             $this->assign('subTypeData', $subTypeData);
        }
    }
    public function getDataAjax() {

            $start = $_POST['p'];
            $proId = session("proId");
            $typeId = isset($_POST['type']) ? intval(htmlentities($_POST['type'])) : 0;
            $subTypeId = isset($_POST['sub']) ? intval(htmlentities($_POST['sub'])) : 0;
            $schoolId = session("schoolId");
            $lowPrice = isset($_POST['low']) ? intval(htmlentities($_POST['low'])) : 0;
            $highPrice = isset($_POST['high']) ? intval(htmlentities($_POST['high'])) : 20000;
            $order = isset($_POST['order']) ? intval(htmlentities($_POST['order'])) : 0;
            $itmType = isset($_POST['buy']) ? intval(htmlentities($_POST['buy'])) : 0;
            $saleType = isset($_POST['by']) ? intval(htmlentities($_POST['by'])) : 0;
           
           //$this->ajaxReturn($_POST);

            $tbPub = M("pub");

            $strSql = "province = $proId";

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

            $pubData = $tbPub->where($strSql)->limit($start * 30, 30)->select();

            for($i = 0; $i != count($pubData); $i++){
                 $tmp = json_decode($pubData[$i]["image_data"], true);
                //改成缩略图输出
                $splitTmp = split("/", $tmp[0]);
                $splitTmpLength = count($splitTmp) - 1;

                //验证是不是原图片
                if (!preg_match("/default/i", $splitTmp[$splitTmpLength])) {
                        $lastStr = array_pop($splitTmp);
                        array_push($splitTmp, "m_".$lastStr);         
                }

                $pubData[$i]["image_data"] = implode("/", $splitTmp);
                $pubData[$i]['content'] = strip_tags($pubData[$i]['content']);      //在list中显示 去掉一切格式		 
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

            $this->ajaxReturn($pubData);
    }
    
    //输出对应城市、大类、小类、大学、价格获得对应的商品
    public function getData(){
   
        $proId = session("proId");
        $typeId = isset($_GET['type']) ? intval(htmlentities($_GET['type'])) : 0;
        $subTypeId = isset($_GET['sub']) ? intval(htmlentities($_GET['sub'])) : 0;
        $schoolId = session("schoolId");
        $lowPrice = isset($_GET['low']) ? intval(htmlentities($_GET['low'])) : 0;
        $highPrice = isset($_GET['high']) ? intval(htmlentities($_GET['high'])) : 20000;
        $order = isset($_GET['order']) ? intval(htmlentities($_GET['order'])) : 0;
        $itmType = isset($_GET['buy']) ? intval(htmlentities($_GET['buy'])) : 0;
        $saleType = isset($_GET['by']) ? intval(htmlentities($_GET['by'])) : 0;

        $tbPub = M("pub");
        $tbUser = M("user");
	
        $strSql = "province = $proId";

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

        $pubData = $tbPub->where($strSql)->limit(0, 30)->select();
        for($i = 0; $i != count($pubData); $i++){
            $tmp = json_decode($pubData[$i]["image_data"], true);
            
            //改成缩略图输出
            $splitTmp = explode("/", $tmp[0]);
             $splitTmpLength = count($splitTmp) - 1;
             if (!preg_match("/default/i", $splitTmp[$splitTmpLength])) {
                 $lastStr = array_pop($splitTmp);
                 array_push($splitTmp, "m_".$lastStr);         
             }
             
             
             $pubData[$i]["image_data"] = implode("/", $splitTmp);
             $pubData[$i]['content'] = strip_tags($pubData[$i]['content']);
             $pubData[$i]['school'] = getSchool($pubData[$i]['school']);

             $k = 1;
             foreach($tmp as $j){
                 if ($j == ","){
                     $k++;
                 }
             }

             //根据uid获取xiaoplus
             $uid = $pubData[$i]['uid'];
             $pubData[$i]['xiaoplus'] = $tbUser->where("uid = $uid")->limit(1)->getField("xiaoplus");

             $pubData[$i]["sum"] = $k;
        }
        
        $this->assign("goods", $pubData);
    }

    //输出价格区间
    protected function getPriceList(){
        $tbPriceList = M("price_list");
        $priceListData = $tbPriceList->where("pid <> 0")->select();
        $this->assign("priceListData", $priceListData);
    }
    
    public function changeSchoolOnlyCookie() {
        $checked = $_POST['checked'];
        
        if ($checked) {
            setcookie("xt_school_only", 1, time() + 3600 * 24 * 365, "/", C("COOKIE_DOMAIN"));  
        } else {
            setcookie("xt_school_only", 0, time() + 3600 * 24 * 365, "/", C("COOKIE_DOMAIN"));
        }
        
        $this->ajaxReturn($checked);
    }
}
?>
