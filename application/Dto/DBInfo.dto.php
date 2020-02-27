<?php
namespace APP\Dto;

class DBInfo{
	private $dbHost;
	private $dbName;
	private $dbID;
	private $dbPWD;
	private $dbPort;

	public function __construct() {
		$this->dbHost				=	'localhost';
		$this->dbName				=	'OneToss';
		$this->dbID					=	'root';
		$this->dbPort				=	'3306';
		$this->dbPWD				=	'ekffur32!';
		$this->dbType				=	'mysql';
	}

	public function get_dbHost(){
		return $this->dbHost;
	}

	public function get_dbName(){
		return $this->dbName;
	}

	public function get_dbID(){
		return $this->dbID;
	}

	public function get_dbPort(){
		return $this->dbPort;
	}

	public function get_dbPWD(){
		return $this->dbPWD;
	}

	public function get_dbType(){
		return $this->dbType;
	}
}
