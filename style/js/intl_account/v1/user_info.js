$(function(){
    $("#get-more").click(function() {
        $("#get-more .more").css("display", "none");
        $("#get-more div").css("display", "block");
        $.ajax({
            type: "post",
            url: URL + "/getPublist",
            data: {"p": parseInt($("#get-more").attr('index'), 10), "uid": $("#userID").val() },
            success: function(msg){

                var tmp =  parseInt($("#get-more").attr("index"), 10);
                $("#get-more").attr("index", ++tmp);

                //add
                var length =  msg  === null ? 0 : msg.length;
                for(var i = 0; i != length; ++i) {
                    $("<li class='pub-sub'><span class='user-detail-title'><a class='title' title='" + msg[i].title + "' href='" + domainExtraURL + "/Detail/index/id/" + msg[i].pub_id+ "' target='_blank' >" + msg[i].title + "</a><span class='catalog'>(" +msg[i].province+ "-" + msg[i].school + "-" + msg[i].type + "-" + msg[i].sub_type + ")</span></span><span class='date'>" +msg[i].ctime+ "</span></li>").appendTo($("#publist"));
                }
                $("#get-more .more").css("display", "block");
                $("#get-more div").css("display", "none");
            },
            dataType: "json"
        });
    });

    //用户详细资料
    $("#get-more-user-info").click(function() {
        $("#user-info-view").css("display", "block");
    });
});