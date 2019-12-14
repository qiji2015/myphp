<?php require 'header.php';?>
<div class="layui-wrap">
    <div class="layui-container">
    <div class="header-left">
        <div class="cover-img"><img src="/myphp/assets/ui/images/demo8.jpg" alt=""></div>
        <div class="info-div">
            <div class="title">
                <i class="icon common-topic">#</i>
                <h3><?php echo $_tpl['tags']['tag_Name'];?></h3>
            </div>
            <p>134177.9万阅读<em>/
            </em><?php echo $_tpl['tags']['tag_Count'];?>评论<em>/
            </em><?php echo $_tpl['tags']['tag_Count'];?>人关注<em>/
        	</em><?php echo $_tpl['tags']['tag_Count'];?>篇内容</p>
        </div>
    </div>
    
    <div class="header-right"><a class="publish-btn" href="#" target="_blank"><i class="icon common-input"></i>发布内容</a></div>
    
    </div>
</div>

<div class="layui-container mT20 layui-clear">
    <div class="dis" id="tbc_01">
		<div id="my-gallery-container">
        
        </div>
    	<button type="button" class="layui-btn layui-btn-radius layui-btn-primary btn-loading" onclick="loading = layer.load(); setTimeout(function() {loadMore()}, 1200)">加载更多</button> <!--模拟加载效果-->
    </div>
</div>

<script src="/myphp/assets/ui/js/jquery-2.1.1.min.js"></script>
<script src="/myphp/assets/ui/layui/layui.all.js"></script>
<script>
//导航
layui.use('element', function(){
  var element = layui.element;
  //…
});

//幻灯片
layui.use('carousel', function(){
  var carousel = layui.carousel;
  //建造实例
  carousel.render({
    elem: '#test1'
    ,width: '100%' //设置容器宽度
	,height: '100%'
    ,arrow: 'always' //始终显示箭头
    //,anim: 'updown' //切换动画方式
  });
});
// 响应式轮播图
  window.onload = function () {
    var bannerH = $('#test1 img')[0].height;
    $('.layui-carousel').css('height',bannerH+'px');
  }

layui.use('form', function(){
  var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
  
  //……
  
  //但是，如果你的HTML是动态生成的，自动渲染就会失效
  //因此你需要在相应的地方，执行下述方法来手动渲染，跟这类似的还有 element.init();
  form.render();
});  
</script>
<script type="text/javascript" src="/myphp/assets/ui/js/mp.mansory.js"></script>	
<script>
var mslist = [], page = 1, loading;
var loadMore = function() {

	//模拟数据由ajax返回
	var templist = <?php echo json_encode($_tpl['hot']);?>;
	mslist = mslist.concat(templist);

	var template = '<div class="item">'+
			'<a href="/y/{{log_ID}}.html"><img src="{{log_Meta}}" /></a>'+
		    '<div class="detail-box">'+
		    	'<a href="/y/{{log_ID}}.html" class="title">{{log_Title}}</a>'+
		        '<div class="user-box">'+
					'<img src="/myphp/assets/ui/images/34.jpg"/>'+
		    		'<span>{{mem_Name}}</span>'+
		            '<div class="common-praise"><i class="icon-common-praise2"></i><span>{{log_ViewNums}}</span></div>'+
		    	'</div>'+
		    '</div>'+
		'</div>';

	var html = '';
	for (var i = 0; i < mslist.length; i++) {
		html += template.replace(/{{log_ID}}/g, mslist[i].log_ID)
					.replace(/{{log_Meta}}/g, mslist[i].log_Meta)
					.replace(/{{log_Title}}/g, mslist[i].log_Title)
					.replace(/{{mem_Name}}/g, mslist[i].mem_Name)
					.replace(/{{log_ViewNums}}/g, mslist[i].log_ViewNums);
	}

	$("#my-gallery-container").html(html).mpmansory({
		childrenClass: 'item', // default is a div
		columnClasses: 'padding', //add classes to items
		breakpoints:{
			lg: 3, 
			md: 4, 
			sm: 6,
			xs: 6
		},
		distributeBy: { order: false, height: false, attr: 'data-order', attrOrder: 'disc' }, //default distribute by order, options => order: true/false, height: true/false, attr => 'data-order', attrOrder=> 'asc'/'desc'
		onload: function (items) {
			//make somthing with items
		} 
	});
	//success
	page++;
	layer.close(loading);
}
$(function() {
	loadMore();
})
</script>
<?php require 'footer.php';?>