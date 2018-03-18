<?php require 'common/header.php';?>
<link rel="stylesheet" type="text/css" href="<?php echo core_lib_constant::ADMIN_URL;?>assets/awesomplete/awesomplete.css"/>
<script src="<?php echo core_lib_constant::ADMIN_URL;?>assets/awesomplete/awesomplete.min.js" type="text/javascript"></script>
<div id="divMain">
<div class="divHeader"><?php echo $_tpl['blogtitle'] ?></div><div class="SubMenu"></div><div id="divMain2">
<form class="search" method="post" action="<?php echo Qtpl::createUrl('admin', 'car',array('op'=>'add'),'admin');?>">
<?php
if($_tpl['car_id']){
?>
<p>品牌：<input name="car_brand_name" value="<?php echo $_tpl['onecar']['car_brand_name'];?>" style="width:150px;" type="text" />
  车型：<input name="car_type_name" value="<?php echo $_tpl['onecar']['car_type_name'];?>" style="width:150px;" type="text" />
  <input name="car_id" type="hidden" value="<?php echo $_tpl['car_id'];?>" />
  <input type="submit" class="button" value="编辑"/>
</p>
<?php
}else{
?>
<p>
  品牌：<input name="car_brand_name" data-list="<?php echo $_tpl['car_brand'];?>" class="awesomplete" value="" style="width:150px;" type="text"/>
  车型：<input name="car_type_name" style="width:150px;" type="text" value="" />
  <input type="submit" class="button" value="添加"/>
</p>
<?php
}
?>
</form>
<form class="search" method="post" action="<?php echo Qtpl::createUrl('admin', 'car',array('op'=>'search'),'admin');?>">
<p>搜索车型:
  <input name="key" style="width:250px;" type="text" value="" />
  <input type="submit" class="button" value="搜索"/>
</p>
</form>
  <table border="1" class="tableFull tableBorder table_hover table_striped tableBorder-thcenter">
    <tr>
      <th>ID</th><th>品牌</th><th>车型</th><th>操作</th>
    </tr>
    <?php
      if($_tpl['car']){
      foreach ($_tpl['car'] as $k=>$v) {
    ?>
    <tr>
      <td class="td5"><?php echo $v['car_id'];?></td>
      <td class="td10"><?php echo $v['car_brand_name'];?></td>
      <td class="td10"><?php echo $v['car_type_name'];?></td>
      <td class="td10 tdCenter">
        <a href="<?php echo Qtpl::createUrl('admin', 'car',array('car_id'=>$v['car_id'],'op'=>'add'),'admin');?>" class="">编辑</a>&nbsp;
        <a href="<?php echo Qtpl::createUrl('admin', 'car',array('car_id'=>$v['car_id'],'op'=>'del'),'admin');?>" class="">删除</a>
      </td>
    </tr>
     <?php
      }
      }else{
          echo '<td colspan="4">暂无内容</td>';
        }
      ?>
  </table><hr/>
  <p class="pagebar">
    <?php echo Qtpl::paged($_tpl['totalSize'],core_lib_constant::PAGE_NUM,$_tpl['page'],'admin','car','style','admin');?>
  </p>
</div>
</div>

<?php require 'common/footer.php';?>