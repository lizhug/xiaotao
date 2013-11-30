/***************************************************修改和删除用户****************************************************/
$(".userList-change").click(function() {
	var massage = new Array();
	$(this).parent().parent().find('input[type=text]').each(function() {
		massage.push($(this).val());
	});
	$.ajax({
		type:"post",
		url:URL + "/updateUser",
		data:{
			"uid":massage[0],
			"admin_level":massage[1],
			"uname":massage[2],
			"email":massage[3],
			"password":massage[4],
			"city":massage[5],
			"area":massage[6]
		},
		success:function(msg) {
			if(msg == false) {
				alert("修改失败");
			} else {
				window.location.reload();
			}
		},
		dataType:"json"
	});
});

$(".userList-delete").click(function() {
	var value = $(this);
	$.ajax({
		type:"post",
		url:APP + "/Admin/deleteUser",
		data:{
			"uid":$(this).attr("data-id")
		},
		success:function(msg) {
			if(msg == false) {
				alert("修改失败");
			} else {
				if(value.val()=="删除")
					value.attr("value","恢复");
				else
					value.attr("value","删除");
			}
		},
		dataType:"json"
	});
});

/*************************************************更改和删除出售信息****************************************************/
$(".pubList-change").click(function() {
	var massage = new Array();
	$(this).parent().parent().find('input[type=text]').each(function() {
		massage.push($(this).val());
	});
	$.ajax({
		type:"post",
		url:URL + "/updatePub",
		data:{
			"pub_id":massage[0],
			"uid":massage[1],
			"title":massage[2],
			"price":massage[4],
			"is_complete":massage[5]
		},
		success:function(msg) {
			if(msg == false) {
				alert("修改失败");
			} else {
				window.location.reload();
			}
		},
		dataType:"json"
	});
});

$(".pubList-delete").click(function() {
	var value=$(this);
	
	$.ajax({
		type:"post",
		url:APP + "/Admin/deletePub",
		data:{
			"pub_id":$(this).attr("data-id")
		},
		success:function(msg) {
			if(msg == false) {
				alert("修改失败");
			} else {
				if(value.val()=="删除") {
					value.attr("value","恢复");
				} else {
					value.attr("value","删除");
				}
			}
		},
		dataType:"json"
	});
});

/****************************************************修改网站关键词和描述**************************************************/

$(".web_config-submit").click(function() {
	$.ajax({
		type:"post",
		url: APP + "/Admin/updateWebConfig",
		data: {
			"description":$(".detail-description").val(),
			"keywords":$(".detail-keywords").val()
		},
		success:function(msg) {
			if(msg != false) {
				window.location.reload();
			} else {
				alert("修改失败");
			}
		},
		dataType: "text"
	});
});
/****************************************************修改学校和专业**************************************************/
$(".school-change").click(function() {
	var schoolInfo = new Array();
	$(this).parent().find('input[type=text]').each(function() {
		schoolInfo.push($(this).val());
	});
	$(this).parent().find('select').each(function() {
		schoolInfo.push($(this).val());
	});
	console.log(schoolInfo);
	$.ajax({
		type:"post",
		url: APP + "/Admin/updateSchool",
		data: {
			"school_id": schoolInfo[0],
			"school": schoolInfo[1],
			"area_id": schoolInfo[4]
		},
		success:function(msg) {
			console.log(msg);
			if(msg == true) {
				window.location.reload();
			} else {
				alert("修改失败");
			}
		},
		dataType: "text"
	});

});

$(".major-change").click(function() {
	var majorInfo = new Array();
	$(this).parent().find('input[type=text]').each(function() {
		majorInfo.push($(this).val());
	});
	console.log(majorInfo);
	$.ajax({
		type:"post",
		url: APP + "/Admin/updateMajor",
		data: {
			"major_id": majorInfo[0],
			"major": majorInfo[1]
		},
		success:function(msg) {
			console.log(msg);
			if(msg == true) {
				window.location.reload();
			} else {
				alert("修改失败");
			}
		},
		dataType: "text"
	});

});

/****************************************************添加和删除学校**************************************************/
$(".school-add").click(function() {
	var schoolInfo = new Array();
	schoolInfo.push($(this).parent().find('input[type=text]').val());
	$(this).parent().find('select').each(function() {
		schoolInfo.push($(this).val());
	});
	if (schoolInfo[0] != "") {
		console.log(schoolInfo);
		$.ajax({
			type:"post",
			url: APP + "/Admin/addSchool",
			data: {
				"school": schoolInfo[0],
				"area_id": schoolInfo[2]
			},
			success:function(msg) {
				console.log(msg);
				if(msg == true) {
					window.location.reload();
				} else {
					alert("添加失败");
				}
			},
			dataType: "text"
		});
	} else {
		alert("学校名不能为空")
	}
});

$(".school-delete").click(function() {
	var schoolInfo = new Array();
	$(this).parent().find('input[type=text]').each(function() {
		schoolInfo.push($(this).val());
	});
	console.log(schoolInfo[0]);
	$.ajax({
		type:"post",
		url: APP + "/Admin/deleteSchool",
		data: {
			"id": schoolInfo[0]
		},
		success:function(msg) {
			console.log(msg);
			if(msg == true) {
				window.location.reload();
			} else {
				alert("删除失败");
			}
		},
		dataType: "text"
	});
});

/****************************************************添加和删除专业**************************************************/
$(".major-add").click(function() {
	var majorInfo = new Array();
	majorInfo.push($(this).parent().find('input[type=text]').val());
	majorInfo.push($(this).parent().find('select').val());
	if (majorInfo[0] != "") {
		$.ajax({
			type:"post",
			url: APP + "/Admin/addMajor",
			data: {
				"major": majorInfo[0],
				"school_id": majorInfo[1]
			},
			success:function(msg) {
				console.log(msg);
				if(msg == true) {
					window.location.reload();
				} else {
					alert("添加失败");
				}
			},
			dataType: "text"
		});
	} else {
		alert("专业名不能为空");
	}
});

$(".major-delete").click(function() {
	var majorInfo = new Array();
	$(this).parent().find('input[type=text]').each(function() {
		majorInfo.push($(this).val());
	});
	console.log(majorInfo);
	$.ajax({
		type:"post",
		url: APP + "/Admin/deleteMajor",
		data: {
			"major_id": majorInfo[0]
		},
		success:function(msg) {
			console.log(msg);
			if(msg == true) {
				window.location.reload();
			} else {
				alert("删除失败");
			}
		},
		dataType: "text"
	});
});

/****************************************************如果省份改变就更新城市**************************************************/
//如果省份改变就重新读取
$(".province").change(function(){
    var proDOMVal = $(this).val();
    console.log(proDOMVal);
    getProChange(proDOMVal,$(this).parent().find("select[class=city]")); 
});


//初始化城市  省份改变就自动调用
function getProChange(pid,key){
    $.ajax({
        url: APP + "/Account/getAllArea",
        data: {"pid": pid},
        success: function(msg){
            key.html("");
        	console.log(msg);
            $.each(msg, function(i, e){
                key.append("<option value='" + e.area_id + "'>" + e.title + "</option>");
            });
        },
        dataType: "json"
    });
};


function getCityChange(pid){
    $.post(
        "__URL__/getAllArea",
        {"pid": pid},
        function(msg){
            var areaDOM = $("#area");
            areaDOM.html("");
            $.each(msg, function(i, e){
                areaDOM.append("<option value='" + e.area_id + "'>" + e.title + "</option>");
            });
        },
        "json"
    );
};

/**********************************************************删除和恢复评论************************************************************/
$(".feedback-delete").click(function() {
	id = $(this).attr("data-id");
	$.ajax({
		type:"post",
		url: APP + "/Admin/updateFeedback",
		data: {
			"id": id
		},
		success:function(msg) {
			console.log(msg);
			if(msg == true) {
				window.location.reload();
			} else {
				alert("失败");
			}
		},
		dataType: "json"
	});
});

$(".tab-unread-feedback").click(function() {
	$(".feedback_unread").show();
	$(".feedback_read").hide();
})

$(".tab-read-feedback").click(function() {
	$(".feedback_read").show();
	$(".feedback_unread").hide();
})

$(".feedback-reply").click(function() {
	var btn = $(this);
	email = $(this).parent().parent().parent().find("input[type=text]").val();
	content = $(this).parent().parent().find("input[type=text]").val();
	fb_id = $(this).attr("data-id");
	console.log(fb_id);
	if(confirm("是否发送?")){
		$.ajax({
			type:"post",
			url: APP + "/Admin/emailFeedback",
			data: {
				"feedback_id": fb_id,
				"email": email,
				"content": content
			},
			success:function(msg) {
				console.log(msg);
				if(msg == true) {
					//btn.attr("value","已发送");
					alert("邮件发送成功");
				} else {
					alert("邮件发送失败");
				}
			},
			dataType: "json"
		});
	}
})