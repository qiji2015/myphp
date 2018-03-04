<?php
class Qtpl{
	static function assign($params=array()){
		$_tpl = array();
		foreach ($params as $key => $val) {
			if ($key != '') {
				$_tpl[$key] = $val;
			}
		}
		return $_tpl;
	}

	/**
	 * 创建url
	 * @param string $ac 控制器名称
	 * @param string $do 方法名称
	 * @param array $params get参数
	 * @param string $domain 由常量控制
	 * @return string
	 */
	static function createUrl($ac, $do, $params = array(), $domain=''){
		switch ($domain){
			case 'admin':
				$url = core_lib_constant::MAIN_URL;
				break;
			default:
				$url = core_lib_constant::MAIN_URL;
				break;
		}
		if(core_lib_constant::REWRITE){
			$url .= "/{$ac}/{$do}.html";
			$qstr = '?';
		}else{
			$url .= "?ac={$ac}&do={$do}";
			$qstr = '&';
		}
		if($params){
			foreach ($params as $k=>$v){
				$qstr .= "{$k}={$v}&";
			}
			$qstr = rtrim($qstr,'&');
			$url = $url.$qstr;
		}
		return $url;
	}

	/**
	 * 创建分页
	 * @param int $count 总记录数
	 * @param int $limit 单页数
	 * @param int $i_page 当前页
	 * @param string $style
	 * @param string $domain
	 */
	function paged($count, $limit, $i_page, $ac='', $do='', $style = 'style', $domain = "") {
		$_GET = Qutil::filter($_GET);
		$ac = $ac ? $ac : $_GET['ac'];
		$do = $do ? $do : $_GET['do'];
		unset($_GET['ac']);
		unset($_GET['do']);
		$get = $_GET;
		$pagenum = ceil($count / $limit);
		$page = min($pagenum, $i_page);
		$prepg = $page - 1;
		$nextpg = $page == $pagenum ? 0 : $page + 1;
		$offset = ($page - 1) * $limit;
		$startdata = $count ? ($offset + 1) : 0;
		$enddata = min($offset + $limit, $count);
		if ($pagenum > 10) {
			if ($page >= 10) {
				$pageStart = $page - 10 / 2;
				if ($pagenum - $pageStart < 10) {
					$pageStart = $pagenum - 10;
				}
			} else {
				$pageStart = 0;
			}
			$pageEnd = min(($pageStart + 10), $pagenum);
		} else {
			$pageStart = 0;
			$pageEnd = $pagenum;
		}
		$j = 0;
		for ($i = $pageStart; $i < $pageEnd; $i++) {
			$params['pages'][$j]['page'] = $i + 1;
			$params['pages'][$j]['url'] = Qtpl::createUrl($ac, $do, array_merge($get, array('p'=>$i + 1)), $domain);
			$j++;
		}
		$params['first'] = Qtpl::createUrl($ac, $do, array_merge($get, array('p'=>1)), $domain);
		if (!empty($nextpg)) {
			$params['nextpg'] = Qtpl::createUrl($ac, $do, array_merge($get, array('p'=>$nextpg)), $domain);
		} else {
			$params['nextpg'] = null;
		}
		if (!empty($prepg)) {
			$params['prepg'] = Qtpl::createUrl($ac, $do, array_merge($get, array('p'=>$prepg)), $domain);
		} else {
			$params['prepg'] = null;
		}
		$params['currpage'] = $i_page;
		$params["total"] = $pagenum; //总页数
		return $this->render("page/{$style}.php", $params);
	}
}
