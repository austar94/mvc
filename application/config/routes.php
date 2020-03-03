<?php
namespace APP\config;

class reoutes
{
	public function get_exRoute(){
		//예외 route 규칙 작성
		/**
		 * 예외 사항 작성 규칙
		 * @var array
		 *
		 * $ex_route['bbs/notice/(:num)']		=	'board/notice/$i';
		 * mvc.heeyam.com/bbs/notice/3			=>	application/Controller/board/notice.php function index $_params['num'] = 3
		 *
		 * $ex_route['api/onetoss/{func}/{orderNum}']		=	'api/onetoss';
		 * mvc/heeyam.com/api/onetoss/order/44869ssfew		=>	application/Contoller/api/onetoss.php function order $_parmas['orderNum'] = 44869ssfew
		 *
		 *
		 * $ex_route['api/heeyam/order/menu']				=	'api/heeyam/order/menu';
		 * mvc.heeyam.com/api/heeyam/order/menu				=	application/Controller/api/heeyam/order.php function menu
		 *
		 * $ex_route['api/heeyam/bread/list']				=	'api/heeyam/bread/list';
		 * mvc.heeyam.com/api/heeyam/bread/list				=>	application/Conroller/api/heeyam/bread/list.php function index
		 */
		$ex_route										=	[];
		$ex_route['bbs/notice']							=	'board/notice';
		$ex_route['api/onetoss/{func}']					=	'api/onetoss';
		$ex_route['api/heeyam/order/{func}']			=	'api/heeyam/order';
		$ex_route['api/heeyam/bread/list']				=	'api/heeyam/bread/list';

		return $ex_route;
	}
