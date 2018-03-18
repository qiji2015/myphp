<?php
class core_action extends Qtpl{
	function render($tpl,$params=array()){
		$_tpl = parent::assign($params);
		$bloghost = core_lib_constant::MAIN_URL;
		require(QPHP::$appDir."/tpl/{$tpl}");
	}
	function redirect($url){
		header("Location:{$url}");
		exit;
	}

	function showMsg($msg, $state = 1, $url = '', $json = 0){
		$params['msg'] = $msg;
		$params['url'] = $url;
		$params['state'] = $state;
		if($json > 0){
			echo json_encode($params);
		}else{
			echo $this->render("showmsg.php", $params);
		}
		exit;
	}

	/**
	 * 检查表单token是否合法
	 * @param unknown_type $type 1生成token 2检查token
	 */
	function formToken($type = 1){
		session_start();
		if($type == 1){
			$_SESSION['token'] = md5(Qutil::getRandom(8).core_lib_constant::COOKIE_KEY);
			return $_SESSION['token'];
		}else{
			if(empty($_REQUEST['token']) or $_REQUEST['token'] != $_SESSION['token']){
				$_SESSION['token'] = '';
				return false;
			}
			$_SESSION['token'] = '';
			return true;
		}
	}
}
