<?php
if($_tpl['total']){
	$str = '<div class="pagination">';
  	if(!$_tpl['prepg']){
  		$str .= '<a href="#">上一页</a>';
	}else{
		$str .= '<a href="'.$_tpl['prepg'].'">上一页</a>';
	}
	if($_tpl['pages']){
		foreach ($_tpl['pages'] as $v) {
			$class = $_tpl['currpage'] == $v['page'] ? ' class="number current"' : ' class="number"';
			$str .= "<a href=\"{$v['url']}\"{$class}>{$v['page']}</a>";
		}
	}
	if($_tpl['currpage'] >= $_tpl['total']){
  		$str .= '<a href="#">下一页</a>';
	}else{
		$str .= '<a href="'.$_tpl['nextpg'].'">下一页</a>';
	}
	$str .= '</div><div class="clear"></div>';
	echo $str;
}