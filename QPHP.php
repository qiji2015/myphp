<?php
class QPHP{
	
	public static $splitFlag="/";
	public static $urlFormat="-";
	public static $urlSuffix=".html";
	public static $appDir=".";
	public static $_debug=0;
	public static $defaultControl="control";
	
	public static function setAppDir($dir){
		QPHP::$appDir = $dir;
		return true;
	}

	public static function getAppDir(){
		return QPHP::$appDir;
	}
	
	 /**
	 * main method!
	 * @param string $path
	 * @return boolean
	 */
	public static function run(){
		$action = $_GET['ac'] ? $_GET['ac'] : "index";
		$method = $_GET['do'] ? $_GET['do'] : "index";
		$classFile = $action.".action.php";
		$app_file = QPHP::$appDir.DIRECTORY_SEPARATOR.QPHP::$defaultControl.DIRECTORY_SEPARATOR.$classFile;
		if(!file_exists($app_file)){
			QPHP::debug("file[$app_file] not exists");
			return false;
		}else{
			require(realpath($app_file));
		}
		$classname = QPHP::$defaultControl."_".$action;
		if(!class_exists($classname)){
			QPHP::debug("class[$classname] not exists");
			return false;
		}

		$classInstance = new $classname;
		if(!method_exists($classInstance,$method)){
			QPHP::debug("method[$method] not exists in class[$classname]");
			return false;
		}
		$path_array = array();
		$path_array[0] = QPHP::$defaultControl;
		$path_array[1] = $action;
		$path_array[2] = $method;
		return call_user_func(array(&$classInstance,$method),$path_array);

	}
	
	public static function setDebug($debug){
		QPHP::$_debug = $debug;
	}
	
	/**
	* 调试
	*/
	private function debug($debugmsg){
		if(QPHP::$_debug){
			echo "QPHP debug: ".$debugmsg;
		}
	}
}