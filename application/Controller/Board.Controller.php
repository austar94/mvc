<?php
namespace APP\Controller;
if (!defined('_STAR_')) exit;

use APP\Core\Controller;
use APP\Libs\Helper;
use APP\Dto\Member;
use APP\Libs\Pagination;
use APP\Model\BoardModel;
use APP\Libs\FileManager;

class BoardController extends Controller
{
	public function __construct()
	{

	}

	public function index() {
		$this->noticeList();
	}

	// 공지사항 목록
	public function noticeList() {
		$BoardModel			=	new BoardModel();

		$recordPerPage		=	(int)10;        //	한 페이지당 최대 게시글 개수.
		$pnoPerPage			=	10;        //	한 페이지당 최대 페이지번호 개수.
		$pno				=   (int)$this->get("pno", 1) ? (int)$this->get("pno", 1) : 1;             //	페이지번호.
		$temp				=	($pno * $recordPerPage) - $recordPerPage;

		$search				=	array(
			"noticeType"		=>	1,
			"searchType"		=>	4,
			"searchWord"		=>	$this->get("searchWord", 1),
			"order"				=>	" nc.noticeCode DESC ",
			"allCount"			=>	1
		);
		$msg				=	$BoardModel->get_notice($temp, $recordPerPage, $search);
		$noticeList			=	$msg->get_data();

		// 검색어 정리
		$data				=	array(
			"searchWord"		=>	$this->get("searchWord", 1)
		);
		$paging             =   new Pagination($recordPerPage, $pnoPerPage, $pno, $msg->get_msg(), "", "url", $data);

		$data				=	array(
			"noticeList"		=>	$noticeList,
			"paging"            =>  $paging->setPaging(),
			"isView"			=>	"Y",
			"searchWord"		=>	$this->get("searchWord", 1),
			"pno"				=>	$pno
		);
		$this->head();
		$this->view('/board/board_notice_list', $data);
	}
	
	// 공지사항 상세
	public function notice() {
		$BoardModel			=	new BoardModel();
		$Helper				=	new Helper();

		if(!$this->get("no", 1)){
			$Helper->goPage("/board/noticeList", "잘못된 접근입니다.");
			exit;
		}

		// 상세정보 호출
		$search				=	array(
			"noticeCode"		=>	$this->get("no", 1),
			"isView"			=>	"Y"
		);
		$msg				=	$BoardModel->get_notice(1, 0, $search);
		if(!$msg->get_data()){
			$Helper->goPage("/board/noticeList", "삭제되었거나 존재하지 않는 공지사항입니다.");
			exit;
		}
		$notice				=	$msg->get_data()[0];

		// 조회수 업데이트
		$data				=	array(
			"noticeCode"		=>	$notice["noticeCode"],
			"procType"			=>	1,
			"limit"				=>	1
		);
		$BoardModel->update_notice($data);

		
		$data				=	array(
			"notice"			=>	$notice,
			"pno"				=>	(int)$this->get("pno", 1),
			"searchWord"		=>	$this->get("searchWord", 1)
		);

		$this->head();
		$this->view('/board/board_notice_view', $data);
	}
	
	// faq 목록
	public function faqList() {
		$BoardModel			=	new BoardModel();

		$recordPerPage		=	(int)10;        //	한 페이지당 최대 게시글 개수.
		$pnoPerPage			=	10;        //	한 페이지당 최대 페이지번호 개수.
		$pno				=   (int)$this->get("pno", 1) ? (int)$this->get("pno", 1) : 1;             //	페이지번호.
		$temp				=	($pno * $recordPerPage) - $recordPerPage;

		$search				=	array(
			"searchType"		=>	1,
			"searchWord"		=>	$this->get("searchWord", 1),
			"order"				=>	" fa.FAQCode DESC ",
			"isView"			=>	"Y",
			"allCount"			=>	1
		);
		$msg				=	$BoardModel->get_faq($temp, $recordPerPage, $search);
		$faqList			=	$msg->get_data();

		// 검색어 정리
		$data				=	array(
			"searchWord"		=>	$this->get("searchWord", 1)
		);
		$paging             =   new Pagination($recordPerPage, $pnoPerPage, $pno, $msg->get_msg(), "", "url", $data);

	
		$data				=	array(
			"faqList"			=>	$faqList,
			"paging"            =>  $paging->setPaging(),
			"searchWord"		=>	$this->get("searchWord", 1),
			"pno"				=>	$pno
		);
		$this->head();
		$this->view('/board/board_faq_list', $data);
	}

	// faq 상세
	public function faq() {
		$BoardModel			=	new BoardModel();
		$Helper				=	new Helper();

		if(!$this->get("no", 1)){
			$Helper->goPage("/board/faqList", "잘못된 접근입니다.");
			exit;
		}

		// 상세 정보 호출
		$search				=	array(
			"FAQCode"			=>	$this->get("no", 1),
			"isView"			=>	"Y"
		);
		$msg				=	$BoardModel->get_faq(1, 0, $search);
		if(!$msg->get_data()){
			$Helper->goPage("/board/faqList", "삭제되었거나 존재하지 않는 자주묻는 질문입니다.");
			exit;
		}
		$FAQ				=	$msg->get_data()[0];

		// 조회수 업데이트
		$data				=	array(
			"FAQCode"			=>	$FAQ["FAQCode"],
			"procType"			=>	1,
			"limit"				=>	1
		);
		$BoardModel->update_faq($data);

		$data				=	array(
			"FAQ"				=>	$FAQ,
			"pno"				=>	(int)$this->get("pno", 1),
			"searchWord"		=>	$this->get("searchWord", 1)
		);
		$this->head();
		$this->view('/board/board_faq_view', $data);
	}
	
	// 1:1 목록
	public function inquiryList() {
		$BoardModel			=	new BoardModel();
		$Helper				=	new Helper();

		if(!$this->isLogin()){
			$Helper->goPage("/intro/login", "로그인 후 이용 가능합니다.");
			exit;
		}

		$recordPerPage		=	(int)10;        //	한 페이지당 최대 게시글 개수.
		$pnoPerPage			=	10;        //	한 페이지당 최대 페이지번호 개수.
		$pno				=   (int)$this->get("pno", 1) ? (int)$this->get("pno", 1) : 1;             //	페이지번호.
		$temp				=	($pno * $recordPerPage) - $recordPerPage;

		$search				=	array(
			"searchType"		=>	1,
			"searchWord"		=>	$this->get("searchWord", 1),
			"order"				=>	" iq.inquiryCode DESC ",
			"allCount"			=>	1
		);
		$msg				=	$BoardModel->get_inquiry($temp, $recordPerPage, $search);
		$inquiryList		=	$msg->get_data();

		// 검색어 정리
		$data				=	array(
			"searchWord"		=>	$this->get("searchWord", 1)
		);
		$paging             =   new Pagination($recordPerPage, $pnoPerPage, $pno, $msg->get_msg(), "", "url", $data);

	
		$data				=	array(
			"inquiryList"		=>	$inquiryList,
			"paging"            =>  $paging->setPaging(),
			"searchWord"		=>	$this->get("searchWord", 1),
			"pno"				=>	$pno
		);
		$this->head();
		$this->view('/board/board_inquiry_list', $data);
	}
	
	// 1:1 등록
	public function inquiryReg() {
		$BoardModel			=	new BoardModel();
		$Helper				=	new Helper();

		if(!$this->isLogin()){
			$Helper->goPage("/intro/login", "로그인 후 이용 가능합니다.");
			exit;
		}

		$this->head();
		$this->view('/board/board_inquiry_reg');
	}

	// 1:1 등록
	public function post_add_inquiry(){
		$FileManager		=	new FileManager();
		$BoardModel			=	new BoardModel();
		$Helper				=	new Helper();
		$Member				=	new Member();

		if(!$this->isLogin()){
			$data				=	array(
				"errCd"				=>	1,
				"errMsg"			=>	"로그인 후 이용가능합니다.",
				"url"				=>	"/intro/login"
			);
			echo json_encode($data);
			exit;
		}

		// 입력값 확인
		$arr_check			=	array(
			array(
				"basic"			=>	1,
				"type"			=>	"가aA1!",
				"name"			=>	"inquiryTitle",
				"target"		=>	"input[name=inquiryTitle]",
				"title"			=>	"제목"
			),
			array(
				"basic"			=>	1,
				"type"			=>	"가aA1!",
				"name"			=>	"inquiryInfo",
				"target"		=>	"textarea[name=inquiryInfo]",
				"title"			=>	"내용"
			)
		);
		$msg				=	$Helper->check_postParam($arr_check);
		if(!$msg->get_result()){
			$data           =   array(
				"errCd"			=>  0,
				"errMsg"		=>  $msg->get_msg(),
				"target"		=>	$msg->get_target()
			);
			echo json_encode($data);
			exit;
		}

		// 파일 업로드
		$inquiryFileName1			=	"";
		$inquiryFileName2			=	"";
		$inquiryFilPath1			=	"";
		$inquiryFilPath2			=	"";
		$orgInquiryFileName1		=	"";
		$orgInquiryFileName2		=	"";
		if($_FILES){
			// 이미지를 저장하기 전 저장할 위치 생성
			$newFileName 			=	uniqid(date('YmdHis') . "_");
			$uploadPath				=	$FileManager->setMakeDir($_SERVER['DOCUMENT_ROOT'] . '/file', '', 'inquiry', '', 'Ymd');
			$uploadSavePath 		=	$_SERVER['DOCUMENT_ROOT'];				//현재 파일위치까지 저장하고 있기 때문에


			// 실제 사용되는 파일 갯수만큼 반복문 실행
			for($i = 1; $i <= 2; $i++){
				$upload					=	$_FILES["inquiryFileName" . $i];
	
				//파일 명은 존재하지만 임시파일이 존재하지 않을경우 이미지 용량이 너무 크거나 오류가 생긴것 경고창을 띄우고 돌려보냄
				if($upload['name'] && is_uploaded_file($upload['tmp_name']) == ''){
					$data           =   array(
						"errCd"			=>  0,
						"errMsg"		=>  "최대 업로드 가능 파일 크기는 10MB입니다.",
						"target"		=>	"input[name=inquiryFileName2]"
					);
					echo json_encode($data);
					exit;
				}
	
				// 입시 파일에 해당하는 파일이 없을 경우
				if (is_uploaded_file($upload['tmp_name']) == '') {
					
				}
				// 업로드된 파일이 존재하 경우
				else {
					$ext								=	substr(strrchr($upload['name'], '.'), 1);			// 이미지 확장자 분리
					${"inquiryFileName" . $i}			=	$newFileName . '.' . $ext;
					${"orgInquiryFileName" . $i}		=	$upload['name'];
					${"inquiryFilPath" . $i}			=	$uploadPath . $fileName_1;
	
					// 임시 폴더에 존재하는 이미지를 저장하려는 경로로 복사하며 회전값이 존재할 경우 회전값 적용
					$msg2						=	$FileManager->uploadFile($upload['tmp_name'], $uploadPath . $fileName_1);
					// 성공적으로 해당 이미지를 원하는 위치로 옮겼을 경우
					if ( $msg2->get_result() ) {
						
					} else {
						$data			=	array(
							"errCd"			=>  0,
							"errMsg"		=>  "파일 업로드에 실패하였습니다.",
							"target"		=>	"input[name=inquiryFileName2]"
						);
						echo json_encode($data);
						exit;
					}
				}
			}
		}

		// 1:1 입력
		$data					=	array(
			"SEQ_userCode"				=>	$Member->get_SEQ_userCode(),
			"inquiryTitle"				=>	$this->post("inquiryTitle", 1),
			"inquiryInfo"				=>	$this->post("inquiryInfo", 1),
			"inquiryFileName1"			=>	$inquiryFileName1,
			"inquiryFileName2"			=>	$inquiryFileName2,
			"inquiryFilPath1"			=>	$inquiryFilPath1,
			"inquiryFilPath2"			=>	$inquiryFilPath2,
			"orgInquiryFileName1"		=>	$orgInquiryFileName1,
			"orgInquiryFileName2"		=>	$orgInquiryFileName2
		);
		$msg					=	$BoardModel->insert_inquiry($data);
		if($msg->get_result()){
			$data			=	array(
				"errCd"			=>  1,
				"errMsg"		=>  "1:1문의가 등록되었습니다.",
				"url"			=>	"/board/inquiryList"
			);
			echo json_encode($data);
			exit;
		} else {
			$data			=	array(
				"errCd"			=>  1,
				"errMsg"		=>  "오류가 발생하였습니다.\n관리자에게 문의해주세요."
			);
			echo json_encode($data);
			exit;
		}
	}

	// 1:1 상세
	public function inquiry() {
		$this->head();
		$this->view('/board/board_inquiry_view');
	}

}
