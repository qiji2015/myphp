<?php
class core_model_user extends core_model{
	/**
	 * 设置主健名称
	 */
	public function primarykey(){
		return "user_id";
	}

	/**
	 * 设置当前表名
	 */
	public function tableName(){
		return core_lib_constant::TABLE_PRE."user";
	}
}
