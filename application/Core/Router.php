<?php
namespace APP\Core;

use APP\Controller\HomeController;
use APP\config\routes;

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
			throw new \Exception ('요청할 수 없는 method입니다. ' . REQUEST_METHOD);
		}
	}

	/**
	 * url접근시 path설정
	 * @return [type] [description]
	 */
	public function find_path(){
		//baseController 설정
		if (!$this->url_controller) {
			switch (REQUEST_METHOD) {
				case 'GET':
					$page							=	new HomeController();
					$page->index();
					break;
				case 'POST':
					//post는 기본설정 사용하지 않음
					throw new \Exception ('잘못된 요청입니다. ' . REQUEST_URI);
					break;
				default:
					throw new \Exception ('잘못된 요청입니다. ' . REQUEST_URI);
				break;
			}
		}
		//전달받은 controller 에 맞는 컨트롤 파일이 존재하는지 확인
		else if (file_exists(APP . 'Controller/' . ucfirst($this->url_controller) . 'Controller.php')) {

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
					throw new \Exception ('잘못된 요청입니다. ' . REQUEST_URI);
				break;
			}
		}
		//전달받은 컨트롤러 정보의 파일이 존재하지 않을경우 에러 사이트로 이동
		else {
			throw new \Exception ('해당하는 페이지를 찾을 수 없습니다. ' . REQUEST_URI);
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
			if(!$_POST['CSRF_TOKEN_NAME']) throw new \Exception ('잘못된 요청입니다. ' . REQUEST_URI);
			if($_POST['CSRF_TOKEN_NAME'] != $_SESSION[CSRF_TOKEN_NAME][URL]) throw new \Exception ('잘못된 요청입니다. ' . REQUEST_URI);
		}

		//HTTP_REFERER 방식 사용할 경우
		//이전 페이지를 가지고있는지 확인하고 이전 페이지가 존재할 경우 현재 도메인과 비교하여 다를경우 돌려보냄
		if(CHECK_CSRF_REFERER == 'Y'){
			if(!parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)) throw new \Exception ('잘못된 요청입니다. ' . REQUEST_URI);
			if(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) != URL_DOMAIN) throw new \Exception ('잘못된 요청입니다. ' . REQUEST_URI);
		}
	}

	/**
	 * post 요청에 따른 path 찾기
	 * @return [type] [description]
	 */
	public function post_path(){
		//전달받은 controller의 객체를 생성
		$controller = "\\APP\\Controller\\" . ucfirst($this->url_controller) . 'Controller';
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
			throw new \Exception ('요청하신 페이지를 찾지 못했습니다. ' . REQUEST_URI);
		}
	}

	/**
	 * get 요청에 따른 path 찾기
	 * @return [type] [description]
	 */
	public function get_path(){
		//전달받은 controller의 객체를 생성
		$controller = "\\APP\\Controller\\" . ucfirst($this->url_controller) . 'Controller';
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
				throw new \Exception ('해당하는 페이지를 찾을 수 없습니다. ' . REQUEST_URI);
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
			 $url						=	filter_var($url, FILTER_SANITIZE_URL);				//FILTER_SANITIZE_URL 모든 불법 url 문자 제거
			 $url						=	explode('/', $url);

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
