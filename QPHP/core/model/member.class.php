<?php
class core_model_member extends core_model{
	/**
	 * 设置主健名称
	 */
	public function primarykey(){
		return "mem_ID";
	}

	/**
	 * 设置当前表名
	 */
	public function tableName(){
		return core_lib_constant::TABLE_PRE."member";
	}
	//获取用户名
	function getMemName(){
		if(!$this->_pkid) return false;
		return $this->getData('mem_Name');
	}
}
