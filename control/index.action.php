<?php
class control_index extends core_action{

	public $params;

	function __construct(){
		
	}

	function index(){
		$es = new Qes();
		//$data['query']['match']['to'] = "海螺沟";
		$data['query']['bool']['must']['query_string']['query'] = "test";
		//$data['from'] = 3;
		//$data['size'] = 2;
		print_r($es->search($data));
		/*
		$data['title'] = "test";
		$data['content'] = "test";
		$data['intro'] = "test";
		$data['cat_id'] = "2";
		$data['cat_name'] = "test";
		$data['tags'] = "test";
		$data['from'] = "test";
		$data['from_region_id'] = "10001";
		$data['to'] = "test";
		$data['to_region_id'] = "20001";
		$data['car'] = "福特";
		$data['road'] = "G7";
		print_r($es->add($data));
		*/
		//$data['_id'] = "7mrj9GEBRVWw6AGqGhsw";
		//$data['doc']['title'] = "测试";
		//print_r($es->update($data));
		//print_r($es->delete('7mrj9GEBRVWw6AGqGhsw'));
	}
	function search(){
		$params['car'] = '';
		$this->render('home/index.php', $params);
	}

}
