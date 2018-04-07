<?php require 'common/header.php';?>
<div id="divMain">
<div class="divHeader"><?php echo $_tpl['blogtitle'] ?></div><div class="SubMenu"></div><div id="divMain2">
<form class="search" method="post" action="<?php echo Qtpl::createUrl('admin', 'index','','admin');?>">
<p>搜索:&nbsp;&nbsp;分类 
<select class="edit" size="1" name="category" style="width:140px;" >
  <option value="">任意</option>
  <?php
      if($_tpl['cate']){
      foreach ($_tpl['cate'] as $k=>$v) {
    ?>
  <option value="<?php echo $k;?>"><?php echo $v;?></option>
  <?php
    }}
  ?>
</select>
  <input name="search" style="width:250px;" type="text" value="" />
  <input type="submit" class="button" value="提交"/>
</p>
</form>
  <table border="1" class="tableFull tableBorder table_hover table_striped tableBorder-thcenter">
    <tr>
      <th>ID</th><th>分类</th><th>作者</th><th>标题</th><th>日期</th><th>操作</th>
    </tr>
    <?php
      if($_tpl['post']){
      foreach ($_tpl['post'] as $k=>$v) {
    ?>
    <tr>
      <td class="td5"><?php echo $v['log_ID'];?></td>
      <td class="td10"><?php echo $_tpl['cate'][$v['log_CateID']];?></td>
      <td class="td10"><?php echo $_tpl['mem'][$v['log_AuthorID']];?></td>
      <td><a href="#" target="_blank"><?php echo $v['log_Title'];?></td>
      <td class="td20"><?php echo date("Y-m-d H:i:s",$v['log_PostTime']);?></td>
      <td class="td10 tdCenter">
        <a href="<?php echo Qtpl::createUrl('admin', 'attr_edit',array('id'=>$v['log_ID']),'admin');?>" class="button"><img src="<?php echo $bloghost ?>/zb_system/image/admin/page_edit.png" title="编辑属性" width="16"></a>
      </td>
    </tr>
    <?php
      }
      }else{
          echo '<td colspan="6">暂无内容</td>';
        }
      ?>
  </table><hr/>
<p class="pagebar">
    <?php echo Qtpl::paged($_tpl['totalSize'],core_lib_constant::PAGE_NUM,$_tpl['page'],'admin','index','style','admin');?>
  </p>
</div>
</div>
<?php require 'common/footer.php';?>