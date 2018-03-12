<?php
class core_model_road extends core_model{
	/**
	 * 设置主健名称
	 */
	public function primarykey(){
		return "road_id";
	}

	/**
	 * 设置当前表名
	 */
	public function tableName(){
		return core_lib_constant::TABLE_PRE."road";
	}
	//获取所有公路
	function getallroad($condi = ''){
		return $this->select($condi)->items;
	}
	//创建汽车信息
	function create($data){
		if($data['road_name']){
			$rs = $this->selectOne(array('road_name'=>$data['road_name']));
			if($rs){
				$this->setError(0,'已经存在');
				return false;
			}
			return $this->insert($data);
		}else{
			return false;
		}
	}
	//编辑公路信息
	function edit($data){
		if(!$this->_pkid) return false;
		if($data['road_name']) $this->set('road_name', $data['road_name']);
		return $this->save();
	}
	//删除公路信息
	function del(){
		if(!$this->_pkid) return false;
		return $this->delete(array('road_id'=>$this->_pkid));
	}
}
