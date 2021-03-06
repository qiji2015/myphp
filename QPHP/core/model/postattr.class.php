<?php
class core_model_postattr extends core_model{
	public $_esdata;
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
		return core_lib_constant::TABLE_PRE."post_attr";
	}

	function getPost(){
		if(!$this->_pkid) return false;
		return $this->getData();
	}

	function bulidData($data){
		$arr = array();
		$id = intval($data['id']);
		$model = new core_model_post($id);
		$post = $model->getPost();
		$attr_arr = $this->selectOne(array('log_ID'=>$id));
		if($attr_arr){
			$arr = json_decode($attr_arr['json_data'],true);
			if($arr[$id]) unset($arr[$id]);
		}
		$arr['title'] = $post['log_Title'];
		$arr['info'] = $post['log_Intro'];
		$arr['content'] = $post['log_Content'];
		$arr['addtime'] = $post['log_PostTime'];
		$arr['lasttime'] = date('Y-m-d H:i:s',time());
		$arr['cat_id'] = $post['log_CateID'];
		$model_cate = new core_model_category($post['log_CateID']);
		$arr['cat_name'] = $model_cate->getCateName();
		if($post['log_Tag']){
			$model_cate = new core_model_tag();
			$arr['tags'] = $model_cate->getTagName($post['log_Tag']);
		}
		if($data['log_AuthorID']){
			$arr['user_id'] = $data['log_AuthorID'];
			$model_mem = new core_model_member($data['log_AuthorID']);
			$arr['user_name'] = $model_mem->getMemName();
		}
		$arr['id_hash'] = $id;
		if($data['from']) {
			$arr['from'] = $data['from'];
			$model_region = new core_model_region();
			$arr['from_all'] = $model_region->getRegionIdByName($data['from']);
		}
		if($data['to']) {
			$arr['to'] = $data['to'];
			$toarr = explode(',', trim($data['to']));
			$model_region = new core_model_region();
			foreach ($toarr as $vv) {
				if($vv){
					$toall_arr[] = $model_region->getRegionIdByName($vv);
				}
			}
			if($toall_arr) $arr['to_all'] = join(',',array_unique($toall_arr));
		}
		if($data['spot']) $arr['spot'] = $data['spot'];
		if($data['car']) $arr['car'] = $data['car'];
		if($data['road']) $arr['road'] = $data['road'];
		if($data['whither_type']) $arr['whither_type'] = $data['whither_type'];
		if($data['month']) $arr['month'] = $data['month'];
		if($data['holiday']) $arr['holiday'] = $data['holiday'];
		if($data['consume']) $arr['consume'] = $data['consume'];
		if($data['person']) $arr['person'] = $data['person'];
		if($data['cycle']) $arr['cycle'] = $data['cycle'];

		if($data['xiaofei']) $arr['xiaofei'] = $data['xiaofei'];
		if($data['juli']) $arr['juli'] = $data['juli'];
		if($data['huanxian']) $arr['huanxian'] = $data['huanxian'];
		if($data['_id']) $arr['_id'] = $data['_id'];
		$this->_esdata = $arr;
		//print_r($this->_esdata);
		return $arr;
	}

	function create(){
		if(!$this->_esdata) return false;
		$es = new Qes();
		if($this->getData('log_ID')){
			$rs = $es->delete($this->_esdata['_id']);
			//$rs = $es->update($this->_esdata);
			if(!$rs){
				$this->setError(0,'更新ES错误'.$this->_esdata['_id']);
				return false;
			}
		}
		$es_id = $es->add($this->_esdata);
		if(!$es_id){
			$this->setError(0,'插入ES错误');
			return false;
		}
		$attr = array(
			'log_ID'=>$this->_esdata['id_hash'],
			'es_id'=>$es_id['_id'],
			'json_data'=>json_encode($this->_esdata)
		);
		return $this->insert($attr,1);
	}
}
