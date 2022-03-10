<?php

function array_find(array $array, Closure $callback){

	$index = array_find_index($array, $callback);
	if(is_null($index)) return null;
	return $array[$index];

}

function array_find_index(array $array, Closure $callback){

	foreach($array as $key => $val){

		$res = $callback($val);

		if($res === true){
			return $key;
		}

	}

	return null;

}