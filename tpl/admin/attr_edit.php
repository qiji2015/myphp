<?php require 'common/header.php';?>
<link rel="stylesheet" type="text/css" href="<?php echo core_lib_constant::ADMIN_URL;?>assets/awesomplete/awesomplete.css"/>
<script src="<?php echo core_lib_constant::ADMIN_URL;?>assets/awesomplete/awesomplete.min.js" type="text/javascript"></script>
<div id="divMain">
<div class="divHeader"><?php echo $_tpl['blogtitle'] ?></div><div class="SubMenu"></div>
<div id="divMain2">
  <form name="edit" method="post" action="<?php echo Qtpl::createUrl('admin', 'attr_edit',array('op'=>'edit'),'admin');?>">
      <input name="_id" type="hidden" value="<?php echo $_tpl['attr']['es_id'];?>" />
      <input name="id" type="hidden" value="<?php echo $_tpl['id'];?>" />
      <p>
        <span class="title">名称:</span><?php echo $_tpl['post']['log_Title'];?>
      </p>
      <p>
        <span class="title">出发点:</span><br />
        <input size="40" name="from" type="text" value="<?php echo $_tpl['data']['from'];?>" data-list="<?php echo $_tpl['from'];?>" class="awesomplete" /> 注：如果是多个直接用 , 分隔，填写最后一级地区，系统自动抓取上级地区
      </p>
      <p>
        <span class="title">目的地:</span><br />
        <input size="40" name="to" type="text" value="<?php echo $_tpl['data']['to'];?>" data-list="<?php echo $_tpl['from'];?>" data-multiple/> 注：如果是多个直接用 , 分隔，填写最后一级地区，系统自动抓取上级地区
      </p>
      <p>
        <span class="title">景点:</span><br />
        <input size="40" name="spot" type="text" value="<?php echo $_tpl['data']['spot'];?>" data-list="<?php echo $_tpl['spot'];?>" data-multiple2 />
      </p>
      <p>
        <span class="title">公路:</span><br />
        <input size="40" name="road" type="text" value="<?php echo $_tpl['data']['road'];?>" data-list="<?php echo $_tpl['road'];?>" data-multiple3 />
      </p>
      <p>
        <span class="title">车型:</span><br />
        <input size="40" name="car" type="text" value="<?php echo $_tpl['data']['car'];?>" data-list="<?php echo $_tpl['car'];?>" data-multiple4 />
      </p>
      <p>
        <span class="title">风光:</span><br />
        <select name="whither_type">
          <?php foreach ($_tpl['whither_type'] as $k=>$v) { ?>
          <option value="<?php echo $v['f_name'];?>" <?php if($v['f_name'] == $_tpl['data']['whither_type']) echo "selected";?>>
            <?php echo $v['f_name'];?>
          </option>
          <?php }?>
        </select>
      </p>
      <p>
        <span class="title">人群:</span><br />
        <input size="40" name="person" type="text" value="<?php echo $_tpl['data']['person'];?>" data-list="<?php echo $_tpl['person'];?>" data-multiple6 />
      </p>
      <p>
        <span class="title">月季:</span><br />
        <select name="month">
          <?php foreach ($_tpl['month'] as $k=>$v) { ?>
          <option value="<?php echo $v['attr_name'];?>" <?php if($v['attr_name'] == $_tpl['data']['month']) echo "selected";?>>
            <?php echo $v['attr_name'];?>
          </option>
          <?php }?>
        </select>
      </p>
      <p>
        <span class="title">假期:</span><br />
        <select name="holiday">
          <?php foreach ($_tpl['holiday'] as $k=>$v) { ?>
          <option value="<?php echo $v['attr_name'];?>" <?php if($v['attr_name'] == $_tpl['data']['holiday']) echo "selected";?>>
            <?php echo $v['attr_name'];?>
          </option>
          <?php }?>
        </select>
      </p>
      <p>
        <span class="title">周期:</span><br />
        <select name="cycle">
          <?php foreach ($_tpl['cycle'] as $k=>$v) { ?>
          <option value="<?php echo $v['attr_name'];?>" <?php if($v['attr_name'] == $_tpl['data']['cycle']) echo "selected";?>>
            <?php echo $v['attr_name'];?>
          </option>
          <?php }?>
        </select>
      </p>
      <p>
        <span class="title">环线:</span><br />
        <select name="huanxian">
          <?php foreach ($_tpl['huanxian'] as $k=>$v) { ?>
          <option value="<?php echo $v['attr_name'];?>" <?php if($v['attr_name'] == $_tpl['data']['huanxian']) echo "selected";?>>
            <?php echo $v['attr_name'];?>
          </option>
          <?php }?>
        </select>
      </p>
      <p>
        <span class="title">距离:</span><br />
        <select name="juli">
          <?php foreach ($_tpl['juli'] as $k=>$v) { ?>
          <option value="<?php echo $v['attr_name'];?>" <?php if($v['attr_name'] == $_tpl['data']['juli']) echo "selected";?>>
            <?php echo $v['attr_name'];?>
          </option>
          <?php }?>
        </select>
      </p>
      <p>
        <span class="title">人均消费:</span><br />
        <select name="xiaofei">
          <?php foreach ($_tpl['xiaofei'] as $k=>$v) { ?>
          <option value="<?php echo $v['attr_name'];?>" <?php if($v['attr_name'] == $_tpl['data']['xiaofei']) echo "selected";?>>
            <?php echo $v['attr_name'];?>
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
<script>
new Awesomplete("input[data-multiple]",{filter:function(text,input){return Awesomplete.FILTER_CONTAINS(text,input.match(/[^,]*$/)[0])},item:function(text,input){return Awesomplete.ITEM(text,input.match(/[^,]*$/)[0])},replace:function(text){var before=this.input.value.match(/^.+,\s*|/)[0];this.input.value=before+text+", "}});
new Awesomplete("input[data-multiple2]",{filter:function(text,input){return Awesomplete.FILTER_CONTAINS(text,input.match(/[^,]*$/)[0])},item:function(text,input){return Awesomplete.ITEM(text,input.match(/[^,]*$/)[0])},replace:function(text){var before=this.input.value.match(/^.+,\s*|/)[0];this.input.value=before+text+", "}});
new Awesomplete("input[data-multiple3]",{filter:function(text,input){return Awesomplete.FILTER_CONTAINS(text,input.match(/[^,]*$/)[0])},item:function(text,input){return Awesomplete.ITEM(text,input.match(/[^,]*$/)[0])},replace:function(text){var before=this.input.value.match(/^.+,\s*|/)[0];this.input.value=before+text+", "}});
new Awesomplete("input[data-multiple4]",{filter:function(text,input){return Awesomplete.FILTER_CONTAINS(text,input.match(/[^,]*$/)[0])},item:function(text,input){return Awesomplete.ITEM(text,input.match(/[^,]*$/)[0])},replace:function(text){var before=this.input.value.match(/^.+,\s*|/)[0];this.input.value=before+text+", "}});
new Awesomplete("input[data-multiple5]",{filter:function(text,input){return Awesomplete.FILTER_CONTAINS(text,input.match(/[^,]*$/)[0])},item:function(text,input){return Awesomplete.ITEM(text,input.match(/[^,]*$/)[0])},replace:function(text){var before=this.input.value.match(/^.+,\s*|/)[0];this.input.value=before+text+", "}});
new Awesomplete("input[data-multiple6]",{filter:function(text,input){return Awesomplete.FILTER_CONTAINS(text,input.match(/[^,]*$/)[0])},item:function(text,input){return Awesomplete.ITEM(text,input.match(/[^,]*$/)[0])},replace:function(text){var before=this.input.value.match(/^.+,\s*|/)[0];this.input.value=before+text+", "}});
</script>
<?php require 'common/footer.php';?>