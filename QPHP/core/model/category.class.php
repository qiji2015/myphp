<?php
class core_model_category extends core_model{
	/**
	 * 设置主健名称
	 */
	public function primarykey(){
		return "cate_ID";
	}

	/**
	 * 设置当前表名
	 */
	public function tableName(){
		return core_lib_constant::TABLE_PRE."category";
	}
	//获取所有公路
	function getCateName(){
		if(!$this->_pkid) return false;
		return $this->getData('cate_Name');
	}
	//获取所有分类
	function getAllCate(){
		$rs = $this->select();
		$arr = array();
		if($rs->items){
			foreach ($rs->items as $k => $v) {
				$arr[$v['cate_ID']] = $v['cate_Name'];
			}
		}
		return $arr;
	}
}
