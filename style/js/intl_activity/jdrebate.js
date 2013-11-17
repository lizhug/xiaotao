define(["jquery"], function($) {
	var initialize = function() {
		bindEvent();
	};


	//事件绑定
	var bindEvent = function() {

        //去往京东按钮居中
        (function(){
            var windowHeight = parseInt(window.screen.height, 10) - 120;
            $(".go-jd").css("top", windowHeight / 2 + "px");
        }());

		//购物车按钮点击
		$("#buy-car").on("click", function() {
			$("#notice-wrap").remove();
			//获取购物车页面
			$.post(
				URL + "/getBuyPage",
				function(returnData) {
					var content = $(returnData['content']);
					content.appendTo($("body"));

                    $("#user-name").val(getCookie("userName"));
                    $("#user-addr").val(getCookie("userAddr"));
                    $("#user-phone").val(getCookie("userPhone"));
				},
				"json"
			);
		});

		//关闭提示面板
		$(".notice-box-close").live("click", function(){
			$("#notice-wrap").remove();
		});

		//快速登录按钮
		$(".login-btn").live("click", function(){

			//关闭所有的notice-wrap面板
			$("#notice-wrap").remove();
			$.post(
				URL + "/quickLogin",
				function(returnData) {
					var content = $(returnData['content']);
					content.appendTo($("body"));
				},
				"json"
			);
		});

		//快速登录按钮
		$(".register-btn").live("click", function(){

			//关闭所有的notice-wrap面板
			$("#notice-wrap").remove();
			$.post(
				URL + "/quickRegister",
				function(returnData) {
					var content = $(returnData['content']);
					content.appendTo($("body"));
				},
				"json"
			);
		});

		$("#get-login").live("click", myLogin);			//登录
        $(".form-login #password").live("keypress", function(e) {
            if (e.keyCode == 13) {
                myLogin();
            }
        });
		$(".form-register input").live("blur", inputValid);		//是去焦点验证
		$("#get-register").live("click", myRegister);		//注册
    		
    	//点击验证码更换
        $("#verify").live("click", function(){
           var timenow = new Date().getTime();
           $("#verify").attr("src",  APP + "/Public/verifyCode/" + timenow);
        });

        //提交物品
        $("#submit-check-btn").live("click", saveBuyCar);
        $("#my-already-buy").live("click", showAlreadyList);
        $(".already-buy-list-item .btn-danger").live("click", deleteItem);
        $(".already-buy-list-item .btn-pass").live("click", completeItem);

        //查看已订购列表
        $(".show-list").live("click", function() {
            $("#notice-wrap").remove();
            showAlreadyList();
        });
  	};

    //取消订单
    var deleteItem = function() {
        var flag = confirm("确认取消订单么？不可恢复喔！");
        if (flag) {
            var itemId = $(this).attr("itemId"),
                that = $(this);
            $.post(
                URL + "/deleteItem",
                {"itemId": itemId},
                function(returnData) {
                    if (returnData.flag) {
                        that.parent().remove();
                        alert("取消成功!您还可以去挑选其他物品喔！5% 等待你");
                    }
                },
                "json"
            );
        }
    };

    //完成订单
    var completeItem = function() {
        var flag = confirm("您确认收到货了么？谨慎标记喔!");
        if (flag) {
            var itemId = $(this).attr("itemId"),
                that = $(this);
            $.post(
                URL + "/completeItem",
                {"itemId": itemId},
                function(returnData) {
                    if (returnData.flag) {
                        that.parent().remove();
                        alert("确认成功!");
                        window.location.reload();
                    }
                },
                "json"
            );
        }
    };

    //查看已购买列表
    var showAlreadyList = function() {
        $.post(
            URL + "/getAlreadyBuy",
            function(returnData) {
                var content = $(returnData['content']);
                    content.appendTo($("body"));
            },
            "json"
        );
    };


    //购物车提交
    var saveBuyCar = function() {
        var flag = true;            //标记能不能提交
        //必填项确定没有空的
        $.each($("input.buy-car-input"), function(e, i) {
            if ($.trim($(i).val()) === "") {      
                $(i).css("border", "1px solid #f00");
                $(i).parent().find(".register-notice").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/error.png' />");
                flag = false;
            } else {
                $(i).css("border", "1px solid #CCCCCC");
                $(i).parent().find(".register-notice").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/ok.png' />")
            }
        });

        if (!flag) {
            return;
        }
        $("#submit-check-btn").unbind("click", saveBuyCar).addClass("disabled").html("正在提交...");
        var url = $.trim($("#item-url").val()),
            userName = $.trim($("#user-name").val()),
            userAddr = $.trim($("#user-addr").val()),
            userPhone = $.trim($("#user-phone").val()),
            userAddition = $.trim($("#user-addition").val());

            setCookie("userName", userName);
            setCookie("userPhone", userPhone);
            setCookie("userAddr", userAddr);

        $.post(
            URL + "/saveBuyCar",
            {"url": url, "userName": userName, "userAddr": userAddr, "userPhone": userPhone, "userAddition": userAddition},
            function(msg) {
		//console.log(msg);return;
                if (msg.flag) {
                    $(".notice-box-content").html(msg.context);
                } else {
                    alert("提交失败！");
                    window.location.reload();
                }
            },
            "json"
        );
    };

  	 //正则表达式
      
  	var isMail = /^[a-zA-Z0-9-._]{1,50}@[a-zA-Z0-9-]{1,65}.(com|net|org|info|biz|([a-z]{2,3}.[a-z]{2}))$/i,
        isXiaoPlus = /[1-9][0-9]{4,10}/,
        isPwd = /^[a-z0-9_-]{6,18}$/i,
  	    isUserName = /^[a-z0-9_\u4E00-\u9FFF]{2,15}$/i;

  	 //登录函数          
    var myLogin = function(){
        var user = $("#user"),
	        userVal = $.trim(user.val()),
	        pwd = $("#password"),
	        pwdVal = $.trim(pwd.val()),
	        tipBox = $("#tip");

        //账号和密码为空
        if (userVal == "" || pwdVal == ""){
            tipBox.text("输入不能为空");
            user.focus();
            return ;
        }

        //验证账号格式错误
        if (!isMail.test(userVal) && !isXiaoPlus.test(userVal)){
            tipBox.text("账号格式错误");
            user.focus();
            return ;
        }

        //密码格式
        if (!isPwd.test(pwdVal)){
            tipBox.text("密码格式错误");
            pwd.focus();
            return ;
        }
        //后台验证用户合法性，若存在则跳转到登录页面
        $("#get-login").addClass("disabled").html("登录中...").unbind("click", myLogin);
        
        $.ajax({
            type: "post",
            url: APP + "/Login/getLogin",
            data: {"user": userVal.toLowerCase(), "pwd": pwdVal, "autoLogin": 1},
            success: function(msg){
               if (!msg.isFit){
                    tipBox.css({display: "block"})
                        .text("账号或者密码错误");
                    $("#get-login").removeClass("disabled").bind("click", myLogin).html("登录");
                } else {
                    tipBox.text(msg.msg);
                    window.location.reload();
                }
            },
            dataType: "json"
        });
    };

    //验证函数
    var inputValid = function(){

        var obj = $(this),
        	tipBox = $("#tip"),
            objVal = $.trim(obj.val()),
            type = obj.attr("input_type"),
            notice = obj.parent().find(".register-notice");

       //当失去焦点的时候判断是否为空
       if (objVal == ""){
           obj.css({border: "1px solid red"});
           tipBox.html("填写的内容不能为空");
           notice.html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/error.png' />");
           return ;
       } else {
           obj.css({border: "1px solid #cccccc"});
           notice.css("color", "#A8A8A8").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/ok.png' />");
       }
       //邮箱验证    密码验证    用户名验证
       if (type === "mail"){
           if (!isMail.test(objVal)){
               obj.css({border: "1px solid red"});
               tipBox.html("邮箱地址错误");
               notice.css("color", "#f00").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/error.png' />");
               return ;
           } else {
               obj.css({border: "1px solid #cccccc"});
               tipBox.html("");
               notice.css("color", "#A8A8A8").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/ok.png' />");
           }
       } else if (type === "pwd"){
            if (!isPwd.test(objVal)){
               obj.css({border: "1px solid red"});
               tipBox.html("密码不符合规范");
               notice.css("color", "#f00").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/error.png' />");
               return ;
            } else {
               obj.css({border: "1px solid #cccccc"});
               tipBox.html("");
               notice.css("color", "#A8A8A8").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/ok.png' />");
           }
       } 
    };

    var myRegister = function(){
        var pwd = $("#pwd"),
            pwdVal = $.trim(pwd.val()),
            email = $("#email"),
            emailVal = $.trim(email.val()),
            verifyCode = $("#verifyCode"),
            verifyCodeVal = $.trim(verifyCode.val());
            tipBox = $("#tip");

            //账号和密码为空
        if (pwdVal == "" || emailVal == ""){
            tipBox.text("输入不能为空");
            return ;
        }

        //验证邮箱格式错误
        if (!isMail.test(emailVal)){
            tipBox.text("邮箱格式错误");
            email.focus();
            return ;
        }
        
        //密码格式
        if (!isPwd.test(pwdVal)){
            tipBox.text("密码格式错误");
            pwd.focus();
            return ;
        }
        //提交按钮的css改变 和文案改变
        $("#get-register").addClass("disabled").unbind("click", myRegister).html("正在注册中...");
        $.post(
            APP + "/Register/registerSubmit",
            {"userName": "用户" + Math.random().toString().substr(2,10), "mail": emailVal.toLowerCase(), "pwd": pwdVal, "verify": verifyCodeVal},
            function(msg){
                 if (!!msg.isFit){
                     $("#get-register").removeClass("disabled").html("注册成功!");
                     window.location.reload();
                } else {                  
                    tipBox.text(msg.msg);
                    var timenow = new Date().getTime();
                    $("#get-register").removeClass("disabled").bind("click", myRegister).html("重新注册");
                    $("#verify").attr("src", domainExtraURL + "/Public/verifyCode/" + timenow);
                }
            },
            "json"
        );
    };


    /************************************************************************
    |    函数名称： setCookie                                                |
    |    函数功能： 设置cookie函数                                            |
    |    入口参数： name：cookie名称；value：cookie值                        |
    |    维护记录： Spark(创建）                                            |
    |    版权所有： (C) 2006-2007 北京东方常智科技有限公司                    |
    |    编写时间： 2007年9月13日 21:00                                        |
    *************************************************************************/
    function setCookie(name, value) 
    { 
        var argv = setCookie.arguments; 
        var argc = setCookie.arguments.length; 
        var expires = (argc > 2) ? argv[2] : null; 
        if(expires!=null) 
        { 
            var LargeExpDate = new Date (); 
            LargeExpDate.setTime(LargeExpDate.getTime() + (expires*1000*3600*24));         
        } 
        document.cookie = name + "=" + escape (value)+((expires == null) ? "" : ("; expires=" +LargeExpDate.toGMTString())); 
    }
    /************************************************************************
    |    函数名称： getCookie                                                |
    |    函数功能： 读取cookie函数                                            |
    |    入口参数： Name：cookie名称                                            |
    |    维护记录： Spark(创建）                                            |
    |    版权所有： (C) 2006-2007 北京东方常智科技有限公司                    |
    |    编写时间： 2007年9月13日 21:02                                        |
    *************************************************************************/
    function getCookie(Name) 
    { 
        var search = Name + "=" 
        if(document.cookie.length > 0) 
        { 
            offset = document.cookie.indexOf(search) 
            if(offset != -1) 
            { 
                offset += search.length 
                end = document.cookie.indexOf(";", offset) 
                if(end == -1) end = document.cookie.length 
                return unescape(document.cookie.substring(offset, end)) 
            } 
            else return "" 
        } 
    } 

    /************************************************************************
    |    函数名称： deleteCookie                                            |
    |    函数功能： 删除cookie函数                                            |
    |    入口参数： Name：cookie名称                                        |
    |    维护记录： Spark(创建）                                        |
    |    版权所有： (C) 2006-2007 北京东方常智科技有限公司                |
    |    编写时间： 2007年9月15日 18:10                                    |
    *************************************************************************/    
    function deleteCookie(name) 
    { 
                         var expdate = new Date(); 
                         expdate.setTime(expdate.getTime() - (86400 * 1000 * 1)); 
        setCookie(name, "", expdate); 
    } 

	return {
		initialize: initialize
	}
});