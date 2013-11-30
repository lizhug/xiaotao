  /*
             *  1、账号格式的本地验证 包括邮箱验证 xiaoplus账号的合法性
             *  2、账号两头trim操作
             *  3、
             */

$(function(){

    //正则表达式
    var isMail = /^[a-zA-Z0-9-._]{1,50}@[a-zA-Z0-9-]{1,65}.(com|net|org|info|biz|([a-z]{2,3}.[a-z]{2}))$/i,
        isXiaoPlus = /[1-9][0-9]{4,10}/,
        isPwd = /^[a-z0-9_-]{6,18}$/i;

    function myLogin(){
        var user = $("#user"),
        userVal = $.trim(user.val()),
        pwd = $("#password"),
        pwdVal = $.trim(pwd.val()),
        tipBox = $("#tip"),
        autoLogin = $("#login-auto").attr("checked") == "checked" ? true : false;

        //账号和密码为空
        if (userVal == "" || pwdVal == ""){
            tipBox.text("账号和密码不能为空");
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
        $("#login").addClass("disabled").html("登录中...").unbind("click", myLogin);

        $.ajax({
            type: "post",
            url: URL + "/getLogin",
            data: {"user": userVal.toLowerCase(), "pwd": pwdVal, "autoLogin": autoLogin},
            success: function(msg){
               
               if (!msg.isFit){
                    tipBox.css({display: "block"})
                        .text("账号或者密码错误");
                    $("#login").removeClass("disabled").bind("click", myLogin).html("登录");
                } else {
                    tipBox.text(msg.msg);
                    var urlString = window.location.href.split("?")[1];
					
					if (urlString != undefined) {
						urlString = urlString.split("&");
						for (var i = 0; i != urlString.length; ++i) {
							var urlStringChild = urlString[i].split("=");
							if (urlStringChild[0] === "redirectURL") {
								window.location.href = decodeURIComponent(urlStringChild[1]);
							} 
						}
					}  else {
						window.location.href = domainExtraURL;
					}
                }
            },
	    error: function(msg) {console.log(msg);},
            dataType: "json"
        });
    }

    //点击登陆后的操作
    $("#login").bind("click", myLogin);
    $("#password").bind("keypress", function(e){
        if (e.keyCode == 13){
            myLogin();
        }
    });

});