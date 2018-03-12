<?php
class core_model_attrlist extends core_model{
	/**
	 * 设置主健名称
	 */
	public function primarykey(){
		return "attr_id";
	}

	/**
	 * 设置当前表名
	 */
	public function tableName(){
		return core_lib_constant::TABLE_PRE."attr_list";
	}
	//获取属性
	function getattr($pid = 0){
		return $this->select(array('is_show'=>1,'parent_id'=>$pid))->items;
	}
	//添加属性
	function addattr($data){
		if($data['parent_name']){
			if($data['attr_name']){
				$data['parent_id'] =  $this->getparentid($data['parent_name']);
			}else{
				$data['parent_id'] = 0;
				$data['attr_name'] = $data['parent_name'];
				unset($data['parent_name']);
			}
			$data['is_show'] = 1;//$data['is_show'] ? 1 : 0;
			return $this->insert($data);
		}else{
			return false;
		}
	}
	//编辑属性
	function editattr($data){
		if(!$this->_pkid) return false;
		$is_show = intval($data['is_show']) ? 1 : 0;
		if ($this->getData('parent_id') == 0 and $is_show == 0){
			$this->update(array('parent_id'=>$this->_pkid),array('is_show'=>0));
		}
		$this->set('attr_name', $data['attr_name']);
		$this->set('parent_id', intval($data['parent_id']));
		$this->set('is_show', $is_show);
		return $this->save();
	}
	//删除属性
	function delattr(){
		if(!$this->_pkid) return false;
		if ($this->getData('parent_id') == 0){
			$this->delete(array('parent_id'=>$this->_pkid));
		}
		return $this->delete(array('attr_id'=>$this->_pkid));
	}

	function getparentid($name){
		$rs = $this->selectOne(array('attr_name'=>$name));
		return $rs['attr_id'];
	}
}
