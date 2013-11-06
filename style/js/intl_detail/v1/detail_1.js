 $(function(){
    var success = $("#success");
    //收藏
    $("#itm-save").click(function(){
        var id = $("#itm-action").attr("pub_id");

        $.post(
            URL + "/itmSave", 
            {"id": id},
            function(msg){

                if (msg.isFit){
                    success.text(msg.msg).fadeIn(100).delay(2000).fadeOut(100);
                    $("#itm-save").css("display", "none");
                    $("#itm-cancel").css("display", "block");
                } else {
                    success.text(msg.msg).fadeIn(100).delay(2000).fadeOut(100);
                }
            },
            "json"
        )
    });

    //取消收藏
    $("#itm-cancel").click(function(){
        var id = $("#itm-action").attr("pub_id");

        $.post(
            URL + "/itmCancel",
            {"id": id},
            function(msg){

                if (msg.isFit){
                   success.text(msg.msg).fadeIn(0).delay(2000).fadeOut(0);
                    $("#itm-cancel").css("display", "none");
                    $("#itm-save").css("display", "block");
                } else {
                    success.text(msg.msg).fadeIn(0).delay(2000).fadeOut(0);
                }
            },
            "json"
        )
    });

    //初始化收藏状态
    var status = $("#itm-action").attr("status");

    if (status != 0 || status == ""){
         $("#itm-cancel").css("display", "none");
         $("#itm-save").css("display", "block");
    } else {
         $("#itm-cancel").css("display", "block");
         $("#itm-save").css("display", "none");
    }

    //图片切换
    $(".img-select img").live("click", function(){
       $(".big-img img").attr("src", $(this).attr("src"));
       $.each($(".img-select img"), function(){
           $(this).removeClass("selected");
       });

       $(this).addClass("selected");
    });


    $("#delete-detail").bind("click", function(){
            var pid = parseInt($(this).attr("pub_id"), 10);

            $.ajax({
                    type: "post",
                    url: URL + "/deleteDetail",
                    data: {"pid": pid},
                    success: function(msg) {
                            console.log(msg);
                            if (msg) {
                                    alert("修改成功!");
                            }
                    },
                    dataType: "json"
            });
    });
    $("#change-status").bind("click", function(){
            var $self = $(this),
                    pid = $(this).attr("pid"),
                    status = $(this).attr("status") == 0 ? 1 : 0;

            $.ajax({
                    type: "post",
                    url: APP + "/Account/pubComplete",
                    data: {"pub_id": pid, "is_complete": status},
                    success: function(data) {
                            console.log(data);
                            var returnCode = data.returnCode;

                            if (returnCode) {
                                    $self.attr("status", status);

                                    if (status == 1){
                                            $self.html("已完成");
                                    } else {
                                            $self.html("交易中");
                                    }
                            }
                    },
                    error:function(ss) {
                            console.log(ss);
                    },
                    dataType: "json"
             });
    });
    
    //detail-down面板切换
    $(".detail-down-section li").bind("click", switchDetailContent);
    
    function switchDetailContent() {
        if (!$(this).hasClass("current")) {
             $(this).parent().children(".current").removeClass("current");          //切换current类
             $(this).addClass("current");
             
             var index = $(this).attr("index");         //显示面板
             $.each($(".detail-down-content"), function(i, e) {
                 var target = $(e);
                 if (target.hasClass("current")) {
                     target.removeClass("current");
                 } 
                 
                 if (target.attr("index") === index) {
                     target.addClass("current");
                 }
             });
        }
    }

});