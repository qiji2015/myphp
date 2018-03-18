<?php require 'common/header.php';?>
<link rel="stylesheet" type="text/css" href="<?php echo core_lib_constant::ADMIN_URL;?>assets/awesomplete/awesomplete.css"/>
<script src="<?php echo core_lib_constant::ADMIN_URL;?>assets/awesomplete/awesomplete.min.js" type="text/javascript"></script>
<div id="divMain">
<div class="divHeader"><?php echo $_tpl['blogtitle'] ?></div><div class="SubMenu"></div><div id="divMain2">
<form class="search" method="post" action="<?php echo Qtpl::createUrl('admin', 'attr',array('op'=>'add'),'admin');?>">
<p>属性类型:
  <input name="parent_name" style="width:150px;" type="text" value="" data-list="<?php echo $_tpl['parent_list'] ?>" class="awesomplete" />
  属性值：<input name="attr_name" style="width:150px;" type="text" value="" />
  <input type="submit" class="button" value="添加"/>
</p>
</form>
  <table border="1" class="tableFull tableBorder table_hover table_striped tableBorder-thcenter">
    <tr>
      <th>类型</th><th>属性值</th>
    </tr>
    <?php
      if($_tpl['attr']){
      foreach ($_tpl['attr'] as $k=>$v) {
    ?>
    <tr>
      <td class="td5"><?php echo $v['attr_name'];?></td>
      <td class="td10"><?php echo $v['vv'];?></td>
    </tr>
    <?php
      }
      }else{
          echo '<td colspan="2">暂无内容</td>';
        }
      ?>
  </table>
</div>
</div>

<?php require 'common/footer.php';?>