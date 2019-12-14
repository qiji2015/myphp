<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title><?php echo $_tpl['page_title'];?>-好鹿自驾</title>
    <link rel="stylesheet" href="/myphp/assets/ui/layui/css/layui.css"  media="all">
    <link rel="stylesheet" type="text/css" href="/myphp/assets/ui/css/style.css">
<!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
<!--[if lt IE 9]>
  <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
  <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
<![endif]--> 
<script type="text/javascript" language="javascript">
function g(o){return document.getElementById(o);}
function HoverLi(n){
for(var i=1;i<=2;i++){g('tb_'+i).className='normaltab';g('tbc_0'+i).className='undis';}g('tbc_0'+n).className='dis';g('tb_'+n).className='hovertab';
}
</script>
</head>
<body>
<div class="layui-header header header-doc">
<div class="layui-container">
	<a class="logo" href="/home/">
      <img src="/myphp/assets/ui/images/logo.png" alt="">
    </a>
    
	<ul class="layui-nav" lay-filter="">
        <li class="layui-nav-item layui-hide-xs layui-this"><a href="/home/">首页</a></li>
        <li class="layui-nav-item layui-hide-xs"><a href="#">目的地</a></li>
        <li class="layui-nav-item layui-hide-xs"><a href="#">游记</a></li>
        <li class="layui-nav-item layui-hide-xs">
          <a href="javascript:;">路况</a>
          <dl class="layui-nav-child">
            <dd><a href="">移动模块</a></dd>
          </dl>
        </li>
  		<li class="layui-nav-item layui-hide-xs"><a href="">社区</a></li>
        
        <li class="layui-nav-item layui-hide-sm">
          <a href="javascript:;">
          	<button type="button" class="navbar-toggle">  
                <span class="sr-only">nav</span>  
                <span class="icon-bar"></span>  
                <span class="icon-bar"></span>  
                <span class="icon-bar"></span>  
            </button>
          </a>
          <dl class="layui-nav-child"> <!-- 二级菜单 -->
            <dd><a href="index.html">首页</a></dd>
            <dd><a href="list.html">目的地</a></dd>
            <dd><a href="info.html">游记</a></dd>
            <dd><a href="#">路况</a></dd>
          </dl>
        </li>
	</ul>
    
    <div class="layui-login layui-hide-xs">
    	<a href="#">注册</a>
    	<a href="#">登陆</a>
    </div>
</div>
</div>