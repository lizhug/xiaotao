<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>个人资料 | 个人中心</title><meta name="description" content="校淘是一个学生校园生活信息平台, 包括学生二手, 学生兼职, 学生团购信息" /><meta name="keywords" content="校淘, 广州大学城二手信息, 广州大学城兼职, 广州大学城校园生活平台" /><meta name="google-site-verification" content="3cLhFP3TzXq6q0G3WNsv_kCjk9Enwb6pjv5dNyuU1SA" /><meta name="baidu-site-verification" content="G0zgKN7TA4" /><meta name="360-site-verification" content="957d07164ae6d60113e64742e4479173" /><meta property="qc:admins" content="033324556011704536375" /><script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=3167760632" type="text/javascript" charset="utf-8"></script><script type="text/javascript">
    var APP = "__APP__",
        URL = "__URL__",
        domainExtraURL = "domainURL",
        imgExtraURL = "imgURL",
        staticExtraURL = "staticURL";

</script><link rel="shortcut icon" type="image/x-icon" href="/style/img/common/icon/xiaotao.ico" /><link type="text/css" rel="stylesheet" href="/style/css/common/public.css?v=<?php echo ($VERSION); ?>" /><link type="text/css" rel="stylesheet" href="/style/css/common/header_all.css?v=<?php echo ($VERSION); ?>" /><link type="text/css" rel="stylesheet" href="/style/css/common/search_one.css?v=<?php echo ($VERSION); ?>" /><link type="text/css" rel="stylesheet" href="/style/css/common/footer_all.css?v=<?php echo ($VERSION); ?>" /><link type="text/css" rel="stylesheet" href="/style/css/common/account-global.css?v=<?php echo ($VERSION); ?>" /><link type="text/css" rel="stylesheet" href="/style/css/intl_account/v1/info.css?v=<?php echo ($VERSION); ?>" /><link type="text/css" rel="stylesheet" href="/style/css/common/school-select.css?v=<?php echo ($VERSION); ?>" /></head><body><div class="header"><div class="nav-top"><ul class="school-select"><li><strong><?php echo ($schoolName); ?></strong><a class="school-select-link" href="javascript:void(0);">[切换学校]</a></li></ul><ul class="user-info-wrap"><?php if(isset($_SESSION['uname'])): ?><li class="my-account-menu"><a href="domainURL/Account/info"><img class="header-user-img" src="imgURL/<?php echo ($_SESSION['user-img']); ?>" alt="<?php echo ($_SESSION['uname']); ?>" title="<?php echo ($_SESSION['uname']); ?>"/></a><a href="domainURL/Account/info" class="user-uname"><?php echo ($_SESSION["uname"]); ?><i class="tri-dropdown tri"></i></a><ul class="dropdown-account"><li><a href="domainURL/Account/info" >个人中心</a></li><li><a href="javascript:void(0);" id="loginout" class="login-out">退出</a></li></ul></li><?php else: ?><li class="account-login-register"><a href="domainURL/Login" id="header-active-login" class="header-login-btn">会员登陆</a><i class="vertical-bar"></i><a href="domainURL/Register" id="header-active-register">免费注册</a></li></li><?php endif; ?><li class="site-back separator-left"><a href="domainURL">返回主页</a></li></ul></div></div><!--搜索栏--><div class="search-wrap"><div class="search-upper"><a href="domainURL" class="site-logo"><img src="/style/img/common/icon/logo.png?v=<?php echo ($VERSION); ?>" alt="<?php echo ($schoolName); ?>" title="<?php echo ($schoolName); ?>"/><h1 style="height:0;width:0;margin:0;padding:0;display:block;font-size:0">校淘</h1></a><div class="school-info"><h2 title="<?php echo ($schoolName); ?>"><?php echo ($schoolName); ?></h2><a class="school-select-link" href="javascript:void(0)">[切换学校]</a></div><div class="search-box-wrap"><input type="search" class="search-input-box" id="search-input-box" placeholder="想找什么? 输入类别名称或者关键词试试" /><div class="btn btn-blue site-search-btn">搜索</div><div class="site-recommend"><a href="http://t.xiaoplus.com/Detail/index/id/204">联想笔记本</a><a href="#"></a><a href="#"></a></div></div><a href="domainURL/Pub" class="btn btn-blue site-publish">免费发布信息</a></div></div><!--搜索栏--><div class="nav-menu-wrap"><div class="nav-box"><a href="domainURL" class="nav-item">首页</a><a href="domainURL" class="nav-item">校淘二手</a></div></div><div class="site-main bg-index-site"><div class="site-body account-content"><div class="user-menu"><div class="user-info-manager"><h2>账户管理</h2><a href="domainURL/Account/info">个人资料</a><a href="domainURL/Account/header">更改头像</a><a href="domainURL/Account/changePassword">修改密码</a></div><div class="user-itm-manager"><h2>信息管理</h2><a href="domainURL/Account/pub">我的发布</a><a href="domainURL/Account/store" >我的收藏</a></div></div><form id="user-detail" class="user-detail" method="post" action="__URL__/infoSave"><div id="intr"><h2>基本资料</h2><ul class="user-detail-list"><li><div class="detail-title">校PLUS ID</div><div class="detail-content" style="border:none"><?php echo ($data['xiaoplus']); ?></div><li><div class="detail-title">用户名</div><input class="detail-content" id="uname" name="uname" value="<?php echo ($data['uname']); ?>" placeholder="用户名" /><li><div class="detail-title">所在地</div><select class="detail-content" style="width: 85px;" id="province" name="province"><?php if(is_array($area)): $i = 0; $__LIST__ = $area;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($data['province'] == $vo['area_id']): ?><option value="<?php echo ($vo['area_id']); ?>" selected='selected'><?php echo ($vo["title"]); ?></option><?php else: ?><option value="<?php echo ($vo['area_id']); ?>" ><?php echo ($vo["title"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?></select><select class="detail-content" style="width: 85px;margin-left: 20px;" id="city" name="city"><?php if(is_array($cityList)): $i = 0; $__LIST__ = $cityList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($data['city'] == $vo['area_id']): ?><option value="<?php echo ($vo['area_id']); ?>" selected='selected'><?php echo ($vo["title"]); ?></option><?php else: ?><option value="<?php echo ($vo['area_id']); ?>" ><?php echo ($vo["title"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?></select><select class="detail-content" style="width: 85px;margin-left: 20px;" id="area" name="area"><?php if(is_array($areaList)): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($data['area'] == $vo['area_id']): ?><option value="<?php echo ($vo['area_id']); ?>" selected='selected'><?php echo ($vo["title"]); ?></option><?php else: ?><option value="<?php echo ($vo['area_id']); ?>" ><?php echo ($vo["title"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?></select></li><li><div class="detail-title">性别</div><select class="detail-content" name="sex" id="sex"><?php if($data['sex'] == 1): ?><option value="0">男</option><option value="1" selected="true">女</option><?php else: ?><option value="0" checked="true">男</option><option value="1">女</option><?php endif; ?></select></li><li><div class="detail-title">生日</div><select name="b-year" class="detail-content" style="width: 90px;" id="b-year"><?php $__FOR_START_1741911946__=2013;$__FOR_END_1741911946__=1920;for($i=$__FOR_START_1741911946__;$i > $__FOR_END_1741911946__;$i+=-1){ if($data['birth'][0] == $i): ?><option value="<?php echo ($i); ?>" selected='true'><?php echo ($i); ?></option><?php else: ?><option value="<?php echo ($i); ?>"><?php echo ($i); ?></option><?php endif; } ?></select><select name="b-month" class="detail-content" style="width: 60px; margin-left: 20px;" id="b-month"><?php $__FOR_START_604624452__=1;$__FOR_END_604624452__=13;for($i=$__FOR_START_604624452__;$i < $__FOR_END_604624452__;$i+=1){ if($data['birth'][1] == $i): ?><option value="<?php echo ($i); ?>" selected='true'><?php echo ($i); ?></option><?php else: ?><option value="<?php echo ($i); ?>"><?php echo ($i); ?></option><?php endif; } ?></select><select name="b-day" class="detail-content" style="width: 60px; margin-left: 20px;" id="b-day"><?php $__FOR_START_247217970__=1;$__FOR_END_247217970__=32;for($i=$__FOR_START_247217970__;$i < $__FOR_END_247217970__;$i+=1){ if($data['birth'][2] == $i): ?><option value="<?php echo ($i); ?>" selected='true'><?php echo ($i); ?></option><?php else: ?><option value="<?php echo ($i); ?>"><?php echo ($i); ?></option><?php endif; } ?></select></li></ul></div><div id="contact"><h2>联系信息</h2><ul class="user-detail-list"><li><div class="detail-title">QQ</div><input class="detail-content" name="qq" value="<?php echo ($profile['data']['qq']); ?>" id="qq" placeholder="QQ号码" /><li><div class="detail-title">绑定邮箱</div><?php if($_SESSION['email'] == ''): ?><input class="detail-content account-email"  value="" /><span class="add-mail btn btn-warn" id="add-mail">添加绑定邮箱</span><?php else: ?><div class="detail-content account-email" style="border:none;"><?php echo ($data['email']); ?></div><?php endif; ?></li></ul></div><div id="edu"><h2>教育信息</h2><ul class="user-detail-list"><li><div class="detail-title">大学</div><input  id="college" class="detail-content" value="<?php echo (getschool($profile['data']['college'])); ?>"><input name="college" id='college-id' type="hidden" value="<?php echo ($profile['data']['college']); ?>" /></li><li><div class="detail-title" >入学年份</div><select class="detail-content" name="year" id="year"><?php $__FOR_START_468563066__=2013;$__FOR_END_468563066__=1949;for($i=$__FOR_START_468563066__;$i > $__FOR_END_468563066__;$i+=-1){ if($profile['data']['year'] == $i): ?><option value="<?php echo ($i); ?>" selected='true'><?php echo ($i); ?></option><?php else: ?><option value="<?php echo ($i); ?>"><?php echo ($i); ?></option><?php endif; } ?></select></li><li><div id="user-submit"><div class="btn btn-blue" id="save">保存</div><div class="btn btn-danger" id="cancel">取消</div></div></li></ul></div></form></div></div><div class="footer"><!-- 意见反馈start --><div class = "feedback_status"><div class = "feedback_title">意见反馈</div></div><div class = "feedback_body"><div class = "feedback_contents"><div class="feedback-header"><strong style="float: left;">意见反馈</strong><span class="close-btn"></span></div><div class = "feedback_text"></div><p class = "feedback_tips"></p><textarea class="feedback_words" placeholder = "请输入您的建议，感谢您的支持" style="resize:none"></textarea></div><button class = "feedback_send">发送</button></div><!-- 意见反馈end--><!--ul class = "partner-block"><li><span class = "aboutPartner">合作伙伴:</span><a href="http://sysumedia.com/" class = "partner"><img src = "http://t.xiaoplus.com/style/img/common/btn/yixian.png" alt = "逸仙"></a></li></ul--><ul class="help-block"><li class = "aboutXiaotao"><a href="domainURL/Register/deal" target="_blank"><span>使用条款</span></a><span>|</span></li><li class = "aboutXiaotao"><a href="domainURL/About/aboutUs.html"><span>关于校淘</span></a><span>|</span></li><li class = "aboutXiaotao"><a href="http://weibo.com/u/3536655650"><span>加入校淘</span></a><span>|</span></li><li class = "aboutXiaotao"><a href="http://weibo.com/u/3536655650"><span>联系校淘</span></a><span>|</span></li><li class = "aboutXiaotao"><a href="http://weibo.com/u/3536655650"><span>广告合作</span></a><!--span>|</span--></li><!--li class = "aboutXiaotao"><a href="dmoainURL/sitemap.html"><span>网站地图</span></a></li--><li></li></ul><dl class="partner-block"><dt>合作伙伴: </dt><dd><a href="http://sysumedia.com/" class="partner">
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
</script><script type="text/javascript" src="/style/js/common/jquery.form.min.js"></script><script type='text/javascript' src='/style/js/intl_account/v1/school-select.js' ></script><script type='text/javascript' src='/style/js/intl_account/v1/info-user-private.js' ></script></body></html>