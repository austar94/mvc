<?php
//시간대 설정
if (function_exists("date_default_timezone_set"))
	date_default_timezone_set("Asia/Seoul");


defined('DS') or define('DS', DIRECTORY_SEPARATOR);				//DIRECTORY_SEPARATOR 디렉토리 상수로서 리눅스면 /, 윈도우면 \를 반환한다

//프로젝트 내 어플리케이션 위치 
define('APP', 			__DIR__  . DS . '..' . DS .'application' . DIRECTORY_SEPARATOR);
define('VIEW', 			__DIR__  . DS . '..' . DS .'application' . DS .'view' . DIRECTORY_SEPARATOR);
define('CONTROLLER', 	__DIR__  . DS . '..' . DS .'application' . DS .'Controller' . DIRECTORY_SEPARATOR);
define('MODEL', 		__DIR__  . DS . '..' . DS .'application' . DS .'Model' . DIRECTORY_SEPARATOR);
/* 
set_error_handler('handler'); */

//composer
require __DIR__ . DS . '..' . DS . 'vendor' . DS . 'autoload.php';

//기본설정
require APP . 'config' . DS .'config.php';

//어플리케이션 호출
use APP\Core\Application;

//시작
$app		=	new Application();
$app->run();
