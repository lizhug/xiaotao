$(function(){
    //初始化城市  省份改变就自动调用
    function getProChange(pid){
        $.ajax({
            url: URL + "/getAllArea",
            data: {"pid": pid},
            success: function(msg){
                var cityDOM = $("#city");
                cityDOM.html("");
                $.each(msg, function(i, e){
                    cityDOM.append("<option value='" + e.area_id + "'>" + e.title + "</option>");
                });
            },
            complete: function (){
                var cityDOMVal = $("#city option:selected").val();
                getCityChange(cityDOMVal);
            },
            dataType: "json"
        });
    };

    function getCityChange(pid){
        $.post(
            URL + "/getAllArea",
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

    //如果省份改变就重新读取
    $("#province").change(function(){
        var proDOMVal = $("#province option:selected").val();
        getProChange(proDOMVal);
    });

    //如果城市改变就重新读取
     $("#city").change(function(){
        var cityDOMVal = $("#city option:selected").val();
        getCityChange(cityDOMVal);
    });

    //重新加载这个页面
    $("#cancel").click(function(){
        window.location.reload();
    });

    //提交表单
    $("#save").click(function(){
        var isUserName = /^[a-z0-9_\u4E00-\u9FFF]{2,15}$/i,
            isNum = /^[\d]{6,14}$/i;
        var userNewName = $("#uname").val();
        if (userNewName === "") {
            alert("用户名不能为空!");
            return;
        }

        if (!isUserName.test(userNewName)) {
            alert("用户名格式错误!");
            return;
        }
        
        if ($.trim($("#qq").val()) != "") {
            if (!isNum.test($("#qq").val())) {
                alert("qq号码格式错误！");
                return;
            }
        }

        $("#user-detail").ajaxSubmit({
            success: function(msg){
                //alert(msg.msg);
                window.location.reload();
            },
            error: function(e) {
                //console.log(e.responseText);
            },
            dataType: "json"
        });
    });

    $("#add-mail").bind("click", addMail);

    function addMail() {

       var email = $(this).parent().find(".account-email").val(),
	   currentObj = $(this),
           isMail = /^[a-z\d]+(\.[a-z\d]+)*@([\da-z](-[\da-z])?)+(\.{1,2}[a-z]+)+$/i;

       //验证邮件
       if (!isMail.test(email)) {
           $(this).html("邮箱格式错误!点击重新验证!");
           return;
       }

       currentObj.unbind("click", addMail).addClass("disabled").html("正在发送验证邮件...");
       $.ajax({
          type:"post",
          url: URL + "/bindEmail", 
          data: {"mail": email},
          success: function(msg) {
             if(msg.flag) {
                 $("#add-mail").removeClass("disabled").html(msg.msg);
             } else {
                 $("#add-mail").removeClass("disabled").bind("click", addMail).html(msg.msg);
             }
          },
          dataType: "json"
       });
    }
});