<?php
class Qes{
	function __construct(){
		$this->_url = core_lib_constant::ES_HOST.core_lib_constant::ES_INDEX;
		$this->_searchurl = core_lib_constant::ES_HOST.core_lib_constant::ES_INDEX."/_search";
	}
	//创建索引
	public function create(){
	}
	//添加数据
	public function add($data){
		unset($data['_id']);
		$rs = $this->post($this->_url, $data);
		return $rs;
	}
	//搜索
	public function search($data){
		$rs = $this->post($this->_searchurl, $data);
		return $rs['hits'];
	}
	//删除
	public function delete($id){
		$this->_url .= "/{$id}";
		$rs = $this->post($this->_url, array(), 'delete');
		return $rs;
	}
	//更新
	public function update($data){
		$url = $this->_url."/{$data['_id']}/_update";
		unset($data['_id']);
		$rs = $this->post($url, array('doc'=>$data,'detect_noop'=>true));
		return $rs;
	}
	//请求ES
	public function post($url, $data, $opt = 'post'){
		$data = json_encode($data);
		$fu = new Qfetchurl();
		//$fu->debug(1);
		$fu->setHeader('Content-Type','application/json');
		$fu->setOpt($opt,$data);
        $res = $fu->fetch($url);
        //var_dump($res);
        return json_decode($res,true);
	}
}
