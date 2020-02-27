<?php

namespace APP\Core;

use PDO;
use APP\Dto\DBInfo;

class Model
{
    /**
     * db파라미터
     * @var [type]
     */
	public $db					=	null;			//공통사용으로 private를  사용하면 안됨
	private $DB_HOST			=	'';
	private $DB_NAME			=	'';
	private $DB_USER			=	'';
	private $DB_PASS			=	'';
	private $DB_TYPE			=	'';

    /**
     * db 연결
     */
    function __construct()
    {
        try {
			$DBInfo			=	new DBInfo();
			$this->DB_HOST	=	$DBInfo->get_dbHost();
			$this->DB_NAME	=	$DBInfo->get_dbName();
			$this->DB_USER	=	$DBInfo->get_dbID();
			$this->DB_PASS	=	$DBInfo->get_dbPWD();
			$this->DB_TYPE	= 	$DBInfo->get_dbType();

            self::openDatabaseConnection();
        } catch (\PDOException $e) {
			throw new \Exception("데이터베이스 연결에 실패하였습니다.");
            // exit('Database connection could not be established.');
        }
    }

    /**
     * db 접속
     * @return [type] [description]
     */
    private function openDatabaseConnection()
    {
		$options			=	'PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION';
        // $options			=	array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);

        // setting the encoding is different when using PostgreSQL
        if ($this->DB_TYPE == "pgsql") {
            $databaseEncodingenc =	" options='--client_encoding=utf8'";
        } else {
            $databaseEncodingenc =	"; charset=utf8";
        }

        $this->db			=	new PDO($this->DB_TYPE . ':host=' . $this->DB_HOST . ';dbname=' . $this->DB_NAME . $databaseEncodingenc, $this->DB_USER, $this->DB_PASS, $options);
    }
}
