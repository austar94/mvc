<?php
namespace APP\Controller;

use APP\Core\Controller;
use APP\Model\HomeService;
use APP\Libs\Helper;

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
//         $Helper             =   new Helper();
//         $public_key          =   '-----BEGIN PUBLIC KEY-----
// MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDJHnV6fZUJBxYmg/xgAOKBAg30
// 9AGHJFc1x1U6ufIe7eGToUII+rGmNQ72L+UoJqgecBJdP2YGrYv//hi5rzZQUBAN
// 3QsuTV0Sks8Ift/465fqgy6N0VyRGwssxTwvrSpw5hcEjRddUYhMRtRK0e4HC1pP
// WfjuL8tR1SmmJ015VQIDAQAB
// -----END PUBLIC KEY-----';
//
//         $_rawBody			=	file_get_contents("php://input");
//         $arr_data			=	json_decode($_rawBody);								//받은 데이터를 array로 변수에 넣음
//         $arr_data			=	json_decode(json_encode($arr_data), True);
//
//         $id                 =   '';
//         $pw                 =   '';
//         if($arr_data['id']){
//             $id             =   $Helper->rsa_encrypt($arr_data['id'], $public_key);
//         }
//
//         if($arr_data['pw']){
//             $pw             =   $Helper->rsa_encrypt($arr_data['pw'], $public_key);
//         }
//
//         $data               =   array(
//             'id'                =>  $id,
//             'pw'                =>  $pw
//         );
//         print_r(json_encode($data, JSON_UNESCAPED_UNICODE));
// 		exit;
        // require APP . 'view/_templates/header.php';
        // require APP . 'view/home/index.php';
        // require APP . 'view/_templates/footer.php';
        $this->header();
		$this->view('/home/index', $search);
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
        // require APP . 'view/_templates/header.php';
        // require APP . 'view/home/example_two.php';
        // require APP . 'view/_templates/footer.php';
        $this->header();
		$this->view('/home/example_two');
		$this->footer();
	}


}
