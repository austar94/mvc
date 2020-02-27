<?php
namespace APP\Model;

use APP\Core\Model;
use APP\Dto\Meta;

class HomeService extends Model
{
	/**
	 * sql 예시
	 * @return [type] [description]
	 */
	public function get_boardList(){
		//옵션을 넣었기떄문에 예외 옵션가능
		$SQL		=	"SELECT * FROM MetaInfo";
		$msg		=	$this->run($SQL);

		if($msg->get_result()){
			return $msg->get_data()->fetchAll();
		} else {
			return $msg->get_msg();
		}
	}
}
