$(function() {

        //大类
        obj = $("#type a[typeId = " + typeId + "]");
        obj.addClass("selected");

        //小类
        obj = $("#subType a[subTypeId = " + subTypeId + "]");
        obj.addClass("selected");

        //价格
        obj = $("#price a[priceId = " + priceId + "]");
        obj.addClass("selected");


        $("#getMore").click(function() {

                $("#getMore .more").css("display", "none");
                $("#getMore div").css("display", "block");

                $.ajax({
                        type: "post",
                        url: URL + "/getDataAjax",
                        data: {
                            "p": parseInt($("#getMore").attr('index'), 10),
                            "typeId": $("#type a.selected").attr("typeId"),
                            "subTypeId": $("#subType a.selected").attr("subTypeId"),
                            "lowPrice": $("#price a.selected").attr("priceId").split("_")[0],
                            "highPrice": $("#price a.selected").attr("priceId").split("_")[1],
							'buy': itmSelect,
							'order': orderList
                        },
                        success: function(msg) {

                            var tmp = parseInt($("#getMore").attr("index"), 10);
                            $("#getMore").attr("index", ++tmp);

                            //add
                            var length = msg === null ? 0 : msg.length;

                            for (var i = 0; i != length; ++i) {
                                $("<li class='itm-list'><a class='itm-picture' href='" + domainExtraURL + "/Detail/index/id/" + msg[i].pub_id + "' target='_blank'><img src='" + imgExtraURL + "/" + msg[i].image_data + "' alt='itm-list-picture'/></a><div class='itm-info'><div class='itm-upper-box'><a class='itm-title' href='" + domainExtraURL + "/Detail/index/id/" + msg[i].pub_id + "target='_blank' title='" + msg[i].title + "'><strong>" + msg[i].title + "</strong><span>&nbsp;[" + msg[i].sum + "图]</span></a><div class='itm-price'> <strong>" + msg[i].price + "</strong>元</div></div><div class='itm-content-box'>" + msg[i].content +"</div><div class='itm-other-box'><span class='itm-other-info'>" + msg[i].saler + "&nbsp;&nbsp;&nbsp;|</span><span class='itm-other-info'>" + msg[i].school + "&nbsp;&nbsp;&nbsp;|</span><span class='itm-other-info'>" + msg[i].ctime + "</span></div></div></li>").insertBefore($("#getMore"));
                            }
                            
                            $("#getMore .more").css("display", "block");
                            $("#getMore div").css("display", "none");
                            
                        },
                        dataType: "json"
                    });
                });
        $(".totoptar").mouseover(function(){
            $(".totoptar").attr("src", "http://t.xiaoplus.com/style/img/intl_search/icon/totopwords.png");
        });
        $(".totoptar").mouseout(function(){
            $(".totoptar").attr("src", "http://t.xiaoplus.com/style/img/intl_search/icon/totoparrow.png");
        });
        
        $(".school-only input, .school-only label").bind("click", schoolOnly);
        
        function schoolOnly(event) {
            var checked = $("#school-only-checkbox").attr("checked");
            
            if (checked === undefined) {
                checked = 0;
            } else {
                checked = 1;
            }
            
            $.ajax({
                type: "post",
                url: APP + "/Search/changeSchoolOnlyCookie", 
                data: {"checked": checked},
                success: function(msg) {
                    window.location.reload();
                }
            });
        }
        
});