<?php
namespace APP\Model;

use PDO;
use APP\Core\Model;
use APP\Dto\Meta;
use APP\Dto\Message;
use APP\Dto\Member;

class BoardModel extends Model
{
	
	/**
	 * get_inquiry 1:1문의
	 *
	 * @param  mixed $pageNo
	 * @param  mixed $recordPerPage
	 * @param  mixed $data
	 * @return void
	 */
	public function get_inquiry($pageNo = 0,$recordPerPage = 0, $data = array()){
        $msg			=	new Message();
		$where			=	'';
		$values			=	array();
		$LIMIT			=	'';

		$inquiryCode	=	$data["inquiryCode"] ?? "";
		$isView			=	$data["isView"] ?? "";
		$searchType		=	$data["searchType"] ?? "";
		$searchWord		=	$data["searchWord"] ?? "";
		$startDate		=	$data["startDate"] ?? "";
		$endDate		=	$data["endDate"] ?? "";

		$allCount		=	$data['allCount'] ?? "";
		$select			=	$data['select'] ?? "";
		$join			=	$data['join'] ?? "";
		$order			=	$data['order'] ?? "";
        $group			=	$data['group'] ?? "";


		if($inquiryCode){
			$where				.=	' AND iq.inquiryCode = :inquiryCode';
			$values[]			=	$this->set_bindParam($inquiryCode, 'inquiryCode', 'int');
		}

		if($isView){
			$where				.=	' AND iq.isView = :isView';
			$values[]			=	$this->set_bindParam($isView, 'isView', 'str', 10);
		}

		if($searchWord){
			if($searchType == 1){
				$where			.=	' AND iq.inquiryTitle LIKE CONCAT(:searchWord, "%")';
				$values[]		=	$this->set_bindParam($searchWord, 'searchWord', "str", 100);
			}
		}

		if($startDate){
			$where				.=	' AND iq.regDate >= :startDate';
			$values[]			=	$this->set_bindParam($startDate . '000000', 'startDate', "str", 20);
		}

		if($endDate){
			$where				.=	' AND iq.regDate <= :endDate';
			$values[]			=	$this->set_bindParam($endDate . '235959', 'endDate', "str", 20);
		}

		$groupBy				=	$group			?	' GROUP BY '.$group			:	'';
		$orderBy				=	$order			?	' ORDER BY '.$order			:	'';

		$countValues			=	$values;
		if($pageNo || $recordPerPage){
			if(!$recordPerPage){
				$LIMIT			=	'LIMIT :pageNo';
				$values[]	=	$this->set_bindParam($pageNo, 'pageNo', 'int', 11);
			} else {
				$LIMIT			=	'LIMIT :pageNo, :recordPerPage';
				$values[]	=	$this->set_bindParam($pageNo, 'pageNo', 'int', 11);
				$values[]	=	$this->set_bindParam($recordPerPage, 'recordPerPage', 'int', 11);
			}
		}
		
		$SQL				=	"SELECT $select iq.inquiryCode, iq.inquiryTitle, iq.inquiryInfo, iq.regDate, iq.inquiryAnswer, iq.answerRegDate, iq.inquiryState, iq.managerCode, iq.SEQ_userCode
								FROM tbl_inquiry AS iq
								$join
								WHERE 1=1 $where
								$groupBy
								$orderBy
								$LIMIT";
		$msg				=	$this->run_list($SQL, $values, 1);

		if($allCount){
			$SQL			=	"SELECT COUNT(iq.inquiryCode) AS count
								FROM tbl_inquiry AS iq
								$join
								WHERE 1=1 $where
								$groupBy";
			$msg2 			=	$this->run_once($SQL, $countValues);
			$temp			=	$msg2->get_data();

			if($temp){
				$msg->set_msg($temp['count']);
			} else {
				$msg->set_msg(0);
			}
		}

		return $msg;
	}
	
	/**
	 * insert_inquiry 1:1 문의 등록
	 *
	 * @param  mixed $data
	 * @return void
	 */
	public function insert_inquiry($data){
		
	}
		
	/**
	 * get_notice 공지사항 호출	
	 *
	 * @param  mixed $pageNo
	 * @param  mixed $recordPerPage
	 * @param  mixed $data
	 * @return void
	 */
	public function get_notice($pageNo = 0,$recordPerPage = 0, $data = array()){
        $msg			=	new Message();
		$where			=	'';
		$values			=	array();
		$LIMIT			=	'';

		$noticeCode		=	$data["noticeCode"] ?? "";
		$noticeType		=	$data["noticeType"] ?? "";
		$isView			=	$data["isView"] ?? "";
		$searchType		=	$data["searchType"] ?? "";
		$searchWord		=	$data["searchWord"] ?? "";
		$startDate		=	$data["startDate"] ?? "";
		$endDate		=	$data["endDate"] ?? "";

		$allCount		=	$data['allCount'] ?? "";
		$select			=	$data['select'] ?? "";
		$join			=	$data['join'] ?? "";
		$order			=	$data['order'] ?? "";
        $group			=	$data['group'] ?? "";


		if($noticeCode){
			$where				.=	' AND nc.noticeCode = :noticeCode';
			$values[]			=	$this->set_bindParam($noticeCode, 'noticeCode', 'int');
		}

		if($noticeType){
			$where				.=	' AND nc.noticeType = :noticeType';
			$values[]			=	$this->set_bindParam($noticeType, 'noticeType', 'int');
		}
		
		if($isView){
			$where				.=	' AND nc.isView = :isView';
			$values[]			=	$this->set_bindParam($isView, 'isView', 'str', 10);
		}

		if($searchWord){
			if($searchType == 1){
				$where			.=	' AND nc.noticeTitle LIKE CONCAT("%", :searchWord, "%")';
				$values[]		=	$this->set_bindParam($searchWord, 'searchWord', "str", 100);
			} else if($searchType == 2){
				$where			.=	' AND nc.noticeInfo LIKE CONCAT("%", :searchWord, "%")';
				$values[]		=	$this->set_bindParam($searchWord, 'searchWord', "str", 100);
			} else if($searchType == 3){
				$where			.=	' AND mn.managerName LIKE CONCAT("%", :searchWord, "%")';
				$values[]		=	$this->set_bindParam($searchWord, 'searchWord', "str", 100);
			} 
			else if($searchType == 4){
				$where			.=	' AND (nc.noticeTitle LIKE CONCAT("%", :searchWord1, "%")';
				$where			.=	' OR (nc.noticeInfo LIKE CONCAT("%", :searchWord2, "%"))';
				$values[]		=	$this->set_bindParam($searchWord, 'searchWord1', "str", 100);
				$values[]		=	$this->set_bindParam($searchWord, 'searchWord2', "str", 100);
			}
		}

		if($startDate){
			$where				.=	' AND nc.regDate >= :startDate';
			$values[]			=	$this->set_bindParam($startDate . '000000', 'startDate', "str", 20);
		}

		if($endDate){
			$where				.=	' AND nc.regDate <= :endDate';
			$values[]			=	$this->set_bindParam($endDate . '235959', 'endDate', "str", 20);
		}

		$groupBy				=	$group			?	' GROUP BY '.$group			:	'';
		$orderBy				=	$order			?	' ORDER BY '.$order			:	'';

		$countValues			=	$values;
		if($pageNo || $recordPerPage){
			if(!$recordPerPage){
				$LIMIT			=	'LIMIT :pageNo';
				$values[]	=	$this->set_bindParam($pageNo, 'pageNo', 'int', 11);
			} else {
				$LIMIT			=	'LIMIT :pageNo, :recordPerPage';
				$values[]	=	$this->set_bindParam($pageNo, 'pageNo', 'int', 11);
				$values[]	=	$this->set_bindParam($recordPerPage, 'recordPerPage', 'int', 11);
			}
		}
		
		$SQL				=	"SELECT $select nc.noticeCode, nc.managerCode, nc.noticeType, nc.noticeTitle, nc.noticeInfo, nc.regDate, nc.hitCount, nc.isView
								FROM tbl_notice AS nc
								$join
								WHERE 1=1 $where
								$groupBy
								$orderBy
								$LIMIT";
		$msg				=	$this->run_list($SQL, $values, 1);

		if($allCount){
			$SQL			=	"SELECT COUNT(nc.noticeCode) AS count
								FROM tbl_notice AS nc
								$join
								WHERE 1=1 $where
								$groupBy";
			$msg2 			=	$this->run_once($SQL, $countValues);
			$temp			=	$msg2->get_data();

			if($temp){
				$msg->set_msg($temp['count']);
			} else {
				$msg->set_msg(0);
			}
		}

		return $msg;
	}

		
	/**
	 * update_notice 공지사항 수정
	 *
	 * @param  mixed $data
	 * @return void
	 */
	public function update_notice($data){
		$msg			=	new Message();
		$SQL			=	"";
		$values			=	[];
		$where			=	"";
		$set			=	"";

		$noticeCode		=	$data["noticeCode"] ?? "";

		$procType		=	$data["procType"] ?? "";
		$limit			=	$data["limit"] ?? "";

		if($procType == 1){
			$set			.=	" hitCount = hitCount + 1 ";

			$where			.=	" noticeCode = :noticeCode ";
			$values[]		=	$this->set_bindParam($noticeCode, 'noticeCode', 'int');
		}
		else {
			return $msg;
		}

		if($limit){
			$where		.=	" LIMIT :limit";
			$values[]	=	$this->set_bindParam($limit, 'limit', 'int');
		}

		$SQL				=	"UPDATE tbl_notice 
									SET $set  WHERE $where";
		$msg				=	$this->run_once($SQL, $values, 1, 0, 0);
		return $msg;
	}

		
	/**
	 * get_faq 자주묻는질문 조회
	 *
	 * @param  mixed $pageNo
	 * @param  mixed $recordPerPage
	 * @param  mixed $data
	 * @return void
	 */
	public function get_faq($pageNo = 0, $recordPerPage = 0, $data = array()){
        $msg			=	new Message();
		$where			=	'';
		$values			=	array();
		$LIMIT			=	'';

		$FAQCode		=	$data["FAQCode"] ?? "";
		$searchType		=	$data["searchType"] ?? "";
		$searchWord		=	$data["searchWord"] ?? "";
		$isView			=	$data["isView"] ?? "";
		$startDate		=	$data["startDate"] ?? "";
		$endDate		=	$data["endDate"] ?? "";

		$allCount		=	$data['allCount'] ?? "";
		$select			=	$data['select'] ?? "";
		$join			=	$data['join'] ?? "";
		$order			=	$data['order'] ?? "";
        $group			=	$data['group'] ?? "";


		if($FAQCode){
			$where				.=	' AND fa.FAQCode = :FAQCode';
			$values[]			=	$this->set_bindParam($FAQCode, 'FAQCode', 'int');
		}

		if($searchWord){
			if($searchType == 1){
				$where			.=	' AND fa.FAQTitle LIKE CONCAT("%", :searchWord, "%")';
				$values[]		=	$this->set_bindParam($searchWord, 'searchWord', "str", 100);
			} else if($searchType == 2){
				$where			.=	' AND (fa.FAQInfo LIKE CONCAT("%", :searchWord1, "%") OR fa.FAQAnswer LIKE CONCAT("%", :searchWord2, "%"))';
				$values[]		=	$this->set_bindParam($searchWord, 'searchWord1', "str", 100);
				$values[]		=	$this->set_bindParam($searchWord, 'searchWord2', "str", 100);
			}
		}

		if($isView){
			$where				.=	' AND fa.isView = :isView';
			$values[]			=	$this->set_bindParam($isView, 'isView', 'str', 2);
		}

		if($startDate){
			$where				.=	' AND nc.regDate >= :startDate';
			$values[]			=	$this->set_bindParam($startDate . '000000', 'startDate', "str", 20);
		}

		if($endDate){
			$where				.=	' AND nc.regDate <= :endDate';
			$values[]			=	$this->set_bindParam($endDate . '235959', 'endDate', "str", 20);
		}


		$groupBy				=	$group			?	' GROUP BY '.$group			:	'';
		$orderBy				=	$order			?	' ORDER BY '.$order			:	'';

		$countValues			=	$values;
		if($pageNo || $recordPerPage){
			if(!$recordPerPage){
				$LIMIT			=	'LIMIT :pageNo';
				$values[]	=	$this->set_bindParam($pageNo, 'pageNo', 'int', 11);
			} else {
				$LIMIT			=	'LIMIT :pageNo, :recordPerPage';
				$values[]	=	$this->set_bindParam($pageNo, 'pageNo', 'int', 11);
				$values[]	=	$this->set_bindParam($recordPerPage, 'recordPerPage', 'int', 11);
			}
		}
		
		$SQL				=	"SELECT $select fa.FAQCode, fa.managerCode, fa.FAQType, fa.FAQTitle, fa.FAQInfo, fa.FAQAnswer, fa.regDate, fa.hitCount, fa.isView
								FROM tbl_faq AS fa
								$join
								WHERE 1=1 $where
								$groupBy
								$orderBy
								$LIMIT";
		$msg				=	$this->run_list($SQL, $values);

		if($allCount){
			$SQL			=	"SELECT COUNT(fa.FAQCode) AS count
								FROM tbl_faq AS fa
								$join
								WHERE 1=1 $where
								$groupBy";
			$msg2 			=	$this->run_once($SQL, $countValues);
			$temp			=	$msg2->get_data();

			if($temp){
				$msg->set_msg($temp['count']);
			} else {
				$msg->set_msg(0);
			}
		}

		return $msg;
	}

	/**
	 * update_faq 자주묻는질문 업데이트	
	 *
	 * @param  mixed $data
	 * @return void
	 */
	public function update_faq($data){
		$msg			=	new Message();
		$SQL			=	"";
		$values			=	[];
		$where			=	"";
		$set			=	"";

		$FAQCode		=	$data["FAQCode"] ?? "";

		$procType		=	$data["procType"] ?? "";
		$limit			=	$data["limit"] ?? "";

		if($procType == 1){
			$set			.=	" hitCount = hitCount + 1 ";

			$where			.=	" FAQCode = :FAQCode ";
			$values[]		=	$this->set_bindParam($FAQCode, 'FAQCode', 'int');
		}
		else {
			return $msg;
		}

		if($limit){
			$where		.=	" LIMIT :limit";
			$values[]	=	$this->set_bindParam($limit, 'limit', 'int');
		}

		$SQL				=	"UPDATE tbl_faq 
									SET $set  WHERE $where";
		$msg				=	$this->run_once($SQL, $values, 1, 0, 0);
		return $msg;
	}
}