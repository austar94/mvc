<?php
namespace APP\Libs;
if (!defined('_STAR_')) exit;

use APP\Libs\Crypto;
use APP\Dto\Message;

class Helper
{

	// 개인용 단방향 암호
	function hash_crypt($string, $salt = "", $type = "sha256"){
		return hash($type, $string . $salt);
	}

	//복구 가능한 암호화
	//	암호화
	function encrypt($str = "") {
		$crypto						=	new Crypto();
		return $crypto->encrypt($str);
	}

	/**
	 * decrypt 복호화
	 *
	 * @param  mixed $str
	 * @return void
	 */
	function decrypt($str = "") {
		$crypto						=	new Crypto();
		return $crypto->decrypt($str);
	}

	// 공동 암호화
	function crypt_($password) {
		return hash("sha256", $password . SALT_KEY);
 	}


	//	remove AllTags
	// 바인딩을 통해 injection 문제를 해결하기 때문에 addslashes 사용 안함.
	function allTags($data)
	{
		if ($data) {
			if(is_array($data)) {
				foreach($data as $key => $value){
					// $data[$key]			=	strip_tags(addslashes(trim($value)));
					$data[$key]			=	strip_tags(trim($value));
				}
				return $data;
			} else {
				// return strip_tags(addslashes(trim($data)));
				return strip_tags(trim($data));
			}
		} else {
			return $data;
		}
	}

	function allStrip($data)
	{
		if ($data) {
			if(is_array($data)) {
				foreach($data as $key => $value){
					$data[$key]			=	stripslashes($value);
				}
				return $data;
			} else {
				return stripslashes($data);
			}
		} else {
			return $data;
		}
	}

	/**
	 * 페이지 이동 및 alert
	 * @param  string $url   이동할 url
	 * @param  string $msg   alert 메시지
	 * @param  string $frame [description]
	 * @return [type]        [description]
	 */
	public function goPage($url = '', $msg = '', $frame = '') {
		if ($url == '') {
			echo '<meta content="text/html" charset="utf-8">';
			echo '<script>';
			if ($msg != '') echo 'alert(\'' . $msg . '\');';
			if ($frame == 'frame') {
				echo 'parent.location.href = \'' . $_SERVER['HTTP_REFERER'] . '\'';
			} else if ($frame == 'top') {
				echo 'top.location.href = \'' . $_SERVER['HTTP_REFERER'] . '\'';
			} else {
				echo 'location.href = \'' . $_SERVER['HTTP_REFERER'] . '\'';
			}
			echo '</script>';
		} else {
			echo '<meta content="text/html" charset="utf-8">';
			echo '<script>';
			if ($msg != '') echo 'alert(\'' . $msg . '\');';
			if ($frame == 'frame') {
				echo 'parent.location.href = \'' . $url . '\';';
			} else if ($frame == 'top') {
				echo 'top.location.href = \'' . $url . '\';';
			} else {
				echo 'location.href = \'' . $url . '\';';
			}
			echo '</script>';
		}
		exit;
	}

	/**
	 * post xss방지
	 * @param  string  $param    파라미터 명, 없을 경우 전체
	 * @param  integer $isFilter 파라미터 검사, 0:검사안함, 1:빈값 및 http태그검사, 2:엄격한검사
	 * @return [type]            [description]
	 */
	function post($param = '', $isFilter = 0){

		if($isFilter) foreach($_POST as $key=>$post) $_POST[$key] = $this->allTags($post);

		if($param){
			return $_POST[$param] ? $_POST[$param] : '';
		} else {
			return $_POST;
		}
	}

	/**
	 * 이전 페이지로 이동
	 * @param  string $msg   [description]
	 * @param  string $frame [description]
	 * @return [type]        [description]
	 */
	public function goBack($msg = '', $frame = '') {
		echo '<meta content="text/html" charset="utf-8">';
		echo '<script>';
		if ($msg != '') echo 'alert(\'' . $msg . '\');';
		if ($frame == '') echo 'history.go(-1);';
		echo '</script>';
		exit;
	}

	/**
	 * 값 체크
	 * 기본 폼
	 * basic		:	1 일 경우 무조건 입력, 0일 경우 입력되었을 때만 확인
	 * type - 가	:	한글 입력가능
	 * type - a		:	영어 소문자 입력가능
	 * type - A		:	영어 대문자 입력가능
	 * type - 1		:	숫자 입력가능
	 * type - !		:	특수문자 입력가능
	 * type - ,		:	, 쉼표 입력가능
	 * type - .		:	. 점 입력가능
	 * tpye 입력의 경우 붙여서 쓰면됨 ex : 한글, 영어 입력가능시 type -  가a
	 * errMsg		:	문제 발생시 지정 멘트
	 * name			:	입력값 name
	 * title		:	해당 입력의 명칭 ex : 아이디, 비밀번호, 검색어 등
	 * 
	 * length 는 utf-8을 기준으로 함.
	 * length		:	1 - 무조건 한개이상 입력		@@ 해당 내용은 basic과 겹쳐지는 내용으로 사용하지 않음.
	 * length		:	3-10 해당 조건 안으로만 입력 가능
	 * length		:	80 1부터 시작할 경우 최대 입력 가능 조건만 충족 ex - 최대 80자까지 입력 가능합니다.
	 * inputType	:	입력 타입에 대한 설정, 기본적으로 입력을 기본으로함 (radio, select 등일 때는 선택 으로 낱말 변경 필요)
	 */
	public function check_postParam($arr_check){
		$msg					=	new Message();
		$arr_post				=	$this->post("", 1);
		if(!$arr_check) return $msg;

		for($i = 0; $i < count($arr_check); $i++){
			$check				=	$arr_check[$i];
			$basic				=	$check["basic"] ?? "";
			$type				=	$check["type"] ?? "";
			$name				=	$check["name"] ?? "";
			$title				=	$check["title"] ?? "";
			$inputType			=	$check["inputType"] ?? "입력";
			$target				=	$check["target"] ?? "";
			$length				=	$check["length"] ?? "";
			$errMsg				=	$check["errMsg"] ?? "";

			// basic 값 확인
			if($basic){
				// 필수 입력 확인
				if(!$arr_post[$name]){
					if($errMsg) $msg->set_msg($errMsg);
					else $msg->set_msg($title . "를(을) " . $inputType . "해주세요.");
					$msg->set_target($target);
					return $msg;
				}
			}

			// 입력 가능 갯수 확인
			if($length){
				// 최소 - 최대 입력 갯수가 정해져 있을 경우.
				if(strpos($length, "-") !== false){
					$arr_length			=	explode("-", $length);

					// 최소 입력 수 보다 입력 내용이 작을 경우
					if(mb_strlen($arr_post[$name], "UTF-8") < $arr_length[0]){
						$msg->set_msg($title . "를(을) " . $arr_length[0] . "자 이상 입력해주세요.");
						$msg->set_target($target);
						return $msg;
					}

					// 최대 입력 수보다 입력 내용이 많은 경우
					if(mb_strlen($arr_post[$name], "UTF-8") > $arr_length[1]){
						$msg->set_msg($title . "는 최대 " . $arr_length[0] . "자까지 입력 가능합니다.");
						$msg->set_target($target);
						return $msg;
					}
				}
				// 최대 입력 수 만 정해져 있을 경우
				else {
					if(mb_strlen($arr_post[$name], "UTF-8") > $length){
						$msg->set_msg($title . "는 최대 " . $length . "자까지 입력 가능합니다.");
						$msg->set_target($target);
						return $msg;
					}
				}
			}

			// 입력에 따른 형식 확인이 존재하는 경우, 정규식 검사
			// 입력할수없는 것들만 체크해서 해당 내용이 존재하는지 확인
			if($type){
				$pregMatch		=	"";
				$preg_errMsg	=	"";

				// 한글 입력가능
				if(strpos($type, "가") !== false){
					$preg_errMsg	=	"한글";
				} else {
					$pregMatch		.=	"가-힣";
				}

				if(strpos($type, "a") !== false){
					$preg_errMsg	.=	$preg_errMsg ? ", 소문자" : $preg_errMsg;
				} else {
					$pregMatch		.=	"a-z";
				}

				if(strpos($type, "A") !== false){
					$preg_errMsg	.=	$preg_errMsg ? ", 대문자" : $preg_errMsg;
				} else {
					$pregMatch		.=	"A-Z";
				}

				if(strpos($type, "1") !== false){
					$preg_errMsg	.=	$preg_errMsg ? ", 숫자" : $preg_errMsg;
				} else {
					$pregMatch		.=	"0-9";
				}

				// if(strpos($type, "!") !== false){
				// 	$preg_errMsg	.=	$preg_errMsg ? ", 특수문자(`~!@#$%^&*|\\\'\";:\/?^=^+_()<>)" : $preg_errMsg;
				// } else {
				// 	$pregMatch		.=	"`~!@#$%^&*|\\\'\";:\/?^=^+_()<>";
				// }

				if(strpos($type, ",") !== false){
					$preg_errMsg	.=	$preg_errMsg ? ", 쉼표(,)" : $preg_errMsg;
					$pregMatch		.=	",";
				} else {
					// 이미 특수문자에 대한 입력값을 검사하는경우 포함할 필요 없음.
					if(strpos($type, "!") === false){
						$pregMatch		.=	",";
					}
				}

				if(strpos($type, ".") !== false){
					$preg_errMsg	.=	$preg_errMsg ? ", 점(.)" : $preg_errMsg;
				} else {
					// 이미 특수문자에 대한 입력값을 검사하는경우 포함할 필요 없음.
					if(strpos($type, "!") === false){
						$pregMatch		.=	".";
					}
				}

				if(preg_match("/\[" . $pregMatch . "]/", $arr_post[$name])){
					$msg->set_msg($title . "는 " . $preg_errMsg . "만 입력 가능합니다.");
					$msg->set_target($target);
					return $msg;
				}
			}
		}

		$msg->set_result(1);
		return $msg;
	}

	//=========================================================================================================
	// 날짜형식 확인
	public function check_dateForm($date){
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
			return true;
		} else {
			return false;
		}
	}
	//=========================================================================================================

	//	임의(Random)의 문자열 생성 하는 함수
	public function getRandomString($type = '', $len = 10) {
		$lowercase				=	'abcdefghijklmnopqrstuvwxyz';
		$uppercase				=	'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$numeric				=	'0123456789';
		$special				=	'`~!@#$%^&*()-_=+\\|[{]};:\'",<.>/?';
		$key					=	'';
		$token					=	'';

		if ( $type == '' ) {
			$key				=	$lowercase.$uppercase.$numeric;
		} else {
			if (strpos($type, '09') > -1) $key .= $numeric;
			if (strpos($type, 'az') > -1) $key .= $lowercase;
			if (strpos($type, 'AZ') > -1) $key .= $uppercase;
			if (strpos($type, '$') > -1) $key .= $special;
		}

		for ($i = 0; $i < $len; $i++) {
			$token				.=	$key[mt_rand(0, strlen($key) - 1)];
		}
		return $token;
	}

	// 사용예
	// echo '기본 : ' . getRandomString() . '<br />';
	// echo '숫자만 : ' . getRandomString('09') . '<br />';
	// echo '숫자만 30글자 : ' . getRandomString('09', 30) . '<br />';
	// echo '소문자만 : ' . getRandomString('az') . '<br />';
	// echo '대문자만 : ' . getRandomString('AZ') . '<br />';
	// echo '소문자+대문자 : ' . getRandomString('azAZ') . '<br />';
	// echo '소문자+숫자 : ' . getRandomString('az09') . '<br />';
	// echo '대문자+숫자 : ' . getRandomString('AZ09') . '<br />';
	// echo '소문자+대문자+숫자 : ' . getRandomString('azAZ09') . '<br />';
	// echo '특수문자만 : ' . getRandomString('$') . '<br />';
	// echo '숫자+특수문자 : ' . getRandomString('09$') . '<br />';
	// echo '소문자+특수문자 : ' . getRandomString('az$') . '<br />';
	// echo '대문자+특수문자 : ' . getRandomString('AZ$') . '<br />';
	// echo '소문자+대문자+특수문자 : ' . getRandomString('azAZ$') . '<br />';
	// echo '소문자+대문자+숫자+특수문자 : ' . getRandomString('azAZ09$') . '<br />';
	//	임의(Random)의 문자열 생성 하는 함수
	//	함수 부분 =========================================================================================================

	/**
	 * @date		2019-09-14
	 * @author		star
	 * @details		두 날짜 시간 계산
	 * 기본 타입 : date1	=	date('Y-m-d H:i:s')
	 */
	function dateDiff2($date1, $date2){
		$date1_time				=	strtotime($date1);
		$date2_time				=	strtotime($date2);

		$date1_date				=	floor($date1_time / 86400);
    	$date2_date				=	floor($date2_date / 86400);

    	$datetimediff 			=	$date1_time - $date2_time;
    	$datedist 				=	$date1_date - $date2_date;
    	$datediff				=	floor($datetimediff / 86400);
    	$weekdiff				=	floor($datediff / 7);
    	$timediff				=	$datetimediff % 86400;

    	$hour					=	floor($timediff / 3600);
    	$min					=	floor($timediff % 3600 / 60);
		$sec					=	floor($timediff % 3600 % 60);

		$data					=	array(
			'date'					=>	$datediff,
			'hour'					=>	$hour,
			'min'					=>	$min,
			'sec'					=>	$sec
		);
		return $data;

	}
	//	날짜 차이 계산	=====================================================

	// 

	// 배열 내 str 값 기준으로 오름차순으로 정렬한다 
	// $result = arr_sort( $arr, 'str' , 'asc' ); 
	// 배열 내 num 값 기준으로 내림차순으로 정렬한다 
	// $result = arr_sort( $arr,'num', 'desc' );
	function arr_sort( $array, $key, $sort ){ 
		$keys = array();
		$vals = array(); 
		foreach( $array as $k=>$v ){ 
			$v["relevancy"]	= (int)$v["relevancy"];

			$i = $v[$key].'.'.$k; 
			
			$vals[$i] = $v; 
			array_push($keys, $k); 
		}
		
		unset($array); 
		if( $sort=='asc' ){ 
			ksort($vals); 
		}else{ 
			krsort($vals); 
		} 
		
		$ret = array_combine( $keys, $vals ); 
		unset($keys); 
		unset($vals);
		return $ret; 
	}

/**
 * debugPDO
 *
 * Shows the emulated SQL query in a PDO statement. What it does is just extremely simple, but powerful:
 * It combines the raw query and the placeholders. For sure not really perfect (as PDO is more complex than just
 * combining raw query and arguments), but it does the job.
 *
 * @author Panique
 * @param string $raw_sql
 * @param array $parameters
 * @return string
 */
// static public function debugPDO($raw_sql, $parameters) {
//
//     $keys = array();
//     $values = $parameters;
//
//     foreach ($parameters as $key => $value) {
//
//         // check if named parameters (':param') or anonymous parameters ('?') are used
//         if (is_string($key)) {
//             $keys[] = '/' . $key . '/';
//         } else {
//             $keys[] = '/[?]/';
//         }
//
//         // bring parameter into human-readable format
//         if (is_string($value)) {
//             $values[$key] = "'" . $value . "'";
//         } elseif (is_array($value)) {
//             $values[$key] = implode(',', $value);
//         } elseif (is_null($value)) {
//             $values[$key] = 'NULL';
//         }
//     }
//
//     /*
//     echo "<br> [DEBUG] Keys:<pre>";
//     print_r($keys);
//
//     echo "\n[DEBUG] Values: ";
//     print_r($values);
//     echo "</pre>";
//     */
//
//     $raw_sql = preg_replace($keys, $values, $raw_sql, 1, $count);
//
//     return $raw_sql;
// }


}
