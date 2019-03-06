<?php

function xss($data, $problem='') {
	$data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = strip_tags($data);
	if ($problem && strlen($data) == 0) {
		return ($problem);
	}
    return $data;
}

function corta($str, $left, $right) {
    $str = substr ( stristr ( $str, $left ), strlen ( $left ) );
	@$leftLen = strlen ( stristr ( $str, $right ) );
	$leftLen = $leftLen ? - ($leftLen) : strlen ( $str );
	$str = substr ( $str, 0, $leftLen );
	return $str;
}

function clearStr($str) {
	if(!isset($str)) {
		return null;
	}elseif(is_array($str)){
		return $str;
	}elseif(strlen($str) == 0) {
		return null;
	}elseif($str == false) {
		return null;
	}

	$str = xss($str);
	$str = rtrim($str);
	$str = ltrim($str);
	$str = str_replace(array("\n", "\t", "  ", "	", "\r", "(", ")", ";", ">", "<", "$"), '', $str);
	$str = utf8_decode($str);
	return $str;
}
