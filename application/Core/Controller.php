<?php
namespace APP\Core;

use APP\Router;

class Controller extends \Exception
{

	public function __construct()
    {
		//잘 작동함..
		// throw new \Exception("로그인이 필요한 페이지입니다.");
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
