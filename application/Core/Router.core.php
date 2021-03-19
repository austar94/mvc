<?php
namespace APP\Core;
if (!defined('_STAR_')) exit;

use APP\Controller\HomeController;
use APP\config\routes;
use APP\Core\MonoLog;
use APP\Dto\Member;
use APP\Libs\Helper;

class Router
{
	//컨트롤러 지시
	public $url_controller;

	//컨트롤러 function
	public $url_action;

	//기타 파라미터
	public $url_params;

	/**
	 * method 확인
	 * @return [type] [description]
	 */
	public function check_method(){
		if(REQUEST_METHOD != 'POST' && REQUEST_METHOD != 'GET'){
			throw new \Exception('요청할 수 없는 method입니다. ', 405);
		}
	}

	// 유저 접근권한 확인
	public function check_userAuthority(){
		$Member				=	new Member();
		$Helper				=	new Helper();

		// 로그인 한 유저일 경우
		if($Member->get_userCode()){
			// 최상위 관리자를 제외한 나머지 유저의 경우
			if($Member->get_userGrade() == 2){
				// 현재 위치에 대한 접근 권한을 확인하고 접근 권한이 없을 경우 메인페이지로 보냄
				if($this->url_controller == "plan" && !$Member->get_isMenu1()){
					$Helper->goPage("/", "해당 메뉴에 대한 접근권한이 없습니다.");
					exit;
				} else if($this->url_controller == "budget" && !$Member->get_isMenu2()){
					$Helper->goPage("/", "해당 메뉴에 대한 접근권한이 없습니다.");
					exit;
				} else if($this->url_controller == "settlement" && !$Member->get_isMenu3()){
					$Helper->goPage("/", "해당 메뉴에 대한 접근권한이 없습니다.");
					exit;
				} else if($this->url_controller == "setting" && !$Member->get_isMenu4()){
					$Helper->goPage("/", "해당 메뉴에 대한 접근권한이 없습니다.");
					exit;
				} else if($this->url_controller == "expense" && !$Member->get_isMenu5()){
					$Helper->goPage("/", "해당 메뉴에 대한 접근권한이 없습니다.");
					exit;
				} else if($this->url_controller == "cardSetting" && !$Member->get_isMenu6()){
					$Helper->goPage("/", "해당 메뉴에 대한 접근권한이 없습니다.");
					exit;
				}
			}
		}
	}

	/**
	 * url접근시 path설정
	 * @return [type] [description]
	 */
	public function find_path(){
		try {
			$page							=	new HomeController();

			//baseController 설정
			if (!$this->url_controller) {
				switch (REQUEST_METHOD) {
					case 'GET':
						$page->index();
						break;
					default:
						throw new \Exception('잘못된 요청입니다.', 405);
					break;
				}
			}
			//전달받은 controller 에 맞는 컨트롤 파일이 존재하는지 확인
			else if (file_exists(APP . 'Controller/' . ucfirst(strtolower($this->url_controller)) . '.Controller.php')) {
				//전달받은 controller의 action(function)이 존재하는지 확인
				//post일경우와 get일 경우를 나눔
				switch (REQUEST_METHOD) {
					case 'GET':
						$this->check_get_csrf();
						$this->get_path();
						break;
					case 'POST':
						$this->check_post_csrf();
						$this->post_path();
						break;
					default:
						throw new \Exception('잘못된 요청입니다.', 405);
					break;
				}
			}
			// 전달된 컨트롤러가 실제 컨트롤러가 아니라 HOME에 있을 가능성이 있음.(GET)
			else if (REQUEST_METHOD == "GET" && method_exists($page, $this->url_controller) &&
			is_callable(array($page, $this->url_controller))) {

				$this->check_get_csrf();
				$page->{$this->url_controller}();
			}
			// 전달된 컨트롤러가 실제 컨트롤러가 아니라 HOME에 있을 가능성이 있음.(POST)
			else if(REQUEST_METHOD == "POST" && method_exists($page, "post_" . $this->url_controller) &&
			is_callable(array($page, "post_" . $this->url_controller))) {

				$this->check_post_csrf();
				$page->{"post_" . $this->url_controller}();

			} 
			//전달받은 컨트롤러 정보의 파일이 존재하지 않을경우 에러 사이트로 이동
			else {
				throw new \Exception('해당하는 페이지를 찾을 수 없습니다. ', 404);
			}
		} catch (\Throwable $e) {
			$MonoLog			=	new MonoLog();
			$MonoLog->log_exceptionErr($e);

			if($e->getCode()){
				throw new \Exception($e->getMessage(), $e->getCode());
			} else {
				throw new \Exception('요청하신 페이지를 찾을 수 없습니다.', '404');
			}
		}
	}

	/**
	 * get csrf 방지를 위한 체크
	 * @return [type] [description]
	 */
	public function check_get_csrf(){
		//token 방식 사용할 경우
		//GET요청이므로 현재 사이트에 해당하는 csrf값을 생성하여 세션에 저장
		if(CHECK_CSRF_TOKEN == 'Y'){
			//현재 페이지에대한 csrf 토큰값이 없을 경우 생성시킴
			if(!$_SESSION[CSRF_TOKEN_NAME][URL]){
				$_SESSION[CSRF_TOKEN_NAME][URL]       = base64_encode(openssl_random_pseudo_bytes(32));
			}
		}
	}

	/**
	 * post csrf 방지를 위한 체크
	 * @return [type] [description]
	 */
	public function check_post_csrf(){
		//token 방식 사용할 경우
		if(CHECK_CSRF_TOKEN == 'Y'){
			//post로 전달된 토큰값 확인 후 세션에 저장된 현재 페이지의 토큰값과 비교
			if(!$_POST['CSRF_TOKEN_NAME']) throw new \Exception('잘못된 요청입니다.', 405);
			if($_POST['CSRF_TOKEN_NAME'] != $_SESSION[CSRF_TOKEN_NAME][URL]) throw new \Exception('잘못된 요청입니다.', 405);
		}

		//HTTP_REFERER 방식 사용할 경우
		//이전 페이지를 가지고있는지 확인하고 이전 페이지가 존재할 경우 현재 도메인과 비교하여 다를경우 돌려보냄
		if(CHECK_CSRF_REFERER == 'Y'){
			if(!parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)) throw new \Exception('잘못된 요청입니다.', 405);
			if(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) != URL_DOMAIN) throw new \Exception('잘못된 요청입니다.', 405);
		}
	}

	/**
	 * post 요청에 따른 path 찾기
	 * @return [type] [description]
	 */
	public function post_path(){
		//전달받은 controller의 객체를 생성
		$controller = "\\APP\\Controller\\" . ucfirst(strtolower($this->url_controller)) . 'Controller';
		$this->url_controller			=	new $controller();

		//post_를 붙여서 생성
		$this->url_action				=	$this->url_action ? 'post_' . $this->url_action : $this->url_action;

		if (method_exists($this->url_controller, $this->url_action) &&
			is_callable(array($this->url_controller, $this->url_action))) {

			//전송 가능한 파라미터가 존재할 경우
			if (!empty($this->url_params)) {
				//해당 controller의 action(function)으로 파라미터 전송
				call_user_func_array(array($this->url_controller, $this->url_action), $this->url_params);
			}
			//전송 가능한 파라미터가 없을경우
			else {
				//해당 controller의 action(function)만 호출
				$this->url_controller->{$this->url_action}();
			}
		}
		//controller는 존재하지만 해당 action(function)이 존재하지 않을경우 post의 경우 반환을 중지한ㄴ다.
		else {
			throw new \Exception('요청하신 페이지를 찾지 못했습니다. ', 404);
		}
	}

	/**
	 * get 요청에 따른 path 찾기
	 * @return [type] [description]
	 */
	public function get_path(){
		//전달받은 controller의 객체를 생성
		try {
			$controller = "\\APP\\Controller\\" . ucfirst(strtolower($this->url_controller)) . 'Controller';

			$this->url_controller			=	new $controller();

			if (method_exists($this->url_controller, $this->url_action) &&
				is_callable(array($this->url_controller, $this->url_action))) {

				//전송 가능한 파라미터가 존재할 경우
				if (!empty($this->url_params)) {
					//해당 controller의 action(function)으로 파라미터 전송
					call_user_func_array(array($this->url_controller, $this->url_action), $this->url_params);
				}
				//전송 가능한 파라미터가 없을경우
				else {
					//해당 controller의 action(function)만 호출
					$this->url_controller->{$this->url_action}();
				}
			}
			//controller는 존재하지만 해당 action(function)이 존재하지 않을경우
			else {
				//action(function)값 자체가 들어오지 않은경우
				if (strlen($this->url_action) == 0) {
					//controller는 정상적으로 호출됬지만 함께넘어온 action(function)이 존재하지 않기때문에 해당 controller의 기본값만 실행
					$this->url_controller->index();
				}
				//cation(function)값이 들어 왔지만 해당 action(function)은 존재하지 않기에 존재하지 않은 페이지를 호출한 것이므로 오류페이지로 이동
				else {
					throw new \Exception('해당하는 페이지를 찾을 수 없습니다. ', 404);
				}
			}
		} catch (\TypeError $e){
			throw new \Exception('잘못된 접근입니다.', 405);
		} catch (\Throwable $e) {
			$MonoLog			=	new MonoLog();
			$MonoLog->log_exceptionErr($e);
			if($e->getCode()){
				throw new \Exception($e->getMessage(), $e->getCode());
			} else {
				throw new \Exception('요청하신 페이지를 찾을 수 없습니다.', 404);
			}
		}
	}

	//.htaccess에서 의해서 localhost/board/read/3 형식의 url이 들어오면		-> localhost/index.php/url=board/read/3 형식으로 변경됨
    //해당 변경 정보를 사용하기 적합하게 controller, action, params 등으로 분배
    public function splitUrl()
     {
		 //include_once APP . 'config/routes.php';
         if (isset($_GET['url'])) {
			 $url						=	trim($_GET['url'], '/');
			
			//  $url						=	filter_var($url, FILTER_SANITIZE_URL);				//FILTER_SANITIZE_URL 모든 불법 url 문자 제거
			preg_match("/[a-zA-Z1-9ㄱ-ㅎ|ㅏ-ㅣ|가-힣$-_]+/", $url, $url); 

			 $url						=	explode('/', $url[0]);

			 $this->url_controller		=	isset($url[0]) ? $url[0] : null;
			 $this->url_action			=	isset($url[1]) ? $url[1] : null;
			 unset($url[0], $url[1]);
			 $this->url_params			=	array_values($url);
         }
 	}

	public function get_url_controller(){
		return $this->url_controller;
	}
}
