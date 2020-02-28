<?php
namespace APP\Core;

use APP\Libs\FileManager;
//use Common\dto\Common;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
/**
 * 사용법
 * use Common\classes\MonologManager;
 * $monolog				=	new MonologManager();
 * $monolog->log_info('포인트 지급 시작' . $SQL, $array);			//정보 남기기용 로그
 * $monolog->log_debug('get_userInfo 테스트' . $SQL, $array);			//디버그용 로그
 * $monolog->log_error($_SERVER['REQUEST_URI'] . '오류 발생', $array);			//오류사항용 로그
 */

class MonoLog{
	private $log;
	private $path;

	/**
	 * 개발 중일시 DEV
	 * 이외의 상황에서는 업체번호 사용
	 */
	public function __construct($channel = 'DEV', $dirPath = '/logs') {
		//$Common					=	new Common();
		$FileManager			=	new FileManager();
		$mainPath				=	'/var/www/html/mvc/_log';

		//log 파일 저장을 위한 해당 경로의 디렉터리 생성
		// $msg = $FileManager->setMakeDir($_SERVER['DOCUMENT_ROOT'] . $dirPath, '', $channel, '', 'Ym');
		$msg = $FileManager->setMakeDir($mainPath . $dirPath, '', $channel, '', 'Ym');

		//경로 및 채널 준비
		// $this->path				=	$Common->DOCUMENTROOT() . $dirPath .'/' . $channel . '/' . date('Y') . '/' . date('Ym') . '/' . date('Ymd')  . '.log';
		$this->path				=	$mainPath . $dirPath .'/' . $channel . '/' . date('Y') . '/' . date('Ym') . '/' . date('Ymd')  . '.log';
		$this->log				=	new Logger($channel);

		//로그 기록할때마다 호출하면 기록이 누적되어 최초 한번만 선언
		$this->log->pushHandler(new StreamHandler($this->path, Logger::DEBUG));
		$this->log->pushHandler(new FirePHPHandler());
	}

	public function log_info($msg, $values = ''){
		if($values){
			$this->log->info($msg, $values);
		} else {
			$this->log->info($msg);
		}

	}

	public function log_warning($msg, $values = ''){
		if($values){
			$this->log->warning($msg, $values);
		} else {
			$this->log->warning($msg);
		}
	}

	public function log_error($msg, $values = ''){
		if($values){
			$this->log->error($msg, $values);
		} else {
			$this->log->error($msg);
		}
	}
}
