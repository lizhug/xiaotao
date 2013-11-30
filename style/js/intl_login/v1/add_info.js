$(function() {

    var isMail = /^[a-zA-Z0-9-._]{1,50}@[a-zA-Z0-9-]{1,65}.(com|net|org|info|biz|([a-z]{2,3}.[a-z]{2}))$/i,
        isPwd = /^[a-z0-9_-]{6,18}$/i,
        isValid = false;
		
    //验证函数
    function inputValid(){
        var obj = $(this),
            objVal = $.trim(obj.val()),
            type = obj.attr("input_type"),
            notice = obj.parent().find(".notice"),
            oldword = notice.html();

        //当失去焦点的时候判断是否为空
        if (objVal == ""){
            notice.css("color", "#f00").html("<img class='unvalid-tip' src='http://t.xiaoplus.com/style/img/common/icon/error.png' />填写的内容不能为空");
            return ;
        } else {
            notice.css("color", "#A8A8A8").html(oldword);
        };
        //邮箱验证    密码验证    用户名验证
        if (type == "mail"){
            if (!isMail.test(objVal)){
                    notice.css("color", "#f00").html("<img class='unvalid-tip' src='http://t.xiaoplus.com/style/img/common/icon/error.png' />请输入正确的邮箱地址");
                    return ;
            } else {
                    notice.css("color", "#A8A8A8").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/ok.png' />");
            }
        } else if (type == "pwd"){
            if (!isPwd.test(objVal)){
              notice.css("color", "#f00").html("<img class='unvalid-tip' src='http://t.xiaoplus.com/style/img/common/icon/error.png' />密码不符合规范");
                   return ;
            } else if ($(this).val().length < 6) {
                    notice.css("color", "#f00").html("<img class='unvalid-tip' src='http://t.xiaoplus.com/style/img/common/icon/error.png' />密码少于六位!");
                    return ;
            } else {
                   notice.css("color", "#A8A8A8").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/ok.png' />");
            }
        } 	
    };

	//检验重复密码是否正确
	function rePwdValid() {
		var notice = $(this).parent().find(".notice");	
		if ($(this).val() != $("#password").val()) {
			notice.css("color", "#f00").html("<img class='unvalid-tip' src='http://t.xiaoplus.com/style/img/common/icon/error.png' />密码不一致!");
			return ;
		} else if ($(this).val().length < 6) {
                    notice.css("color", "#f00").html("<img class='unvalid-tip' src='http://t.xiaoplus.com/style/img/common/icon/error.png' />密码少于六位!");
                    return ;
                } else {
			notice.css("color", "#A8A8A8").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/ok.png' />");
		}
	}
	
	function bindEvent() {
		$("#check_email").delegate("input", "blur", inputValid);
		$("#re-pwd").bind("blur", rePwdValid);   
		$("#sub_button").bind("click", myRegister);
	}
	
	function myRegister() {
		var length = $(".valid-tip").length;
		if (length == 3) {
                    var email = $.trim($("#email").val()),
			pwd = $.trim($("#password").val());
                        $("#sub_button").unbind("click").addClass("disabled").html("正在提交...");
			$.ajax({
				url: URL + "/addUserInfo",
				type: "post",
				data: {"email": email, "password": pwd},
				success: function(msg){
					var flag = msg['flag'];
					if (!flag) {
                                            $("#sub_button").removeClass("disabled").bind("click").html("提交")
                                            $("#email").parent().find(".notice").css("color", "#f00").html(msg['msg']);
					} else {
                                            $("#sub_button").html("注册成功");
                                            window.location.replace(domainExtraURL);	
					}
				},
				dataType: "json"
			
			})
		} else {
                    
                }
        }
	bindEvent();
});