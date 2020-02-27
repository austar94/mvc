<?php

namespace APP\Core;

use PDO;
use APP\Dto\DBInfo;
use APP\Dto\Message;

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
			// throw new \Exception("데이터베이스 연결에 실패하였습니다. " . $e->getMessage() . $e->getCode());
			throw new \Exception("데이터베이스 연결에 실패하였습니다.");
        }
    }

    /**
     * db 접속
     * @return [type] [description]
     */
    private function openDatabaseConnection()
    {
		/**
		 * pdo 옵션
		 * @var array
		 * PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION			-	오류 혹은 예외 발생시 try catch exception 발생
		 */
		$options  			=	array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );

        //sql별 인코딩 설정
        if ($this->DB_TYPE == "pgsql") {
            $databaseEncodingenc =	" options='--client_encoding=utf8'";
        } else {
            $databaseEncodingenc =	"; charset=utf8";
        }

		//db연결
        $this->db			=	new PDO($this->DB_TYPE . ':host=' . $this->DB_HOST . ';dbname=' . $this->DB_NAME . $databaseEncodingenc, $this->DB_USER, $this->DB_PASS, $options);
    }

	/**
	 * sql 실행
	 * @param  string $sql  [sql 본문]
	 * @param  array  $args [sql 검색값]
	 * @param  string $isLog 로그 작성여부
	 * @return array       sql 결과값
	 */
	public function run($sql, $args = [], $isLog = 0)
    {
		$msg					=	new Message();

		try {
			//검색값이 존재할 경우
			if ($args) {
				$stmt			=	$this->db->prepare($sql);
				$stmt->execute($args);
			} else {
				$stmt 			=	$this->db->query($sql);
			}

			$msg->set_result(1);
			$msg->set_data($stmt);

		} catch (\PDOException $e) {
			$msg->set_msg($e->getMessage());
		} finally {
			//로그기록
			if($isLog){
				
			}

			return $msg;
		}
    }
}
