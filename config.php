<?PHP
date_default_timezone_set("Asia/ChongQing");
require('./QPHP/QPHP.php');
define('PLUGINS_DIR','./QPHP/plugins');
define('QPHPCORE_DIR','./QPHP/core');
define('QPHP_DIR','./QPHP');
define('APP_DIR','./');

function __autoload($sClass) {
	if ('Q' == $sClass{0}) {
		$file = PLUGINS_DIR."/{$sClass}.class.php";
	}else if('core' == substr($sClass,0,4)){//加载QPHP核心模块
		$file = QPHPCORE_DIR.str_replace('_','/',substr($sClass, 4)).'.class.php';
	}else{
		$file = QPHP::$appDir.str_replace("_","/",$sClass).".class.php";
	}
	if(file_exists($file)) return require_once($file);
}
spl_autoload_register('__autoload');