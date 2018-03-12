<?php
class core_model_spot extends core_model{
	/**
	 * 设置主健名称
	 */
	public function primarykey(){
		return "jq_id";
	}

	/**
	 * 设置当前表名
	 */
	public function tableName(){
		return core_lib_constant::TABLE_PRE."spot";
	}
	//获取所有公路
	function getallspot(){
		return $this->select()->items;
	}
	//创建景区信息
	function create($data){
		if($data['abbreviation']){
			$rs = $this->selectOne(array('abbreviation'=>$data['abbreviation']));
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
		if($data['name']) $this->set('name', $data['name']);
		#if($data['place']) $this->set('place', $data['place']);
		if($data['place_id']) $this->set('place_id', $data['place_id']);
		if($data['level']) $this->set('level', $data['level']);
		if($data['abbreviation']) $this->set('abbreviation', $data['abbreviation']);
		return $this->save();
	}
}
