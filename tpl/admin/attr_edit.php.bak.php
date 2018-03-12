<?php require 'common/header.php';?>
<style type="text/css">
  #region_list{
    position: absolute;
    z-index: 99;
    width: 145px;
    height: auto;
    background-color: white;
    border: black solid 1px;
    display: none;
  }
  .click_work{
    padding-bottom: 8px;
    font-weight:lighter;
    font-size: 13px;
    cursor:pointer;/*鼠标放上变成小手*/
  }
  .click_work:hover{
    color: orange;
    background-color: gray;
  }
  .error{
    color: gray;
    cursor:pointer;/*鼠标放上变成小手*/
  }
</style>

<div id="divMain">
<div class="divHeader"><?php echo $_tpl['blogtitle'] ?></div>
<div class="SubMenu"></div>
<div id="divMain2" class="edit post_edit">
    <div id="divEditLeft">
      <div id="divEditTitle" class="editmod2">
        <div id="titleheader" class="editmod">
          <label for="edtTitle" class="editinputname" >标题</label>
          <div>
            <input type="text" name="Title" id="edtTitle" maxlength="50" />
          </div>
        </div>
      </div>
    <!-- alias( -->
    <div id="alias" class="editmod2">
      <label for="edtAlias" class="editinputname" >别名</label>
      <input type="text" name="Alias" id="edtAlias" maxlength="250" value="" />
    </div>
    <!-- )alias -->
    <!-- tags( -->
    <div id="tags" class="editmod2">
      <label  for="edtTag"  class='editinputname'>标签</label>
      <input type="text"  name="Tag" id="edtTag" value="" />(逗号分割) <a href="#" id="showtags">显示常用标签</a>
    </div>
    <!-- Tags -->
    <div id="ulTag" class="editmod2" style="display:none;">
      <div id="ajaxtags">Waiting...</div>
    </div>
    <!-- )tags -->
    <!-- alias( -->
    <div class="editmod2">
      <label class="editinputname" >别名</label>
      <input type="text" name="Alias" maxlength="250" value="" />
    </div>
    <div class="editmod2">
      <label class="editinputname" >别名</label>
      <input type="text" name="Alias" maxlength="250" value="" />
    </div>
    <div class="editmod2">
      <label class="editinputname" >别名</label>
      <input type="text" name="Alias" maxlength="250" value="" />
    </div>
    <div class="editmod2">
      <label class="editinputname" >别名</label>
      <input type="text" name="Alias" maxlength="250" value="" />
    </div>
    <div class="editmod2">
      <label class="editinputname" >地区</label>
      <input style="position: relative;" type="text" name="Alias" id="region" maxlength="250" value="" />
      <div id="region_list"></div>
    </div>
    <!-- )alias -->
    </div>
    <!-- divEditLeft -->
    <div id="divEditRight">
      <div id="divEditPost">
        <div id="divBox">
          <div id="divFloat">
            <div id='post' class="editmod">
              <input class="button" style="width:180px;height:38px;" type="submit" value="提交" id="btnPost" onclick='return checkArticleInfo();' />
            </div>
            <!-- cate -->          <div id='cate' class="editmod"> <label for="cmbCateID" class="editinputname" style="max-width:65px;text-overflow:ellipsis;">分类</label>
              <select style="width:180px;" class="edit" size="1" name="CateID" id="cmbCateID">
  <option  value="1">中国</option><option  value="2">&nbsp;└重庆</option><option  value="3">&nbsp;&nbsp;&nbsp;└沙坪坝</option><option  value="4">&nbsp;└北京</option>            </select>
            </div>
            <!-- cate -->
            <!-- level -->
            <div id='level' class="editmod"> <label for="cmbPostStatus" class="editinputname" style="max-width:65px;text-overflow:ellipsis;">状态</label>
              <select class="edit" style="width:180px;" size="1" name="Status" id="cmbPostStatus" onChange="cmbPostStatus.value=this.options[this.selectedIndex].value">
  <option value="0" selected="selected" >公开</option><option value="1"  >草稿</option><option value="2"  >审核</option>            </select>
            </div>
            <!-- )level -->
            <!-- newdatetime( -->
            <div id='newdatetime' class="editmod"> 
              <label for="edtDateTime" class="editinputname" style="max-width:65px;text-overflow:ellipsis;">日期</label>
              <input type="text" name="PostTime" id="edtDateTime"  value="2018-03-06 17:33:46" style="width:180px;"/>
              </div>
            <!-- )newdatetime -->
          </div>
        </div>
      </div>
    </div>
    <!-- divEditRight -->
</div>
<script type="text/javascript" src="<?php echo $bloghost ?>zb_system/script/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript">
var tag_loaded=false; //是否已经ajax读取过TAGS
var sContent="",sIntro="";//原内容与摘要
var isSubmit=false;//是否提交保存
var editor_api={
  editor: {
    content:{
      obj:{},
      get:function(){return ""},
      insert:function(){return ""},
      put:function(){return ""},
      focus:function(){return ""}
    },
    intro:{
      obj:{},
      get:function(){return ""},
      insert:function(){return ""},
      put:function(){return ""},
      focus:function(){return ""}
    }
  }
}

//日期时间控件
$.datepicker.regional['zh-CN'] = {
  closeText: '完成',
  prevText: '上个月',
  nextText: '下个月',
  currentText: '现在',
  monthNames: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
  monthNamesShort: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],
  dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
  dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],
  dayNamesMin: ['日','一','二','三','四','五','六'],
  weekHeader: '周',
  dateFormat: 'yy-mm-dd',
  firstDay: 1,
  isRTL: false,
  showMonthAfterYear: true,
  yearSuffix: ' 年  '
};
$.datepicker.setDefaults($.datepicker.regional['zh-CN']);
$.timepicker.regional['zh-CN'] = {
  timeOnlyTitle: '时间',
  timeText: '时间',
  hourText: '小时',
  minuteText: '分钟',
  secondText: '秒钟',
  millisecText: '毫秒',
  currentText: '现在',
  closeText: '完成',
  timeFormat: 'HH:mm:ss',
  ampm: false
};
$.timepicker.setDefaults($.timepicker.regional['zh-CN']);
$('#edtDateTime').datetimepicker({
  showSecond: true
  //changeMonth: true,
  //changeYear: true
});


//显示tags
$(document).click(function (event){$('#ulTag').slideUp("fast");});

$('#showtags').click(function (event) {
  event.stopPropagation();
  var offset = $(event.target).offset();
  $('#ulTag').css({ top: offset.top + $(event.target).height()+20+ "px", left: offset.left});
  $('#ulTag').slideDown("fast");
  if(tag_loaded==false){$.getScript('../cmd.php?act=misc&type=showtags');tag_loaded=true;}
  return false;
});
function AddKey(i) {
  var strKey=$('#edtTag').val();
  var strNow=","+i
  if(strKey==""){
    strNow=i
  }
  if(strKey.indexOf(strNow)==-1){
    strKey=strKey+strNow;
  }
  $('#edtTag').val(strKey);
}
function DelKey(i) {
  var strKey=$('#edtTag').val();
  var strNow="{"+i+"}"
  if(strKey.indexOf(strNow)!=-1){
    strKey=strKey.substring(0,strKey.indexOf(strNow))+strKey.substring(strKey.indexOf(strNow)+strNow.length,strKey.length)
  }
  $('#edtTag').val(strKey);
}

//文章编辑提交区随动JS开始
var oDiv=document.getElementById("divFloat");
var H=0;var Y=oDiv;
while(Y){H+=Y.offsetTop;Y=Y.offsetParent;};
$(window).bind("scroll resize",function(){
  var s=document.body.scrollTop||document.documentElement.scrollTop;
  if(s>H){
    $("#divFloat").addClass("boxfloat");
  }
  else{
  $("#divFloat").removeClass("boxfloat");
  }
});
//快速搜索
$(function(){
    $('#region').keyup(function(){
      var keywords = $(this).val();
      if (keywords=='') { $('#region_list').hide(); return };
      $.ajax({
        url: 'http://suggestion.baidu.com/su?wd=' + keywords,
        dataType: 'jsonp',
        jsonp: 'cb', 
        beforeSend:function(){
          $('#region_list').append('<div>12345</div>');
        },
        success:function(data){
          $('#region_list').empty().show();
          if (data.s=='')
          {
            $('#region_list').append('<div class="error">Not find  "' + keywords + '"</div>');
          }
          $.each(data.s, function(){
            $('#region_list').append('<div class="click_work">'+ this +'</div>');
          })
        },
        error:function(){
          $('#region_list').empty().show();
          $('#region_list').append('<div class="click_work">Fail "' + keywords + '"</div>');
        }
      })
    });
    $(document).on('click','.click_work',function(){
      var word = $(this).text();
      $('#region').val(word);
      $('#region_list').hide();
    })

  })
</script>
<?php require 'common/footer.php';?>