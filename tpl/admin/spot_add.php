<?php require 'common/header.php';?>
<div id="divMain">
<div class="divHeader"><?php echo $_tpl['blogtitle'] ?></div><div class="SubMenu"></div>
<div id="divMain2">
  <form name="edit" method="post" enctype='multipart/form-data' action="<?php echo Qtpl::createUrl('admin', 'spot',array('op'=>'add'),'admin');?>">
      <input name="spot_id" type="hidden" value="<?php echo $_tpl['spot']['jq_id'];?>" />
      <p>
        <span class="title">全称:</span><br />
        <input size="40" name="name" type="text" value="<?php echo $_tpl['spot']['name'];?>"/>
      </p>
      <p>
        <span class="title">简称:</span><br />
        <input size="40" name="abbreviation" type="text" value="<?php echo $_tpl['spot']['abbreviation'];?>"/>
      </p>
      <p>
        <span class="title">头图:</span><br />
        <input name="pic_url" type="file"/>
        <input size="40" name="pic_url2" type="hidden" value="<?php echo $_tpl['spot']['pic_url'];?>"/>
      </p>
      <p>
        <span class="title">级别:</span><br />
        <input size="40" name="level" type="text" value="<?php echo $_tpl['spot']['level'];?>"/>
      </p>
      <p>
        <span class="title">门票:</span><br />
        <input size="40" name="ticket" type="text" value="<?php echo $_tpl['spot']['ticket'];?>"/>
      </p>
      <p>
        <span class="title">开放时间:</span><br />
        <input size="40" name="opentime" type="text" value="<?php echo $_tpl['spot']['opentime'];?>"/>
      </p>
      <p>
        <span class="title">电话:</span><br />
        <input size="40" name="telphone" type="text" value="<?php echo $_tpl['spot']['telphone'];?>"/>
      </p>
      <p>
        <span class="title">所在地:</span><br />
        <input size="40" name="place" type="text" value="<?php echo $_tpl['spot']['place'];?>"/>
      </p>
      <p>
        <span class="title">涉及公路:</span><br />
        <input size="40" name="road" type="text" value="<?php echo $_tpl['spot']['road'];?>"/>
      </p>
      <p>
        <span class="title">景点类型:</span><br />
        <select name="whither">
          <?php foreach ($_tpl['whither'] as $k=>$v) { ?>
          <option value="<?php echo $v['f_name'];?>" <?php if($v['f_name'] == $_tpl['spot']['whither']) echo "selected";?>>
            <?php echo $v['f_name'];?>
          </option>
          <?php }?>
        </select>
      </p>
      <p>
        <input type="submit" class="button" value="提交" />
      </p>
    </form>
</div>
</div>
<?php require 'common/footer.php';?>