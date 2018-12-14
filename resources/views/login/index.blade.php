<!doctype html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>MRSSO单点登录验证中心</title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" type="text/css" href="lib/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="lib/font-awesome/css/font-awesome.css">

    <script src="lib/jquery-1.11.1.min.js" type="text/javascript"></script>

    <link rel="stylesheet" type="text/css" href="stylesheets/theme.css">
    <link rel="stylesheet" type="text/css" href="stylesheets/premium.css">
    <style type="text/css">
        #line-chart {
            height:300px;
            width:800px;
            margin: 0px auto;
            margin-top: 1em;
        }
        .navbar-default .navbar-brand, .navbar-default .navbar-brand:hover { 
            color: #fff;
        }
        .none {
            display: none;
        }
        .notic {
            color: red;
            display: none;
        }
    </style>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="lib/html5.js"></script>
    <![endif]-->
  

    <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
    <!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
    <!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
    <!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
    <!--[if (gt IE 9)|!(IE)]><!--> 
   
    <!--<![endif]-->
</head>
<body class=" theme-blue">
    <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <a class="" href="index.html"><span class="navbar-brand"><span class="fa fa-paper-plane"></span> 用户登录</span></a></div>

        <div class="navbar-collapse collapse" style="height: 1px;">

        </div>
      </div>
    </div>

    <div class="dialog">
    <div class="panel panel-default">
        <p class="panel-heading no-collapse">用户登陆</p>
        <div class="panel-body" style="text-align:center;" id="loading"><img src="images/loading-icons/loading7.gif"></div>
        <div id="login-form" class="panel-body none">
            <div class="form-group notic" id="msg"></div>
            <div class="form-group">
                <label>用户帐号</label>
                <input type="text" class="form-control span12" id="username">
            </div>
            <div class="form-group">
            <label>用户密码</label>
                <input type="password" class="form-control span12 form-control" id="password">
            </div>
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <a href="javascript:;" class="btn btn-primary pull-right" id="submit">登陆</a>
            <label class="remember-me"><input type="checkbox"> 记住</label>
            <div class="clearfix"></div>
        </div>
    </div>
    </div>
    <script src="lib/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="crypto/md5.js" ></script>
    <script type="text/javascript" src="lib/seajs/sea.js" ></script>

    <script type="text/javascript">
        $(function() {
            var match = document.cookie.match(new RegExp('color=([^;]+)'));
            if(match) var color = match[1];
            if(color) {
                $('body').removeClass(function (index, css) {
                    return (css.match (/\btheme-\S+/g) || []).join(' ')
                })
                $('body').addClass('theme-' + color);
            }

            $('[data-popover="true"]').popover({html: true});
            
        });

        $(function() {
            var uls = $('.sidebar-nav > ul > *').clone();
            uls.addClass('visible-xs');
            $('#main-menu').append(uls.clone());
        });

        seajs.config({
            base: "/lib/seajs/modules/",
            alias : {
                "jquery" : "jquery.js"
            }
        });

        seajs.use('login', function(login) {
            login.submit();//侦听登录按钮的点击事件
            login.prelogin();//取得一次性的密钥
        });

    </script>
  
</body></html>