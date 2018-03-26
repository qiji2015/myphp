<?php
class control_admin extends core_action{

	public $params;

	function __construct(){
		if(!$this->isLogin()){
			$this->redirect("/zb_system/login.php");
		}
	}

	function index(){
		$params = array('blogtitle'=>'文章管理');
		$this->render('admin/index.php', $params);
	}
	function attr(){
		$params = array('blogtitle'=>'属性管理');
		$model = new core_model_attrlist();
		$post = Qutil::filter($_POST);
		$rs = $model->getattr(0);
		if($rs){
			$params['parent_list'] = $rs;
			foreach ($rs as $k=>$v) {
				$rs[$k]['vv'] = self::_join($model->getattr($v['attr_id']),'attr_name');
			}
			$params['attr'] = $rs;
		}
		switch ($_GET['op']) {
			case 'add':
				$fun = $attr_id > 0 ? 'editattr' : 'addattr';
				if($post['parent_name']){
					$res =$model->$fun(array('attr_name'=>$post['attr_name'],'parent_name'=>$post['parent_name']));
					$this->redirect(Qtpl::createUrl('admin', 'attr','','admin'));
				}
				break;
		}
		$this->render('admin/attr.php', $params);
	}

	function attr_edit(){
		$params = array('blogtitle'=>'编辑属性');
		$id = intval($_REQUEST['id']);
		$model = new core_model_post($id);
		$params['post'] = $model->getPost();
		$model_attr = new core_model_postattr($id);
		$params['attr'] = $model_attr->getPost();
		if($params['attr']) $params['data'] = json_decode($params['attr']['json_data'],true);
		if($_POST){
			$post = Qutil::filter($_POST);
			$model_attr->bulidData($post);
			$model_attr->create();
			$this->redirect(Qtpl::createUrl('admin', 'attr_edit','','admin'));
		}
		//属性内容
		$model_region = new core_model_region();
		$params['from'] = self::_join($model_region->getregion(1),'region_name');
		$model_spot = new core_model_spot();
		$params['spot'] = self::_join($model_spot->getallspot(),'abbreviation');
		$model_road = new core_model_road();
		$params['road'] = self::_join($model_road->getallroad(),'road_name');
		$model_car = new core_model_car();
		$params['car'] = self::_join($model_car->getall(),'full_name');
		$model_attr = new core_model_attrlist();
		$params['whither_type'] = $model_attr->getattr(3);//风光
		$params['person'] = $model_attr->getattr(4);//人群
		$params['month'] = $model_attr->getattr(1);//月份
		$params['holiday'] = $model_attr->getattr(2);//假期
		$params['cycle'] = $model_attr->getattr(5);//周期
		//属性内容 end
		$params['id'] = $id;
		$this->render('admin/attr_edit.php', $params);
	}

	function road(){
		$params = array('blogtitle'=>'公路管理');
		$road_id = intval($_REQUEST['road_id']);
		$page = intval($_REQUEST['p']);
		$road_name = Qutil::filter($_REQUEST['road_name']);
		$model = new core_model_road($road_id);
		switch ($_GET['op']) {
			case 'add':
				$fun = $road_id > 0 ? 'edit' : 'create';
				if($road_name) {
					$model->$fun(array('road_name'=>$road_name));
					$this->redirect(Qtpl::createUrl('admin', 'road','','admin'));
				}
				break;
			case 'search':
				if($road_name) $condi = "road_name like '%{$road_name}%'";
				break;
			case 'del':
				if($road_id) {
					$model->del();
					$this->redirect(Qtpl::createUrl('admin', 'road','','admin'));
				}
				break;
		}
		if($road_id) $params['oneroad'] = $model->get();
		$params['road_id'] = $road_id;
		$params['page'] = $page;
		$model->setPage($page);
		$model->setLimit(core_lib_constant::PAGE_NUM);
		$model->setCount(TRUE);
		$rs = $model->select($condi);
	    $params['totalSize'] = $rs->totalSize;
		$params['road'] = $rs->items;
		$this->render('admin/road.php', $params);
	}
	function spot(){
		$params = array('blogtitle'=>'景点管理');
		$spot_id = intval($_REQUEST['spot_id']);
		$page = intval($_REQUEST['p']);
		$key = Qutil::filter($_REQUEST['key']);
		$post = Qutil::filter($_POST);
		$model = new core_model_spot($spot_id);
		switch ($_GET['op']) {
			case 'add':
				$params['onespot'] = $model->getData();
				$params['spot_id'] = $spot_id;
				$fun = $spot_id > 0 ? 'edit' : 'create';
				unset($post['spot_id']);
				$region_model = new core_model_region();
				$re = $region_model->selectOne("region_name like '{$post['place']}%'");
				if($re) $post['region_id'] = $re['region_id'];
				if($post['name']) $model->$fun($post);
				break;
			case 'search':
				if($key) $condi = "name like '%{$key}%' or abbreviation like '%{$key}%'";
				break;
			case 'del':
				if($car_id) $model->del();
				break;
		}
		$params['page'] = $page;
		$model->setPage($page);
		$model->setLimit(core_lib_constant::PAGE_NUM);
		$model->setCount(TRUE);
		$rs = $model->select($condi);
	    $params['totalSize'] = $rs->totalSize;
	    $params['spot'] = $rs->items;
		$this->render('admin/spot.php', $params);
	}
	function car(){
		$params = array('blogtitle'=>'车型管理');
		$car_id = intval($_REQUEST['car_id']);
		$page = intval($_REQUEST['p']);
		$key = Qutil::filter($_REQUEST['key']);
		$model = new core_model_car($car_id);
		$car_brand_name = Qutil::filter($_REQUEST['car_brand_name']);
		$car_type_name = Qutil::filter($_REQUEST['car_type_name']);
		switch ($_GET['op']) {
			case 'add':
				$fun = $car_id > 0 ? 'edit' : 'create';
				if($car_brand_name) $model->$fun(array('car_brand_name'=>$car_brand_name,'car_type_name'=>$car_type_name));
				break;
			case 'search':
				if($key) $condi = "car_brand_name like '%{$key}%' or car_type_name like '%{$key}%'";
				break;
			case 'del':
				if($car_id) $model->del();
				break;
		}
		$brand = $model->getallbrand();
		if($brand){
			$params['car_brand'] = self::_join($brand,'car_brand_name');
		}
		if($car_id) $params['onecar'] = $model->get();
		$params['car_id'] = $car_id;
		$params['page'] = $page;
		$model->setPage($page);
		$model->setLimit(core_lib_constant::PAGE_NUM);
		$model->setCount(TRUE);
		$rs = $model->select($condi);
	    $params['totalSize'] = $rs->totalSize;
	    $params['car'] = $rs->items;
		$this->render('admin/car.php', $params);
	}
	//处理数组
	private function _join($arr, $key, $sp=','){
		$str = '';
		if(!$arr) return $str;
		foreach ($arr as $v) {
			$str .= $v[$key].$sp;
		}
		return rtrim($str,$sp);
	}
}
