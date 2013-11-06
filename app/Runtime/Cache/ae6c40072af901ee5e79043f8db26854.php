<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>重新获取密码</title><meta name="description" content="校淘是一个学生校园生活信息平台, 包括学生二手, 学生兼职, 学生团购信息" /><meta name="keywords" content="校淘, 广州大学城二手信息, 广州大学城兼职, 广州大学城校园生活平台" /><meta name="google-site-verification" content="3cLhFP3TzXq6q0G3WNsv_kCjk9Enwb6pjv5dNyuU1SA" /><meta name="baidu-site-verification" content="G0zgKN7TA4" /><meta name="360-site-verification" content="957d07164ae6d60113e64742e4479173" /><script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=3167760632" type="text/javascript" charset="utf-8"></script><script type="text/javascript">
    var APP = "__APP__",
        URL = "__URL__",
        domainExtraURL = "domainURL",
        imgExtraURL = "imgURL",
        staticExtraURL = "staticURL";

</script><link rel="shortcut icon" type="image/x-icon" href="/style/img/common/icon/xiaotao.ico" /><link type="text/css" rel="stylesheet" href="/style/css/common/public.css?v=<?php echo ($VERSION); ?>" /><link type="text/css" rel="stylesheet" href="/style/css/common/header_all.css?v=<?php echo ($VERSION); ?>" /><link type="text/css" rel="stylesheet" href="/style/css/common/search_one.css?v=<?php echo ($VERSION); ?>" /><link type="text/css" rel="stylesheet" href="/style/css/common/footer_all.css?v=<?php echo ($VERSION); ?>" /><style type="text/css">          .main {
              display: block;
              min-height: 300px;
              text-align: center;
          }
          .tip {
              padding: 50px 0 50px 0;
              margin: 20px auto 0;
          }
          .tip .wrap {
            width: 600px;
            margin: 0 auto;
            padding-left: 50px;
          }

          .tip .alert {
            width: 600px;
            margin: 30px auto;
          }
       
          .tip .btn {
              margin-top: 40px;
              border-radius: 5px;
          }

        
          .tip label {
            font-size: 16px;
            width: 100px;
            display: block;
            float: left;
            height: 30px;
            padding-top: 5px;
          }
          #pwd, #rpwd {
            height: 30px;
            width: 350px;
            float: left;
            display: block;
            margin-left: 30px;
            margin-bottom: 20px;
            outline: none;
            border: 1px solid #cccccc;
            box-shadow: inset 0px 1px 2px #cccccc;
            padding-left: 10px;
            font-size: 16px;
            color: #333333;
          }
          #pwd:focus, #rpwd:focus {
            box-shadow: none;
            border: 1px solid #A7C0F1;
          }
       
        </style></head><body><div class="header"><div class="nav-top"><ul class="school-select"><li><strong><?php echo ($schoolName); ?></strong><a class="school-select-link" href="javascript:void(0);">[切换学校]</a></li></ul><ul class="user-info-wrap"><?php if(isset($_SESSION['uname'])): ?><li class="my-account-menu"><a href="domainURL/Account/info"><img class="header-user-img" src="imgURL/<?php echo ($_SESSION['user-img']); ?>" alt="<?php echo ($_SESSION['uname']); ?>" title="<?php echo ($_SESSION['uname']); ?>"/></a><a href="domainURL/Account/info" class="user-uname"><?php echo ($_SESSION["uname"]); ?><i class="tri-dropdown tri"></i></a><ul class="dropdown-account"><li><a href="domainURL/Account/info" >个人中心</a></li><li><a href="javascript:void(0);" id="loginout" class="login-out">退出</a></li></ul></li><?php else: ?><li class="account-login-register"><a href="domainURL/Login" id="header-active-login" class="header-login-btn">会员登陆</a><i class="vertical-bar"></i><a href="domainURL/Register" id="header-active-register">免费注册</a></li></li><?php endif; ?><li class="site-back separator-left"><a href="domainURL">返回主页</a></li></ul></div></div><!--搜索栏--><div class="search-wrap"><div class="search-upper"><a href="domainURL" class="site-logo"><img src="/style/img/common/icon/logo.png?v=<?php echo ($VERSION); ?>" alt="<?php echo ($schoolName); ?>" title="<?php echo ($schoolName); ?>"/><h1 style="height:0;width:0;margin:0;padding:0;display:block;font-size:0">校淘</h1></a><div class="school-info"><h2 title="<?php echo ($schoolName); ?>"><?php echo ($schoolName); ?></h2><a class="school-select-link" href="javascript:void(0)">[切换学校]</a></div><div class="search-box-wrap"><input type="search" class="search-input-box" id="search-input-box" placeholder="想找什么? 输入类别名称或者关键词试试" /><div class="btn btn-blue site-search-btn">搜索</div><div class="site-recommend"><a href="http://t.xiaoplus.com/Detail/index/id/204">联想笔记本</a><a href="#"></a><a href="#"></a></div></div><a href="domainURL/Pub" class="btn btn-blue site-publish">免费发布信息</a></div></div><!--搜索栏--><div class="main"><div class="tip"><div class="alert alert-danger">请设置您的新密码</div><div class="wrap"><label for="email">新密码</label><input type="password" id="pwd" placeholder="新密码" /><div class="clear"></div><label for="email">确认密码</label><input type="password" id="rpwd" placeholder="确认密码" /><div class="clear"></div></div><div class="btn btn-blue" id="save">确认</div></div></div><div class="footer"><!-- 意见反馈start --><div class = "feedback_status"><div class = "feedback_title">意见反馈</div></div><div class = "feedback_body"><div class = "feedback_contents"><div class="feedback-header"><strong style="float: left;">意见反馈</strong><span class="close-btn"></span></div><div class = "feedback_text"></div><p class = "feedback_tips"></p><textarea class="feedback_words" placeholder = "请输入您的建议，感谢您的支持" style="resize:none"></textarea></div><button class = "feedback_send">发送</button></div><!-- 意见反馈end--><!--ul class = "partner-block"><li><span class = "aboutPartner">合作伙伴:</span><a href="http://sysumedia.com/" class = "partner"><img src = "http://t.xiaoplus.com/style/img/common/btn/yixian.png" alt = "逸仙"></a></li></ul--><ul class="help-block"><li class = "aboutXiaotao"><a href="domainURL/Register/deal" target="_blank"><span>使用条款</span></a><span>|</span></li><li class = "aboutXiaotao"><a href="domainURL/About/aboutUs.html"><span>关于校淘</span></a><span>|</span></li><li class = "aboutXiaotao"><a href="http://weibo.com/u/3536655650"><span>加入校淘</span></a><span>|</span></li><li class = "aboutXiaotao"><a href="http://weibo.com/u/3536655650"><span>联系校淘</span></a><span>|</span></li><li class = "aboutXiaotao"><a href="http://weibo.com/u/3536655650"><span>广告合作</span></a><!--span>|</span--></li><!--li class = "aboutXiaotao"><a href="dmoainURL/sitemap.html"><span>网站地图</span></a></li--><li></li></ul><dl class="partner-block"><dt>合作伙伴: </dt><dd><a href="http://sysumedia.com/" class="partner">
                逸仙传媒
            </a></dd></dl><div class="copyright">Copyright © 2013 - 2013 All Rights Reserved. <a href="http://www.miitbeian.gov.cn/">粤ICP备1306480号</a></div></div><script type="text/javascript" src="/style/js/common/jquery-1.8.2.min.js?v=<?php echo ($VERSION); ?>"></script><!--script type="text/javascript" src="/style/js/common/jquery.placeholder.1.3.min.js"></script--><script type="text/javascript" src="/style/js/common/public.js?v=<?php echo ($VERSION); ?>"></script><script type="text/javascript" src="/style/js/common/header_all.js?v=<?php echo ($VERSION); ?>"></script><!--ga 代码段--><script type="text/javascript">
	var _gaq = _gaq || [];
	var pluginUrl = 
	 '//www.google-analytics.com/plugins/ga/inpage_linkid.js';
	_gaq.push(['_require', 'inpage_linkid', pluginUrl]);
	  _gaq.push(['_setAccount', 'UA-43069935-1']);
	  _gaq.push(['_setDomainName', '.xiaoplus.com']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

</script><!--百度统计代码段--><script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F18375cebd45a156f1af4e908293651ad' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">	//隐藏反馈页面面板
    $(".feedback_status").click(function() {
        $(".feedback_body").toggle("normal");
    });
    
	
	//关闭按钮
    $(".feedback-header .close-btn").bind("click", closeFeedBack);
    
	
	//点击关闭按钮的响应
    function closeFeedBack() {
         $(".feedback_body").css("display", "none");
    }

	//绑定点击函数
    $(".feedback_send").bind("click", sendFeedback);
	$(".feedback_words").bind("keypress", function(e) {
		if (e.keyCode == 13) {
			sendFeedback();
		}
	});
	
	
	
	
	function sendFeedback() {
        var words = $(".feedback_words").val();
        if(words == "请输入您的建议，感谢您的支持" || words == '') {
            $(".feedback_tips").html("亲，内容不能为空哦~");
            return;
        } else {
            $.ajax({
                type:"post",
                url: APP + "/Public/feedbackSubmit",
                data:{
                    "content": words,
                    "email": ""
                },
                success:function(msg) {
                    if(msg.status == true) {
                        var uname = "<?php echo ($_SESSION['uname']); ?>";
                        if(uname == '')
                            uname = "游客";
                        $(".feedback_text").prepend('<div><p style="padding: 4px;background: #FFFAEC;border:1px solid #26B8C7;"><strong>' + uname + ': </strong> &nbsp;<span style="color: gray;font-size:10px;">(' + msg.data["ctime"] + ')</span><br />' + ' &nbsp&nbsp&nbsp '  + words + '</p><p><strong>校淘: </strong><br />反馈已收到!感谢支持!</p></div>');
                        $(".feedback_tips").html("");
                         $(".feedback_words").val("");
                    } else {
                        alert("提交失败");
                    }
                },
                dataType:"json"
            })
        }
    }
</script><script>           $(function(){
                var tipBox = $("#tip .alert"),
                    isPwd = /^[a-z0-9_-]{6,18}$/i;

                //验证函数
                var inputValid = function(obj, type){
                    obj.bind({
                         blur: function(){
                             var objVal = $.trim(obj.val());
                            //当失去焦点的时候判断是否为空
                            if (objVal === ""){
                                obj.css({border: "1px solid red"});
                                tipBox.text("填写的内容不能为空");
                                return ;
                            } else {
                                obj.css({border: "1px solid #cccccc"});
                                 tipBox.text("请设置您的新密码");
                            }
                          
                            //密码验证
                            if (type === "pwd"){
                                 if (!isPwd.test(objVal)){
                                    obj.css({border: "1px solid red"});
                                    tipBox.text("密码不符合规范");
                                    return ;
                                 } else {
                                    obj.css({border: "1px solid #cccccc"});
                                    tipBox.text("请设置您的新密码");
                                }
                            } else if (type === "rpwd"){
                                if (objVal !== $("#pwd").val()){
                                    obj.css({border: "1px solid red"});
                                    tipBox.text("两次密码输入不一致");
                                    return ;
                                } else {
                                    obj.css({border: "1px solid #cccccc"});
                                    tipBox.text("请设置您的新密码");
                                }
                            }
                         }
                    });
                };

                inputValid($("#pwd"), "pwd");
                inputValid($("#rpwd"), "rpwd");
                //提交的函数
                var myChange =  function(){
                    var newPwd = $("#pwd"),
                        newPwdVal = $.trim(newPwd.val()),
                        rnewPwd = $("#rpwd"),
                        rnewPwdVal = $.trim(rnewPwd.val());
                     

                    //账号和密码为空
                    if (newPwdVal === "" || rnewPwdVal === ""){
                        tipBox.text("密码不能为空");
                        newPwd.focus();
                        return ;
                    } else {
                        tipBox.text("请设置您的新密码");
                    }

                    //密码格式
                    if (!isPwd.test(newPwdVal) || !isPwd.test(rnewPwdVal)){
                        tipBox.text("密码格式错误");
                        return ;
                    } else {
                        tipBox.text("请设置您的新密码");
                    }
                    $("#save").unbind("click");
                    //后台验证用户合法性，若存在则跳转到登录页面
                    $.post(
                        "__URL__/setNewPwd",
                        {"pwd": newPwdVal},
                        function(msg){
                            if (msg.isFit){
                                tipBox.text(msg.msg);
                                window.location.replace("domainURL/Login");
                            } else {
                               tipBox.text(msg.msg);
                               $("#save").bind("click");
                            }
                        },
                        "json"
                    );
                };

                //提交
                $("#save").bind("click", function(){
                    myChange();
                });
         
           });
       </script></body></html>