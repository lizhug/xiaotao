$(function(){
    //搜索框操作
    $("#search-input-box").keydown(function(e){
        var keyWords;
        if (e.keyCode == 13){
            keyWords = $(this).val();
            if (keyWords == ""){
                return;
            } else {
                window.location.href = APP + "/Search/index?search=" + encodeURIComponent(keyWords) + "&type=0&sub=0&low=0&high=20000";
            }
        }
    });

    $(".site-search-btn").click(function(){
        var keyWords = $("#search-input-box").val();
        if (keyWords == ""){
            return;
        } else {
            window.location.href = APP + "/Search/index?search=" + encodeURIComponent(keyWords) + "&type=0&sub=0&low=0&high=20000";
        }
    });

     //验证码函数
     $("#verifyImg").click(function(){
         var timenow = new Date().getTime();
         $(this).attr("src", APP + "/Public/verifyCode/" + timenow);
     });
     
     //redirectURL
    $(".header-login-btn").attr("href", $(".header-login-btn").attr("href") + "?redirectURL=" + encodeURIComponent(window.location.href));


});




