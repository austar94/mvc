<?php
header("Content-Type:text/html; charset=utf-8;"); 

//시간대 설정
if (function_exists("date_default_timezone_set"))
	date_default_timezone_set("Asia/Seoul");


defined('DS') or define('DS', DIRECTORY_SEPARATOR);				//DIRECTORY_SEPARATOR 디렉토리 상수로서 리눅스면 /, 윈도우면 \를 반환한다

//프로젝트 내 어플리케이션 위치
define('APP', 			__DIR__  . DS . '..' . DS .'application' . DS);
define('VIEW', 			__DIR__  . DS . '..' . DS .'application' . DS .'View' . DS);
define('CONTROLLER', 	__DIR__  . DS . '..' . DS .'application' . DS .'Controller' . DS);
define('MODEL', 		__DIR__  . DS . '..' . DS .'application' . DS .'Model' . DS);
define('TEMPLATES_PATH', __DIR__  . DS . '..' . DS .'application' . DS .'View' . DS . '_templates' . DS);
define('LIBS', __DIR__  . DS . '..' . DS .'application' . DS .'Libs' . DS);


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
