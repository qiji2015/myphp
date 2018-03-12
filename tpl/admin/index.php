<?php require 'common/header.php';?>
<div id="divMain">
<div class="divHeader"><?php echo $_tpl['blogtitle'] ?></div><div class="SubMenu"></div><div id="divMain2">
<form class="search" id="search" method="post" action="#">
<p>搜索:&nbsp;&nbsp;分类 <select class="edit" size="1" name="category" style="width:140px;" >
  <option value="">任意</option><option value="1">中国</option></select>
  <label><input type="checkbox" name="istop" value="True"/>&nbsp;置顶</label>
  <input name="search" style="width:250px;" type="text" value="" />
  <input type="submit" class="button" value="提交"/>
</p>
</form>
  <table border="1" class="tableFull tableBorder table_hover table_striped tableBorder-thcenter">
    <tr>
      <th>ID</th><th>分类</th><th>作者</th><th>标题</th><th>日期</th><th>操作</th>
    </tr>
    <tr>
      <td class="td5">6</td>
      <td class="td10">沙坪坝</td>
      <td class="td10">admin</td>
      <td><a href="#" target="_blank">自驾甘南迎接最盛大的节日，错过再等一年。</td>
      <td class="td20">2018-02-26 21:49:31</td>
      <td class="td10 tdCenter"><a href="#" class="button"><img src="<?php echo $bloghost ?>/zb_system/image/admin/page_edit.png" alt="编辑" title="编辑" width="16"></a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a onclick="return window.confirm('单击“确定”继续。单击“取消”停止。');" href="#" class="button"><img src="<?php echo $bloghost ?>/zb_system/image/admin/delete.png" alt="删除" title="删除" width="16"></a></td>
    </tr>
  </table><hr/>
  <p class="pagebar"><a href="http://www.mxsp.com/zb_system/cmd.php?act=ArticleMng">‹‹</a>&nbsp;&nbsp;<span class="now-page">1</span>&nbsp;&nbsp;<a href="http://www.mxsp.com/zb_system/cmd.php?act=ArticleMng">››</a>&nbsp;&nbsp;</p>
</div>
</div>
<?php require 'common/footer.php';?>