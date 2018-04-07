<?php
class core_model_region extends core_model{
	public $_regionstr;
	/**
	 * 设置主健名称
	 */
	public function primarykey(){
		return "region_id";
	}

	/**
	 * 设置当前表名
	 */
	public function tableName(){
		return core_lib_constant::TABLE_PRE."region";
	}

	function getregion($pid = 1){
		return $this->select(array('parent_id'=>$pid))->items;
	}
	
	function getRegionIdByName($name){
		$rs = $this->selectOne("region_name like '{$name}%'");
		if($rs){
			$this->getAllRegion($rs['region_id']);
		}
		return $this->_regionstr.$name;
	}
	function getAllRegion($id){
		$rs = $this->selectOne(array('region_id'=>$id));
		if($rs){
			if($rs['parent_id'] > 0){
				$arr = $this->getAllRegion($rs['parent_id']);
				$this->_regionstr .= $arr['region_name'].'-';
			}
		}
		return $rs;
	}
}
