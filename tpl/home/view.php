<?php require 'header.php';?>
<div class="wrap">
<div class="layui-container mT20 layui-clear">
	<div class="left-box">
        <ul class="action-list">
                <a href="#" class="counter">
                    <i class="icon-common-collect"></i><span>收藏</span>
                </a>
                
            <a href="#" class="counter">
                <i class="icon-common-comment"></i><span><?php echo $_tpl['view']['log_CommNums'];?></span>
            </a>
            
           <a href="#" class="counter">
           		<i class="icon-common-praise"></i><span><?php echo $_tpl['view']['log_ViewNums'];?></span>
           </a>
        </ul>
	</div>    
    <div class="layui-row layui-col-space10">   
    	<div class="layui-col-xs12 layui-col-sm9 layui-col-md9">
        	<div class="box">
                    <ul class="topic-list">
                        <?php foreach ($_tpl['tags'] as $v) { ?>
                        <li><a class="topic_btn" href="/tag/<?php echo $v['tag_ID'];?>.html" target="_blank"># <?php echo $v['tag_Name'];?></a></li>
                        <?php } ?>
                    </ul>
                    
                    <p class="time-box">
                    <?php echo date('Y-m-d H:i:s',$_tpl['view']['log_PostTime']);?>
                    </p>
                    
        <div class="author">
            <div class="head">
                <i><img src="/myphp/assets/ui/images/34.jpg"></i>
            </div>

            <div class="name-signature">
                <span class="name">
                    <?php echo $_tpl['userinfo']['mem_Name'];?>
                </span>
                <span class="signature"><?php echo $_tpl['userinfo']['mem_Intro'] ? $_tpl['userinfo']['mem_Intro'] :"暂无";?></span>
            </div>
        </div>
        

                 <div class="newsInfo">
    				<div class="title"><?php echo $_tpl['view']['log_Title'];?></div>   
                    <div class="newsInfo-main-box">
                    	<?php echo Qtpl::replacepath($_tpl['view']['log_Content']);?>
                   </div>
            	</div>
                
               <div class="thread-isLogin layui-clear">
                  <a href="#" class="fl"><img src="/myphp/assets/ui/images/user_d.png"></a>
                  <span class="fl">请先登录再发布评论</span>
                  <a href="#" class="fr">去登录</a>
               </div>
               
               <div class="msg-list layui-clear">
               	
                	<div class="msg-item layui-clear">
                    	<div class="user-logo">
                        	<img src="/myphp/assets/ui/images/34.jpg">
                        </div>
                        
                        <div class="content">
                            <div class="cont-footer layui-clear">
                                <span class="userName">左脚常抽筋</span> <span class="time">8月06日 12:30</span>
                                    <div class="handle">
                                        <a class="counter" href="#"><i class="icon-common-praise"></i><span>30</span></a>
                                        <a class="counter" href="#" ><i class="icon-common-comment"></i><span>回复</span></a>
                                    </div>
                                </div>
                                
                        <div class="cont-sub">照片拍的真漂亮，是我迄今为止看到2020 H9 最好看的一组照片</div>
                        
                        <div class="cont-reply" >
                            <div class="reply-list">
                                <div class="reply-item">
                                    <div class="content2">
                                        <div class="cont-footer">
                                            <span class="userName">evacuee</span><span class="time">8月06日 13:14</span>
                                        </div>
                                        
                                            <div class="cont-sub">开森</div>
                                     </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                  </div>
                  
               </div>
        </div>     

    <div class="layui-col-xs12 layui-col-sm3 layui-col-md3 ">  
    	<div class="authorBox">
         <div class="author">
            <div class="head">
                <i><img src="/myphp/assets/ui/images/34.jpg"></i>
            </div>

            <div class="name-signature">
                <span class="name">
                    <?php echo $_tpl['userinfo']['mem_Name'];?>
                </span>
                <span class="signature"><?php echo $_tpl['userinfo']['mem_Intro'] ? $_tpl['userinfo']['mem_Intro'] :"暂无";?></span>
            </div>
        </div>
            <div class="author-count">
                <a href="#" target="_blank"><p>作品</p><b><?php echo $_tpl['userinfo']['mem_Articles'];?></b></a>
                <a href="#" target="_blank"><p>评论</p><b><?php echo $_tpl['userinfo']['mem_Comments'];?></b></a>
                <a href="#" target="_blank"><p>关注</p><b>0</b></a>
            </div>
        </div>
        
        <div class="right-box mT10">
            <div class="right_title">相关推荐</div>
            <div class="right-side-list">
                <ul>
                    <?php foreach ($_tpl['hot'] as $v) { ?>
                    <li>
                        <a href="/y/<?php echo $v['log_ID'];?>.html" target="_blank">
                            <img src="<?php echo $v['log_Meta'];?>" alt=""/>
                            <div class="infospan">
                                <h1><?php echo $v['log_Intro'];?></h1>
                            </div>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        
      <div class="right-box mT20">
            <div class="right_title">关注我们</div>
            <div class="right-side-list">
            	<p class="mT10">扫一扫关注我们，了解最新精彩内容</p>
          		<p class="mT10" align="center"><img src="/myphp/assets/ui/images/qr.png" width="90%" alt=""/></p>
            </div>
        </div>
   </div>	  
 </div> 
</div>
<?php require 'footer.php';?>