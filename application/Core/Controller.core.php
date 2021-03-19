<?php
namespace APP\Core;
if (!defined('_STAR_')) exit;

use APP\Libs\Helper;
use APP\Dto\Member;
use APP\Core\MonoLog;

class Controller
{

	public function __construct()
    {
		
	}

	/**
	 * check_login 로그인 확인
	 * post일 경우 json 반환, get일 경우 페이지 이동
	 *
	 * @return void
	 */
	function check_login(){
		if(!$this->isLogin()){
			if(REQUEST_METHOD == "POST"){
				$data           =   array(
					'errCd'         =>  999,
					'errMsg'        =>  '로그인 후 이용가능합니다.'
				);
				echo json_encode($data);
				exit;
			} else {
				$Helper             =   new Helper();
				if(!$this->isLogin()) $Helper->goPage('/intro');
			}
		}
	}

	/**
	 * 로그인 상태 반환
	 * @return boolean [description]
	 */
	function isLogin(){
		$Member			=	new Member();

		if($Member->get_userCode()){
			return true;
		}
		return false;
	}


	/**
	 * 로그인 상태 및 권한 확인
	 * @param  integer $userType 유저 권한
	 * @return [type]            [description]
	 */
	//function checkAuth($userType = [1,2,3,4,5]){
	//	$Member			=	new Member();
	//	$Helper			=	new Helper();
	//	if(!$Member->get_userType()){
	//		// throw new \Throwable('로그인 후 이용 가능합니다.', 401);
	//		$Helper->goPage('/', '로그인 후 이용 가능합니다.');
	//		exit;
	//	} else if(!in_array($Member->get_userType(), $userType)){
	//		throw new \Exception('해당 페이지를 이용할 수있는 권한이 없습니다.', 403);
	//	}
	//}

	/**
	 * get xss방지
	 * @param  string  $param    [description]
	 * @param  integer $isFilter [description]
	 * @return [type]            [description]
	 */
	function get($param = '', $isFilter = 0){
		$Helper				=	new Helper();
		$getData			=	$_GET;

		if($param){
			$getData		=	$_GET[$param] ?? "";
		}

		if($isFilter){
			if($param){
				$getData			=	$getData ? $Helper->allTags($getData) : "";
			} else {
				foreach($getData as $key=>$get) $getData[$key] = $Helper->allTags($get);
			}
		}

		return $getData;
		//if($isFilter) foreach($_GET as $key=>$get) $_GET[$key] = $Helper->allTags($get);

		// if($param){
		// 	return $_GET[$param] ?? "";
		// } else {
		// 	return $_GET;
		// }
	}

	/**
	 * post xss방지
	 * @param  string  $param    파라미터 명, 없을 경우 전체
	 * @param  integer $isFilter 파라미터 검사, 0:검사안함, 1:빈값 및 http태그검사, 2:엄격한검사
	 * @return [type]            [description]
	 */
	function post($param = '', $isFilter = 0){
		$Helper				=	new Helper();
		$postData			=	$_POST;

		if($param){
			$postData		=	$_POST[$param] ?? "";
		}

		if($isFilter) {
			if($param){
				// $_POST[$param] = $_POST[$param] ? $Helper->allTags($_POST[$param]) : "";
				$postData			=	$postData ? $Helper->allTags($postData) : "";
			} else {
				// foreach($_POST as $key=>$post) $_POST[$key] = $Helper->allTags($post);
				foreach($postData as $key=>$post) $postData[$key] = $Helper->allTags($post);
			}
		}

		return $postData;
		// if($param){
		// 	return $postData ?? "";
		// } else {
		// 	return $postData;
		// }
	}

	//이거 왜 컨트롤쪽에..?
	/**
	 * view 호출
	 * @param  string $path 호출 위치
	 * @param  array  $data 뷰에 전달할 정보
	 * @return [type]       [description]
	 */
	function view($path, $data = []) {
		$Helper			=	new Helper();
		$Member			=	new Member();
		$login_user		=	array(
			'login_userCode'		=>	$Member->get_userCode(),
			'login_userID'			=>	$Member->get_userID(),
			'login_userName'		=>	$Member->get_userName(),
			'login_userType'		=>	$Member->get_userType()
		);

		if($data) extract($data, EXTR_SKIP);
		if($login_user) extract($login_user, EXTR_SKIP);

		$file			=	APP . '/View' . $path . '.php';

		if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("해당하는 페이지를 찾지 못했습니다.");
        }
	}

	//헤더
	function head(){
		$Helper			=	new Helper();
		require APP . 'View/_templates/head.php';
	}

	//푸터
	function footer(){
		$Helper			=	new Helper();
		require APP . 'View/_templates/footer.php';
	}
}
