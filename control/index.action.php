<?php
class control_index extends core_action{

	public $params;

	function __construct(){
		
	}
	function index(){
		$params['page_title'] = "首页";
		$list = 'log_Meta,log_ViewNums,log_CommNums,log_PostTime,log_Title,log_Intro,log_ID,log_CateID,log_AuthorID';
		$m_tag = new core_model_tag();
		$m_tag->setLimit(10);
		$params['tags'] = $m_tag->select("tag_Count>0")->items;
		$model = new core_model_post();
		$condi = "log_Status=0 and log_IsLock=0 and log_IsTop=1";
		$model->setLimit(5);
		$params['focus'] = $model->select($condi,$list,"order by log_PostTime desc")->items;
		$condi1 = "log_Status=0 and log_IsLock=0";
		$page = intval($_GET['p']);
		$params['page'] = $page;
		$model->setPage($page);
		$model->setLimit(20);
		$leftjoin = array("zbp_member"=>"log_AuthorID=mem_ID");
		$list .= ",mem_Name";
		//define(DEBUG,1);
		$params['hot'] = $model->select($condi1,$list,"","order by log_PostTime desc",$leftjoin)->items;
		$this->render('home/index.php', $params);
	}
	//搜索页
	function search(){
		
		$params = $this->_getAllAttr();
		//print_r($params['attrlist']);
		$this->render('home/search.php', $params);
	}
	//标签页
	function tag(){
		$id = intval($_GET['inpath']);
		$list = 'mem_Name,log_Meta,log_ViewNums,log_CommNums,log_PostTime,log_Title,log_Intro,log_ID,log_CateID,log_AuthorID';
		$m_tag = new core_model_tag($id);
		$params['tags'] = $m_tag->getData();
		$model = new core_model_post();
		$condi = "log_Status=0 and log_IsLock=0 and log_Tag like '%{{$id}}%'";
		$page = intval($_GET['p']);
		$params['page'] = $page;
		$model->setPage($page);
		$model->setLimit(20);
		$leftjoin = array("zbp_member"=>"log_AuthorID=mem_ID");
		$params['page_title'] = $params['tags']['tag_Name'];
		//define(DEBUG,1);
		$params['hot'] = $model->select($condi,$list,"","order by log_PostTime desc",$leftjoin)->items;
		$this->render('home/tags.php', $params);
	}
	//搜索引擎页
	function rss(){
		$params = $this->_getAllAttr();
		//print_r($params['attrlist']);
		$this->render('home/rss.php', $params);
	}
	//详情页
	function view(){
		$id = intval($_GET['inpath']);
		$model = new core_model_post();
		$condi = "log_Status=0 and log_IsLock=0 and log_ID={$id}";
		$params['view'] = $model->selectOne($condi);
		$params['page_title'] = $params['view']['log_Title'];
		$params['tags'] = array();
		if($params['view']['log_Tag']){
			preg_match_all("/{(\d+)}/", $params['view']['log_Tag'], $tagid);
			$tagsid = join(',',$tagid[1]);
			$m_tag = new core_model_tag();
			$params['tags'] = $m_tag->select("tag_ID in({$tagsid})")->items;
		}
		//user
		$m_mem = new core_model_member($params['view']['log_AuthorID']);
		$params['userinfo'] = $m_mem->getData();
		//推荐
		$model->setLimit(5);
		$params['hot'] = $model->select(
			"log_Status=0 and log_IsLock=0 and log_CateID={$params['view']['log_CateID']}",
			"log_Meta,log_Title,log_Intro,log_ID",
			"order by log_PostTime desc"
			)->items;
		//print_r($params['hot']);
		$this->render('home/view.php', $params);
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

	function es(){
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
}
