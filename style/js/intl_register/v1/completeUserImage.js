$(function(){
    $("#userImageSubmit .btn").click(function(){
          try {
              $.post(
                APP + "/Account/cropImg",
                {"selection": ias.getSelection(), "imgPath": imgPath},
                function(msg){
                    window.location.replace(domainExtraURL);
                }
             );
          } catch(e) {
              window.location.replace(domainExtraURL);
          }
    });

    var imgPath,
        imgName,
        ias;    //图像文件的句柄

     var preview = function(img, selection) {
        var scaleX = 150 / (selection.width || 1);
        var scaleY = 150 / (selection.height || 1);

        $("#user-img-preview img").css({
            width: Math.round(scaleX * img.width) + 'px',
            height: Math.round(scaleY * img.height) + 'px',
            marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
            marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
        });
     }

    //实现图片一选择就上传、ajax
    $("#new-head").change(function(){
        $("#loading").css("visibility", "visible");

        $("#completeInfoForm").ajaxSubmit({
            type: "post",
            success: function(msg){

                $("#loading").css("visibility", "hidden");
                $("#user-img").css("visibility", "visible");
                console.log(msg);
                
                imgPath = msg.savepath + msg.savename;
                imgName = msg.savename;

                $('#user-img').attr("src", imgExtraURL + "/" + imgPath);
                $("#user-img-preview div img").attr("src", imgExtraURL + "/" + imgPath);


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