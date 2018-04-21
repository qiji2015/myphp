<?php require 'common/header.php';?>
<div id="divMain">
<div class="divHeader"><?php echo $_tpl['blogtitle'] ?></div><div class="SubMenu"></div><div id="divMain2">
<form class="search" method="post" action="<?php echo Qtpl::createUrl('admin', 'samekey',array('op'=>'add'),'admin');?>">
<p>
  同义词：<input name="name" value="<?php echo $_tpl['onesamekey']['key'];?>" style="width:300px;" type="text" />
  <input name="id" type="hidden" value="<?php echo $_tpl['onesamekey']['id'];?>" />
  <input type="submit" class="button" value="添加"/>（多个词使用“,”分隔）
</p>
</form>
<form class="search" method="post" action="<?php echo Qtpl::createUrl('admin', 'samekey',array('op'=>'search'),'admin');?>">
<p>搜索:
  <input name="key" style="width:250px;" type="text" value="" />
  <input type="submit" class="button" value="搜索"/>
</p>
</form>
  <table border="1" class="tableFull tableBorder table_hover table_striped tableBorder-thcenter">
    <tr>
      <th>ID</th><th>同义词</th><th>操作</th>
    </tr>
    <?php
      if($_tpl['samekey']){
      foreach ($_tpl['samekey'] as $k=>$v) {
    ?>
    <tr>
      <td class="td5"><?php echo $v['id'];?></td>
      <td class="td10"><?php echo $v['key'];?></td>
      <td class="td10 tdCenter">
        <a href="<?php echo Qtpl::createUrl('admin', 'samekey',array('id'=>$v['id']),'admin');?>" class="">编辑</a>&nbsp;
        <a href="<?php echo Qtpl::createUrl('admin', 'samekey',array('id'=>$v['id'],'op'=>'del'),'admin');?>" class="">删除</a>
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
    <?php echo Qtpl::paged($_tpl['totalSize'],core_lib_constant::PAGE_NUM,$_tpl['page'],'admin','car','style','admin');?>
  </p>
</div>
</div>

<?php require 'common/footer.php';?>