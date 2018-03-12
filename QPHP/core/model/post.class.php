<?php
class core_model_post extends core_model{
	/**
	 * 设置主健名称
	 */
	public function primarykey(){
		return "log_ID";
	}

	/**
	 * 设置当前表名
	 */
	public function tableName(){
		return core_lib_constant::TABLE_PRE."post";
	}

	function getPost(){
		if(!$this->_pkid) return false;
		return $this->getData();
	}
}
