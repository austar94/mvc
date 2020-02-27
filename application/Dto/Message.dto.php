<?php
namespace APP\Dto;

class Message{
	private $result;
	private $msg;
	private $code;
	private $data;

	public function __construct() {
		$this->result				=	0;
		$this->msg					=	'';
		$this->code					=	'00';
		$this->data					=	'';
	}

	public function get_result(){
		return $this->result;
	}

	public function set_result($result){
		$this->result				=	$result;
	}

	public function get_msg(){
		return $this->msg;
	}

	public function set_msg($msg){
		$this->msg					=	$msg;
	}

	public function get_code(){
		return $this->code;
	}

	public function set_code($code){
		$this->code					=	$code;
	}

	public function get_data(){
		return $this->data;
	}

	public function set_data($data){
		$this->data					=	$data;
	}
}
