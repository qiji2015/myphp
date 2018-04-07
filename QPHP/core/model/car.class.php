<?php
class core_model_car extends core_model{
	/**
	 * 设置主健名称
	 */
	public function primarykey(){
		return "car_id";
	}

	/**
	 * 设置当前表名
	 */
	public function tableName(){
		return core_lib_constant::TABLE_PRE."car";
	}
	//获取属性
	function getcar(){
		return $this->getData();
	}
	//获取所有车型
	function getall(){
		$rs = $this->select()->items;
		$car = array();
		if($rs){
			foreach ($rs as $key => &$v) {
				$v['full_name'] = $v['car_brand_name'].''.$v['car_type_name'];
			}
		}
		return $rs;
	}
	//获取所有车品牌
	function getallbrand(){
		$rs = $this->select('','car_brand_name','',"group by car_brand_name")->items;
		$brand = array();
		if($rs){
			foreach ($rs as $key => $v) {
				$car['car_brand_name'][] = $v['car_brand_name'];
			}
		}
		return $car;
	}
	//创建汽车信息
	function create($data){
		if($data['car_brand_name'] && $data['car_type_name']){
			return $this->insert($data);
		}else{
			return false;
		}
	}
	//编辑汽车信息
	function edit($data){
		if(!$this->_pkid) return false;
		if($data['car_brand_name']) $this->set('car_brand_name', $data['car_brand_name']);
		if($data['car_type_name']) $this->set('car_type_name', $data['car_type_name']);
		return $this->save();
	}
}
