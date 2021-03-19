<?php
namespace APP\Controller;

use APP\Core\Controller;
use APP\Core\MonoLog;
use APP\Libs\Helper;

/**
 * 에러코드에 대한 상세
 * https://ko.wikipedia.org/wiki/HTTP_%EC%83%81%ED%83%9C_%EC%BD%94%EB%93%9C
 */
class ErrorController extends Controller
{
    /**
     * 오류 형태에 따라 분류
     * @param  array  $e [description]
     * @return [type]    [description]
     */
    public function index($e = [])
    {
        //오류 저장
        // if(SYS_DEBUG){
        //     $MonoLog				=	new MonoLog();
        //     $MonoLog->log_info('==============================');
        //     $MonoLog->log_info('error 발생');
        //     $MonoLog->log_info('error Code : ' . $e->getCode());
        //     $MonoLog->log_info('error Message : ' . $e->getMessage());
        //     $MonoLog->log_info('error File : ' . $e->getFile());
        //     $MonoLog->log_info('error Line : ' . $e->getLine());
        //     $MonoLog->log_info('error Trace : ', $e->getTrace());
        //     $MonoLog->log_info('error Previous : ' . $e->getPrevious());
        //     $MonoLog->log_info('error TraceAsString : ' . $e->getTraceAsString());
        // }

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
            //401 : 로그인 안됨
            case '401':
                //로그인페이지로 이동
                $Helper->goPage('/', $e->getMessage());
            break;
            //403 : 유저 권한 부족
            //로그아웃 시켜버림
            case '403':
                $Helper->goPage('/home/logout', '해당 페이지를 접근할 수 있는 권한이 없습니다.');
                break;
            break;
            //404 : 해당 페이지 없음
            case '404':

                $data           =   array(
                    'errMsg'        =>  $e->getMessage()
                );

                $this->head();
                $this->view('/error/index', $data);
                $this->footer();
                break;
            case '405' :
                $Helper->goBack('잘못된 접근입니다.');
                break;
            //검증되지 않은 오류
            //로그아웃 시켜버림
            default:
                $Helper->goBack('해당 페이지를 접근할 수 없습니다.');
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


    // :: starting : ksg_20210214_2338 : [세무야] 404페이지.
	public function error() {
		$this->head();
		$this->view('/error/error');
	}

}
