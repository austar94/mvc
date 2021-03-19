<?php
namespace APP\Libs;

/**
 * 참고 : https://github.com/jasongrimes/php-paginator/blob/master/src/JasonGrimes/Paginator.php
 */
class Pagination
{
	const NUM_PLACEHOLDER = '(:num)';	//page 숫자가 들어갈 곳

	protected $recordPerPage;			//한 페이지당 최대 게시글 개수
	protected $pnoPerPage;				//한 페이지당 최대 페이지번호
	protected $pno;						//현재페이지
	protected $totalCount;				//전체 게시글 개수
	protected $numPages;				//페이지 갯수
	protected $urlPattern;
	protected $patternType;
	protected $urlParam;

	public function __construct($recordPerPage, $pnoPerPage, $pno = 1, $totalCount, $urlPattern = 'goPage((:num))', $patternType = 'script', $arr_data = [])
    {

        $this->recordPerPage	=	$recordPerPage;
        $this->pnoPerPage		=	$pnoPerPage;
        $this->pno				=	$pno;
        $this->totalCount		=	$totalCount;
		$this->urlPattern		=	$urlPattern;
		$this->patternType		=	$patternType;

		$this->set_paramData($arr_data);
    }

	//파라미터 데이터 정리
	public function set_paramData($arr_data){
		$this->urlParam			=	'';

		if($arr_data){
			foreach ($arr_data as $key => $value) {
				if($key == 'url' || $key == 'pno') continue;
				$this->urlParam		.=	'&' . $key . '=' . $value;
			}
		}
	}

	public function set_page(){
		$pnoCount			=	ceil($this->totalCount / $this->recordPerPage);
		$temp				=	0;
		$ppno				=	1;

		while(1){
			$temp				+=	$this->pnoPerPage;
			if ($temp >= $this->pno) break;
			$ppno++;
		}

		$page				=	array();
		$page['pno']		=	$this->pno;
		$page['ppno']		=	$ppno;
		$page['maxPpno']	=	ceil($pnoCount / $this->pnoPerPage);
		$page['spno']		=	($ppno - 1) * $this->pnoPerPage + 1;
		$page['epno']		=	$ppno * $this->pnoPerPage;
		$page['sno']		=	$this->totalCount - (($this->pno - 1) * $this->recordPerPage);
		$page['pnoCount']	=	$pnoCount;

		if ($page['epno'] > $pnoCount) $page['epno'] = $pnoCount;

		return $page;
	}

	public function setPaging(){
		$page				=	$this->set_page();
		
		$page_str			=	'<ul>';

		if($page['ppno'] > 1){
			//현재블럭이 첫번째 블럭을 넘어섰으면 1페이지로가는 단축 제공
			//$page_str			.=	'<a href="' . htmlspecialchars($this->getPageUrl(1)) . '" class="pagingBtn first"></a>';

			//현재블록이 첫블럭을 넘었으면 이전 블럭으로 가는 단축
			$page_str			.=	'<li class="prev arrow">';
			$page_str			.=	'	<a href="' . htmlspecialchars($this->getPageUrl($page['spno'] - 1)) . '"></a>';
			$page_str			.=	'</li>';
		}

		//중간 페이지
		for($i = $page['spno']; $i <= $page['epno']; $i++){
			if($i == $this->pno){
				$page_str		.=	'<li><a href="#none" class="num current">'.$i.'</a></li>';
			} else {
				$page_str		.=	'<li><a href="' . htmlspecialchars($this->getPageUrl($i)) . '" class="num">'.$i.'</a></li>';
			}
		}

		if($page['ppno'] != $page['maxPpno'] && $page['pno'] && $this->totalCount){
			//현재 이후의 블럭이 있을 경우 이후 블럭으로 이동하는 단축
			$page_str		.=	'<li class="next arrow">';
			$page_str		.=	'	<a href="' . htmlspecialchars($this->getPageUrl($page['epno'] + 1)) . '"></a>';
			$page_str		.=	'</li>';

			//마지막 블럭값으로 이동
			//$page_str			.=	'<a href="' . htmlspecialchars($this->getPageUrl($page['pnoCount'])) . '" class="pagingBtn last"></a>';
		}

		$page_str		.=	'</ul>';

		return $page_str;
	}

	/**
	 * 이동 페이지 구성
	 * @param  [type] $pageNum [description]
	 * @return [type]          [description]
	 */
	public function getPageUrl($pageNum)
  	{
		$action			=	$this->patternType == 'script' ? 'javascript:' : '';

	    if($this->patternType == 'script'){
	        $url		=	$action . str_replace(self::NUM_PLACEHOLDER, $pageNum, $this->urlPattern);
	    } else {
	        $url		=	$action . $this->urlPattern . '?pno=' . $pageNum . $this->urlParam;
	    }

		return $url;
	}



	// public function __toString()
    // {
    //     return $this->setPaging();
    // }

}
