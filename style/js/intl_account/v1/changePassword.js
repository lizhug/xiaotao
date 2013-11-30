$(function(){
    var tipBox = $("#tip"),
        isNum = /^[0-9]{4}$/,
        isPwd = /^[a-z0-9_-]{6,18}$/i;

    //验证函数
    var inputValid = function(obj, type){
        obj.bind({
             blur: function(){
                 var objVal = $.trim(obj.val());
                //当失去焦点的时候判断是否为空
                if (objVal == ""){
                    obj.css({border: "1px solid red"});
                    tipBox.text("填写的内容不能为空");
                    return ;
                } else {
                    obj.css({border: "1px solid #cccccc"});
                     tipBox.text("");
                }
             
                //密码验证
                if (type == "pwd"){
                     if (!isPwd.test(objVal)){
                        obj.css({border: "1px solid red"});
                        tipBox.text("密码不符合规范");
                        return ;
                     } else {
                        obj.css({border: "1px solid #cccccc"});
                        tipBox.text("");
                    }
                } else if (type == "rpwd"){
                    if (objVal != $("#new-pwd").val()){
                        obj.css({border: "1px solid red"});
                        tipBox.text("两次密码输入不一致");
                        return ;
                    } else {
                        obj.css({border: "1px solid #cccccc"});
                        tipBox.text("");
                    }
                } else if (type == "verifyCode"){
                    if (!isNum.test(objVal)){
                        obj.css({border: "1px solid red"});
                        tipBox.text("验证码格式错误");
                        return ;
                    } else {
                        obj.css({border: "1px solid #cccccc"});
                        tipBox.text("");
                    }
                }
             }
        });
    };

    inputValid($("#old-pwd"), "pwd");
    inputValid($("#new-pwd"), "pwd");
    inputValid($("#rnew-pwd"), "rpwd");
    inputValid($("#verifyCode"), "verifyCode");

    //提交的函数
    var myChange =  function(){
        var oldPwd = $("#old-pwd"),
            oldPwdVal = $.trim(oldPwd.val()),
            newPwd = $("#new-pwd"),
            newPwdVal = $.trim(newPwd.val()),
            rnewPwd = $("#rnew-pwd"),
            rnewPwdVal = $.trim(rnewPwd.val()),
            verifyCode = $("#verifyCode"),
            verifyCodeVal = $.trim(verifyCode.val());

        //账号和密码为空
        if (oldPwdVal == "" || newPwdVal == "" || rnewPwdVal == ""){
            tipBox.text("账号和密码不能为空");
            oldPwd.focus();
            return ;
        } else {
            tipBox.text("");
        }

        //密码格式
        if (!isPwd.test(oldPwdVal) || !isPwd.test(newPwdVal) || !isPwd.test(rnewPwdVal)){
            tipBox.text("密码格式错误");
            return ;
        } else {
            tipBox.text("");
        }
      
        //后台验证用户合法性，若存在则跳转到登录页面
        $.post(
            URL + "/changePwd",
            {"oldPwd": oldPwdVal, "newPwd": rnewPwdVal, "verify": verifyCodeVal},
            function(msg){
           
                if (!msg.isFit){
                    alert(msg.msg);
                    var timenow = new Date().getTime();
                    $("#verifyImg").attr("src", domainExtraURL + "/Public/verifyCode/" + timenow);
                } else {
                   alert(msg.msg);
                   window.location.reload();
                }
            },
            "json"
        );
    };

    //提交
    $("#save-pwd").click(function(){
        myChange();
    });
    $("#verifyCode").keypress(function(e){
        if (e.keyCode == 13){
            myChange();
        }
    });

    $("#verifyImg").click(function(){
       var timenow = new Date().getTime();
       $(this).attr("src", domainExtraURL + "/Public/verifyCode/" + timenow);
    });



    //取消
    $("#cancel").click(function(){
        window.location.reload();
    })


});