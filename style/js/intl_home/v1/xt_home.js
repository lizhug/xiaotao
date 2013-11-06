 $(function() {

    //banner幻灯片
    var unslider = $('.banner').unslider({
        speed: 500,
        delay: 5000,
        keys: true,
        dots: true,
        fluid: false
    });

    var data = unslider.data("unslider");

    /**************幻灯片end*****************/

    /********************右侧面板切换*************/
    var t;   //时间变量
    var isRun = false;
    function setIsRunFalse() {
            isRun = false;
    }

    var panelChange = function(){

        //匿名函数传递参数
        (function(obj){
            return t = setTimeout(function(){
                var num = parseInt(obj.attr("class").split("-")[2]);
                $.each($(".panel-guide > li"), function(){
                        $(this).removeClass("selected");
                });

                obj.addClass("selected");

                $.each($(".panel-bd > div"), function(){
                    if ($(this).attr("class") == "panel-detail-" + num){
                        $(this).css("display", "block");
                        isRun = true;
                        setTimeout(setIsRunFalse,4000);
                        rolling($(".panel-detail-" + num));
                    } else {
                        $(this).css("display", "none");
                    }
                });
            }, 200);
        }($(this)));
    };

    $.each($(".panel-guide > li"), function(){
        $(this).bind({
            "mouseover": panelChange,
            "mouseout": function(){
                clearTimeout(t);
            }
        });
    });

    /***************切换end*****************/


    /*****************纵向右侧走马灯**************/
    function rolling ($obj){
        var roll=$obj.height();
        var list=$obj.children("ul").height();

        if(list>roll){
            var martop=0;
            // $obj.children("ul li").clone().appendTo($obj.children("ul"));
            function rollText(){
                if(isRun)
                    clearInterval(troll);
                martop += 51;

                if(martop>(list - roll)){
                    martop=0;
                }
                $obj.children("ul").animate({
                    "margin-top":-martop
                },"linear");
            }
            var troll=setInterval(rollText,3000);
            $obj.hover(
                function(){
                    clearInterval(troll);
                },
                function(){
                    troll=setInterval(rollText,3000);
                }
            );
        }
    };
    rolling($(".panel-detail-1"));

    /*******************走马灯end******************/
});