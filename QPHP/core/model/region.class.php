<?php
class core_model_region extends core_model{
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
}
