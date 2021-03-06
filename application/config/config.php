<?php

//세션설정
@session_save_path($_SERVER['DOCUMENT_ROOT'] . '/../_session');												//	이 옵션에서 LG U+ 전자결제 에러.
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
define('URL_PUBLIC_FOLDER', 'public');          //노출 가능한 폴더
define('URL_PROTOCOL', '//');
define('URL_DOMAIN', $_SERVER['HTTP_HOST']);
define('URL_SUB_FOLDER', str_replace(URL_PUBLIC_FOLDER, '', dirname($_SERVER['SCRIPT_NAME'])));				//하위폴더 설정
define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER);													//현재 페이지에대한 URL 정의

//설정값을 워낙 자주써서..
define('REQUEST_URI', $_SERVER['REQUEST_URI']);
define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);

//csrf 검증
define('CHECK_CSRF_TOKEN', 'N');                                                                       //CSRF POST TOKEN 확인 체크 여부
define('CSRF_TOKEN_NAME', '_csrf');                                                                         //토큰 방식 이용시 토큰 전달 명
define('CHECK_CSRF_REFERER', 'N');                                                                     //CSRF POST HTTP_REFERER 확인

define('SALT_KEY', 'iFf123FUf19vN9cHFw2B');          // 고유값입니다. 변경하지 말것

// 해당 상수가 정의되지 않으면 각 개별 페이지 사용 불가
define('_STAR_', true);
