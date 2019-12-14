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
	//搜索页
	function search(){
		
		$params = $this->_getAllAttr();
		//print_r($params['attrlist']);
		$this->render('home/search.php', $params);
	}
	//标签页
	function tag(){
		echo "标签页";
		print_r($_GET);
	}
	//搜索引擎页
	function rss(){
		$params = $this->_getAllAttr();
		//print_r($params['attrlist']);
		$this->render('home/rss.php', $params);
	}
	//详情页
	function view(){
		echo "详情页";
		print_r($_GET);
	}
	//列表页
	function list(){
		echo "列表页";
		print_r($_GET);
	}
	function _getAllAttr(){
		$m_road = new core_model_road();
		$attr['road'] = $m_road->select()->items;
		$m_spot = new core_model_spot();
		$attr['spot'] = $m_spot->select()->items;
		$m_car = new core_model_car();
		$attr['car'] = $m_car->select()->items;
		$m_whither = new core_model_whither();
		$attr['whither'] = $m_whither->select(array('parent_id>0'))->items;
		$m_region = new core_model_region();
		$attr['region'] = $m_region->getregion(1);
		$model_attr = new core_model_attrlist();
		$attr['person'] = $model_attr->getattr(4);//人群
		$attr['month'] = $model_attr->getattr(1);//月份
		$attr['holiday'] = $model_attr->getattr(2);//假期
		$attr['cycle'] = $model_attr->getattr(5);//周期
		$attr['huanxian'] = $model_attr->getattr(3);//环线
		$attr['xiaofei'] = $model_attr->getattr(6);//消费
		$attr['juli'] = $model_attr->getattr(7);//距离
		return $attr;
	}
}
