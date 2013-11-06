$(function() {
    //编辑器实例化
    var editor = new baidu.editor.ui.Editor();
    editor.render("editor"); //editor为剪辑器的id

    var isString = /^[a-z0-9_\u4E00-\u9FFF_-|\s]{1,50}$/i,
        isPhone = /^[0-9\/]{1,30}$/i,
        isPrice = /^[a-z0-9\._\u4E00-\u9FFF_-]{1,20}$/i;

    //事件委托 防止输入为空
    $("#itm-info").delegate("input", "blur", function(e) {
        var target = $(e.target),
            alertBox = $(".pub-detail .alert-danger"),
            targetVal = $.trim(target.val());


        if (targetVal === "") {
            target.css({
                border: "1px solid red"
            });
            alertBox.text("填入的内容不能为空");
            return;
        }

        
        if (target.hasClass("item-user-phone")) {
            if (!isPhone.test(targetVal)) {
                target.css({
                    border: "1px solid red"
                });
                alertBox.text("数字输入不符合规范");
                $(this).parent().find(".notice").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/error.png' />");
                return;
            } 
        } else if (target.hasClass("item-user-name") || target.hasClass("item-title")){
            if (!isString.test(targetVal)) {
                target.css({
                    border: "1px solid red"
                });
                alertBox.text("字符输入过长或出现非法字符!");
                $(this).parent().find(".notice").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/error.png' />");
                return;
            }
        } else if (target.hasClass("item-price")) {
            if (!isPrice.test(targetVal)) {
                target.css({
                    border: "1px solid red"
                });
                alertBox.text("请输入合法的字符!");
                $(this).parent().find(".notice").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/error.png' />");
                return;
            }
        }
        
        $(this).parent().find(".notice").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/ok.png' />");

        alertBox.text("请填入您的物品信息");
    });

    //事件委托 获取焦点的时候边框变回原样
    $(".pub-detail").delegate("input", "focus", function(e) {
        var target = e.target,
            target = $(target);

        target.css({
            border: "1px solid #cccccc"
        });
    });

    function userPub() {
        //验证
        var title = $("#itm-title"),
            titleVal = title.val(),
            pub_id = $("#itm-id").val(),
            subTypeVal = $("#itm-sub-type option:selected").attr("type_id"),
            price = $("#itm-price"),
            priceVal = price.val(),
            sale = $("#itm-saler"),
            saleVal = sale.val(),
            phone = $("#itm-phone"),
            phoneVal = phone.val(),
            dealTypeVal = $(".itm-deal-type input:checked").val(),
            alertBox = $(".pub-detail .alert-danger"),
            isbuy = $(".itm-by-type input:checked").val();
        
                //同步编辑器的内容
        if (editor.hasContents()) {
            editor.sync();
        }

        if (titleVal === "" || priceVal === "" || phoneVal === "" || saleVal === "") {
            alertBox.text("请填写完毕再进行提交!");
            return;
        }

        $("#pub").addClass("disabled").html("发布中...").unbind("click", userPub);

        $.post(
            URL + "/updatePub", {
                "pub_id" : pub_id,
                "sub_type": subTypeVal,
                "title": titleVal,
                "price": priceVal,
                "phone": phoneVal,
                "saler": saleVal,
                'isbuy': dealTypeVal,
		 by: isbuy,
                "content": editor.getContent()
            },
            function(msg) {
                if (msg) {  
                    $("#pub").removeClass("disabled").html("修改成功!");
                    window.location.replace(domainExtraURL + "/Detail/index/id/" + pub_id);
                } else {
                    $("#pub").removeClass("disabled").html("发布失败, 重新发布").bind("click", userPub);
                }
            },
            "json"
        );
    }

    //绑定发布按钮
    $("#pub").bind("click", userPub);

    //图片切换
    $(".img-select img").live("click", function(){
       $(".big-img img").attr("src", $(this).attr("src"));
       $.each($(".img-select img"), function(){
           $(this).removeClass("selected");
       });

       $(this).addClass("selected");
    });
});