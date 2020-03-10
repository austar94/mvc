<?php

namespace APP\Libs;

class Helper
{
    //	remove AllTags
	function allTags($data)
	{
		if ($data) {
			if(is_array($data)) {
				foreach($data as $key => $value){
					$data[$key]			=	strip_tags(addslashes(trim($value)));
				}
				return $data;
			} else {
				return strip_tags(addslashes(trim($data)));
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
