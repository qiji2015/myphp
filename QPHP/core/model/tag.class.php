<?php
class core_model_tag extends core_model{
	/**
	 * 设置主健名称
	 */
	public function primarykey(){
		return "tag_ID";
	}

	/**
	 * 设置当前表名
	 */
	public function tableName(){
		return core_lib_constant::TABLE_PRE."tag";
	}
	//获取tag名称
	function getTagName($str){
		preg_match_all('{\d+}', $str, $ids);
		$ids = join(',',$ids[0]);
		$rs = $this->select("tag_ID in($ids)")->items;
		if(!$rs) return '';
		foreach ($rs as $v) {
			$strs .= $v['tag_Name'].',';
		}
		return rtrim($strs,',');
	}
}
