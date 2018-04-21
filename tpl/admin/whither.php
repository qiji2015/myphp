<?php require 'common/header.php';?>
<div id="divMain">
<div class="divHeader"><?php echo $_tpl['blogtitle'] ?></div><div class="SubMenu"></div><div id="divMain2">
<form class="search" method="post" action="<?php echo Qtpl::createUrl('admin', 'whither',array('op'=>'add'),'admin');?>">
<p>
  请选择：
  <select name="parent_id">
  <option value="0" selected>一级</option>
  <?php foreach ($_tpl['parent'] as $k=>$v) { ?>
  <option value="<?php echo $v['id'];?>">
    <?php echo $v['f_name'];?>
  </option>
  <?php }?>
  </select>
  名称：<input name="f_name" value="" style="width:150px;" type="text" />
  <input type="submit" class="button" value="添加"/>
</p>
</form>
<form class="search" method="post" action="<?php echo Qtpl::createUrl('admin', 'whither',array('op'=>'search'),'admin');?>">
<p>搜索:
  <input name="key" style="width:250px;" type="text" value="" />
  <input type="submit" class="button" value="搜索"/>
</p>
</form>
  <table border="1" class="tableFull tableBorder table_hover table_striped tableBorder-thcenter">
    <tr>
      <th>ID</th><th>名称</th><th>操作</th>
    </tr>
    <?php 
    foreach ($_tpl['p'] as $v){
      echo "<tr><td>{$v['id']}</td><td><b>{$v['f_name']}</b></td><td><a href='".Qtpl::createUrl('admin', 'whither',array('id'=>$v['id'],'op'=>'del'),'admin')."'>删除</a></td></tr>";
      if($_tpl['c'][$v['id']]){
        foreach ($_tpl['c'][$v['id']] as $vv){
          echo "<tr><td>{$vv['id']}</td><td>&nbsp&nbsp&nbsp&nbsp|____{$vv['f_name']}</td>
          <td><a href='".Qtpl::createUrl('admin', 'whither',array('id'=>$vv['id'],'op'=>'del'),'admin')."'>删除</a></td>
          </tr>";
        }
      }
    }
    ?>
  </table><hr/>
  <p class="pagebar">
    <?php echo Qtpl::paged($_tpl['totalSize'],core_lib_constant::PAGE_NUM,$_tpl['page'],'admin','car','style','admin');?>
  </p>
</div>
</div>

<?php require 'common/footer.php';?>