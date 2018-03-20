<?PHP
@error_reporting(E_ALL & ~E_NOTICE);
@ini_set("session.cookie_httponly", 1); 
header('X-Content-Type-Options:nosniff');
header('X-Frame-Options:SAMEORIGIN');
header('X-XSS-Protection:1; mode=block');
require_once("./config.php");
//QPHP::setDebug(true);
define('APP_ROOT',str_replace("\\",DIRECTORY_SEPARATOR,dirname(__FILE__)). DIRECTORY_SEPARATOR);
QPHP::setAppDir(".");
//临时禁止其他功能
if(in_array($_GET['ac'],array())){
	header('HTTP/1.1 404 Not Found');
	header('Status: 404 Not Found');
	require_once(__dir__.'/tpl/404.html');
	exit;
}
if (false === ($res = QPHP::run())) {
	header('HTTP/1.1 404 Not Found');
	header('Status: 404 Not Found');
	require_once(__dir__.'/tpl/404.html');
	exit;
} else {
	echo $res;
	exit;
}