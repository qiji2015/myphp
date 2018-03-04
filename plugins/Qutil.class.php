<?php
/**
 * 常用的静态方法
 */
class Qutil{

	/**
	 * 过滤数据
	 * @param string|array $data ($_GET,$_POST,$_COOKIE)
	 * @param string $type int|float
	 * @param bool $isnum true|false
	 */
	static function filter($str, $type = ''){
		if(is_array($str)){
			foreach($str as $key => $val) {
				$str[$key] = self::filter($val, $type);
			}
			return $str;
		}
		switch ($type){
			case 'int':
				$str = intval($str);
				break;
			case 'float':
				$str = floatval($str);
				break;
			default:
				$str = htmlspecialchars($str, ENT_QUOTES);
				$str = strip_tags($str);
        $str = str_replace("'", '&#39;', $str);
        $str = str_replace("\"", '&quot;', $str);
        $str = str_replace("\\", '', $str);
        $str = str_replace("\/", '', $str);
        $str = str_replace("+/v", '', $str);
		}
		return $str;
	}

	/**
	 * 获取ip
	 */
	static function getIP($long=false) {
		$xip = getenv('HTTP_X_FORWARDED_FOR');
		$rip = getenv('REMOTE_ADDR');
		$srip = $_SERVER['REMOTE_ADDR'];
		if($xip && strcasecmp($xip, 'unknown')) {
			$ip = $xip;
		} elseif($rip && strcasecmp($rip, 'unknown')) {
			$ip = $rip;
		} elseif($srip && strcasecmp($srip, 'unknown')) {
			$ip = $srip;
		}
		preg_match("/[\d\.]{7,15}/", $ip, $match);
		$ip = $match[0] ? $match[0] : 'unknown';
		if($long){
			return sprintf("%u",ip2long($ip));
		}
		return $ip;
	}

	/**
	 * 检查手机格式
	 * @return boolean
	 */
	static function IsMobile($Argv){
		$RegExp='/^(?:13|15|18|14)[0-9]\d{8}$/';
		return preg_match($RegExp,$Argv) ? true : false;
	}

	/**
	 * 判断一个字符串中是否只包含了 数字和逗号(sql in查询的时候使用)
	 * @param string $string
	 * @return boolean
	 */
	static function onlyNumAndComma($string){
		if(preg_match('/[^\d,]+/', $string)){
			return false;
		}
		return true;
	}

	/**
	 * 获取随机数
	 * @param int $len 长度
	 * @param int $type 1数字 2字母 0混合
	 */
	static function getRandom($len, $type=0) {
		if($type == 0) {
			$chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		} else if($type == 1) {
			$chars = '0123456789';
		} else if($type == 2) {
			$chars = 'abcdefghijklmnopqrstuvwxyz';
		}
		$max = strlen($chars) - 1;
		mt_srand((double)microtime() * 1000000);
		for($i = 0; $i < $len; $i++){
			$hash.= $chars[mt_rand(0, $max)];
		}
		return $hash;
	}

	/**
	 * 获取时间差
	 * @param int $start_time 开始时间
	 * @param int $end_time 结束时间
	 * @return array
	 */
	static function timediff($start_time,$end_time=0){
		if($start_time > $end_time){
			$arr = array("day" => 0,"hour" => 0,"min" => 0,"sec" => 1);
		} else{
			$timediff = $end_time-$start_time;
			$days = intval($timediff/86400);
			$remain = $timediff%86400;
			$hours = intval($remain/3600);
			$remain = $remain%3600;
			$mins = intval($remain/60);
			$secs = $remain%60;
			$arr = array("day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs);
		}
		return $arr;
	}

	/**
	 * 设置cookie
	 * @param array $array
	 * @param int $life 过期时间单位秒
	 * @param $path 设置作用路径
	 * @param $domain 设置作用域名
	 */
	static function ssetcookie($array, $life=0, $path = '/', $domain = '') {
		$_array = array_keys($array);
		for($i = 0; $i < count($array); $i++) {
			$httpOnly = true;
			setcookie($_array[$i], $array[$_array[$i]], $life ? (time()+$life) : 0, $path, $domain, '', $httpOnly);
		}
	}

	/**
	 * 双向加密解密
	 * @param string $string
	 * @param string $operation
	 * @param mix $key
	 * @param int $expiry
	 * @return string
	 */
	static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
		$ckey_length = 4;
		$key = md5($key ? $key : core_lib_constant::BOSS_KEY);
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
		$cryptkey = $keya . md5($keya . $keyc);
		$key_length = strlen($cryptkey);
		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
		$string_length = strlen($string);
		$result = '';
		$box = range(0, 255);
		$rndkey = array();
		for ($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}
		for ($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}
		for ($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}
		$_str = '';
		if ($operation == 'DECODE') {
			if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
				$_str = substr($result, 26);
			}
		} else {
			$_str = $keyc . str_replace('=', '', base64_encode($result));
		}
		return $_str;
	}

	static function mk_dir($dir, $mode = 0777) {
	    if (is_dir($dir) || @mkdir($dir, $mode))
	        return true;
	    if (!self::mk_dir(dirname($dir), $mode))
	        return false;
	    return @mkdir($dir, $mode);
	}

	//重写的base64_encode 用于对称加密
	static function base64_encode($str){
		if(is_numeric($str)) $str = $str*789;
		$str_arr = str_split($str);//分成单个字符
		$mod = count($str_arr)%3;//不够3个
		if($mod > 0) $bmod = 3 - $mod; //计算需要补多少才能够3个
		for($i = 0; $i<$bmod; $i++){//不够3个补\0
			$str_arr[] = "\0";
		}
		//字符串转换为二进制
		foreach($str_arr as $v){
			$bit .= str_pad(decbin(ord($v)),8,'0',STR_PAD_LEFT);
		}
		$len = ceil(strlen($bit)/6);
		$base64_config = self::getBase64Config();
		//把二进制按照六位进行转换为base64索引
		for($i = 0; $i<$len - $bmod; $i++){
			$enstr .= $base64_config[bindec(str_pad(substr($bit,$i*6,6),8,0,STR_PAD_LEFT))];
		}
		//补=号
		for($buf = 1; $buf <= $bmod; $buf++){
			$enstr .= "=";
		}
		return $enstr;
	}
	//重写的base64_decode 用于对称加密
	static function base64_decode($str){
		$buf = substr_count($str,'=');//统计=个数
		$str_arr = str_split($str);//分成单个字符
		$base64_config = self::getBase64Config();
		//转换为二进制字符串
		foreach($str_arr as $v){
			$index = array_search($v,$base64_config);
			$index = $index ? $index : "\0";
			$bit .= str_pad(decbin($index),6,0,STR_PAD_LEFT);
		}
		$len = ceil(strlen($bit)/8);
		//二进制转换为ASCII，在转换为字符串
		for($i=0; $i<$len-$buf; $i++){
			$destr .= chr(bindec(str_pad(substr($bit,$i*8,8),8,0,STR_PAD_LEFT)));
		}
		if(is_numeric($destr)) $destr = $destr/789;
		return $destr;
	}
	//混淆的base64索引
	static function getBase64Config(){
		return array(
			'k','2','R','E','B','F','h','S','V','r','X','5','L','Y','j','H','3','b','6','y','P','J','v','c','U','7','u','A','D','d','0','f',
			'x','I','T','Z','O','g','m','z','Q','e','w','N','1','s','p','9','t','o','l','8','i','W','M','C','n','4','K','G','q','a','+','/'
		);
	}

	//判断是否是ajax请求
	public static function isAjax()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

	//防止xss的过滤函数
	static function RemoveXSS($val) {
	  // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
	  // this prevents some character re-spacing such as <java\0script>
	  // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
	  $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

	  // straight replacements, the user should never need these since they're normal characters
	  // this prevents like <IMG SRC=@avascript:alert('XSS')>
	  $search = 'abcdefghijklmnopqrstuvwxyz';
	  $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	  $search .= '1234567890!@#$%^&*()';
	  $search .= '~`";:?+/={}[]-_|\'\\';
	  for ($i = 0; $i < strlen($search); $i++) {
	     // ;? matches the ;, which is optional
	     // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

	     // @ @ search for the hex values
	     $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
	     // @ @ 0{0,7} matches '0' zero to seven times
	     $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
	  }

	  // now the only remaining whitespace attacks are \t, \n, and \r
	  $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
	  $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
	  $ra = array_merge($ra1, $ra2);

	  $found = true; // keep replacing as long as the previous round replaced something
	  while ($found == true) {
	     $val_before = $val;
	     for ($i = 0; $i < sizeof($ra); $i++) {
	        $pattern = '/';
	        for ($j = 0; $j < strlen($ra[$i]); $j++) {
	           if ($j > 0) {
	              $pattern .= '(';
	              $pattern .= '(&#[xX]0{0,8}([9ab]);)';
	              $pattern .= '|';
	              $pattern .= '|(&#0{0,8}([9|10|13]);)';
	              $pattern .= ')*';
	           }
	           $pattern .= $ra[$i][$j];
	        }
	        $pattern .= '/i';
	        $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
	        $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
	        if ($val_before == $val) {
	           // no replacements were made, so exit the loop
	           $found = false;
	        }
	     }
	  }
	  return $val;
	}
	//判断是否为内网IP
	function isLocalIp($ip){
		return preg_match('%^127\.|10\.|192\.168|172\.(1[6-9]|2|3[01])%',$ip);
	}
}
