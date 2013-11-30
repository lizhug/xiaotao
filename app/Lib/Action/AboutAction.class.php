<?php

class AboutAction extends GlobalAction {

    public function suggestion(){
                //更改物品发布学校
                $tb = M("pub");
		$data = $tb->field("school, pub_id")->select();
		for ($i = 0; $i != count($data); $i++) {
			if ($data[$i]['school'] == 1) {
                            $data[$i]['school'] = 100;
                        } 
			$uid = $data[$i]['pub_id'];
			$tb->where("pub_id = $uid")->save($data[$i]);
		}
        
		//更改头像
		/*$tb = M("user");
		$data = $tb->field("head, uid")->select();
		for ($i = 0; $i != count($data); $i++) {
			$data[$i]['head'] = str_replace("Data/user/", "data/user/", $data[$i]['head']);
			$uid = $data[$i]['uid'];
			$tb->where("uid = $uid")->save($data[$i]);
		}*/
		
		//更改上传图
		/*$tb = M("pub");
		$data = $tb->field("image_data, pub_id")->select();
		
		for ($i = 0; $i != count($data); $i++) {
			$data[$i]['image_data'] = str_replace("Data/", "data/", $data[$i]['image_data']);
			$pub_id = $data[$i]['pub_id'];
			$tb->where("pub_id = $pub_id")->save($data[$i]);
		}*/
                
        
        //json加密
            /*    $tb = M("pub");
		$data = $tb->field("image_data, pub_id")->select();
		set_time_limit(0);
                
                $dataLength = count($data);
		for ($i = 0; $i != $dataLength; $i++) {
			//$data[$i]['image_data'] = $data[$i]['image_data'];
                       // var_dump($data[$i]["image_data"]);
                        //这边单独的图片一栏
                        $tmp = explode("\"", $data[$i]["image_data"]);
                        $tmpArray = Array();
                       // var_dump($tmp);
                        $tmpLength = count($tmp);
                        for ($j = 0; $j != $tmpLength; $j++){
                            if (strlen($tmp[$j]) > 3){
                                $tmpArray[] =  $tmp[$j];
                            }
                        }

                        $data[$i]["image_data"] = json_encode($tmpArray);
 
                        var_dump($data[$i]['image_data']);
			$pub_id = $data[$i]['pub_id'];
			$tb->where("pub_id = $pub_id")->save($data[$i]);
		}
              */  
                //更改\
		/*
                $tb = M("pub");
		$data = $tb->field("image_data, pub_id")->select();
		
		for ($i = 0; $i != count($data); $i++) {
			$data[$i]['image_data'] = str_replace("\\", "", $data[$i]['image_data']);
			$pub_id = $data[$i]['pub_id'];
			$tb->where("pub_id = $pub_id")->save($data[$i]);
		}
                 */
		
		//更改详情中的图
		/*$tb = M("pub");
		$data = $tb->field("content, pub_id")->select();
		
		for ($i = 0; $i != count($data); $i++) {
			$data[$i]['content'] = str_replace("/Data/thumb/", "/data/thumb/", $data[$i]['content']);
			$pub_id = $data[$i]['pub_id'];
			$tb->where("pub_id = $pub_id")->save($data[$i]);
		}*/
	
    	/*$tb = M("user_profile");
		$data = $tb->select();
	
		foreach ($data as $key => $value) {
			$temp = json_decode($value['data'], true);
			$temp['college'] = 100;
			$temp['major'] = "";
			$value['data'] = json_encode($temp);
			$uid = $value['uid'];
			$tb->where("uid = $uid")->save($value);
		}*/
		//$dd = "ddd我'";
		//echo $dd;
		//echo addslashes(strip_tags($dd));
		
		/*$tb = M("user");
		$data['admin_level'] = 1;
		$tb->where("email = '9812263@qq.com'")->save($data);
		var_dump($tb->where("email = 'a1098035@qq.com'")->select());
		*/
		
		//$tbSchool->query("show databases");
		//$tbSchool = M();
		//$dd['sid'] = 0;
		//$ttt = $tbSchool->where("school_id = 1")->save($dd);
	/*	
		$d\dd = $tbSchool->query("CREATE TABLE `xt_feedback` (
			`id` int(11) NOT NULL auto_increment,
			`uid` int(11),
			`email` varchar(255) NOT NULL,
			`content` text,
			`ctime` int(11),
			PRIMARY KEY  (`id`)
			);");
			
		var_dump($ddd);
			$tb = M("feedback");
		
		
		$data = $tb->select();
		var_dump($data);*/
	
		//$tbSchool = M("xt_web_config");
		
		//$dd = $tbSchool->select();
		
		//var_dump($dd);
		//$tbSchool->query("insert into `xt_web_config` (`id`, `description`, `keywords`, `uid`, `ctime`) values (1, '21212', '212', 1, 11)");
		/*
		$tbSchool = M();
		$eee = $tbSchool->query("CREATE TABLE `xt_pub_glance_logs` (
			`id` int(11) NOT NULL auto_increment,
			`uid` int(11),
			`pid` int(11),
			`ctime` int(11),
			 PRIMARY KEY (`id`)
			);"
		);
		$ddd = $tbSchool->query("CREATE TABLE `xt_user_follow` (
			`follow_id` int(11) NOT NULL auto_increment,
			`uid` int(11),
			`fid` int(11),
			`remark` varchar(255),
			`ctime` int(11),
			 PRIMARY KEY (`follow_id`)
			);"
		);
		
		echo $eee;
		echo $ddd;*/
		//$tbPub = M("user");

		//$dd = $tbPub->where("uid = 312")->select();

		//var_dump($dd);
        //$tbPub->query("alter table `xt_feedback` add isdel tinyint(1) not null");
		//$tbPub->query("alter table `xt_feedback` add isreply tinyint(1) not null");
		//var_dump($tbPub->select());
			
		//var_dump($tbSchool->query("insert into `xt_web_config` (`description`, `keywords`, `uid`, `ctime`) values ('fsf', 'fds', 11, 212211212)"));
		
		//$tb = M("user_open");
		//$data = $tb->select();
		//var_dump($tbSchool->select());
		
		//echo $ttt;
		//print_r($data); 
		//$pubData = $tb->query("select * from xt_pub right join xt_user on xt_pub.uid = xt_user.uid and xt_pub.pub_id = 1") or die(mysql_error());
		//print_r($pubData);
       // $tbPub->query("ALTER TABLE `xt_pub` ADD `is_complete` TINYINT(1) NOT NULL");
        //echo connection_status();
		//$tbPub = M();
        //$tbPub->query("alter table `xt_pub` drop isbuy");
        //$tbPub->query("alter table `xt_pub` add isbuy tinyint(1) not null default 0");
		//$tb = M("pub");
		//$data['isbuy'] = 1;
		//var_dump($tb->where("isbuy = 1")->select());
		//phpinfo();
		//$this->display();
		
		//phpinfo();
		
		//import('ORG.Net.IpLocation');// 导入IpLocation类

        //$Ip = new IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件

        //$ipinfo = $Ip->getlocation();
		
		//var_dump($ipinfo);
    }
}


?>
