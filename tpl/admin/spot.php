<?php require 'common/header.php';?>
<link rel="stylesheet" type="text/css" href="<?php echo core_lib_constant::ADMIN_URL;?>assets/awesomplete/awesomplete.css"/>
<script src="<?php echo core_lib_constant::ADMIN_URL;?>assets/awesomplete/awesomplete.min.js" type="text/javascript"></script>
<div id="divMain">
<div class="divHeader"><?php echo $_tpl['blogtitle'] ?></div><div class="SubMenu"></div><div id="divMain2">
<form class="search" method="post" action="<?php echo Qtpl::createUrl('admin', 'spot',array('op'=>'add'),'admin');?>">
<?php
if($_tpl['spot_id']){
?>
<p>全称：<input name="name" value="<?php echo $_tpl['onespot']['name'];?>" style="width:280px;" type="text" />
  简称：<input name="abbreviation" value="<?php echo $_tpl['onespot']['abbreviation'];?>" style="width:150px;" type="text" />
  级别：<input name="level" value="<?php echo $_tpl['onespot']['level'];?>" style="width:150px;" type="text" />
  地区：<input name="place" value="<?php echo $_tpl['onespot']['place'];?>" data-list="<?php echo $_tpl['all_region'];?>" class="awesomplete" style="width:150px;" type="text" />
  <input name="spot_id" type="hidden" value="<?php echo $_tpl['spot_id'];?>" />
  <input type="submit" class="button" value="编辑"/>
</p>
<?php
}else{
?>
<p>
  全称：<input name="name" value="" style="width:150px;" type="text" />
  简称：<input name="abbreviation" value="" style="width:150px;" type="text" />
  级别：<input name="level" value="" style="width:150px;" type="text" />
  地区：<input name="place" value="" data-list="<?php echo $_tpl['all_region'];?>" class="awesomplete" style="width:150px;" type="text" />
  <input type="submit" class="button" value="添加"/>
</p>
<?php
}
?>
</form>
<form class="search" method="post" action="<?php echo Qtpl::createUrl('admin', 'spot',array('op'=>'search'),'admin');?>">
<p>搜索景区:
  <input name="key" style="width:250px;" type="text" value="" />
  <input type="submit" class="button" value="搜索"/>
</p>
</form>
  <table border="1" class="tableFull tableBorder table_hover table_striped tableBorder-thcenter">
    <tr>
      <th>ID</th><th>全称</th><th>简称</th><th>级别</th><th>所在地</th><th>操作</th>
    </tr>
    <?php
      if($_tpl['spot']){
      foreach ($_tpl['spot'] as $k=>$v) {
    ?>
    <tr>
      <td class="td5"><?php echo $v['jq_id'];?></td>
      <td class="td10"><?php echo $v['name'];?></td>
      <td class="td10"><?php echo $v['abbreviation'];?></td>
      <td class="td10"><?php echo $v['level'];?></td>
      <td class="td10"><?php echo $v['place'];?></td>
      <td class="td10 tdCenter">
        <a href="<?php echo Qtpl::createUrl('admin', 'spot',array('spot_id'=>$v['jq_id'],'op'=>'add'),'admin');?>" class="">编辑</a>&nbsp;
        <a href="<?php echo Qtpl::createUrl('admin', 'spot',array('spot_id'=>$v['jq_id'],'op'=>'del'),'admin');?>" class="">删除</a>
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
    <?php echo Qtpl::paged($_tpl['totalSize'],core_lib_constant::PAGE_NUM,$_tpl['page'],'admin','spot','style','admin');?>
  </p>
</div>
</div>

<?php require 'common/footer.php';?>