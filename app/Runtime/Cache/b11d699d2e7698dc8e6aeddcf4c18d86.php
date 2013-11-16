<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title><?php if(intval(htmlentities($_GET['sub'])) == 0): ?>全部<?php else: echo (getpubtype(intval(htmlentities($_GET['sub'] )))); endif; ?>&nbsp;-&nbsp;<?php if(intval(htmlentities($_GET['type'])) == 0): ?>全部<?php else: echo (getpubtype(intval(htmlentities($_GET['type'])))); endif; ?>&nbsp;-&nbsp;校淘</title><meta name="description" content="校淘是一个学生校园生活信息平台, 包括学生二手, 学生兼职, 学生团购信息" /><meta name="keywords" content="校淘, 广州大学城二手信息, 广州大学城兼职, 广州大学城校园生活平台" /><meta name="google-site-verification" content="3cLhFP3TzXq6q0G3WNsv_kCjk9Enwb6pjv5dNyuU1SA" /><meta name="baidu-site-verification" content="G0zgKN7TA4" /><meta name="360-site-verification" content="957d07164ae6d60113e64742e4479173" /><meta property="qc:admins" content="033324556011704536375" /><script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=3167760632" type="text/javascript" charset="utf-8"></script><script type="text/javascript">
    var APP = "__APP__",
        URL = "__URL__",
        domainExtraURL = "domainURL",
        imgExtraURL = "imgURL",
        staticExtraURL = "staticURL";

</script><link rel="shortcut icon" type="image/x-icon" href="/style/img/common/icon/xiaotao.ico" /><link type="text/css" rel="stylesheet" href="/style/css/common/public.css?v=<?php echo ($VERSION); ?>" /><link type="text/css" rel="stylesheet" href="/style/css/common/header_all.css?v=<?php echo ($VERSION); ?>" /><link type="text/css" rel="stylesheet" href="/style/css/common/search_one.css?v=<?php echo ($VERSION); ?>" /><link type="text/css" rel="stylesheet" href="/style/css/common/footer_all.css?v=<?php echo ($VERSION); ?>" /><link type="text/css" rel="stylesheet" href="/style/css/common/school-select.css?v=<?php echo ($VERSION); ?>" /><link type="text/css" rel="stylesheet" href="/style/css/intl_search/v1/search_list.css?v=<?php echo ($VERSION); ?>" /></head><body><div class="header"><div class="nav-top"><ul class="school-select"><li><strong><?php echo ($schoolName); ?></strong><a class="school-select-link" href="javascript:void(0);">[切换学校]</a></li></ul><ul class="user-info-wrap"><?php if(isset($_SESSION['uname'])): ?><li class="my-account-menu"><a href="domainURL/Account/info"><img class="header-user-img" src="imgURL/<?php echo ($_SESSION['user-img']); ?>" alt="<?php echo ($_SESSION['uname']); ?>" title="<?php echo ($_SESSION['uname']); ?>"/></a><a href="domainURL/Account/info" class="user-uname"><?php echo ($_SESSION["uname"]); ?><i class="tri-dropdown tri"></i></a><ul class="dropdown-account"><li><a href="domainURL/Account/info" >个人中心</a></li><li><a href="javascript:void(0);" id="loginout" class="login-out">退出</a></li></ul></li><?php else: ?><li class="account-login-register"><a href="domainURL/Login" id="header-active-login" class="header-login-btn">会员登陆</a><i class="vertical-bar"></i><a href="domainURL/Register" id="header-active-register">免费注册</a></li></li><?php endif; ?><li class="site-back separator-left"><a href="domainURL">返回主页</a></li></ul></div></div><!--搜索栏--><div class="search-wrap"><div class="search-upper"><a href="domainURL" class="site-logo"><img src="/style/img/common/icon/logo.png?v=<?php echo ($VERSION); ?>" alt="<?php echo ($schoolName); ?>" title="<?php echo ($schoolName); ?>"/><h1 style="height:0;width:0;margin:0;padding:0;display:block;font-size:0">校淘</h1></a><div class="school-info"><h2 title="<?php echo ($schoolName); ?>"><?php echo ($schoolName); ?></h2><a class="school-select-link" href="javascript:void(0)">[切换学校]</a></div><div class="search-box-wrap"><input type="search" class="search-input-box" id="search-input-box" placeholder="想找什么? 输入类别名称或者关键词试试" /><div class="btn btn-blue site-search-btn">搜索</div><div class="site-recommend"><a href="http://t.xiaoplus.com/Detail/index/id/204">联想笔记本</a><a href="#"></a><a href="#"></a></div></div><a href="domainURL/Pub" class="btn btn-blue site-publish">免费发布信息</a></div></div><!--搜索栏--><div class="nav-menu-wrap"><div class="nav-box"><a href="domainURL" class="nav-item current">首页</a><a href="domainURL" class="nav-item">校淘二手</a></div></div><div class="site-main bg-index-site"><div class="site-body"><div class="select-box" id="select-box"><div class="crumb search-index-crumb"><a href="domainURL">校淘</a> ><a href="domainURL/Search/index?type=<?php echo (intval(htmlentities($_GET['type']))); ?>&sub=0&low=0&high=20000" class="crumb-selected"><?php if(intval(htmlentities($_GET['type'])) == 0): ?>全部 <?php else: echo (getpubtype(intval(htmlentities($_GET['type'])))); endif; ?></a></div><dl class="select-itm select-itm-type" id="type"><dt>大类:</dt><dd><a href="domainURL/Search/index?type=0&sub=<?php echo (intval(htmlentities($_GET['sub']))); ?>&low=<?php echo (intval(htmlentities($_GET['low']))); ?>&high=<?php echo (intval(htmlentities($_GET['high']))); ?>" typeId="0">全部</a><?php if(is_array($typeData)): $i = 0; $__LIST__ = $typeData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="domainURL/Search/index?type=<?php echo ($vo['type_id']); ?>&sub=<?php echo (intval(htmlentities($_GET['sub']))); ?>&low=<?php echo (intval(htmlentities($_GET['low']))); ?>&high=<?php echo (intval(htmlentities($_GET['high']))); ?>" typeId="<?php echo ($vo['type_id']); ?>"><?php echo ($vo["type"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?></dd></dl><dl class="select-itm select-itm-subType" id="subType"><dt>小类:</dt><dd><a  href="domainURL/Search/index?type=<?php echo (intval(htmlentities($_GET['type']))); ?>&sub=0&low=<?php echo (intval(htmlentities($_GET['low']))); ?>&high=<?php echo (intval(htmlentities($_GET['high']))); ?>" subTypeId ="0">全部</a><?php if(is_array($subTypeData)): $i = 0; $__LIST__ = $subTypeData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="domainURL/Search/index?type=<?php echo (intval(htmlentities($_GET['type']))); ?>&sub=<?php echo ($vo['type_id']); ?>&low=<?php echo (intval(htmlentities($_GET['low']))); ?>&high=<?php echo (intval(htmlentities($_GET['high']))); ?>" subTypeId="<?php echo ($vo['type_id']); ?>"><?php echo ($vo['type']); ?></a><?php endforeach; endif; else: echo "" ;endif; ?></dd></dl><dl class="select-itm select-itm-price" id="price"><dt>价格:</dt><dd><a href="domainURL/Search/index?type=<?php echo (intval(htmlentities($_GET['type']))); ?>&sub=<?php echo (intval(htmlentities($_GET['sub']))); ?>&low=0&high=20000" priceId="0_20000">全部</a><?php if(is_array($priceListData)): $i = 0; $__LIST__ = $priceListData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="domainURL/Search/index?type=<?php echo (intval(htmlentities($_GET['type']))); ?>&sub=<?php echo (intval(htmlentities($_GET['sub']))); ?>&low=<?php echo (preg_replace( '/_/', "&high=", $vo['range'] )); ?>" priceId="<?php echo ($vo['range']); ?>"><?php echo (preg_replace( '/_/', "-", $vo['range'])); ?></a><?php endforeach; endif; else: echo "" ;endif; ?></dd></dl><div class="itm-select-line"><a href="domainURL/Search/index?type=<?php echo (intval(htmlentities($_GET['type']))); ?>&sub=<?php echo (intval(htmlentities($_GET['sub']))); ?>&low=<?php echo (intval(htmlentities($_GET['low']))); ?>&high=<?php echo (intval(htmlentities($_GET['high']))); ?>&buy=0&order=<?php echo (intval(htmlentities($_GET['order']))); ?>" class="select-label" title="全部">全部</a><a href="domainURL/Search/index?type=<?php echo (intval(htmlentities($_GET['type']))); ?>&sub=<?php echo (intval(htmlentities($_GET['sub']))); ?>&low=<?php echo (intval(htmlentities($_GET['low']))); ?>&high=<?php echo (intval(htmlentities($_GET['high']))); ?>&buy=1&order=<?php echo (intval(htmlentities($_GET['order']))); ?>" class="select-label-sell" title="只显示出售的信息">出售</a><a href="domainURL/Search/index?type=<?php echo (intval(htmlentities($_GET['type']))); ?>&sub=<?php echo (intval(htmlentities($_GET['sub']))); ?>&low=<?php echo (intval(htmlentities($_GET['low']))); ?>&high=<?php echo (intval(htmlentities($_GET['high']))); ?>&buy=2&order=<?php echo (intval(htmlentities($_GET['order']))); ?>" class="select-label-buy" title="只显示求购信息">求购</a><div class="school-only"><input type='checkbox' <?php if($_COOKIE['xt_school_only'] == 1): ?>checked="true"<?php endif; ?> id="school-only-checkbox"/><label for="school-only-checkbox"><strong title="只浏览中山大学的信息"><?php echo ($schoolName); ?></strong></label></div><a href="domainURL/Search/index?type=<?php echo (intval(htmlentities($_GET['type']))); ?>&sub=<?php echo (intval(htmlentities($_GET['sub']))); ?>&low=<?php echo (intval(htmlentities($_GET['low']))); ?>&high=<?php echo (intval(htmlentities($_GET['high']))); ?>&buy=<?php echo (intval(htmlentities($_GET['buy']))); ?>&order=1" class="itm-order-time" title="时间从新到旧">按时间<i class="icons-sprite"></i></a><a href="domainURL/Search/index?type=<?php echo (intval(htmlentities($_GET['type']))); ?>&sub=<?php echo (intval(htmlentities($_GET['sub']))); ?>&low=<?php echo (intval(htmlentities($_GET['low']))); ?>&high=<?php echo (intval(htmlentities($_GET['high']))); ?>&buy=<?php echo (intval(htmlentities($_GET['buy']))); ?>&order=2" class="itm-order-price" title="价格从低到高">按价格<i class="icons-sprite"></i></a><a href="domainURL/Search/index?type=<?php echo (intval(htmlentities($_GET['type']))); ?>&sub=<?php echo (intval(htmlentities($_GET['sub']))); ?>&low=<?php echo (intval(htmlentities($_GET['low']))); ?>&high=<?php echo (intval(htmlentities($_GET['high']))); ?>&buy=<?php echo (intval(htmlentities($_GET['buy']))); ?>&order=0" class="itm-order" title="默认排序">默认排序</a></div></div><input type="hidden" value="<?php echo ($_SESSION['areaId']); ?>" id="areaId" /><input type="hidden" value="<?php echo ($_SESSION['schoolId']); ?>" id="schoolId" /><div class="content-search"><div class="content-list"><div class="content-title"><h3>信息列表</h3></div><ul class="itm-list-box"><?php if(is_array($goods)): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="itm-list"><a class="itm-picture" href="domainURL/Detail/index/id/<?php echo ($vo['pub_id']); ?>" target="_blank"><img src="imgURL/<?php echo ($vo['image_data']); ?>" alt="<?php echo ($vo['title']); ?> | 校淘"/></a><div class="itm-info"><div class="itm-upper-box"><a class="itm-title" title="<?php echo ($vo['title']); ?>" href="domainURL/Detail/index/id/<?php echo ($vo['pub_id']); ?>" target="_blank"><strong><?php echo ($vo["title"]); ?></strong><span>&nbsp;[<?php echo ($vo["sum"]); ?>图]&nbsp;<?php if($vo['by'] == 1): ?>[商家]<?php else: ?>[个人]<?php endif; ?></span></a><div class="itm-price"><strong title="<?php echo ($vo['price']); ?>"><?php if(is_numeric($vo['price'])): echo ($vo['price']); ?>元</strong><?php else: echo ($vo['price']); ?></strong><?php endif; ?></div></div><div class="itm-content-box"><?php echo ($vo["content"]); ?></div><div class="itm-other-box"><a href="domainURL/Account/user/u/<?php echo ($vo['xiaoplus']); ?>" title="<?php echo ($vo['saler']); ?>" class="itm-other-info"><?php echo ($vo['saler']); ?>&nbsp;&nbsp;|</a><span class="itm-other-info"><?php echo ($vo['school']); ?>&nbsp;&nbsp;|</span><span class="itm-other-info"><?php echo (date("m月d日",$vo["ctime"])); ?></span></div></div></li><?php endforeach; endif; else: echo "" ;endif; ?><li id="getMore" class="get-more" index=1 ><div style="display: none"><img src='/style/img/common/icon/loading.gif' alt="loading"/><span style='vertical-align: top; margin-left: 20px;'>正在加载...</span></div><span class="more">更多</span></li></ul></div><div class="side-bar"><strong class="side-bar-title">热门推荐</strong><ul class="side-bar-list"><?php if(is_array($sider)): $i = 0; $__LIST__ = $sider;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="side-bar-item"><a href="domainURL/Detail/index/id/<?php echo ($vo['pub_id']); ?>"><img src="imgURL/<?php echo ($vo['image_data']); ?>" /><strong title="<?php echo ($vo['title']); ?>"><?php echo ($vo["title"]); ?></strong></a></li><?php endforeach; endif; else: echo "" ;endif; ?></ul></div></div></div></div><a href = "javascript:scroll(0,0)" class = "totop"><img src = "/style/img/intl_search/icon/totoparrow.png" class = "totoptar"/></a><div class="footer"><!-- 意见反馈start --><div class = "feedback_status"><div class = "feedback_title">意见反馈</div></div><div class = "feedback_body"><div class = "feedback_contents"><div class="feedback-header"><strong style="float: left;">意见反馈</strong><span class="close-btn"></span></div><div class = "feedback_text"></div><p class = "feedback_tips"></p><textarea class="feedback_words" placeholder = "请输入您的建议，感谢您的支持" style="resize:none"></textarea></div><button class = "feedback_send">发送</button></div><!-- 意见反馈end--><!--ul class = "partner-block"><li><span class = "aboutPartner">合作伙伴:</span><a href="http://sysumedia.com/" class = "partner"><img src = "http://t.xiaoplus.com/style/img/common/btn/yixian.png" alt = "逸仙"></a></li></ul--><ul class="help-block"><li class = "aboutXiaotao"><a href="domainURL/Register/deal" target="_blank"><span>使用条款</span></a><span>|</span></li><li class = "aboutXiaotao"><a href="domainURL/About/aboutUs.html"><span>关于校淘</span></a><span>|</span></li><li class = "aboutXiaotao"><a href="http://weibo.com/u/3536655650"><span>加入校淘</span></a><span>|</span></li><li class = "aboutXiaotao"><a href="http://weibo.com/u/3536655650"><span>联系校淘</span></a><span>|</span></li><li class = "aboutXiaotao"><a href="http://weibo.com/u/3536655650"><span>广告合作</span></a><!--span>|</span--></li><!--li class = "aboutXiaotao"><a href="dmoainURL/sitemap.html"><span>网站地图</span></a></li--><li></li></ul><dl class="partner-block"><dt>合作伙伴: </dt><dd><a href="http://sysumedia.com/" class="partner">
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
</script><script type='text/javascript' src='/style/js/common/school-select.js'></script><script type="text/javascript">
        var typeId = "<?php echo (intval(htmlentities($_GET['type']))); ?>",
            subTypeId = "<?php echo (intval(htmlentities($_GET['sub']))); ?>",
            schoolId = "<?php echo ($_SESSION['schoolId']); ?>",
            priceId = "<?php echo (intval(htmlentities($_GET['low']))); ?>_<?php echo (intval(htmlentities($_GET['high']))); ?>",

            itmSelect = "<?php echo (intval(htmlentities($_GET['buy']))); ?>" === "" ? 0 : "<?php echo (intval(htmlentities($_GET['buy']))); ?>",
            orderList = "<?php echo (intval(htmlentities($_GET['order']))); ?>" === "" ? 0 : "<?php echo (intval(htmlentities($_GET['order']))); ?>";

        //交易类型
        if (itmSelect === 1) {
            $(".select-label-sell").addClass("active");
        } else if (itmSelect === 2){
            $(".select-label-buy").addClass("active");
        } else {
            $(".select-label").addClass("active");
        }

        //排序
        if (orderList === 1) {
            $(".itm-order-time").addClass("active");
        } else if (orderList === 2){

            $(".itm-order-price").addClass("active");
        } else {
            $(".itm-order").addClass("active");
        }
    </script><script type="text/javascript" src="/style/js/intl_search/v1/search_list.js?v=<?php echo ($VERSION); ?>"></script></body></html>