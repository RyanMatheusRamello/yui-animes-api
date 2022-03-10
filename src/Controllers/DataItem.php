<?php

namespace App\Controllers;

class DataItem {

	private $data;

	public function __construct(){
		$this->data = [];
	}

	public function push($index, $data){
		$this->data[$index][] = $data;
	}

	public function getAll(){
		return $this->data;
	}

	public function set($index, $value){
		$this->data[$index] = $value;
	}

	public function get($index=null){
		return (is_null($index)) ? $this->data : $this->data[$index];
	}

}