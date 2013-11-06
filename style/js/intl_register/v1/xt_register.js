$(function(){
    //提示框
    var tipBox = $("#tip"),
        isMail = /^[a-zA-Z0-9-._]{1,50}@[a-zA-Z0-9-]{1,65}.(com|net|org|info|biz|([a-z]{2,3}.[a-z]{2}))$/i,
        isUserName = /^[a-z0-9_\u4E00-\u9FFF]{2,15}$/i,
        isPwd = /^[a-z0-9_-]{6,18}$/i,
        isRegister = false;   // 这个参数防治重新点击注册按钮
    
    //验证函数
   function inputValid(){
       var obj = $(this),
            objVal = $.trim(obj.val()),
            type = obj.attr("input_type"),
            notice = obj.parent().find(".register-notice");

       //当失去焦点的时候判断是否为空
       if (objVal == ""){
           obj.css({border: "1px solid red"});
           notice.css("color", "#f00").text("填写的内容不能为空");
           return ;
       } else {
           obj.css({border: "1px solid #cccccc"});
           notice.css("color", "#A8A8A8").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/ok.png' />");
       }
       //邮箱验证    密码验证    用户名验证
       if (type === "mail"){
           if (!isMail.test(objVal)){
               obj.css({border: "1px solid red"});
               notice.css("color", "#f00").text("邮箱地址错误");
               return ;
           } else {
               obj.css({border: "1px solid #cccccc"});
                notice.css("color", "#A8A8A8").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/ok.png' />");
           }
       } else if (type === "pwd"){
            if (!isPwd.test(objVal)){
               obj.css({border: "1px solid red"});
               notice.css("color", "#f00").text("密码不符合规范");
               return ;
            } else {
               obj.css({border: "1px solid #cccccc"});
               notice.css("color", "#A8A8A8").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/ok.png' />");
           }
       } else if (type === "name"){
           if (!isUserName.test(objVal)){
               obj.css({border: "1px solid red"});
               notice.css("color", "#f00").text("用户名不符合规范");
               return ;
           } else {
               obj.css({border: "1px solid #cccccc"});
               notice.css("color", "#A8A8A8").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/ok.png' />");
           }
       }
    };

    function bindEvent() {
            $(".site-register").delegate("input", "blur", inputValid);
            $("#submitRegister").bind("click", myRegister);
    }

    $("#submitRegister").bind("click", myRegister);


    function myRegister(){
        var user = $("#user"),
            userVal = $.trim(user.val()),
            pwd = $("#pwd"),
            pwdVal = $.trim(pwd.val()),
            email = $("#email"),
            emailVal = $.trim(email.val()),
            verifyCode = $("#verifyCode"),
            verifyCodeVal = $.trim(verifyCode.val());
            tipBox = $("#tip"),

            autoLogin = $("#login-auto").attr("checked") == "checked" ? true : false;
            //账号和密码为空
        if (userVal == "" || pwdVal == "" || emailVal == ""){
            tipBox.text("输入不能为空");
            return ;
        }

        //验证用户名格式错误
        if (!isUserName.test(userVal)){
            tipBox.text("用户名不符合规范");
            user.focus();
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
        $("#submitRegister").addClass("disabled").unbind("click", myRegister).html("正在注册中...");
        $.post(
            domainExtraURL + "/Register/registerSubmit",
            {"userName": userVal, "mail": emailVal.toLowerCase(), "pwd": pwdVal, "verify": verifyCodeVal},
            function(msg){
                 if (!!msg.isFit){
                     $("#submitRegister").bind("click").html("注册成功!");
                     window.location.replace(domainExtraURL + "/Register/completeBasicInformation");
                } else {                  
                    tipBox.text(msg.msg);
                    var timenow = new Date().getTime();
                    $("#submitRegister").removeClass("disabled").bind("click", myRegister).html("同意以下协议并注册");
                    $("#verify").attr("src", domainExtraURL + "/Public/verifyCode/" + timenow);
                }
            },
            "json"
        );
    };

    //点击验证码更换
    $("#verify").click(function(){
        var timenow = new Date().getTime();
        $("#verify").attr("src",  domainExtraURL + "/Public/verifyCode/" + timenow);
    });
    
    bindEvent();
});