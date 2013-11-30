$(function(){
    var imgPath,            //图片相对路径+名字
        imgName,            //图片名字
        ias;    //图像文件的句柄

     //图片选取预览函数
    function preview(img, selection) {
        var scaleX = 150 / (selection.width || 1);
        var scaleY = 150 / (selection.height || 1);

        $("#user-img-preview img").css({
            width: Math.round(scaleX * img.width) + 'px',
            height: Math.round(scaleY * img.height) + 'px',
            marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
            marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
        });
     }

    //点击保存按钮
    $("#save").click(function(){
        if (ias === undefined){
            alert("请上传图片");
            return ;
        }

        $.post(
            URL + "/cropImg",
            {"selection": ias.getSelection(), "imgPath":  imgPath},         //这边必须用相对路径 插件特性所致 
            function(msg){
                if(!!msg){
                    alert("头像修改成功");
                    window.location.reload();
                } else {
                    alert("修改失败! 请检查您的文件是否有问题");
                }
            }
         );
    });

    //取消按钮
     $("#cancel").click(function(){
         window.location.reload();
    });

    //实现图片一选择就上传、ajax
    $("#new-head").change(function(){
        $("#loading").css("visibility", "visible");         //菊花转转转
        $("#form").ajaxSubmit({
            type: "post",
            success: function(msg){
                $("#loading").css("visibility", "hidden");
                $("#user-img").css("visibility", "visible");
               
                imgPath = msg.savepath + msg.savename;
                imgName = msg.savename;

                $('#user-img').attr("src", imgExtraURL + "/" + imgPath);
                $("#user-img-preview div img").attr("src", imgExtraURL + "/" + imgPath);

                //选中图片
                ias = $('#user-img').imgAreaSelect({
                    aspectRatio: '1:1',
                    persistent : true,  // true，选区以外点击不会启用一个新选区（只能移动/调整现有选区） 
                    maxWidth: 500,
                    maxHeight: 400,
                    minHeight: 100,
                    instance: true,
                    x1: 20,
                    y1: 90,
                    x2: 180,
                    y2: 250,
                    handles: true,
                    onSelectChange: preview
                });
            },
            dataType: "json"
        }); 
    });
});


