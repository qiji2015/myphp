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
	//检查登录
	function checkLogin(){
		$cookie = Qutil::filter($_COOKIE);
		//$cookie = array('username'=>'admin','password'=>'4fff247fb969892381c346bb58d5f131');
		$rs = $this->selectOne(array('mem_Name'=>$cookie['username'],'mem_Status'=>0));
		if($rs['mem_Level'] == 1){
			if($cookie['password'] == md5($rs['mem_Password'].core_lib_constant::ZC_BLOG_CLSID)){
				return true;
			}
		}
		return false;
	}
}
