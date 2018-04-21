<?php
class control_admin extends core_action{

	public $params;

	function __construct(){
		if(!$this->isLogin()){
			//$this->redirect("/zb_system/login.php");
		}
	}

	function index(){
		$params = array('blogtitle'=>'文章管理');
		$model = new core_model_post();
		$params['page'] = $page;
		$model->setPage($page);
		$model->setLimit(core_lib_constant::PAGE_NUM);
		$model->setCount(TRUE);
		if($_POST['category']>0) $condi['log_CateID'] = intval($_POST['category']);
		if($_POST['search']) {
			$search = Qutil::filter($_POST['search']);
			$condi[] = "(log_Title like '%{$search}%' or log_Intro like '%{$search}%')";
		}
		$rs = $model->select($condi);
	    $params['totalSize'] = $rs->totalSize;
	    $params['post'] = $rs->items;
	    $cate = new core_model_category();
	    $params['cate'] = $cate->getAllCate();
	    $mem = new core_model_member();
	    $params['mem'] = $mem->getAllMem();
	    //print_r($params);
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
			//define(DEBUG, TRUE);
			$post = Qutil::filter($_POST);
			$model_attr->bulidData($post);
			if($model_attr->create()){
				return $this->showMsg('操作成功',1,Qtpl::createUrl('admin', 'index','','admin'));
			}else{
				return $this->showMsg('操作失败'.$model_attr->getError(),2,Qtpl::createUrl('admin', 'attr_edit',array('id'=>$id),'admin'));
			}
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
		$whither = new core_model_whither();
		$params['whither_type'] = $whither->select(array('parent_id>0'))->items;
		$model_attr = new core_model_attrlist();
		$params['person'] = $model_attr->getattr(4);//人群
		$params['month'] = $model_attr->getattr(1);//月份
		$params['holiday'] = $model_attr->getattr(2);//假期
		$params['cycle'] = $model_attr->getattr(5);//周期
		$params['huanxian'] = $model_attr->getattr(3);//环线
		$params['xiaofei'] = $model_attr->getattr(6);//周期
		$params['juli'] = $model_attr->getattr(7);//周期
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
				if($_POST){
					if($_FILES['pic_url']['name']){
						$upload = $this->_upload();
						if($upload->upload()){
							$info = $upload->getUploadFileInfo();
							$post['pic_url'] = core_lib_constant::UPLOAD_SAVE_DIR.$info[0]['savename'];
						}else{
							return $this->showMsg('上传失败'.$upload->getErrorMsg(),2);
						}
					}else{
						$post['pic_url'] = $post['pic_url2'];
					}
					#define(DEBUG,TRUE);
					$fun = $spot_id > 0 ? 'edit' : 'create';
					unset($post['spot_id']);
					$region_model = new core_model_region();
					$re = $region_model->selectOne("region_name like '{$post['place']}%'");
					if($re) $post['region_id'] = $re['region_id'];
					if($post['name']) $model->$fun($post);
					$this->redirect(Qtpl::createUrl('admin', 'spot','','admin'));
				}
				$params['spot_id'] = $spot_id;
				$params['spot'] = $model->getData();
				$whither = new core_model_whither();
				$params['whither'] = $whither->select(array('parent_id>0'))->items;
				return $this->render('admin/spot_add.php', $params);
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
	function whither(){
		$params = array('blogtitle'=>'风光管理');
		$id = intval($_REQUEST['id']);
		$page = intval($_REQUEST['p']);
		$key = Qutil::filter($_REQUEST['key']);
		$model = new core_model_whither($id);
		$data = array();
		$data['f_name'] = Qutil::filter($_REQUEST['f_name']);
		$data['parent_id'] = intval($_REQUEST['parent_id']);
		//define(DEBUG,TRUE);
		switch ($_GET['op']) {
			case 'add':
				if(!$model->create($data)){
					return $this->showMsg('操作失败'.$model->getError(),2);
				}
				$this->redirect(Qtpl::createUrl('admin', 'whither','','admin'));
				break;
			case 'search':
				if($key) $condi = "f_name like '%{$key}%'";
				break;
			case 'del':
				if($id) $model->del();
				$this->redirect(Qtpl::createUrl('admin', 'whither','','admin'));
				break;
		}
		$params['parent'] = $model->select(array('parent_id'=>0))->items;
		$params['page'] = $page;
		$model->setPage($page);
		$model->setLimit(core_lib_constant::PAGE_NUM);
		$model->setCount(TRUE);
		$rs = $model->select($condi);
	    $params['totalSize'] = $rs->totalSize;
	    $params['whither'] = $rs->items;
	    $cArr = $pArr = array();
		if($params['whither']){
			foreach ($params['whither'] as $k=>$v){
				if($v['parent_id'] != 0){
					$cArr[$v['parent_id']][] = $v;
				}else{
					$pArr[$v['id']] = $v;
				}
			}
		}
		$params['p'] = $pArr;
		$params['c'] = $cArr;
		$this->render('admin/whither.php', $params);
	}
	function samekey(){
		$params = array('blogtitle'=>'同义管理');
		$id = intval($_REQUEST['id']);
		$page = intval($_REQUEST['p']);
		$key = Qutil::filter($_REQUEST['key']);
		$model = new core_model_samekey($id);
		$data = array();
		$data['key'] = Qutil::filter($_REQUEST['name']);
		//define(DEBUG,TRUE);
		switch ($_GET['op']) {
			case 'add':
				if(!$model->create($data)){
					return $this->showMsg('操作失败'.$model->getError(),2);
				}
				$this->redirect(Qtpl::createUrl('admin', 'samekey','','admin'));
				break;
			case 'search':
				if($key) $condi = "`key` like '%{$key}%'";
				break;
			case 'del':
				if($id) $model->del();
				$this->redirect(Qtpl::createUrl('admin', 'samekey','','admin'));
				break;
		}
		if($id) $params['onesamekey'] = $model->getData();
		$params['page'] = $page;
		$model->setPage($page);
		$model->setLimit(core_lib_constant::PAGE_NUM);
		$model->setCount(TRUE);
		$rs = $model->select($condi);
		$params['samekey'] = $rs->items;
	    $params['totalSize'] = $rs->totalSize;
		$this->render('admin/samekey.php', $params);
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
	function del(){
		$es = new Qes();
		$rs = $es->delete($_GET['k']);
	}
	//处理上传
	function _upload(){
		$filepath = core_lib_constant::UPLOAD_DIR;
		$upload = new Qupload();
		$upload->maxSize  = 2048000;
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');
		$upload->savePath = $filepath;
		$upload->thumb = true;
		$upload->thumbMaxWidth = '600,1200';
		$upload->thumbMaxHeight = '600,1200';
		$upload->thumbPrefix = 'thumb_';
		return $upload;
	}
}
