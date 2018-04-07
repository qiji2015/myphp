<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>系统提示</title>
<style>
.alert{background-color:#f1f1f1; width:495px;margin:60px auto; font-size:12px; line-height:24px;}
.alert .alert_body{ border:1px solid #cbcbcb;background-color:#fff; width:475px; height:143px; position:relative; top:-5px; left:-5px; padding:10px;}
.alert .alert_body h3{font-size:14px; font-weight:bold; margin:0;}
.alert .alert_body .alertcont{margin:15px 0 0 85px; background:url(/myphp/assets/images/m_acc.png) left center no-repeat; padding:5px 50px; line-height:18px; color:#666; min-height:30px; _height:30px;}
.alert .alert_body .alertcont a{color:#000; text-decoration:none;}
.alert .alert_body .alertcont span{font-size:12px; font-weight:bold; color:#000;}
.alert .alert_body .btn{text-align:center; padding-top:0px;}
.alert .alert_body .btn img{border:0;}
.alert .alert_body .pi2{background:url(/myphp/assets/images/m_err.png) left center no-repeat;}
.alert .alert_body .pi1{background:url(/myphp/assets/images/m_acc.png) left center no-repeat; padding-left:55px;}
</style>
</head>
<body>
<div class="alert">
 <div class="alert_body">
  <h3>系统提示</h3>
  <p class="alertcont <?php echo $_tpl['state'] == 1 ? 'pi1' : 'pi2';?>">
  <span><?php echo $_tpl['msg'];?></span>
  </p>
	  <p class="btn"><a href="<?php echo $_tpl['url'] ? $_tpl['url'] : 'javascript:history.go(-1);';?>"><img src="/myphp/assets/images/return.gif" /></a></p>
 </div>
</div>
</body>
</html>
