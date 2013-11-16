<?php

/***
	*这个类继承Action  其他类继承这个类
	*这个类相当于全局类  
***/

class GlobalAction extends Action {
        private $provinceId = "";             //省份
        private $cityId = "";                 //城市
        private $schoolId = "";             //学校id
        private $schoolName = "";           //学校名字
    
	public function __construct() {
            $this->sessionStart();
            $this->setSchoolCookie();
            $this->initSchool();
	        $this->assign("VERSION", C("APP_VERSION"));
	}
        
        public function initSchool() {
            if (isset($_COOKIE['xp_area'])) {
                $cookieData = json_decode($_COOKIE['xp_area'], true);
                $this->assign("schoolName", $cookieData['schoolName']);
                $this->assign("schoolId", $cookieData['schoolId']);
            } else {
                $this->assign("schoolName", $this->schoolName);
                $this->assign("schoolId", $this->schoolId);
            }
        }
        
        public function initArea($schoolId) {
            $tbSchool = M("school");
            $schoolId = $this->schoolId;

            //城市id  和  学校的名字
            $data = $tbSchool->where("school_id = $schoolId")->find();
            $this->proId = $data['province_id'];
            $this->schoolName = $data['school'];
            
            $this->provinceId = $data['province_id'];            
        }

	public function sessionStart() {
            session_name("xp_session_php");
            session_start();
        }

        //设置cookie
	public function setSchoolCookie() {
            if (isset($_POST['schoolId'])) {
          
                    $this->schoolId = $schoolId = intval(addslashes($_POST['schoolId']));  //学校id  必然存在
                    $this->initArea($schoolId);
                    
                    //cookie
                    $cookieStr = array(
                        "schoolId"=>$this->schoolId, 
                        "schoolName"=>$this->schoolName,
                        "proId"=>$this->provinceId
                    );
                    
                    setcookie("xp_area", json_encode($cookieStr), time() + 3600 * 24 * 365, "/", C("COOKIE_DOMAIN")); 
                    setcookie("xt_school_only", 0, time() + 3600 * 24 * 365, "/",  C("COOKIE_DOMAIN"));  
                    session("schoolId", $this->schoolId);
                    session("proId", $this->proId);

                    $this->ajaxReturn($_COOKIE["xp_area"]);
            } else {
                if (!isset($_COOKIE['xp_area'])) {
                    //什么都没有的话根据省份选择对应学校的第一个学校

                    import('ORG.Net.SinaIpLocation');//导入IpLocation类
                    $Ip = new SinaIpLocation(); //实例化类 参数表示IP地址库文件
                    
                    //获取某个IP地址所在的位置
                    $province = $Ip->getProvince();         //现在精度为省、之后扩展到市
                    $tbArea = M("area");
                    $areaIdArray = $tbArea->where("title LIKE `%$tbSchool%` AND pid = 0")->getField("area_id");

                    //省份id
                    $areaId = $areaIdArray[0];

                    $tbSchool = M("school");
                    $schoolData = $tbSchool->where("province_id = $areaId")->find();
                    $this->schoolId = $schoolData['school_id'];

                   $this->initArea($this->schoolId);
                   $cookieStr = array(
                        "schoolId"=>$this->schoolId, 
                        "schoolName"=>$this->schoolName,
                       // "cityId"=>$this->cityId,
                        "proId"=>$this->provinceId
                    );

                   setcookie("xp_area", json_encode($cookieStr), time() + 3600 * 24 * 365, "/",  C("COOKIE_DOMAIN"));
                   setcookie("xt_school_only", 0, time() + 3600 * 24 * 365, "/",  C("COOKIE_DOMAIN"));   
                    
                   session("schoolId", $this->schoolId);
                   session("proId", $this->proId);
                } else {
		 	$cookieData = json_decode($_COOKIE['xp_area'], true);
			$this->schoolId = $cookieData['schoolId'];
			$this->initArea($this->schoolId);
			$this->schoolName = $cookieData['schoolName'];
			session("schoolId", $this->schoolId);
                  	session("proId", $this->proId);	
		}

            }
            return true;
        }

	//学校cookie自定义
	public function aliasCookie($schoolId) {
	
		$this->schoolId = $schoolId;  
		$this->initArea($this->schoolId);

		$cookieStr = array(
			"schoolId"=>$this->schoolId, 
			"schoolName"=>$this->schoolName,
		//	"cityId"=>$this->cityId,
			"proId"=>$this->provinceId
		);

		$_COOKIE['xp_area'] = null;
		
		setcookie("xp_area", json_encode($cookieStr), time() + 3600 * 24 * 365, "/", ".xiaoplus.com");
		setcookie("xt_school_only", 0, time() + 3600 * 24 * 365, "/", ".xiaoplus.com");   

		session("schoolId", $this->schoolId);
		session("proId", $this->provinceId);

		$this->initSchool();
        }

	//可以使用memcache缓存省对应的学校
	public function getSchoolListByProvinceId() {
		$id = $_POST['id'];
		$tbSchool = M("school");
		$data = $tbSchool->where("province_id = $id")->field("alias, school, school_id")->select();
		$this->ajaxReturn($data);
	}
        
        public function showSchoolTable() {
            ob_start();
            $this->display("Public:schoolSelect");
            $content = file_get_contents();
            ob_end_flush();
            $this->ajaxReturn($content);
       }
}



?>
