<?php
class control_curl extends core_action{

	public $params;

	function __construct(){
		
	}
	//景区API
	function index(){
		//sleep(20);
		$p = intval($_GET['p']);
		if($p>2301) exit('end');
		$q = new Qfetchurl();
		$q->setHeader('User-Agent','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.162 Safari/537.36');
		$q->setHeader('Referer','http://www.poi86.com/');
		$q->setCookies(array('suid'=>'8617731772','Hm_lvt_6de4386404b1c497495e385dfad1afea'=>'1521344361'));
		$q->setConnectTimeout(2000);
		$q->setSendTimeout(2000);
		$q->setReadTimeout(2000);
		$str = $q->fetch("http://www.poi86.com/poi/category/41/{$p}.html");
		if(!$str){
			$url ="/myphp/?ac=curl&p={$p}";
			echo "ConnectTimeout<meta http-equiv=\"refresh\" content=\"10;url={$url}/\">";
			exit();
		}
		preg_match_all("/\">(.*)<\/a><\/td>/", $str, $arr);
		$ii = 0;
		foreach ($arr[1] as $key => $vv) {
			$post = array();
			if($key%2 == 0){
				$arr2[$ii]['name'] = $vv;
			}else{
				$arr2[$ii]['place'] = $vv;
				$ii++;
			}
		}
		print_r($arr2);
		//exit();
		$region_model = new core_model_region();
		$model = new core_model_spot();
		foreach ($arr2 as $key => $v) {
			$post = array();
			$post = $v;
			$r = $region_model->selectOne("region_name like '{$post['place']}%'");
			if($r) $post['region_id'] = $r['region_id'];
			$post['abbreviation'] = $v['name'];
			//print_r($post);
			var_dump($model->create($post));
		}
		$p++;
		$url ="/myphp/?ac=curl&p={$p}";
		echo "<meta http-equiv=\"refresh\" content=\"10;url={$url}/\">";
	}
}
