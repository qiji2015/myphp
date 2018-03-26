<!doctype html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1" />
<meta name="generator" content="" />
<meta name="robots" content="none" />
<meta name="renderer" content="webkit" />
<title><?php echo $_tpl['blogtitle'] ?></title>
<link href="<?php echo $bloghost ?>zb_system/css/admin2.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo $bloghost ?>zb_system/css/jquery-ui.custom.css"/>
<script src="<?php echo $bloghost ?>zb_system/script/jquery-2.2.4.min.js" type="text/javascript"></script>
<script src="<?php echo $bloghost ?>zb_system/script/zblogphp.js" type="text/javascript"></script>
<script src="<?php echo $bloghost ?>zb_system/script/c_admin_js_add.php" type="text/javascript"></script>
<script src="<?php echo $bloghost ?>zb_system/script/jquery-ui.custom.min.js" type="text/javascript"></script>
</head>
<body>
<header class="header">
    <div class="logo"><a href="<?php echo $bloghost ?>" target="_blank"><img src="<?php echo $bloghost ?>zb_system/image/admin/none.gif"/></a></div>
    <div class="user"> <a href="<?php echo $bloghost ?>zb_system/cmd.php?act=MemberEdt&amp;id=<?php echo $zbp->user->ID ?>" title=""><img src="<?php echo $bloghost ?>/zb_users/avatar/0.png" width="40" height="40" id="avatar" alt="Avatar" /></a>
      <div class="username">管理员：admin</div>
      <div class="userbtn"><a class="profile" href="<?php echo $bloghost ?>" title="" target="_blank">返回</a>&nbsp;&nbsp;<a class="logout" href="<?php echo $bloghost ?>zb_system/cmd.php?act=logout" title="">注销</a></div>
    </div>
    <div class="menu">
      <ul id="topmenu"></ul>
    </div>
</header>
<aside class="left">
  <ul id="leftmenu">
  	<li>
      <a href="<?php echo Qtpl::createUrl('admin', 'index','','admin');?>"><span>所有文章</span></a>
    </li>
    <li>
      <a href="<?php echo Qtpl::createUrl('admin', 'road','','admin');?>"><span>公路管理</span></a>
    </li>
    <li>
      <a href="<?php echo Qtpl::createUrl('admin', 'spot','','admin');?>"><span>景区管理</span></a>
    </li>
    <li>
      <a href="<?php echo Qtpl::createUrl('admin', 'car','','admin');?>"><span>车型管理</span></a>
    </li>
    <li>
      <a href="<?php echo Qtpl::createUrl('admin', 'attr','','admin');?>"><span>属性管理</span></a>
    </li>
  </ul>
</aside>
<section class="main">
