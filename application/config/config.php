<?php

//세션설정
@session_save_path($_SERVER['DOCUMENT_ROOT'] . '/_session');												//	이 옵션에서 LG U+ 전자결제 에러.
@session_cache_limiter('nocache, must-revalidate');															//	캐시가 유지되어 폼값이 보존
@ini_set('session.gc_maxlifetime', 43200);																	//	초 - 세션 만료시간을 12시간으로 설정
@ini_set('session.cache_expire', 43200);																	//	12시간
@ini_set('session.gc_probability', 1);
@ini_set('session.gc_divisor', 100);
if ( !isset($set_time_limit) ) $set_time_limit = 0;
@set_time_limit($set_time_limit);
@session_set_cookie_params(0, '/', $_SERVER['HTTP_HOST']);													//	해당 도메인만 세션 생성
@ini_set('session.cookie_domain', $_SERVER['HTTP_HOST']);													//	세션이 활성화 될 도메인
@ini_set('session.use_trans_sid', 0);
@ini_set('url_rewriter.tags', '');
session_start();																							//	세션 시작

//	메모리 제한 늘리기
ini_set('memory_limit','512M');

defined('SYS_DEBUG') or define('SYS_DEBUG', true);				//디버그 여부
defined('isMaintenance') or define('isMaintenance', false);		//점검 중일경우

//개발용 디버그 설정
if (SYS_DEBUG) {
    ini_set("display_errors", 1);
}
error_reporting(E_ALL & ~E_NOTICE);				//에러 기록


//url 정의
define('URL_PUBLIC_FOLDER', 'public');																		//노출 가능한 폴더
define('URL_PROTOCOL', '//');
define('URL_DOMAIN', $_SERVER['HTTP_HOST']);
define('URL_SUB_FOLDER', str_replace(URL_PUBLIC_FOLDER, '', dirname($_SERVER['SCRIPT_NAME'])));				//하위폴더 설정
define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER);													//현재 페이지에대한 URL 정의


//에러 설정
