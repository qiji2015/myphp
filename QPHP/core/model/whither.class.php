<?php
class core_model_whither extends core_model{
	/**
	 * 设置主健名称
	 */
	public function primarykey(){
		return "id";
	}

	/**
	 * 设置当前表名
	 */
	public function tableName(){
		return core_lib_constant::TABLE_PRE."whither";
	}
	//创建风光信息
	function create($data){
		if($data['f_name']){
			return $this->insert($data);
		}else{
			return false;
		}
	}
}
