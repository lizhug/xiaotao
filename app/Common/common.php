<?php
/*************************自定义的函数************************************/

//获取性别
function getSex($value){
    if ($value == 0){
        return "男";
    } else if ($value == 1){
        return "女";
    } else {
        return "性别未知";
    }
}

//获取物品类型
function getPubType($value){
    $tbType = M("type");
    $type = $tbType->where("type_id = '$value'")->getField("type");
    return $type;
}

//获取地点名字
function getArea($value){
    $tbArea = M("area");
    $area = $tbArea->where("area_id = '$value'")->getField("title");
    return $area;
}

//获取学校名字
function getSchool($value){
    $tbSchool = M("school");
    $area = $tbSchool->where("school_id = '$value'")->getField("school");
    return $area;
}

//验证码
 function checkVerifyCode (){
    return $_SESSION['verify'] == md5($_POST["verify"]);
}

//标记完成状态的转换
function showIsComplete($value) {
	if ($value == 1) {
		return "已完成";
	} else {
		return "交易中";
	}
}



?>