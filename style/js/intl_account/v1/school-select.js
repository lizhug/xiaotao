 /*************选择学校的js*************************/
 
 function showSchoolList() {
    $.ajax({
        type: "post",
        url: APP + "/Global/showSchoolTable",
        success: function(returnData) {
                $(returnData).appendTo("body");
                 changeSchool();
                 initWindow();
                $(".change-school-body-province a").eq(0).addClass("selected");
        }
    });
}
function changeSchool() {
    var id = $(this).attr("data-id") || 1;
    //移除selected
    $.each($(".change-school-body-province a"), function(i, e) {
        if ($(e).hasClass("selected")) {
            $(e).removeClass("selected");
        }
    });

    $(this).addClass("selected");

    $.post(
            APP + "/Global/getSchoolListByProvinceId",
            {"id": id},
            function (data) {
                    $(".school-list").html("");
                    var length = data.length;
                    var content = "";	//先汇集到content 再总的插入content到容器中
                    for (var i = 0; i !== length; i++) {
                            content += "<a href='javascript:void(0);' title='" + data[i]['school'] + "' school_id='" + data[i]['school_id'] + "'>" + data[i]['school'] + "</a>";
                    }
                    $(content).appendTo($(".school-list"));
            },
            "json"
    );
}

//关闭学校面板
function closeSchoolBox() {
    $(".change-school-box").toggle();
}

//事件绑定
$("#college").live("click", function(){
     if ($(".change-school-box").length === 0) {
        showSchoolList();
     } else {
         $(".change-school-box").toggle();
     }
});

//切换学校
$(".school-list a").live("click", function() {
    var school_id = $(this).attr("school_id"),
        school = $(this).text();;

    $("#college").val(school);
    $("#college-id").val(school_id);
    $(".change-school-box").toggle();
}); 

$(".change-school-head-close").live("click", closeSchoolBox);

//不同省份切换学校
$(".change-school-body-province a").live("click", changeSchool);

//固定弹窗的位置
function initWindow(){
    var windowHeight = $(window).height(),
        divHeight = $(".change-school").height();
    $(".change-school").css({"margin-top": (windowHeight - divHeight) / 2 + "px"});
}



