<?php require 'common/header.php';?>
<div id="divMain">
<div class="divHeader"><?php echo $_tpl['blogtitle'] ?></div><div class="SubMenu"></div><div id="divMain2">
<form class="search" method="post" action="<?php echo Qtpl::createUrl('admin', 'road',array('op'=>'add'),'admin');?>">
<?php
if($_tpl['road_id']){
?>
<p>编辑公路:
  <input name="road_name" style="width:250px;" type="text" value="<?php echo $_tpl['oneroad']['road_name'];?>" />
  <input name="road_id" type="hidden" value="<?php echo $_tpl['road_id'];?>" />
  <input type="submit" class="button" value="编辑"/>
</p>
<?php
}else{
?>
<p>添加公路:
  <input name="road_name" style="width:250px;" type="text" value="" />
  <input type="submit" class="button" value="添加"/>
</p>
<?php
}
?>
</form>
<form class="search" method="post" action="<?php echo Qtpl::createUrl('admin', 'road',array('op'=>'search'),'admin');?>">
<p>搜索公路:
  <input name="road_name" style="width:250px;" type="text" value="" />
  <input type="submit" class="button" value="搜索"/>
</p>
</form>
  <table border="1" class="tableFull tableBorder table_hover table_striped tableBorder-thcenter">
    <tr>
      <th>ID</th><th>公路名称</th><th>操作</th>
    </tr>
    <?php
      if($_tpl['road']){
      foreach ($_tpl['road'] as $k=>$v) {
    ?>
    <tr>
      <td class="td5"><?php echo $v['road_id'];?></td>
      <td class="td10"><?php echo $v['road_name'];?></td>
      <td class="td10 tdCenter">
        <a href="<?php echo Qtpl::createUrl('admin', 'road',array('road_id'=>$v['road_id'],'op'=>'add'),'admin');?>" class="">编辑</a>&nbsp;
        <a href="<?php echo Qtpl::createUrl('admin', 'road',array('road_id'=>$v['road_id'],'op'=>'del'),'admin');?>" class="">删除</a>
      </td>
    </tr>
     <?php
      }
      }else{
          echo '<td colspan="3">暂无内容</td>';
        }
      ?>
  </table><hr/>
  <p class="pagebar">
    <?php echo Qtpl::paged($_tpl['totalSize'],core_lib_constant::PAGE_NUM,$_tpl['page'],'admin','road','style','admin');?>
  </p>
</div>
</div>
<?php require 'common/footer.php';?>