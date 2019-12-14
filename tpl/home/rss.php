<p>目的地</p>
<?php
foreach ($_tpl['region'] as $v) {
  echo "<a href='/search/to-".urlencode($v['region_id']).".html'>{$v['region_name']}自驾游</a> ";
}
?>
<p>周期</p>
<?php
foreach ($_tpl['cycle'] as $v) {
  echo "<a href='/search/cycle-".urlencode($v['attr_id']).".html'>{$v['attr_name']}自驾游</a> ";
}
?>
<p>季节</p>
<?php
foreach ($_tpl['month'] as $v) {
  echo "<a href='/search/month-".urlencode($v['attr_id']).".html'>{$v['attr_name']}自驾游</a> ";
}
?>
<p>车型</p>
<?php
foreach ($_tpl['car'] as $v) {
  echo "<a href='/search/car-".urlencode($v['car_id']).".html'>{$v['car_type_name']}自驾游</a> ";
}
?>
<p>风光类型</p>
<?php
foreach ($_tpl['whither'] as $v) {
  echo "<a href='/search/whither-".urlencode($v['id']).".html'>{$v['f_name']}自驾游</a> ";
}
?>
<p>人群</p>
<?php
foreach ($_tpl['person'] as $v) {
  echo "<a href='/search/person-".urlencode($v['attr_id']).".html'>{$v['attr_name']}自驾游</a> ";
}
?>
<p>人均消费</p>
<?php
foreach ($_tpl['xiaofei'] as $v) {
  echo "<a href='/search/consume-".urlencode($v['attr_id'])."'>{$v['attr_name']}自驾游</a> ";
}
?>
<hr>
以下是手工组合<br>
<p>出发地->目的地</p>
<?php
foreach ($_tpl['region'] as $v) {
  foreach ($_tpl['region'] as $vv) {
    if($v['region_name'] != $vv['region_name']){
      echo "<a href='/search/from-".urlencode($v['region_id'])."_to-".urlencode($vv['region_id']).".html'>从{$v['region_name']}到{$vv['region_name']}自驾游</a> ";
    }
  }
}
?>
<p>目的地->周期</p>
<?php
foreach ($_tpl['region'] as $v) {
  foreach ($_tpl['cycle'] as $vv) {
    echo "<a href='/search/to-".urlencode($v['region_id'])."_cycle-".urlencode($vv['attr_id']).".html'>{$v['region_name']}{$vv['attr_name']}自驾游</a> ";
  }
}
?>
<p>目的地->距离</p>
<?php
foreach ($_tpl['region'] as $v) {
  foreach ($_tpl['juli'] as $vv) {
    echo "<a href='/search/to-".urlencode($v['region_id'])."_distance-".urlencode($vv['attr_id']).".html'>{$v['region_name']}{$vv['attr_name']}自驾游</a> ";
  }
}
?>
<p>目的地->季节</p>
<?php
foreach ($_tpl['region'] as $v) {
  foreach ($_tpl['month'] as $vv) {
    echo "<a href='/search/to-".urlencode($v['region_id'])."_month-".urlencode($vv['attr_id']).".html'>{$v['region_name']}{$vv['attr_name']}自驾游</a> ";
  }
}
?>

<?php
function getquery(){
  $inpath = Qutil::filter($_GET['inpath']);
  $patharr = array('from','to','month','distance','cycle','person','consume','key','whither','car','spot');
  if($inpath){
    $arr = array();
    $kv = explode("_", $inpath);
    foreach ($kv as $v) {
      $kv2 = explode("-", $v);
      if(in_array($kv2[0], $patharr)){
        $arr[$kv2[0]] = $kv2[1];
      }
    }
  }
  return $arr;
}

