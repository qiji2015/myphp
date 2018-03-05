<?php
/**
 * DB类
 */
class Qdb extends DbObject{
	public $host;
	public $port=3306;
	public $user;
	public $password;
	public $database;
	public $pri;
	public $key;
	public $dtype;
	public $charset;
	public $orderby;
	public $groupby;
	public $sql;
	public $count=false;
	public $limit=0;
	public $page=1;
	public $error=array('code'=>0,'msg'=>"");
	
	private $prefix;
	private $countsql;
	private $affectedRows=0;
	
	static $globals;
	static $dbentry;
	function __construct($prefix="mysql"){
		$this->prefix=$prefix;
	}
	
	/**
	 * 初始化数据库
	 * @param array $params
	 * @param string $db 数据库名称或者别名
	 */
	 function init($params, $db=''){
	 	$zone = 'default';//当多数据库的时候可以根据表名来连接不同的数据库 当然表和库要有一定联系
	 	$dbInfo = require(QPHP_DIR.'/db_config.php');
	 	$dbInfo = $dbInfo[$zone];
	 	if(!$dbInfo){
	 		die("dbconfig error");
	 	}
	 	if(!empty($params['type'])) {
			$this->dtype = $params['type'];
		} else {
			$this->dtype = 'main';
		}
		foreach($dbInfo as $key =>$row){
			if(strpos($key,"query") !== false){
				$index = "query";
			}else{
				$index = "main";
			}
			$cache[$zone][$index][]= $row;
		}
		if(self::$dbentry) $this->dtype = self::$dbentry;
		$i =  array_rand($cache[$zone][$this->dtype]);
		foreach($cache[$zone][$this->dtype][$i] as $key=>$value){
			$this->$key = $value;
		}
		$this->key = "{$this->prefix}:{$this->host}:{$this->user}:{$this->password}:$this->database";
		if(!isset(self::$globals[$this->key])) self::$globals[$this->key] = "";
	}
	
	/**
	 * @param boolean count
	 */
	function setCount($count){
		$this->count = $count === true ? true : false;
	}
	
	/**
	 * @param int page 
	 */
	function setPage($page){
		$this->page= intval($page) > 0 ? intval($page) : 1;
	}
	
	/**
	 * @param int limit ,0 is all
	 */
	function setLimit($limit){
		$this->limit= intval($limit) > 0 ? intval($limit) : 1;
	}
	
	/**
	 * @param string groupby
	 */
	function setGroupby($groupby){
		$this->groupby=$groupby;
	}
	
	/**
	 * @param string orderby
	 */
	function setOrderby($orderby){
		$this->orderby=$orderby;
	}

	/**
	 * select
	 * @param mixed $table 
	 * @param array $condition
	 * @param array $item 
	 * @param string $groupby 
	 * @param string $orderby
	 * @param string $leftjoin
	 * @return object|boolean
	 */
	function select($table,$condition="",$item="*",$groupby="",$orderby="",$leftjoin="",$params=array('type'=>'query')){
		if($item=="") $item="*";
		if(is_array($table)){
			$tmp = array();
			for($i=0;$i<count($table);$i++){
				$tmp[] = trim($table[$i]);
			}
			$table = implode(" , ",$tmp);
		}else{
			$table = trim($table);
		}
		if(is_array($item)) $item = implode(" , ",$item);
		$condiStr = $this->__quote($condition,"AND",$bind);
		if($condiStr != ""){
			$condiStr = " WHERE ".$condiStr;
		}
		$join="";
		if(is_array($leftjoin)){
			foreach ($leftjoin as $key=>$value){
				$join.=" LEFT JOIN $key ON $value ";
			}
		}
		$this->groupby  =$groupby!=""?$groupby:$this->groupby;
		$this->orderby  =$orderby!=""?$orderby:$this->orderby;
		$orderby_sql="";
		$orderby_sql_tmp = array();
		if(is_array($orderby)){
			foreach($orderby as $key=>$value){
				if(!is_numeric($key)){
					$orderby_sql_tmp[]=$key." ".$value;
				}
			}
		}else{
			$orderby_sql=$this->orderby;
		}
		if(count($orderby_sql_tmp)>0){
			$orderby_sql=" ORDER BY ".implode(",",$orderby_sql_tmp);
		}
		$limit="";
		if($this->limit != 0){
			$limit = ($this->page-1)*$this->limit;
			$limit = "LIMIT $limit,$this->limit";
		}
		$this->sql="SELECT $item FROM $table $join $condiStr $groupby $orderby_sql $limit";
		if($groupby){
			$this->countsql="SELECT count(1) totalSize FROM (SELECT 1 FROM $table $join $condiStr $groupby) as tmp_count";
		}else{
			$this->countsql="SELECT count(1) totalSize FROM $table $join $condiStr $groupby";
		}
		$data = new DbData;
		$data->page = $this->page;
		$data->limit = $this->limit;
		$start = microtime(true);
		$data->limit = $this->limit;
		$data->items = $this->query($table,$this->sql,$bind,null,$params);
		if($data->items === false){
			return false;
		}
		$data->pageSize = count($data->items);
		$end = microtime(true);
		$data->totalSecond = $end-$start;
		if($this->limit !=0 && $this->count==true && $this->countsql!=""){			
			$result_count = $this->query($table,$this->countsql,$bind,null,$params);
			$data->totalSize = $result_count[0]['totalSize'];
			$data->totalPage = ceil($data->totalSize/$data->limit);
		}
		$this->setCount(false);
		$this->setPage(1);
		$this->setLimit(0);
		$this->setGroupby("");
		$this->setOrderby("");
		return $data;
	}
	/**
	 * 查询一条记录
	 * @param mixed $table
	 * @param array $condition
	 * @param array $item 
	 * @param string $groupby
	 * @param string $orderby
	 * @param string $leftjoin
	 * @return array $data
	 */
	function selectOne($table,$condition="",$item="*",$groupby="",$orderby="",$leftjoin="",$params=array('type'=>'query')){
		$this->setLimit(1);
		$this->setCount(false);
		$data=$this->select($table,$condition,$item,$groupby,$orderby,$leftjoin,$params);
		$this->setCount(false);
		$this->setPage(1);
		$this->setLimit(0);
		$this->setGroupby("");
		$this->setOrderby("");
		if($data === false ) {
			return false;
		}elseif(isset($data->items[0])){
			return $data->items[0];
		}else {
			return array();
		}
	}

	/**
	 * 更新
	 * @param mixed $table
	 * @param string,array $condition
	 * @param array $item
	 * @param int $limit
	 * @return int|boolean
	 */
	function update($table,$condition="",$item="",$params=array('type'=>'main')){
		$value = $this->__quote($item,",",$bind_v);
		$condiStr = $this->__quote($condition,"AND",$bind_c);
		$condiStr = $condiStr ? " WHERE {$condiStr}" : "";
		//出于安全不能整表更新 需要的时候请使用 1=1
		if(empty($condiStr)) return false;
		$this->sql="UPDATE $table SET $value $condiStr";
		if($this->query($table,$this->sql,$bind_v,$bind_c,$params)!==false){
			return $this->rowCount();
		}else{
			return false;
		}
	}
	
	/**
	 * 删除
	 * @param mixed table
	 * @param string,array $condition
	 * @param int $limit
	 * @return int|boolean
	 */
	function delete($table,$condition="",$params=array('type'=>'main')){
		$condiStr = $this->__quote($condition,"AND",$bind);
		$condiStr = $condiStr ? " WHERE {$condiStr}" : "";
		//出于安全不能整表更新 需要的时候请使用 1=1
		if(empty($condiStr)) return false;
		$this->sql="DELETE FROM  $table $condiStr";
		if($this->query($table,$this->sql,$bind,null,$params)!==false){
			return $this->rowCount();
		}else{
			return false;
		}
	}
	/**
	 * 插入记录
	 * @param $table
	 * @param array $item 
	 * @param array $isreplace
	 * @return int
	 */
	function insert($table,$item="",$isreplace=false,$isdelayed=false,$update=array(),$params=array('type'=>'main')){
		if($isreplace==true){
			$command="REPLACE";
		}else{
			$command="INSERT";
		}
		if($isdelayed==true){
			$command.=" DELAYED ";
		}
		$f = $this->__quote($item,",",$bind_f);
		$this->sql="$command INTO $table SET $f ";
		$v = $this->__quote($update,"AND",$bind_v);
		if(!empty($v)){
			$this->sql.="ON DUPLICATE KEY UPDATE ".implode(",",$v);
		}
		$r=$this->query($table,$this->sql,$bind_f,$bind_v,$params);
		if($r===false){
			return false;
		}elseif($this->lastInsertId ()>0){
			return $this->lastInsertId ();
		}elseif($this->affectedRows >0){
			return $this->affectedRows;
		}else{
			return $r;
		}
	}

	/**
	 * 查询
	 * @param string $sql
	 * @return array|boolean
	 */
	function query($table,$sql,$bind1=array(),$bind2=array(),$params=array()){
		if(self::$dbentry) $params['type'] = 'main';
		if(defined("DEBUG")){
			echo "SQL:$sql\n";
			print_r($bind1);
			print_r($bind2);
			print_r($params);
		}
		if(empty($sql)) {
			return false;
		}
		if(!$this->isSafe($sql)) {
			return false;
		}
		$lock = isset($params['lock'])?' '.$params['lock']:'';
		$stmt = $this->getPodObject($table,$params)->prepare($sql.$lock);
		if(!$stmt){
			$this->error['code']=self::$globals[$this->key]->errorCode ();
			$this->error['msg']=self::$globals[$this->key]->errorInfo ();
			if(defined("DEBUG")){
				print_r($this->error);
			}
			return false;
		}
		if(!empty($bind1)){
			foreach($bind1 as $k=>$v){
				$stmt->bindValue($k,$v);
			}
		}
		if(!empty($bind2)){
			foreach($bind2 as $k=>$v){
				$stmt->bindValue($k + count($bind1),$v);
			}
		}
		if($stmt->execute ()){
			$this->affectedRows = $stmt->rowCount();
			return $stmt->fetchAll (PDO::FETCH_ASSOC );
		}else{
			$this->error['code']=$stmt->errorCode ();
			$this->error['msg']=$stmt->errorInfo ();
			if(defined("DEBUG")){
				print_r($this->error);
			}
		}
		return false;
	}
	
	/**
	 * lastInsertId
	 * @return int
	 */
	function lastInsertId(){
		return self::$globals[$this->key]->lastInsertId ();
	}
	
	/**
	 * 影响的数量
	 * @return int
	 */
	function rowCount(){
		return $this->affectedRows;
	}

	/**
	 * 执行sql
	 * @param string $sql
	 * @return array|boolean
	 */
	function execute($sql,$table=''){
		return $this->query($table,$sql);
	}

	function __connect($forceReconnect=false){
		if(empty(self::$globals[$this->key]) || $forceReconnect){
			if(!empty(self::$globals[$this->key])){
				unset(self::$globals[$this->key]);
			}
			try{
				self::$globals[$this->key] = new PDO($this->prefix.":dbname=".$this->database.";host=".$this->host.";port=".$this->port,$this->user,$this->password);
			}catch(Exception $e){
				$dbError = "connect mysql error";
				if(defined("DEBUG")){
					$dbError .= var_export($this,true);
				}
				die($dbError);
			}
		}
		if(!empty($this->charset)){
			self::$globals[$this->key]->exec("SET NAMES ".$this->charset);
		}
	}
	
	function __quote($condition, $split = "AND", &$bind) {
		$condiStr = "";
		if(!is_array($bind)){$bind=array();}
		if(is_array($condition)){
			$v1=array();
			$i=1;
			foreach($condition as $k=>$v){
				if(!is_numeric($k)){
					if(strpos($k,".")>0){
						$v1[]="$k = ?";
					}else{
						$v1[]="`$k` = ?";
					}
					$bind[$i++]=$v;
				}else{
					$v1[]=($v);
				}
			}
			if(count($v1)>0){
				$condiStr=implode(" ".$split." ",$v1);
			}
		}else{
			$condiStr = $condition;
		}
		return $condiStr;
	}

	/**
	 * 建立链接
	 */
	public function getPodObject($db,$params=array('type'=>'query')){
		if(empty($params['dbinfo'])) {
			$this->init($params,$db);
			if(empty(self::$globals[$this->key])){
				$this->__connect(true);
			}
		} else {
			$this->init($params,$db);
			$this->__connect(true);
		}
		return self::$globals[$this->key];
	}

	/**
	 * 开始事务
	 * @param string $db 要执行事务的数据库 默认为当前数据库链接 此参数在多数据库的时候使用
	 */
	public function beginTransaction($db,$params=array('type'=>'main')){
		$this->setDbEntry();
		return $this->getPodObject($db,$params)->beginTransaction();
	}

	/**
	 * 提交事务
	 * @param string $db 要执行事务的数据库
	 */
	public function commit($db,$params=array('type'=>'main')){
		return $this->getPodObject($db,$params)->commit();
	}

	/**
	 * 回滚事务
	 * @param string $db 要执行事务的数据库
	 */
	public function rollBack($db,$params=array('type'=>'main')){
		return $this->getPodObject($db,$params)->rollBack();
	}

	/**
	 * 强制指定读取主数据库
	 * @return
	 */
	public function setDbEntry(){
		self::$dbentry='main';
	}

	/**
	 * 断开所有链接
	 * @return void
	 */
	public function disconnect() {
		self::$globals = NULL;
	}
	
	/**
	 * 检查sql的是否安全
	 * @author 80sec
	 * @param string $sql
	 * return boolean
	 */
	public function isSafe($sql) {
		$safe = true;
		$clean = '';
		$error='';
		$old_pos = 0;
		$pos = -1;
		while(true){
			$pos = strpos($sql, '\'', $pos + 1);
			if($pos === false) break;
			$clean .= substr($sql, $old_pos, $pos - $old_pos);
			while (true) {
				$pos1 = strpos($sql, '\'', $pos + 1);
				$pos2 = strpos($sql, '\\', $pos + 1);
				if ($pos1 === false){
					break;
				}elseif ($pos2 == false || $pos2 > $pos1){
					$pos = $pos1;
					break;
				}
				$pos = $pos2 + 1;
			}
			$clean .= '$s$';
			$old_pos = $pos + 1;
		}
		$clean .= substr($sql, $old_pos);
		$clean = trim(strtolower(preg_replace(array('~\s+~s' ), array(' '), $clean)));
		if (strpos($clean, ' union ') !== false && preg_match('~(^|[^a-z])union($|[^[a-z])~s', $clean) != 0) {
			$safe = false;
		}elseif (strpos($clean, '/*') > 2 || strpos($clean, '--') !== false || strpos($clean, '#') !== false) {
			$safe = false;
		}elseif (strpos($clean, ' sleep') !== false && preg_match('~(^|[^a-z])sleep($|[^[a-z])~s', $clean) != 0) {
			$safe = false;
		}elseif (strpos($clean, 'benchmark') !== false && preg_match('~(^|[^a-z])benchmark($|[^[a-z])~s', $clean) != 0) {
			$safe = false;
		}elseif (strpos($clean, 'load_file') !== false && preg_match('~(^|[^a-z])load_file($|[^[a-z])~s', $clean) != 0) {
			$safe = false;
		}elseif (strpos($clean, 'into outfile') !== false && preg_match('~(^|[^a-z])into\s+outfile($|[^[a-z])~s', $clean) != 0) {
			$safe = false;
		}
		return $safe;
	}
}

abstract class DbObject{
	abstract function init($params,$table="");
	abstract function setCount($count);
	abstract function setPage($page);
	abstract function setLimit($limit);
	abstract function setGroupby($groupby);
	abstract function setOrderby($orderby);
	abstract function select($table,$condition="",$item="*",$groupby="",$orderby="",$leftjoin="");
	abstract function selectOne($table,$condition="",$item="*",$groupby="",$orderby="",$leftjoin="");
	abstract function update($table,$condition="",$item="");
	abstract function delete($table,$condition="");
	abstract function insert($table,$item="",$isreplace=false,$isdelayed=false,$update=array());
	abstract function execute($sql);
}

class DbData{
	var $page=1;
	var $pageSize=0;
	var $limit=0;
	var $totalPage=0;
	var $totalSize=0;
	var $totalSecond=0;
	var $items;
}