<?php

namespace APP\Core;

use PDO;
use APP\Dto\DBInfo;
use APP\Dto\Message;
use APP\Core\MonoLog;

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
			$MonoLog				=	new MonoLog();
			$MonoLog->log_info('==============================');
			$MonoLog->log_info("데이터베이스 연결에 실패하였습니다. " . $e->getMessage() . $e->getCode());
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

	public function run_list($sql, $args = [], $isLog = 0){
		$msg					=	new Message();
		$MonoLog				=	new MonoLog();

		//로그기록
		if($isLog){
			$MonoLog->log_info('===============run_list===============');
			$MonoLog->log_info('$sql : ' . $sql, $args);
		}

		try {
			//검색값이 존재할 경우
			// if ($args) {
			// 	$stmt			=	$this->db->prepare($sql);
			// 	// $stmt->execute($args);
			// 	// if($args){
   			// 	 foreach ($args as $key => $value) {
   			// 		 if(!$value['value']) continue;
			//
   			// 		 if($value['type'] == 'str'){
   			// 			$stmt->bindParam(':'.$value['column'], $value['value'], PDO::PARAM_STR, $value['size']);
   			// 		} else {
   			// 			$stmt->bindParam(':'.$value['column'], $value['value'], PDO::PARAM_INT);
   			// 		}
   			// 	 }
			//
			// 	 $stmt->execute();
			// 	 $rows			=	$stmt->fetchAll();
			//  } else {
			// 	 $rows 			=	$this->db->query($sql);
			//  }
			$stmt			=	$this->db->prepare($sql);
			// $stmt->execute($args);
			if($args){
			 	foreach ($args as $key => $value) {
				 	if(!$value['value']) continue;

				 	if($value['type'] == 'str'){
						$stmt->bindParam(':'.$value['column'], $value['value'], PDO::PARAM_STR, $value['size']);
					} else {
						$stmt->bindParam(':'.$value['column'], $value['value'], PDO::PARAM_INT);
					}
				}
			}

			 $stmt->execute();
			 $rows			=	$stmt->fetchAll();

			 $msg->set_result(1);
			 $msg->set_data($rows);

			 if($isLog){
				 $MonoLog->log_info('result : ', $rows);
			 }

		} catch (\PDOException $e) {
			$msg->set_msg($e->getMessage());
			$MonoLog->log_info('errCode : ' . $e->getCode());
			$MonoLog->log_info('errMsg : ' . $e->getMessage());
		}
		finally {
			if($isLog) $MonoLog->log_info('쿼리 종료');
			return $msg;
		}
	}

	/**
	 * sql 1row실행
	 * @param  string $sql  [sql 본문]
	 * @param  array  $args [sql 검색값]
	 * @param  string $isLog 로그 작성여부
	 * @return array       sql 결과값
	 */
	public function run_once($sql, $args = '', $isLog = 0, $backKey = '')
    {
		$msg					=	new Message();
		$MonoLog				=	new MonoLog();

		//로그기록
		if($isLog){
			$MonoLog->log_info('===============run_once===============');
			$MonoLog->log_info('$sql : ' . $sql, $args);
		}
		try {
			//검색값이 존재할 경우
			if ($args) {
				$stmt			=	$this->db->prepare($sql);
				// $stmt->execute($args);
				// if($args){
   				 foreach ($args as $key => $value) {
   					 if(!$value['value']) continue;

   					 if($value['type'] == 'str'){
   						$stmt->bindParam(':'.$value['column'], $value['value'], PDO::PARAM_STR, $value['size']);
   					} else {
   						$stmt->bindParam(':'.$value['column'], $value['value'], PDO::PARAM_INT);
   					}
   				 }

				 $stmt->execute();
				 if($backKey) $msg->set_code($this->db->lastInsertId());
				 $row			=	$stmt->fetch();
			 } else {
				 $row 			=	$this->db->query($sql);
			 }

			$msg->set_result(1);
			$msg->set_data($row);

			if($isLog){
				$MonoLog->log_info('result : ', $row);
			}

		} catch (\PDOException $e) {
			$msg->set_msg($e->getMessage());
			$MonoLog->log_info('errCode : ' . $e->getCode());
			$MonoLog->log_info('errMsg : ' . $e->getMessage());
		}
		finally {
			if($isLog) $MonoLog->log_info('쿼리 종료');
			return $msg;
		}
    }


	/**
	 * 프로시저 값 + out 받기
	 * @param  array  $sql   [sql[0] - 프로시저본문, sql[1] - out받는 부분]
	 * @param  string  $args  [프로시저에 입력되는 값 바인드]
	 * @param  integer $isLog [로그여부]
	 * @return array         [실행 결과값]
	 */
	function procedule_run($sql, $args = [], $isLog = 0){
		$msg				=	new Message();
		$MonoLog			=	new MonoLog();

		if($isLog){
			$MonoLog->log_info('===============procedule_run===============');
			$MonoLog->log_info('sql[0] : ' . $sql[0], $args);
			$MonoLog->log_info('sql[1] : ' . $sql[1]);
		}

		try {

			 $stmt			=	$this->db->prepare($sql[0]);
			 if($args){
				 foreach ($args as $key => $value) {
					 if(!$value['value']) continue;

					 if($value['type'] == 'str'){
						$stmt->bindParam(':'.$value['column'], $value['value'], PDO::PARAM_STR, $value['size']);
					} else {
						$stmt->bindParam(':'.$value['column'], $value['value'], PDO::PARAM_INT);
					}
				 }
			 }
			 $stmt->execute();
			 $rows			=	$stmt->fetchAll();
			 $stmt->closeCursor();
			 $row			=	$this->db->query($sql[1])->fetch(PDO::FETCH_ASSOC);

			 $data			=	array(
				 'rows'			=>	$rows,
				 'row'			=>	$row
			 );

			 $msg->set_result(1);
			 $msg->set_data($data);
		} catch (\PDOException $e) {
			$msg->set_msg($e->getMessage());
		} finally {
			if($isLog) $MonoLog->log_info('쿼리 종료');
			return $msg;
		}
	}

	/**
	 * 바인드 숏컷
	 * @param [type]  $value 입력값
	 * @param string  $column   :column
	 * @param string  $type  str or int
	 * @param integer $size  str 만 필요
	 */
	function set_bindParam($value, $column, $type, $size = 0){
		$data				=	[];

		$data['value']		=	$value;
		$data['column']		=	$column;
		$data['type']		=	$type;

		if($type == 'str') $data['size'] = $size;

		return $data;
	}
}
