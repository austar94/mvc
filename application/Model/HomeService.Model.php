<?php
namespace APP\Model;

use PDO;
use APP\Core\Model;
use APP\Dto\Meta;
use APP\Dto\Message;

class HomeService extends Model
{

	public function get_userList($data = []){
		$msg			=	new Message();
		$where			=	[];

		$SQL			=	"CALL spGetUserManagerList()";
		$msg			=	$this->run_list($SQL, $where, 1);
		return $msg;
	}

	/**
	 * row 리턴 예제
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	public function get_board($data = []){
		$msg			=	new Message();
		$where			=	[];

		$OrderSEQ		=	$data['OrderSEQ'];

		$call_OrderSEQ	=	$OrderSEQ ? ':OrderSEQ' : "''";
		if($OrderSEQ){
			$where[]	=	$this->set_bindParam($OrderSEQ, 'OrderSEQ', 'str', 20);
		}

		$SQL			=	"CALL spGetOrderDetail($call_OrderSEQ)";
		$msg			=	$this->run_once($SQL, $where, 1);
		return $msg;

	}
	/**
	 * 프로시저 + out 예제
	 * @return [type] [description]
	 */
	public function get_boardList($data = []){
		$msg			=	new Message();
		$SQL			=	[];
		$where			=	[];

		$ClientName		=	$data['ClientName'];
		$CurrentPage	=	$data['CurrentPage'];

		$CurrentPage = 1;

		$call_ClientName	=	$ClientName ? ':ClientName' : "''";
		if($ClientName){
			$where[]		=	$this->set_bindParam($ClientName, 'ClientName', 'str', 50);
		}

		$call_CurrentPage	=	$CurrentPage ? ':CurrentPage' : "''";
		if($CurrentPage){
			$where[]		=	$this->set_bindParam($CurrentPage, 'CurrentPage', 'int');
		}

		$SQL[]		=	"CALL spGetOneTossClientList($call_ClientName, $call_CurrentPage, @PageCount, @PageSize, @TotalCount, @CurrentPage);";
		$SQL[]		=	"SELECT @PageCount AS PageCount, @PageSize AS PageSize, @TotalCount AS TotalCount, @CurrentPage AS CurrentPage;";
		$msg		=	$this->procedule_run($SQL, $where, 1);
		return $msg;
	}
}
