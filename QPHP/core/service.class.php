<?php
/**
 * 业务抽象基础类
 */
class core_service  {
	protected $_time;
	protected $_error = array();
	protected $id;
	protected $marter_table;
	protected $data = array();
	protected $data_many = array();
	protected $data_tmp = array();
	protected $model = array();
	protected $transaction = true;
	protected $dbentry = false;
	protected $lock = false;
	protected $lock_tables = NULL;

	/**
	 * construct
	 * @return void
	 */
	protected function __construct() {
		$this->_time = time();
	}
	
	/**
	 * 设置主键
	 * @return void
	 */
	public function setId($id) {
		$this->model = array();
		$this->data_many = array();
		$this->id = $id;
	}

	/**
	 * 获取主表
	 * @return string
	 */
	public function getMarterTable() {
		return $this->marter_table;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * 开启或关闭事务
	 * @param boolean $isbt 是否开启事务
	 */
	public function setTransaction($isbt){
		$this->transaction=$isbt;
	}

	/**
	 * @param boolean $entry
	 * @return void
	 */
	public function setDbEntry($entry = true) {
		$this->dbentry = $entry;
	}

	/**
	 * 锁定方式访问指定数据表
	 * @param boolean $lock 是否锁定方式访问
	 * @param array $tables 只对指定表开启锁定,支持多表
	 */
	public function setLock($lock = true, $tables = NULL) {
		$this->setDbEntry();
		$this->lock = $lock;
		$this->lock_tables = $tables;
	}

	/**
	 * setError
	 * @param int $code
	 * @param string $msg
	 */
	protected function setError($code=0,$msg="") {
		$this->_error["code"] = $code;
		$this->_error["msg"] = $msg;
	}

	/**
	 * getError
	 * @param string $type
	 */
	public function getError($type="msg") {
		return $this->_error[$type];
	}

	/**
	 * 获取对象成员属性数据,支持获取数据映射中(HAS_ONE,BELONGS_TO)的数据
	 * @return array|string
	 */
	public function get($fields = '*') {
		$fields = empty($fields)?'*':$fields;
		if(strpos($fields,'.') === false) {
			$fields = $this->getMarterTable() .'.'. $fields;
		}
		$key_tree = explode('.', $fields);
		$table = $key_tree[0];
		if(empty($this->id)) {
			if($key_tree[1] == '*' || $key_tree[1] == '') {
				return $this->data[$table];
			}else {
				return $this->data[$table][$key_tree[1]];
			}
		}else {
			if($key_tree[1] == '*' || $key_tree[1] == '') {
				return $this->model($table)->getData("", $this->isLockTable($table));
			}else {
				return $this->model($table)->getData($key_tree[1], $this->isLockTable($table));
			}
		}
	}

	/**
	 * 设置对象成员属性数据,支持设置数据映射中(HAS_ONE,BELONGS_TO)的数据
	 * @param array|string $fields
	 * @param array|string $val
	 * @param boolean
	 */
	public function set($fields, $val = '') {
		if(is_array($fields)) {
			foreach($fields as $key=>$val) {
				if($this->_set($key, $val) === false) {
					$this->setError(0,'设置失败 '.$this->getError());
					return false;
				}
			}
		}else {
			return $this->_set($fields, $val);
		}
		return true;
	}

	/**
	 * @param string $fields
	 * @param max $val
	 * @return boolean;
	 */
	protected function _set($key ,$val) {
		$fields = empty($key)?'*':$key;
		if(strpos($fields,'.') === false) {
			$fields = $this->getMarterTable() .'.'. $fields;
		}
		$key_tree = explode('.', $fields);
		$table = $key_tree[0];
		if($key_tree[1] == '*' || $key_tree[1] == '') {
			$this->setError(0,'不支持的数据格式');
			return false;
		}else {
			if(empty($this->id)) {
				$this->data[$table][$key_tree[1]] = $val;
			}
			$this->data_tmp[$table][$key_tree[1]] = $val;
			$this->model($table)->set($key_tree[1],$val);
		}
		return true;
	}

	/**
	 * 保存数据
	 * @param string $name
	 * @return boolean
	 */
	public function save($name = '') {
		if(empty($name)) {
			foreach($this->data_tmp as $key=>$val) {
				if($this->model($key)->save() === false) {
					$this->setError(0,$this->model($key)->getError().$this->model($key)->getDbError());
					return false;
				}
			}
			$this->data_tmp = array();
			$this->data = array();
		}else {
			if($this->model($name)->save() === false) {
				$this->setError(0,$this->model($name)->getError().$this->model($name)->getDbError());
				return false;
			}
			unset($this->data_tmp[$name]);
			unset($this->data[$name]);
		}
		return true;
	}

	/**
	 * 获取当前保存的Id
	 * @returm $insertId
	 */
	public function getLastId() {
		return $this->model()->getPkid();
	}

	/**
	 * 获取扩展数据,一对多的数据关系
	 * @param string $name 关系名称
	 * @return array|false
	 */
	public function getMany($name) {
		//检查数据是否存在,如果存在就直接返回
		if(isset($this->data_many[$name])) {
			return $this->data_many[$name];
		}
		//重新获取数据
		$relations = $this->model()->relations();
		if(!isset($relations[$name])) {
			$this->setError(0,'无法找到映射关系');
			return false;
		}
		$condition = $this->model()->getRelationsCondition($name);
		if($condition === false) {
			$this->setError(0,'无法找到映射条件，原因:'.$this->model()->getError());
			return false;
		}
		$condition .='=\''.$this->id.'\'';
		$result = $this->model($name)->select($condition);
		if($result === false) {
			$this->setError(0,'获取数据失败 '.$this->model()->getError());
			return false;
		}
		$this->data_many[$name] = $result->items;
		return $this->data_many[$name];
	}

	/**
	 * 清除扩展数据
	 * @param string $table 指定别名
	 * @return void
	 */
	protected function clearManyData($table = '') {
		if(empty($table)) {
			$this->data_many = array();
		}else {
			unset($this->data_many[$table]);
		}
	}

	/**
	 * 清除成员属性
	 * @return void
	 */
	protected function clearData() {
		$this->data = array();
		$this->model()->clearData();
	}

	/**
	 * 清除已经设置的临时数据
	 * @return void
	 */
	protected function clearDataTmp() {
		$this->data_tmp = array();
	}

	/**
	 * 获取相应MODEL
	 * @param string $name 要实例化的MODEL
	 * @return objcect|false
	 */
	protected function model($name = '') {
		//默认为主model
		if(empty($name))$name = $this->marter_table;
		if(!isset($this->model[$name])) {
			//检查是否实例化主表
			if(!isset($this->model[$this->marter_table])) {
				$model_class = $this->marter_table;
				$this->model[$this->marter_table] = new $model_class($this->id);
				//是否强制读取主库
				if($this->dbentry === true) {
					$this->model[$this->marter_table]->setDbEntry();
				}
				return $this->model($name);
			}
			$relations = $this->model[$this->marter_table]->relations();
			if(!isset($relations[$name])) {
				$this->setError(0,'没有找到表关系'.$name);
				return false;
			}
			//如果是多对一的关系
			if($relations[$name][2] == 'BELONGS_TO') {
				$id = $this->get($relations[$name][1]);
			}else {
				$id = $this->id;
			}
			$class_name = $relations[$name][0];
			if(empty($id)) {
				$this->model[$name] = new $class_name;
			}else {
				$this->model[$name] = new $class_name($id);
			}
		}
		return $this->model[$name];
	}

	/**
	 * 开启事务
	 * @param string $db 开启事务的数据库
	 */
	protected function _beginTransaction($db='') {
		if($this->transaction !== true) return false;
		$this->model()->_db->beginTransaction($db);
	}

	/**
	 * 提交事务
	 * @param string $db 要执行事务的数据库
	 */
	protected function _commit($db='') {
		if($this->transaction !== true) return false;
		$this->model()->_db->commit($db);
	}

	/**
	 * 回滚事务
	 * @param string $db 要执行事务的数据库
	 */
	protected function _rollBack($db='') {
		if($this->transaction !== true) return false;
		$this->model()->_db->rollBack($db);
	}

	/**
	 * 检查当前表访问是否为锁定方式
	 * @param string $model 要检查的别名
	 * @return boolean
	 */
	protected function isLockTable($model) {
		if($this->lock == false) {
			return false;
		}
		//检查是否指定了表名
		if($this->lock_tables === NULL) {
			return true;
		}
		//如果是主表，也会锁定
		if($model == $this->getMarterTable()) {
			return true;
		}
		//如果指定了表名，是否在其中
		if(in_array($model, (array)$this->lock_tables)) {
			return true;
		}else {
			return false;
		}
	}
}