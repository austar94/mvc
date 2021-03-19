<?php
namespace APP\Controller;
if (!defined('_STAR_')) exit;

use APP\Core\Controller;
use APP\Libs\Helper;
use APP\Dto\Member;
use APP\Libs\Pagination;

class HomeController extends Controller
{
	public function __construct()
	{

	}

	public function index() {
		$this->head();
		$this->view('/main');
	}
}
