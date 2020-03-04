<?php
namespace APP\Controller;

use APP\Core\Controller;
use APP\Model\HomeService;

class HomeController extends Controller
{
    //로그인이 필요한 페이지라면??
    public function __construct()
    {
        //path 체크
	}



    //디폴트 페이지
    public function index()
    {
        $HomeService	=	new HomeService();
        $search         =   array(
            'OrderSEQ'      =>  '20200227202628000125'
        );
        $msg            =   $HomeService->get_userList();

		$this->header();
		$this->view('view/home/index', $msg->get_data());
		$this->footer();
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
	public function post_get_userList()
    {
        $asdf   =   $this->post('ASDF');
        echo $asdf;

        $HomeService	=	new HomeService();
        $search         =   array(
            'OrderSEQ'      =>  '20200227202628000125'
        );
        $msg            =   $HomeService->get_userList();

		echo json_encode($msg->get_data());
    }


    public function exampleTwo()
    {
        require APP . 'view/_templates/header.php';
        require APP . 'view/home/example_two.php';
        require APP . 'view/_templates/footer.php';
	}


}
