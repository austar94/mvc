<?php
namespace APP\Model;

use APP\Core\Model;
use APP\Dto\Meta;

class HomeService extends Model
{
	public function get_boardList(){
		$SQL		=	"SELECT * FROM MetaInfo";
		$query		=	$this->db->prepare($SQL);
        $query->execute();

		$Count = $query->rowCount();
		if ($Count  > 0){
$query->setFetchMode(PDO::FETCH_CLASS, "Meta");
return $obj = $query->fetchAll();

}

		return $query->fetchAll();
	}
}
