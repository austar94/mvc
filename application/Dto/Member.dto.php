<?php
namespace APP\Dto;
if (!defined('_STAR_')) exit;

use APP\Libs\Helper;

class Member{
	private $userID;
	private $userName;
	private $userType;
	private $userCode;
	private $userGrade;

	public function __construct() {
		$Helper							=	new Helper();

		$this->userID					=	$Helper->decrypt($_SESSION['userID'] ?? "");
		$this->userName					=	$Helper->decrypt($_SESSION['userName'] ?? "");
		$this->userType					=	$Helper->decrypt($_SESSION['userType'] ?? "");
		$this->userCode					=	$Helper->decrypt($_SESSION['userCode'] ?? "");
		$this->userGrade				=	$Helper->decrypt($_SESSION['userGrade'] ?? "");
	}

	public function userID(){
		return $this->userID;
	}

	public function userName(){
		return $this->userName;
	}

	public function userType(){
		return $this->userType;
	}

	public function userCode(){
		return $this->userCode;
	}

	public function userGrade(){
		return $this->userGrade;
	}
}
