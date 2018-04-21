<?php
class core_model_samekey extends core_model{
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
		return core_lib_constant::TABLE_PRE."samekey";
	}
	//创建风光信息
	function create($data){
		$data['key'] = str_replace('，', ',', $data['key']);
		if($data['key']){
			if($this->_pkid){
				$this->set('key',$data['key']);
				return $this->save();
			}
			return $this->insert($data);
		}else{
			$this->setError(0,'参数错误');
			return false;
		}
	}
}
