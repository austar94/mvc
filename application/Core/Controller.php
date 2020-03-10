<?php
namespace APP\Core;

use APP\Libs\Helper;

class Controller extends \Exception
{

	public function __construct()
    {
		//잘 작동함..
		// throw new \Exception("로그인이 필요한 페이지입니다.");
	}


	/**
	 * 로그인 상태 및 권한 확인
	 * @param  integer $userType 유저 권한
	 * @return [type]            [description]
	 */
	function checkAuth($userType = 1){
		$Helper			=	new Helper();

		if(!$_SESSION['userType']){
			throw new \Exception ('로그인 후 이용 가능합니다.', 401);
		} else if($_SESSION['userType'] != $userType){
			throw new \Exception ('해당 페이지를 이용할 수있는 권한이 없습니다.', 401);
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
	 * @param  string  $param    [description]
	 * @param  integer $isFilter [description]
	 * @return [type]            [description]
	 */
	function post($param = '', $isFilter = 0){
		$Helper			=	new Helper();

		if($isFilter) foreach($_POST as $key=>$get) $_POST[$key] = $Helper->allTags($post);

		if($param){
			return $_POST[$param] ? $_POST[$param] : '';
		} else {
			return $_POST;
		}
	}

	//이거 왜 컨트롤쪽에..?
	function view($path, $data = []) {
		extract($data, EXTR_SKIP);
		$file			=	APP . $path . '.php';

		if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("해당하는 페이지를 찾지 못했습니다.");
        }
	}

	//헤더
	function header(){
		require APP . 'view/_templates/header.php';
	}

	//푸터
	function footer(){
		require APP . 'view/_templates/footer.php';
	}
}
