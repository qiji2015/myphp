<?php
class core_model{
	public $_db;	
	protected $_time;
	protected $_pkid;
	protected $_table;
	protected $_primarykey;
	protected $_pkid_lock = array();

	protected $_data=array();
	protected $_dataTmp=array();
	protected $_error= array();

	function __construct($pkid = false){
		$this->_db = new Qdb('mysql');
		$this->_time = time();
		if($pkid){
			$this->setPkid($pkid);
		}
	}
	
	/**
	 * 当没有model文件的时候使用此方法初始化
	 * @param string 表主键
	 * @param string 表名
	 */
	public function init($primarykey, $table){
		$this->_primarykey = $primarykey;
		$this->_table = $table;
	}
	
	/**
	 * 主健名称 该方法需被子类覆盖
	 */
	public function primarykey(){
		return $this->_primarykey ? $this->_primarykey : 'id';
	}

	/**
	 * 当前表名 该方法需被子类覆盖
	 */
	public function tableName(){
		return $this->_table ? $this->_table : false;
	}

	/**
	 * 设置主健值
	 * @param int $id
	 */
	public function setPkid($id){
		$this->_pkid = $id;
		$this->_dataTmp =array();
	}

	/**
	 * 获取当前主健值
	 * @return int
	 */
	public function getPkid(){
		return $this->_pkid;
	}

	/**
	 * 数据输入
	 * @param string $key
	 * @param mixed $value
	 */
	public function set($key,$value){
		if (empty($this->_pkid)){
			$this->_data[0][$key] = $value;
		} else {
			$this->_data[$this->_pkid][$key] = $value;
		}
		$this->_dataTmp[$key] = $value;
	}

	/**
	 * 数据保存
	 * @param mixed $pkid
	 * @param boolean $lock
	 */
	public function save($pkid="",$lock = false){
		if (!$this->_dataTmp){
			return true;
		}
		if ($pkid === false){
			$this->_pkid = null;//清除主键值
		}
		if ($pkid){
			$this->_pkid = $pkid;
		}
		$tableName = $this->tableName();
		if (!$tableName){
			$this->setError(0, 'model缺少表名');
			return false;
		}
		$primarykey = $this->primarykey();
		if (!$primarykey){
			$this->setError(0, 'model缺少主键');
			return false;
		}
		if ($this->_pkid){//存在主健值则为修改
			$condition = array(
					$primarykey => $this->_pkid,
					);
			if ($lock == true){
				$rowCount = $this->_db->update($tableName,$condition,$this->_dataTmp,array('lock'=>1));
			} else {
				$rowCount = $this->_db->update($tableName,$condition,$this->_dataTmp);
			}
			if ($rowCount === false){
				$this->setError(0, $this->getDbError());
				return false;
			} else {
				$this->_dataTmp = array();//清空临时数据
				return true;
			}
		} else {//不存在主健值为插入
			$lastInsertID = $this->_db->insert($tableName,$this->_dataTmp);
			$this->_pkid = $lastInsertID;
			$this->_dataTmp = array();//清空临时数据
			return $lastInsertID;
		}
	}

	/**
	 * 获取一行数据
	 * @param mixed $condition 条件
	 * @param mixed $item 查询字段
	 * @param boolean $lock 是否锁表
	 */
	public function get($condition="",$item="*",$lock = false){
		if ((!$condition) && $this->_data[$this->_pkid]){//没有查询条件并且data数据存在，直接返回数据
			return $this->_data[$this->_pkid];
		}
		$tableName = $this->tableName();
		if (!$tableName){
			return false;
		}
		$primarykey = $this->primarykey();
		if (!$primarykey){
			return false;
		}
		if (!$condition){
			if (!$this->_pkid){
				return false;
			}
			$condition = array($primarykey => $this->_pkid);
		}
		if ($item != "*") {
			$item = str_replace(" ", "", $item);
			if (!is_array($item)){
				$aItem = explode(",", $item);
			}
			if (!in_array($primarykey, $aItem)){//查询字段中不包括主键时，把主键加进去
				$aItem[] =  $primarykey;
			}
			$item = implode(",", $aItem);
		}
		if ($lock == true){
			$data = $this->_db->selectOne($tableName,$condition,$item,null,null,null,array('lock'=>'FOR UPDATE'));
		} else {
			$data = $this->_db->selectOne($tableName,$condition,$item);
		}
		if (!empty($data[$primarykey]) && $this->_pkid != $data[$primarykey]){
			$this->_pkid = $data[$primarykey];
		}
		$this->_data[$this->_pkid] = $data;
		return $this->_data[$this->_pkid];
	}

	/**
	 * 获取data数据
	 * @param string $key 键名
	 * @param boolean $lock
	 */
	public function getData($key="",$lock = false){
		if(!$this->_pkid){
			$this->setError(0, '未设置主键id');
			return false;
		}
		if(!$this->primarykey()) {
			$this->setError(0, '未设置表关系');
			return false;
		}
		//是否需要重新获取数据
		$is_entry = $lock == true && $this->_pkid_lock[$this->_pkid] != true ;
		if($is_entry) {
			//清除数据
			$this->_pkid_lock[$this->_pkid] = true;
			$this->clearData();
		}

		if (!$this->_data[$this->_pkid] || ($key && !isset($this->_data[$this->_pkid][$key]))) {
			if($this->get(array($this->primarykey()=>$this->_pkid), "*", $lock) === false) {
				$this->setError(0, '查询失败');
				return false;
			}
			//保持最新的值
			foreach($this->_dataTmp  as $k=>$v) {
				$this->set($k, $v);
			}
		}
		if($key) {
			return $this->_data[$this->_pkid][$key];
		} else {
			return $this->_data[$this->_pkid];
		}
	}

	/**
	 * 清除data数据
	 */
	public function clearData(){
		$this->_data[(int)$this->_pkid] = null;
	}

	/**
	 * 删除
	 * @return boolean
	 */
	public function del() {
		if (!$this->_pkid) {
			return false;
		}
		$tableName  = $this->tableName();
		$primarykey = $this->primarykey();
		$res = $this->_db->delete($tableName,array($primarykey=>$this->_pkid));
		if ($res > 0) {
			$this->_data[$this->_pkid] = array();
			$this->_dataTmp = array();
			$this->_pkid = null;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * selectOne
	 */
	public function selectOne($condition = "", $item = "*", $groupby = "", $orderby = "", $leftjoin = "", $params = array('type'=>'query')) {
		return $this->_db->selectOne($this->tableName(), $condition, $item, $groupby, $orderby, $leftjoin, $params);
	}

	/**
	 * select
	 */
	public function select($condition = "", $item = "*", $groupby = "", $orderby = "", $leftjoin = "", $params = array('type'=>'query')) {
		return $this->_db->select($this->tableName(), $condition, $item, $groupby, $orderby, $leftjoin, $params);
	}

	/**
	 * insert
	 */
	public function insert($item = "", $isreplace = false, $isdelayed = false, $update = array(), $params = array('type'=>'main')) {
		return $this->_db->insert($this->tableName(), $item, $isreplace, $isdelayed, $update, $params);
	}

	/**
	 * update
	 */
	public function update($condition = "", $item = "", $params = array('type'=>'main')) {
		return $this->_db->update($this->tableName(), $condition, $item, $params);
	}

	/**
	 * delete
	 */
	public function delete($condition = "", $params = array('type'=>'main')) {
		return $this->_db->delete($this->tableName(), $condition, $params);
	}

	/**
	 * query
	 */
	public function query($sql, $bind1 = array(), $bind2 = array(), $params = array()) {
		return $this->_db->query($this->tableName(), $sql, $bind1, $bind2, $params);
	}

	/**
	 * 设置是否统计总数
	 */
	public function setCount($count) {
		$this->_db->setCount($count);
	}

	/**
	 * 设置当前页数
	 */
	public function setPage($page) {
		$this->_db->setPage($page);
	}

	/**
	 * 设置当前返回记录数
	 */
	public function setLimit($limit) {
		$this->_db->setLimit($limit);
	}

	/**
	 * insertId
	 */
	public function lastInsertId() {
		return $this->_db->lastInsertId();
	}

	/**
	 * 获取relations条件
	 * @param string $name 关系名称
	 * @return string|false
	 */
	public function getRelationsCondition($name) {
		$conditon = '';
		$relations = $this->relations();
		if(!isset($relations[$name])) {
			$this->setError(0,'无法获取关系结构');
			return false;
		}
		$condition = $relations[$name][1];
		return $condition;
	}
	
	/**
	 * 强行指定为主库
	 */
	public function setDbEntry($dbtype='main'){
		$this->_db->setDbEntry($dbtype);
	}

	/**
	 * 获取数据库错误信息
	 */
	public function getDbError() {
		return join(',', (array)$this->_db->error['msg']);
	}

	/**
	 * 写入错误信息
	 * @param int $code
	 * @param string $msg
	 */
	protected function setError($code=0,$msg=""){
		$this->_error["code"] = $code;
		$this->_error["msg"] = $msg;	
	}
	
	/**
	 * 获取错误信息
	 * @param string $type
	 */
	public function getError($type="msg"){
		return $this->_error[$type];
	}
	
	public function disconnect() {
		return $this->_db->disconnect();
	}

	public function __destruct() {
		$this->_db = null;	
	}
}