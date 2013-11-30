$(function() {
    //编辑器实例化
    var editor = new baidu.editor.ui.Editor();
    editor.render("editor"); //editor为剪辑器的id

    var isString = /^[a-z0-9_\u4E00-\u9FFF_-|\s]{1,50}$/i,
        canSubmit = false,
        isPhone = /^[0-9\/]{1,30}$/i,
        isPrice = /^[a-z0-9\._\u4E00-\u9FFF_-]{1,20}$/i;

    /*
    
    这个部分选择大类

    */
    var typeId = 1;
    $(".type-img").click(function() {
        
        //如果已经选中就不读取
        if ($(this).hasClass("select")) {
            return;
        }
        
        $.each($(".type-img"), function() {
            $(this).removeClass("select");
        });
        
        $(this).addClass("select");
        typeId = $(this).attr("typeId");
        getSubType(typeId);
    });
    
    //选完大类就从后台读取、而不是点击下一步后读取
    function getSubType(typeId){
        //验证通过从数据库中读取小类
        $.post(
            URL + "/getSubType", {
                "typeId": typeId
            },
            function(msg) {

                var tmp = $("#itm-sub-type");
                $.each(msg, function(e, i) {
                    $("<option type_id='" + i["type_id"] + "' value='" + e + "'>" + i["type"] + "</option>").appendTo(tmp);
                });
            },
            "json"
        );
    }

    // two ----- three
    $(".select-type .goto-pubdetail").click(function() {
        var flag = false;

        $.each($(".type-img"), function() {
            if ($(this).hasClass("select")) {
                flag = true;
            }
        });

        if (!flag) {
            alert("必须选择一个类别才能进入下一步");
            return;
        }

        //change
        $(".pub-detail").css("display", "block");
        $(".select-type").css("display", "none");

        $(".step-one").removeClass("step-selected");
        $(".step-two").addClass("step-selected");
    });

    //事件委托 防止输入为空
    $("#itm-info").delegate("input", "blur", function(e) {
        var target = $(e.target),
            alertBox = $(".pub-detail .alert-danger"),
            targetVal = $.trim(target.val());

        if (targetVal === "") {
            target.css({
                border: "1px solid red"
            });
            canSubmit = false;
            alertBox.text("填入的内容不能为空");
            return;
        }

        
        if (target.hasClass("item-user-phone")) {
            if (!isPhone.test(targetVal)) {
                target.css({
                    border: "1px solid red"
                });
                canSubmit = false;
                alertBox.text("数字输入不符合规范");
                $(this).parent().find(".notice").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/error.png' />");
                return;
            } 
        } else if (target.hasClass("item-user-name") || target.hasClass("item-title")){
            if (!isString.test(targetVal)) {
                target.css({
                    border: "1px solid red"
                });
                canSubmit = false;
                alertBox.text("字符输入过长或出现非法字符!");
                $(this).parent().find(".notice").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/error.png' />");
                return;
            }
        } else if (target.hasClass("item-price")) {
            if (!isPrice.test(targetVal)) {
                target.css({
                    border: "1px solid red"
                });
                canSubmit = false;
                alertBox.text("请输入合法的字符!");
                $(this).parent().find(".notice").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/error.png' />");
                return;
            }
        }
        
        $(this).parent().find(".notice").html("<img class='valid-tip' src='http://t.xiaoplus.com/style/img/common/icon/ok.png' />");
        canSubmit = true;
        
        alertBox.text("请填入您的物品信息");

    });

    //事件委托 获取焦点的时候边框变回原样
    $(".pub-detail").delegate("input", "focus", function(e) {
        var target = $(e.target);

        target.css({
            border: "1px solid #cccccc"
        });
    });


    //图片框的切换
    $(".img-select").delegate("img", "click", function(e) {
        var target = $(e.target);

        $.each($(".img-select img"), function() {
            $(this).removeClass("select");
        });

        target.addClass("select");
        
        //根据缩略图显示大图
        var imgpath = $(this).attr("src").split("/");

        var imgname = imgpath.pop().substring(2);
        imgpath.push(imgname);
        //imgpath.join("/");
        $(".itm-img").attr("src", $(this).attr("src").replace("m_", ""));
    });


    var imgCount = 0;
    var imgPathArray = [];
    //实现图片一选择就上传、ajax
    $("#upload-img-input").bind("change", function() {

        //取消的时候不上传
        if ($(this).val() === "") {
            return ;
        }
        $("#loading").css("visibility", "visible");
        $("#upload-itm-img").ajaxSubmit({
            type: "post",
            success: function(msg) {
                imgCount++;
                $("#loading").css("visibility", "hidden");
               
                var thumbImgPath = msg.savepath + "m_" + msg.savename,
                    imgPath = msg.savepath + msg.savename;
                    imgPathArray.push(imgPath);


                $("<img src='" + APP + "/" + thumbImgPath + "' />").appendTo($(".img-select"));
                $(".itm-img").attr("src", imgPath);
                if (imgCount === 4){
                     $("#upload-itm-img").css({display: "none"});
                     $("#upload-img-input").unbind("change").css({display: "none"});
                }
                $("#upload-itm-img span").text("再次上传图片"); 
            },
            dataType: "json"
        });
    });

    //上一步  返回到选择大类
    $("#turnback").click(function() {
        $(".select-type").css({
            display: "block"
        });
        $(".pub-detail").css("display", "none");
        $(".step-one").addClass("step-selected");
        $(".step-two").removeClass("step-selected");
	$("#itm-sub-type").html("");
    });

    function pubDetail() {
        
        //验证
        var title = $("#itm-title"),
            titleVal = $.trim(title.val()),
            subTypeVal = $("#itm-sub-type option:selected").attr("type_id"),
            price = $("#itm-price"),
            priceVal = $.trim(price.val()),
            sale = $("#itm-sale"),
            saleVal = $.trim(sale.val()),
            phone = $("#itm-phone"),
            phoneVal = $.trim(phone.val()),
            isbuy = $(".itm-by-type input:checked").val(),
            schoolVal = $("#itm-school").attr("school_id"),
            dealTypeVal = $(".itm-deal-type input:checked").val(),
            alertBox = $(".pub-detail .alert-danger");

        if (!canSubmit) {
            alertBox.text("请填写完毕再进行提交!");
            return;
        }

        if (titleVal == "" || priceVal == "" || phoneVal == "" || saleVal == "") {
            alertBox.text("请填写完毕再进行提交!");
            canSubmit = false;
            return;
        }
       
        //同步编辑器的内容
        if (editor.hasContents()) {
            editor.sync();
        }

        $("#pub").addClass("disabled").unbind("click").html("正在发布...");

        $.post(
            URL + "/setPubInsert", {
                "sub_type": subTypeVal,
                "title": titleVal,
                "type": typeId,
		"by": isbuy,
                "price": priceVal,
                "phone": phoneVal,
                "saler": saleVal,
                "school": schoolVal,
                'isbuy': dealTypeVal,
                "image_data": imgPathArray,            //这边还要完善
                "content": editor.getContent()
            },
            function(msg) {
                if (msg) {
			//	alert();
                    $("#pub").removeClass("disabled").html("发布成功");
                    location.replace(domainExtraURL + "/Detail/index/id/" + msg);             
                } else {
                    $("#pub").removeClass("disabled").bind("click").html("发布失败，重新发布!");
                }
            },
            "json"
        );
    }

    //点击发布按钮
    $("#pub").bind("click", pubDetail);
});