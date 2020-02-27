<?php
namespace APP\Core;

use APP\Router;
use APP\Controller\ErrorController;

class Application extends \Exception
{
	//컨트롤러 지시
    private $url_controller;

    //컨트롤러 function
    private $url_action;

    //기타 파라미터
    private $url_params;

    //url호출시 기본 작동
    public function __construct()
    {
       /*  $this->run(); */
	}

	//기동
	public function run(){
		//.htaccess에서 의해서 localhost/board/read/3 형식의 url이 들어오면		-> localhost/index.php/url=board/read/3 형식으로 변경됨


		try{
			$this->splitUrl();

			if (!$this->url_controller) {

				$page = new \APP\Controller\HomeController();
				$page->index();

			}
			//전달받은 controller 에 맞는 컨트롤 파일이 존재하는지 확인
			else if (file_exists(APP . 'Controller/' . ucfirst($this->url_controller) . 'Controller.php')) {

				//전달받은 controller의 객체를 생성
				$controller = "\\APP\\Controller\\" . ucfirst($this->url_controller) . 'Controller';
				$this->url_controller = new $controller();

				//전달받은 controller의 action(function)이 존재하는지 확인
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
						throw new \Exception ('해당하는 페이지를 찾을 수 없습니다. ' . $_SERVER['REQUEST_URI']);
					}
				}
			}
			//전달받은 컨트롤러 정보의 파일이 존재하지 않을경우 에러 사이트로 이동
			else {
				throw new \Exception ('해당하는 페이지를 찾을 수 없습니다. ' . $_SERVER['REQUEST_URI']);
			}
		} catch (\Exception  $e){
			// var_dump(
			// 	$e->getMessage(),
			// 	$e->getCode(),
			// 	$e->getFile(),
			// 	$e->getLine(),
			// 	$e->getTrace(),
			// 	$e->getPrevious(),
			// 	$e->getTraceAsString()
			// );

			$errCon            =   new ErrorController();
			$errCon->index($e);
		}
	}

   //.htaccess에서 의해서 localhost/board/read/3 형식의 url이 들어오면		-> localhost/index.php/url=board/read/3 형식으로 변경됨
   //해당 변경 정보를 사용하기 적합하게 controller, action, params 등으로 분배
    private function splitUrl()
    {
        if (isset($_GET['url'])) {
            $url						=	trim($_GET['url'], '/');
            $url						=	filter_var($url, FILTER_SANITIZE_URL);				//FILTER_SANITIZE_URL 모든 불법 url 문자 제거
			$url						=	explode('/', $url);

			//규칙 재정의보다 예외선언이 횔씬 빠를듯
			//해당 url의 첫번째 값이 미리 선언되어있는 값과 일치하는지 확인 후 일치하는 값이 없을 경우 기존루트, 존재할경우 예외루트로 진행

			/* print_r($url); */

            $this->url_controller		=	isset($url[0]) ? $url[0] : null;
            $this->url_action			=	isset($url[1]) ? $url[1] : null;
			unset($url[0], $url[1]);



			// print_r(array_values($url));

            $this->url_params			=	array_values($url);
        }
	}

}
