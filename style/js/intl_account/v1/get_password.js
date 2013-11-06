 $(function(){
    function sendEmail() {
        var isMail =  /^[a-zA-Z0-9-._]{1,50}@[a-zA-Z0-9-]{1,65}.(com|net|org|info|biz|([a-z]{2,3}.[a-z]{2}))$/i,
            email = $("#email").val();

        if (email == "") {
          $(".site-get-password .error").text("邮箱不能为空");
          return ;
        }

        if (!isMail.test(email)) {
            $(".site-get-password .error").text("邮箱格式错误");
            return ;
        }

        $("#send").unbind("click", sendEmail).addClass("disabled").html("正在发送中...");
        $.ajax({
          type: "post",
          url: APP + "/Account/forgetPwd",
          data: {"email" : email.toLowerCase()},
          success: function(msg) {
 
              if(!msg.flag) {
                  $(".site-get-password .error").text(msg.msg);
                  $("#send").bind("click", sendEmail).removeClass("disabled").html("重新发送");
              } else if (msg.flag){
                  $(".site-get-password .alert").text(msg.msg);
                  $("#email").val("");
                  $("#send").html("已发送");
                 $(".form-wrap").remove();
              }
          },
          timeout: "10s",
          dataType: "json"
        });
    }
    $("#send").bind("click", sendEmail);
});

