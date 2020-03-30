<?php
namespace APP\Core;

use APP\Libs\Helper;
use APP\Dto\Member;
use APP\Core\MonoLog;
use APP\Model\BasicModel;

class Controller
{

	public function __construct()
    {

	}

	/**
	 * 로그인 상태 반환
	 * @return boolean [description]
	 */
	function isLogin(){
		$Member			=	new Member();

		if($Member->get_userNo()){
			return true;
		}
		return false;
	}


	/**
	 * 로그인 상태 및 권한 확인
	 * @param  integer $userType 유저 권한
	 * @return [type]            [description]
	 */
	function checkAuth($userType = [1,2,3,4,5]){
		$Member			=	new Member();
		$Helper			=	new Helper();
		if(!$Member->get_userType()){
			// throw new \Throwable('로그인 후 이용 가능합니다.', 401);
			$Helper->goPage('/', '로그인 후 이용 가능합니다.');
			exit;
		} else if(!in_array($Member->get_userType(), $userType)){
			throw new \Exception('해당 페이지를 이용할 수있는 권한이 없습니다.', 403);
		}
	}

	/**
	 * get xss방지
	 * @param  string  $param    [description]
	 * @param  integer $isFilter [description]
	 * @return [type]            [description]
	 */
	function get($param = '', $isFilter = 0){
		$Helper			=	new Helper();
		if($isFilter) foreach($_GET as $key=>$get) $_GET[$key] = $Helper->allTags($get);

		if($param){
			return $_GET[$param] ? $_GET[$param] : '';
		} else {
			return $_GET;
		}
	}

	/**
	 * post xss방지
	 * @param  string  $param    파라미터 명, 없을 경우 전체
	 * @param  integer $isFilter 파라미터 검사, 0:검사안함, 1:빈값 및 http태그검사, 2:엄격한검사
	 * @return [type]            [description]
	 */
	function post($param = '', $isFilter = 0){
		$Helper			=	new Helper();

		if($isFilter) foreach($_POST as $key=>$post) $_POST[$key] = $Helper->allTags($post);

		if($param){
			return $_POST[$param] ? $_POST[$param] : '';
		} else {
			return $_POST;
		}
	}

	//이거 왜 컨트롤쪽에..?
	/**
	 * view 호출
	 * @param  [type] $path 호출 위치
	 * @param  array  $data 뷰에 전달할 정보
	 * @return [type]       [description]
	 */
	function view($path, $data = []) {
		$Member			=	new Member();
		$login_user		=	array(
			'login_userNo'			=>	$Member->get_userNo(),
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
	function header(){
		require APP . 'View/_templates/header.php';
	}

	//푸터
	function footer(){
		// $BasicModel         =   new BasicModel();
		//
        // $msg                =   $BasicModel->get_configInfo();
        // $config             =   $msg->get_data();
		// $corp				=	array(
		// 	'corpName'			=>	$config['corpName'],
		// 	'corpAddr'			=>	$config['corpAddr']
		// );
		// extract($corp, EXTR_SKIP);

		require APP . 'View/_templates/footer.php';
	}
}
