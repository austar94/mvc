<?php
namespace APP\Controller;

use APP\Core\MonoLog;
use APP\Libs\Helper;

class ErrorController
{
    /**
     * 오류 형태에 따라 분류
     * @param  array  $e [description]
     * @return [type]    [description]
     */
    public function index($e = [])
    {
        //오류 저장
        if(SYS_DEBUG){
            $MonoLog				=	new MonoLog();
            $MonoLog->log_info('==============================');
            $MonoLog->log_info('error 발생');
            $MonoLog->log_info('error Code : ' . $e->getCode());
            $MonoLog->log_info('error Message : ' . $e->getMessage());
            $MonoLog->log_info('error File : ' . $e->getFile());
            $MonoLog->log_info('error Line : ' . $e->getLine());
            $MonoLog->log_info('error Trace : ', $e->getTrace());
            $MonoLog->log_info('error Previous : ' . $e->getPrevious());
            $MonoLog->log_info('error TraceAsString : ' . $e->getTraceAsString());
        }

        if(REQUEST_METHOD == 'POST'){
            $this->post_error($e);
        } else {
            $this->get_errer($e);
        }
    }

    /**
     * get 오류시 page 반환
     * @param  array  $e [description]
     * @return [type]    [description]
     */
    public function get_errer($e = []){
        $Helper			=	new Helper();

        switch ($e->getCode()) {
            //401 : 권한없음
            case '401':
                //로그인페이지로 이동
                $Helper->goPage('/auth', $e->getMessage());
            break;
            //404 : 해당 페이지 없음
            case '404':
                require APP . 'view/_templates/header.php';
                require APP . 'view/error/index.php';
                require APP . 'view/_templates/footer.php';
                break;
            default:
                require APP . 'view/_templates/header.php';
                require APP . 'view/error/index.php';
                require APP . 'view/_templates/footer.php';
            break;
        }
    }

    /**
     * post 오류시 json 형태로 반환
     * @param  array  $e [description]
     * @return [type]    [description]
     */
    public function post_error($e = []){
        $data               =   array(
            'errMsg'            =>  '요청하신 페이지를 찾을 수 없습니다.',
            'errCode'           =>  '404'
        );
        echo json_encode($data);
        exit;
    }
}
