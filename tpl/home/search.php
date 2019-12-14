<p>目的地</p>
<?php
foreach ($_tpl['region'] as $v) {
  echo "<a href='/search/".createurl(array('to'=>$v['region_id']))."'>{$v['region_name']}自驾游</a> ";
}
?>
<p>周期</p>
<?php
foreach ($_tpl['cycle'] as $v) {
  echo "<a href='/search/".createurl(array('cycle'=>$v['attr_id']))."'>{$v['attr_name']}自驾游</a> ";
}
?>
<p>季节</p>
<?php
foreach ($_tpl['month'] as $v) {
  echo "<a href='/search/".createurl(array('month'=>$v['attr_id']))."'>{$v['attr_name']}自驾游</a> ";
}
?>
<p>车型</p>
<?php
foreach ($_tpl['car'] as $v) {
  echo "<a href='/search/".createurl(array('car'=>$v['car_id']))."'>{$v['car_type_name']}自驾游</a> ";
}
?>
<p>风光类型</p>
<?php
foreach ($_tpl['whither'] as $v) {
  echo "<a href='/search/".createurl(array('whither'=>$v['id']))."'>{$v['f_name']}自驾游</a> ";
}
?>
<p>人群</p>
<?php
foreach ($_tpl['person'] as $v) {
  echo "<a href='/search/".createurl(array('person'=>$v['attr_id']))."'>{$v['attr_name']}自驾游</a> ";
}
?>
<p>人均消费</p>
<?php
foreach ($_tpl['xiaofei'] as $v) {
  echo "<a href='/search/".createurl(array('consume'=>$v['attr_id']))."'>{$v['attr_name']}自驾游</a> ";
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
function createurl($kv){
  $arr = getquery();
  $url = array();
  if($arr){
    $arr = array_merge($arr,$kv);
    foreach ($arr as $k => $v) {
      $url[]= "{$k}-{$v}";
    }
    $str = ltrim($str,'_').".html";
  }
  return join('_',$url).".html";
}
