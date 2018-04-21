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
	//获取所有景区
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
			$data2['name'] = $data['name'];
			$data2['place'] = $data['place'];
			$data2['level'] = $data['level'];
			$data2['abbreviation'] = $data['abbreviation'];
			$data2['region_id'] = $data['region_id'];
			if($data['pic_url']) $data2['pic_url'] = $data['pic_url'];
			if($data['ticket']) $data2['ticket'] = $data['ticket'];
			if($data['opentime']) $data2['opentime'] = $data['opentime'];
			if($data['telphone']) $data2['telphone'] = $data['telphone'];
			if($data['road']) $data2['road'] = $data['road'];
			if($data['whither']) $data2['whither'] = $data['whither'];
			return $this->insert($data2);
		}else{
			return false;
		}
	}
	//编辑公路信息
	function edit($data){
		if(!$this->_pkid) return false;
		if($data['name']) $this->set('name', $data['name']);
		if($data['place']) $this->set('place', $data['place']);
		if($data['region_id']) $this->set('region_id', $data['region_id']);
		if($data['level']) $this->set('level', $data['level']);
		if($data['abbreviation']) $this->set('abbreviation', $data['abbreviation']);
		if($data['pic_url']) $this->set('pic_url', $data['pic_url']);
		if($data['ticket']) $this->set('ticket', $data['ticket']);
		if($data['opentime']) $this->set('opentime', $data['opentime']);
		if($data['telphone']) $this->set('telphone', $data['telphone']);
		if($data['road']) $this->set('road', $data['road']);
		if($data['whither']) $this->set('whither', $data['whither']);
		return $this->save();
	}
}
