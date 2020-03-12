<?php
namespace APP\Controller;

use APP\Core\Controller;
use APP\Model\HomeService;
use APP\Libs\Helper;

class ApiController extends Controller
{
    public function __construct()
    {

	}

    //디폴트 페이지
    public function post_rsa()
    {
        $Helper             =   new Helper();
        $public_key          =   '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDJHnV6fZUJBxYmg/xgAOKBAg30
9AGHJFc1x1U6ufIe7eGToUII+rGmNQ72L+UoJqgecBJdP2YGrYv//hi5rzZQUBAN
3QsuTV0Sks8Ift/465fqgy6N0VyRGwssxTwvrSpw5hcEjRddUYhMRtRK0e4HC1pP
WfjuL8tR1SmmJ015VQIDAQAB
-----END PUBLIC KEY-----';

        $_rawBody			=	file_get_contents("php://input");
        $arr_data			=	json_decode($_rawBody);								//받은 데이터를 array로 변수에 넣음
        $arr_data			=	json_decode(json_encode($arr_data), True);

        $id                 =   '';
        $pw                 =   '';
        if($arr_data['id']){
            $id             =   $Helper->rsa_encrypt($arr_data['id'], $public_key);
        }

        if($arr_data['pw']){
            $pw             =   $Helper->rsa_encrypt($arr_data['pw'], $public_key);
        }

        $data               =   array(
            'id'                =>  $id,
            'pw'                =>  $pw
        );
        print_r(json_encode($data, JSON_UNESCAPED_UNICODE));
		exit;
    }
}
