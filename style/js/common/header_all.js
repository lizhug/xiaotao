$(function(){
     
    //退出
     $("#loginout").click(function(){
         $.post(
            APP + "/Account/loginout",
            function(){
               window.location.reload();
            }
        );
     });
     
     $(".my-account-menu").bind({"mouseover": function() {
         $(this).addClass("hovered");
         $(".dropdown-account").css("display", "block");
     }, "mouseout": function() {
         $(this).removeClass("hovered");
         $(".dropdown-account").css("display", "none");
     }});
});

