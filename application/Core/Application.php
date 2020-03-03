<?php
namespace APP\Core;

use APP\Core\Router;
use APP\Controller\ErrorController;

class Application extends \Exception
{
    private $Router;
    //url호출시 기본 작동
    public function __construct()
    {
        $this->Router                   =   new Router();
	}

	/**
     * 어플리케이션 기동
     * @return [type] [description]
     */
	public function run(){
		//.htaccess에서 의해서 localhost/board/read/3 형식의 url이 들어오면		-> localhost/index.php/url=board/read/3 형식으로 변경됨

		try{
            //각 접근에 따른 분류
            $this->Router->check_method();                  //해당하는 method만 허용 ngix에서 기본으로 막고있음
			$this->Router->splitUrl();
            $this->Router->find_path();
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

}
