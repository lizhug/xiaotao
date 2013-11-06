<?php

//定义回调URL通用的URL
define('URL_CALLBACK', 'http://t.xiaoplus.com/Login/callback/type/');

return array(
    'DB_TYPE'               => 'mysql',     // 数据库类型
    'DB_HOST'               => 'localhost', // 服务器地址  xiaotao.db.11241604.hostedresource.com
    'DB_NAME'               => 'xiaotao',          // 数据库名
    'DB_USER'               => 'xiaotao',      // 用户名
    'DB_PWD'                => 'Xiaotao2013',          // 密码
    'DB_PORT'               => '3306',        // 端口
    'DB_PREFIX'             => 'xt_',    // 数据库表前缀
   // 'TMPL_TEMPLATE_SUFFIX'  => '.html',
    'URL_MODEL'             => 2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式，提供最好的用户体验和SEO支持

    'SHOW_PAGE_TRACE' => false, // 显示页面Trace信息
    'APP_VERSION' => 20131103,

    'DEFAULT_MODULE'  => 'Index', // 默认模块名称
    'DEFAULT_ACTION'  => 'index', // 默认操作名称
    'TMPL_PARSE_STRING' => array(
      //  '__PUBLIC__' => './Common'     // 更改默认的__PUBLIC__替换规则
	  "imgURL" => "http://t.xiaoplus.com",        //用户上传的图片地址
	  "staticURL" => "http://t.xiaoplus.com",      //静态文件地址
	  "domainURL" => "http://t.xiaoplus.com"       //网站域名
	  
    ),
	//'ERROR_MESSAGE'         => '您浏览的页面暂时发生了错误！请稍后再试～', //错误显示信息,非调试模式有效
	
    'URL_CASE_INSENSITIVE' => false,			//true则模块名首字母支持小写

    'TOKEN_ON' => true,  // 是否开启令牌验证
    'TOKEN_NAME' => '__hash__',    // 令牌验证的表单隐藏字段名称
    'TOKEN_TYPE' => 'md5',  //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET' => true,  //令牌验证出错后是否重置令牌 默认为true
    //'TMPL_EXCEPTION_FILE' => './Tpl/Public/error.html',
    //'ERROR_PAGE'=>'Public:error',
    'TMPL_ACTION_ERROR'     => 'Public:pageNotFound', // 默认错误跳转对应的模板文件
    
    //腾讯QQ登录配置
    'THINK_SDK_QQ' => array(
        'APP_KEY'    => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK'   => URL_CALLBACK . 'qq',
    ),
    //新浪微博配置
    'THINK_SDK_SINA' => array(
        'APP_KEY'    => '3167760632', //应用注册成功后分配的 APP ID
        'APP_SECRET' => 'f72d4681c75644397d1c4a9ddf506914', //应用注册成功后分配的KEY
        'CALLBACK'   => URL_CALLBACK . 'sina',
    ),
	
    //session设置
    'SESSION_AUTO_START' => false,
    

    //静态文件缓存设置
    "HTML_CACHE_ON" => false,
    'HTML_CACHE_RULES'=> array(
        'Detail:index'            => array('{id}', 0)               //Detail目录的静态文件缓存
    ),

    "DEFAULT_USER_HEADER" => "data/user/default_head.png",                 //用户默认头像地址
    "COOKIE_DOMAIN" => ".xiaoplus.com",         //cookie域名
    
    "EMAIL_ADDR" => "smtp.exmail.qq.com",
    "EMAIL_USER" => "no-reply@xiaoplus.com",
    "EMAIL_PWD" => "NoReply2013",
    "FROM_NAME" => "校PLUS 团队"

)
?>
