<?php
namespace APP\Controller;

use APP\Core\Controller;
use APP\Model\HomeService;

class HomeController extends Controller
{
    //디폴트 페이지
    public function index()
    {
       /*  require APP . 'view/_templates/header.php';
        require APP . 'view/home/index.php';
		require APP . 'view/_templates/footer.php'; */

		/* $this->view('view/home/header'); */
        $HomeService	=	new HomeService();
        $list           =   $HomeService->get_boardList();

        print_r($list);

		$this->header();
		$this->view('view/home/index');
		$this->footer();
		//$this->view('view/_templates/footer');
    }

	//게시판 메인
    public function list($pno = 1)
    {
		$HomeService		=	new HomeService();

		$search				=	array(
			'isUse'				=>	1,
			'pno'				=>	$pno,
			'order'				=>	'bl.boardIdx DESC'
		);
		$result				=	$HomeService->get_boardList('', '', '', $search);


        // load views
        require APP . 'view/_templates/header.php';
        require APP . 'view/home/example_one.php';
        require APP . 'view/_templates/footer.php';
	}

	//게시판 리스트 POST 형식
	public function post_list()
    {
		$HomeService		=	new HomeService();
		$result				=	new BoardResult();

		$pno				=	intval($_POST['pno']);

		try{
			$search			=	array(
				'pno'			=>	$pno
			);
			$result			=	$HomeService->get_boardList($search);
		}
		catch (Exception $e){
			//게시판 조회 실패
			$result.setErrCd(-1);
		}

		$result				=	new BoardResult();

		echo json_encode($result);

    }


    public function exampleTwo()
    {
        require APP . 'view/_templates/header.php';
        require APP . 'view/home/example_two.php';
        require APP . 'view/_templates/footer.php';
	}


}
