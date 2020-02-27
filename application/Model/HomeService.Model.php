<?php
namespace APP\Model;

use PDO;
use APP\Core\Model;
use APP\Dto\Meta;

class HomeService extends Model
{
	/**
	 * sql 예시
	 * @return [type] [description]
	 */
	public function get_boardList(){
		//$SQL		=	"SELECT * FROM MetaInfo limit 1";
		$SQL		=	"CALL spGetOneTossClientList('', '', @PageCount, @PageSize, @TotalCount, @CurrentPage);";
		$SQL		.=	"SELECT @PageCount AS PageCount, @PageSize AS PageSize, @TotalCount AS TotalCount, @CurrentPage AS CurrentPage;";
		$msg		=	$this->run($SQL);


		try {

			do {
			  var_dump($msg->get_data()->fetchAll());
			} while ($msg->get_data()->nextRowset());

		} catch (\Exception $e) {
			$msg->set_msg('오류');
			return $msg->get_msg();
		}
	}
}
