$(function(){
    //点击下一步提交
    $("#nextStep").click(function(){
        $(this).addClass("disabled").html("更新中...");
        $("#completeInfoForm").ajaxSubmit({
            success: function(msg){
                window.location.replace(domainExtraURL + "/Register/completeUserImage");
            },
            error: function(msg) {
                $(this).removeClass("disabled").html("更新失败!");
            },
            dataType: "json"
        });
    });

    //跳过
    $("#skip").click(function(){
        window.location.replace(domainExtraURL + "/Register/completeUserImage");
    });

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
});
