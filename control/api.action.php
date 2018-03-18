<?php
class control_api extends core_action{

	public $params;

	function __construct(){
		
	}
	//公路API
	function road(){
		$this->_checksign();
		$model = new core_model_road();
		if($_POST['op'] == 'add'){
			$road_name = Qutil::filter($_POST['road_name']);
			$rs = $model->create(array('road_name'=>$road_name));
			if($rs){
				$this->_outjson(1,'添加成功');
			}else{
				$this->_outjson(0,'添加失败'.$model->getDbError());
			}
		}else{
			$rs = $model->select()->items;
			$this->_outjson(1,$rs);
		}
	}
	//景区API
	function spot(){
		$this->_checksign();
		$model = new core_model_spot();
		if($_POST['op'] == 'add'){
			$post = Qutil::filter($_POST);
			if(!$post['name'] or !$post['abbreviation'] or !$post['place']){
				$this->_outjson(0,'缺少参数');
			}
			$region_model = new core_model_region();
			$post['region_id'] = $region_model->selectOne("region_name like '{$post['place']}%'");
			$rs = $model->create($post);
			if($rs){
				$this->_outjson(1,'添加成功');
			}else{
				$this->_outjson(0,'添加失败'.$model->getDbError());
			}
		}else{
			$rs = $model->select()->items;
			$this->_outjson(1,$rs);
		}
	}
	//车型API
	function car(){
		$this->_checksign();
		$model = new core_model_car();
		if($_POST['op'] == 'add'){
			$car_brand_name = Qutil::filter($_POST['car_brand_name']);
			$car_type_name = Qutil::filter($_POST['car_type_name']);
			if(!$car_brand_name or !$car_type_name){
				$this->_outjson(0,'缺少参数');
			}
			$rs = $model->create(array('car_brand_name'=>$car_brand_name,'car_type_name'=>$car_type_name));
			if($rs){
				$this->_outjson(1,'添加成功');
			}else{
				$this->_outjson(0,'添加失败'.$model->getDbError());
			}
		}else{
			$rs = $model->select()->items;
			$this->_outjson(1,$rs);
		}
	}
	//获取车品牌API
	function carbrand(){
		$this->_checksign();
		$model = new core_model_car();
		$rs = $model->getallbrand();
		$this->_outjson(1,$rs);
	}
	//属性API
	function attr(){
		$this->_checksign();
		$model_attr = new core_model_attrlist();
		$parent_name = Qutil::filter($_POST['type']);
		if(!$parent_name){
			$this->_outjson(0,'缺少参数[type]');
		}
		$res = $model_attr->selectOne(array('attr_name'=>$parent_name));
		if(!$res) {
			$this->_outjson(0,'类型错误');
		}
		if($_POST['op'] == 'add'){
			$attr_name = Qutil::filter($_POST['attr_name']);
			if(!$attr_name){
				$this->_outjson(0,'缺少参数[attr_name]');
			}
			$rs = $model->addattr(array('attr_name'=>$attr_name,'parent_name'=>$parent_name));
			if($rs){
				$this->_outjson(1,'添加成功');
			}else{
				$this->_outjson(0,'添加失败'.$model->getDbError());
			}
		}else{
			$rs = $model->select(array('parent_id'=>$res['attr_id']))->items;
			$this->_outjson(1,$rs);
		}
	}
	//编辑文章属性API
	function editattr(){
		$post = Qutil::filter($_POST);
		if(!$post['id']){
			$this->_outjson(0,'缺少参数[id]');
		}
		$model_attr->bulidData($post);
		$rs = $model_attr->create();
		if($rs){
			$this->_outjson(1,'操作成功');
		}else{
			$this->_outjson(0,'添加失败'.$model_attr->getError());
		}
	}
	function _checksign(){
		if($_POST['key'] != core_lib_constant::API_KEY){
			$this->_outjson(0,'签名错误');
		}
	}
	function _outjson($state,$msg){
		echo json_encode(array('state'=>$state,'msg'=>$msg));
		exit();
	}
}
