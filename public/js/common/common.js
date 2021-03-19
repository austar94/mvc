function returnNum(str){
    str = str.toString().replace(/[^0-9]/g,'');                           //문자열중 숫자만 추출
    return Number(str.replace(/^\s*/ ,"").replace(/\s*$/ ,""));         //공백제거
}

/**
 * 입력확인
 * @param {} frm 
 * @param {*} input 
 * @param {*} type 
 */
function check_input(frm, input, type){
	if(!input || !frm) return false;
	if(!type) type = "text";

	if(type == "text"){
		// 해당 frm 안의 tipBox 모두 삭제
		$(frm).find(".tipBox").remove();

		for(var i = 0; i < input.length; i++){

			if(!$(frm).find("input[name="+input[i][0]+"]").val().trim()){
				$(frm).find("input[name="+input[i][0]+"]").focus();
				if(input[i][1]) $(frm).find("input[name="+input[i][0]+"]").after('<div class="tipBox">* '+input[i][1]+'은(는) 필수 입력사항입니다.</div>');
				return false;
			}

			// 입력 제한 숫자
			if(input[i][2]){
				if($(frm).find("input[name="+input[i][0]+"]").val().length < input[i][2]){
					$(frm).find("input[name="+input[i][0]+"]").focus();
					if(input[i][2]) $(frm).find("input[name="+input[i][0]+"]").after('<div class="tipBox">* '+input[i][2]+'자 이상 입력해주세요.</div>');
					return false;
				};
			}
		}
		return true;
	}
	return false;
}

// 가이드박스
function set_guideBox(frm, target, msg, type, removeType = 0){
	if(!removeType) $(frm).find(".tipBox").remove();
	if(msg) $(frm).find(target).after('<div class="tipBox">* '+msg+'</div>')
	if(type == 1) $(frm).find(target).focus();
}

// 빈 리스트
function set_emptyList(colspan, msg){
	let list_str	=	"";
	list_str        +=  '<tr id="emptyList">';
    list_str        +=  '   <td colspan="'+colspan+'">';
    list_str        +=  '       <div class="emtyAlarm">'+msg+'</div>';
    list_str        +=  '   </td>';
	list_str        +=  '</tr>';
	return list_str;
}

// 파라미터에 맞는 url형식 리턴
function set_urlParam(pno, params){
	if(!pno) return "";
	let url			=	"";

	url				+=	"?pno=" + pno;

	if(params){
		for(var key in params){
			url		+=	"&" + key + "=" + params[key];
		}
	}

	return url;
}

function isEmpty(value){
	if( value == "" || value == null || value == undefined || ( value != null && typeof value == "object" && !Object.keys(value).length ) ){
		 return true;
	}
	else{ 
		return false;
	} 
};

// 스페이스 확인
function checkSpace(str) {
	if (str.search(/\s/) != -1) {
		return true;
	} else {
		return false;
	}
}


// 알파벳숫자만 입력
$(document).on('keyup', '.onlyAlphabetNum', function(event){
	var inputVal = $(this).val();
	$(this).val(inputVal.replace(/[^0-9a-zA-Z]/gi, ''));
});

// 비밀번호 유효성 확인
function isValidPasswd(str) {
    var cnt = 0;
    if (str == "") {
        return false;
    }

    /* check whether input value is included space or not */
    var retVal = checkSpace(str);
    if (retVal) {
        return false;
    }
    if (str.length < 8) {
        return false;
    }
    for (var i = 0; i < str.length; ++i) {
        if (str.charAt(0) == str.substring(i, i + 1))
            ++cnt;
    }
    if (cnt == str.length) {
        return false;
    }

    var isPW = /^[A-Za-z0-9`\-=\\\[\];',\./~!@#\$%\^&\*\(\)_\+|\{\}:"<>\?]{8,16}$/;
    if (!isPW.test(str)) {
        return false;
    }
    return true;
}

// 이름 유효성
function checkName(str) {
	var nonchar = /[^가-힣A-Za-z]/gi;

	if (str != "" && nonchar.test(str)) {
		return false;
	}

	return true;
}

// 핸드폰 번호 유효성 검사
function isCellPhone(p) {
	var regPhone = /^((01[1|6|7|8|9])[1-9][0-9]{6,7})$|(010[1-9][0-9]{7})$/;
	return regPhone.test(p);
}

/**
 * url파라미터를 이용한 값 입력
 */
function set_param(urlParam){
	if(!urlParam) urlParam = getUrlParams();
	//var urlParam				=	getUrlParams();
	if(!urlParam) return;

    //값 입력
	for(var key in urlParam){
		//input별 입력

		// 예외 조건
		if(key == "arr_keyword"){
			// 해당하는 내용이 존재할 경우.
			if(urlParam[key]){
				$(".keyword-list a").removeClass("on");

				// 콤마 기준으로 내용을 배열로 변환
				let arr_key		=	urlParam[key].split(",");
				for(i = 0; i < arr_key.length; i++){
					let item			=	arr_key[i];

					if(item){
						var dec = decodeURI(item);
						
						$(".keyword-list a[data-keyword='"+dec+"']").addClass("on");
					}
				}
			} else {
				$(".keyword-list a:not(0)").removeClass("on");
				$(".keyword-list a:eq(0)").addClass("on");
			}
			continue;
		}

		if($('input[name="'+key+'"]').length){
            var input           =   $('input[name="'+key+'"]');
            var inputType       =   $(input).attr('type');
            var value           =   decodeURI(urlParam[key]);
            //체크박스
            if(inputType == 'checkbox'){
				if(value){
					$('input[name="'+key+'"][value="'+value+'"]').prop('checked', true);
				} else {
					$('input[name="'+key+'"]').prop('checked', false);
				}
            }
            //라디오
            else if(inputType == 'radio'){
                $(input).prop('checked', true);
            }
            //그 외
            else {
                $(input).val(value);
            }
		}
        //select 입력
        else if($('select[name="'+key+'"]').length){
            $('select[name="'+key+'"]').val(decodeURI(urlParam[key]));
        }
	}
}

function set_objectToUrlForm(params){
    if(!params) return '';
    var urlForm         =   '';
    for(var i = 0; i < params.length; i++){
        var param           =   params[i];

        urlForm         +=  '&' + param['name'] + '=' + param['value'];
    }
    return urlForm;
}

/**
 * url 파라미터 가져오기
 * @return {[type]} [description]
 */
function getUrlParams() {
    var params = {};
    window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(str, key, value) { params[key] = value; });
    return params;
}

/**
 * 전체 선택
 * 해당 체크박스가 속한 테이블의 하위 바디에 존재하는 모든 체크박스 선택 및 해제
 */
$(document).on('change', '#allCheck, .allCheck', function(){
	let table			=	$(this).closest('table');
	let tbody			=	table.find('tbody');

	if($(this).prop('checked')){
		tbody.find('input[type="checkbox"]').prop("checked",true);
	} else {
		tbody.find('input[type="checkbox"]').prop("checked",false);
	}
})

/**
 * 브라우저별 즐겨찾기 추가
 * @param {Array} e 버튼
 */
function addBoookMark(e){
	var bookmarkURL = window.location.href;
    var bookmarkTitle = document.title;
    var triggerDefault = false;

    if (window.sidebar && window.sidebar.addPanel) {
        // Firefox version < 23
        window.sidebar.addPanel(bookmarkTitle, bookmarkURL, '');
    } else if ((window.sidebar && (navigator.userAgent.toLowerCase().indexOf('firefox') > -1)) || (window.opera && window.print)) {
        // Firefox version >= 23 and Opera Hotlist
        var $this = $(this);
        $this.attr('href', bookmarkURL);
        $this.attr('title', bookmarkTitle);
        $this.attr('rel', 'sidebar');
        $this.off(e);
        triggerDefault = true;
    } else if (window.external && ('AddFavorite' in window.external)) {
        // IE Favorite
        window.external.AddFavorite(bookmarkURL, bookmarkTitle);
    } else {
        // WebKit - Safari/Chrome
        alert((navigator.userAgent.toLowerCase().indexOf('mac') != -1 ? 'Cmd' : 'Ctrl') + '+D 키를 눌러 즐겨찾기에 등록하실 수 있습니다.');
    }

    return triggerDefault;
}
/**
 * 페이징
 * @param {int} recordPerPage 		//한 페이지당 최대 게시글 개수
 * @param {int} pnoPerPage 			//한 페이지당 최대 페이지번호
 * @param {int} pno 				//현재 페이지
 * @param {int} totalCount 			//전체 게시물
 * @param {string} target
 */
function setPaging(recordPerPage, pnoPerPage, pno, totalCount){
	pno = pno ? pno : 1;
	let total_page			=	Math.ceil(totalCount / recordPerPage);		//총 게시물 수
	let total_block			=	Math.ceil(total_page / pnoPerPage);
	let now_block			=	Math.ceil(pno / pnoPerPage);
	let first_page			=	((now_block - 1) * pnoPerPage) + 1;
	let last_page			=	Math.min(total_page, now_block * pnoPerPage);
	let prev_page			=	pno - 1;
	let next_page			=	pno + 1;
	let prev_block			=	now_block - 1;
	let next_block			=	now_block + 1;
	let prev_block_page		=	prev_block * pnoPerPage;
	let next_block_page		=	next_block * pnoPerPage - (pnoPerPage - 1);
	let str					=	'';

	if(prev_block > 0){
		str					+=	'<a href="javascript:movePage('+prev_block_page+')" class="pagingBtn first"></a>';
	}
	if(prev_page > 0){
		str					+=	'<a href="javascript:movePage('+prev_page+')" class="pagingBtn prev"></a>';
	}
	for(i = first_page; i <= last_page; i++){
		if(i == pno){
			str				+=	'<a href="javascript:;" class="pagingBtn on">'+i+'</a>';
		} else {
			str				+=	'<a href="javascript:movePage('+i+')" class="pagingBtn">'+i+'</a>';
		}
	}
	if(next_page <= total_page){
		str					+=	'<a href="javascript:movePage('+next_page+')" class="pagingBtn next"></a>';
	}
	if(next_block <= total_block){
		str					+=	'<a href="javascript:movePage('+next_block_page+')" class="pagingBtn last"></a>';
	}
	$('.pagingBox').html(str);
}

/*
function countDown(hrs, min, sec, func, main) {
	sec--;
	console.log(sec);
	if (sec == -01) {
		sec				=	59;
		min				=	min - 1;
	}
	else {
		min				=	min;
	}

	if (min == -01) {
		min				=	59;
		hrs				=	hrs - 1;
	}
	else {
		hrs				=	hrs;
	}

	if (sec<=9) {
		sec				=	"0" + sec;
	}

	if (hrs<=9) {
		hrs 			=	"0" + hrs;
	}

	time				=	hrs + ":" + (min<=9 ? "0" + min : min) + ":" + sec + "";


	main					=	window.setTimeout("countDown("+hrs+","+min+","+sec+", "+func+");", 1000);
	console.log(main);
	if (hrs == '00' && min == '00' && sec == '00') {
		sec = "00";
		window.clearTimeout(main);
		func();
	}
}
 */

/**
 *
 * @param name
 * @param value
 * @param days
 * @returns
 */
function setCookie(name,value,days) {
	var expires = "";
	if (days) {
		var date = new Date();
		date.setTime(date.getTime() + (days*24*60*60*1000));
		expires = "; expires=" + date.toUTCString();
	}
	document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

/**
 *
 * @param name
 * @returns
 */
function getCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

/**
 *	셀렉트박스 선택 값 전달
 * @param e
 */
function sboxSelect(e){
	var value			=	$(e).data('value');
	var target			=	$(e).data('target');

	$('#'+target).val(value);
}

/**
 * trim 함수
 *
 * @param val
 * @returns
 */
function trim(val){
	return val.replace(/(^\s*)|(\s*$)/gi, "");
}

/**
 * textarea 데이터: \n -> <br />
 * @param textAreaData
 * @returns
 */
function nlToBr(textAreaData) {
	if(textAreaData != undefined && textAreaData.length > 0) {
		return textAreaData.replace(/\n/gi, "<br />");
	}
}


/**
 * 날짜 포멧으로 변환(yyyy-MM-dd)
 *
 * @param str
 * @returns
 */
function setDateFormat(str) {
	if(str != null && str.length == 8) {
		return str.substring(0, 4) + "-" + str.substring(4, 6) + "-" + str.substring(6);
	}
	return isNullChangeStr(str,'');
}


/**
 * 금액 포멧에서 ','제거
 *
 * @param str
 * @returns
 */
function removeCommas(str) {
	return str.replace(/,/gi, "");
}

/**
 * 금액 포멧에서 '-'제거
 *
 * @param str
 * @returns
 */
function removeHyphen(str) {
	return str.replace(/-/gi, "");
}

/**
 * 숫자 세자리 단위 마다 콤마
 *
 * @param x
 * @returns
 */
function numberWithCommas(x) {

	if( undefined == x || null == x || '' == x) return x;

	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

/**
 * 숫자 세자리 단위 마타 콤마 (KeyPress)
 * onkeyup="setNumberWithCommasKeyUp(this)"/
 */
function setNumberWithCommasKeyUp(obj) {
	obj.value = numberWithCommas(removeCommas(obj.value)); //콤마 찍기
}

function checkIP(strIP) {
	var expUrl = /^(1|2)?\d?\d([.](1|2)?\d?\d){3}$/;
	return expUrl.test(strIP);
}

function emailCheck(email) {
	var patten = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;

	if (!patten.test(email)) {
		return false;
	}
	return true;
}


/**
 * when ajax call error
 */
function doAjaxError(err){
	remove_loading();
	doubleSubmitFlag    =   false;
	isEcxelUpload		=	1;
	isAllUpload			=	1;
	// alert("error = " + err.status);
	alert("작업이 정상적으로 완료되지 않았습니다. \n잠시 후 다시 시도해주세요.");
}

function set_loading(){
	$('.container').addClass('overlay loadingSpinner');
}

function remove_loading(){
	// 팝업이 나타나있을 경우 해당 removeClass 실행 안시킴
	if($('.popup').is(':visible')){
		return false;
	}

	$('.container').removeClass('overlay loadingSpinner');
}

/**
 * 중복서브밋 방지
 *
 * @returns {Boolean}
 */
var doubleSubmitFlag = false;
function doubleSubmitCheck(){
	if(doubleSubmitFlag){
		return doubleSubmitFlag;
	}else{
		doubleSubmitFlag = true;
		return false;
	}
}

/**
 * 전화번호 포맷 변경
 * @param num
 * @returns
 */
function phone_format(num) {
	if(null == num){
		return "";
	}
	var formatNum = '';

	if(num.length==11){
		formatNum = num.replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
	}else if(num.length==8){
		formatNum = num.replace(/(\d{4})(\d{4})/, '$1-$2');
	}else{
		if(num.indexOf('02')==0){
			if(num.length==9){
				formatNum = num.replace(/(\d{2})(\d{3})(\d{4})/, '$1-$2-$3');
			}
			else{
				formatNum = num.replace(/(\d{2})(\d{4})(\d{4})/, '$1-$2-$3');
			}
		}else{
			formatNum = num.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
		}
	}
	return formatNum;
	//return num.replace(/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/,"$1-$2-$3");
}


/**
 * 핸드폰 형식 확인
 * @param {string} phoneNum
 */
function isPhone(phoneNum) {
	var regExp =/(01[016789])([1-9]{1}[0-9]{2,3})([0-9]{4})$/;

	var myArray;
	if(regExp.test(phoneNum)){
		return true;
	} else {
		return false;
	}
}


/**
 * 숫자만 입력
 * onkeydown="return setNumberOnKeyDown(event)"/
 * class="ime-mode" 필수
 */
function setNumberOnKeyDown(evt) {
	var code = evt.which ? evt.which : event.keyCode;

	//Backspace || Delete || Tab || ESC || Enter || F5
	if (code == 46 || code == 8 || code == 9 || code == 27 || code == 13 || code == 116
			// Ctrl + A , C , X , V
			|| (evt.ctrlKey === true && (code == 65 || code == 67 || code == 86 || code == 88))
			// PageUp ~ ArrowKey
			|| (code >= 33 && code <= 39)
			// 0 ~ 9 || KeyPad 0 ~ 9
			|| (code >= 48 && code <= 57) || (code >= 96 && code <= 105)) {
		return;
	}

	return false;
}


/**
 * 숫자 및 [-]
 * onkeydown="return setMinusNumberOnKeyDown(event)"/
 * @param evt
 * @returns {Boolean}
 */
function setMinusNumberOnKeyDown(evt){

	var code = evt.which ? evt.which : event.keyCode;

	//Backspace || Delete || Tab || ESC || Enter || F5
	if (code == 46 || code == 8 || code == 9 || code == 27 || code == 13 || code == 116
			// Ctrl + A , C , X , V
			|| (evt.ctrlKey === true && (code == 65 || code == 67 || code == 86 || code == 88))
			// PageUp ~ ArrowKey
			|| (code >= 33 && code <= 39) || code == 109 || code == 189
			// 0 ~ 9 || KeyPad 0 ~ 9
			|| (code >= 48 && code <= 57) || (code >= 96 && code <= 105)) {

		return;
	}

	return false;
}

/**
 * 숫자 및 [.]
 * onkeydown="return setPeriodNumberOnKeyDown(event)"/
 * class="ime-mode" 필수
 * @param evt
 * @returns {Boolean}
 */
function setPeriodNumberOnKeyDown(evt){
	var code = evt.which ? evt.which : event.keyCode;

	//Backspace || Delete || Tab || ESC || Enter || F5
	if (code == 46 || code == 8 || code == 9 || code == 27 || code == 13 || code == 116
			// Ctrl + A , C , X , V
			|| (evt.ctrlKey === true && (code == 65 || code == 67 || code == 86 || code == 88))
			// PageUp ~ ArrowKey
			|| (code >= 33 && code <= 39) || code == 110 || code == 190
			// 0 ~ 9 || KeyPad 0 ~ 9
			|| (code >= 48 && code <= 57) || (code >= 96 && code <= 105)) {
		return;
	}

	return false;
}


/**
 * 영어와 숫자만 입력
 * onkeydown="return setEngNumOnKeyDown(event)"/
 * @param evt
 * @returns {Boolean}
 */
function setEngNumOnKeyDown(evt)
{
	var code = evt.which ? evt.which : event.keyCode;

	//Backspace || Delete || Tab || ESC || Enter || F5
	if (code == 46 || code == 8 || code == 9 || code == 27 || code == 13 || code == 116
			// Ctrl + A , C , X , V
			|| (evt.ctrlKey === true && (code == 65 || code == 67 || code == 86 || code == 88))
			// PageUp ~ ArrowKey
			|| (code >= 33 && code <= 39) || code == 109 || code == 189
			// 0 ~ 9 || KeyPad 0 ~ 9
			|| (code >= 48 && code <= 57) || (code >= 96 && code <= 105)
			|| (code >= 65 && code <= 90) || (code >= 97 && code <= 122)) {
		return;
	}

	return false;
}


/**
 * 텍스트 바이트 가져오기
 * @param s
 * @returns {Number}
 */
function getByteLength(s) {

	if (s == null || s.length == 0) {
		return 0;
	}
	var size = 0;

	for ( var i = 0; i < s.length; i++) {

		if (escape(s.charAt(i)).length > 4) {
			size += 2;
		} else {
			size++;
		}
	}

	return size;
}


/**
 * 텍스트 최대바이트 초과시 substring
 * @param s
 * @param len
 * @returns
 */
function cutByteLength(s, len) {

	if (s == null || s.length == 0) {
		return 0;
	}
	var size = 0;
	var index = s.length;

	for ( var i = 0; i < s.length; i++) {
		if (escape(s.charAt(i)).length > 4) {
			size += 2;
		} else {
			size++;
		}
		if( size == len ) {
			index = i + 1;
			break;
		} else if( size > len ) {
			index = i;
			break;
		}
	}

	return s.substring(0, index);
}

/**
 * 텍스트 최대바이트 초과 키업 이벤트
 * @param obj
 * @param size
 */
function setByteCheckKeyUp(obj, size) {
	var str = new String(obj.val());
	var byte = getByteLength(str);

	if (size < byte) {
		obj.blur();
		obj.val(cutByteLength(str, size));
		obj.focus();
		//setByteCheckKeyUp(obj, size);
	}
}

/**
 * 지정자리 버림 (값, 자릿수)
 */
function Floor(n, pos) {
	var digits = Math.pow(10, pos);

	var num = Math.floor(n * digits) / digits;

//	return num.toFixed(pos);
	return num;
}
/**
 *지정자리 올림 (값, 자릿수)
 */
function Ceiling(n, pos) {
	var digits = Math.pow(10, pos);

	var num = Math.ceil(n * digits) / digits;

	return num;
}

function chkPercent(i) {
	var percent = parseFloat(i);

	if (NaN == percent || 99999 < percent) {
		return false;
	}
	return true;
}



/**
 * Call ajax
 */
function ajax(url, dataType, param, method, formType, gbn, callback, async, ingnoreDuble){
	//showLoading();

	if (undefined === async) {
		async = true;
	}

	if(!ingnoreDuble){
		if(doubleSubmitCheck()) return;
	}

	var ajaxData		= {
		type				:	method,
		url					:	url,
		dataType			:	dataType,
		data				:	param,
		async				:	async,
		success				:	function(data){
			// remove_loading();
			doubleSubmitFlag    =   false;
			isEcxelUpload		=	1;
			isAllUpload			=	1;
			
			//토큰 갱신
			if(data.token){
				$('input[name="token"]').val(data.token);
			}
			if(dataType == 'json'){
				if(method == "GET"){
					data		=	JSON.parse(data);
				}
				var errCd		=	data.errCd;
				var errMsg		=	data.errMsg;

				if(errCd == "-9999"){
					alert("세션이 종료되었습니다. 다시 로그인 하여 주시기 바랍니다.");
					location.href = '/';
					return;
				}

				if(errCd == "-9"){
					alert("에러가 발생하였습니다.\n관리자에게 문의하세요.");
					return;
				}

				if(errCd != 0) {
					remove_loading();
					var url		=	data.url;
					if(errMsg){
						alert(errMsg);
					}

					if(url){
						if(url == 1){
							location.reload();
						} else {
							location.href	=	url;
						}
					}
					return;
				}

				callback(data, errCd, errMsg);
			} else {
				callback(data);
			}
		},
		error   : doAjaxError
	};

	if(dataType == "json"){
		if(formType == 1){
			ajaxData.contentType			=	false;
			ajaxData.processData			=	false;
		} else {

		}
	} else if(dataType == 'html'){
		if(formType == 1){
			ajaxData.contentType			=	false;
			ajaxData.processData			=	false;
		} else {
			ajaxData.contentType			=	"application/x-www-form-urlencoded; charset=UTF-8";
		}
	}

	ajaxData.responseText;
	$.ajax(ajaxData);
}

/**
 * Call ajax service with POST
 * @param url
 * @param data
 * @param callback
 */
function postService(url, dataType, data, callback, formType, async, ingnoreDuble){
	ajax(url, dataType, data, "POST", formType, "", callback, async, ingnoreDuble);
}

/**
 * Call ajax service With GET
 *
 * @param url
 * @param callback
 */
//function getService(url , callback){
function getService(url, dataType, data, callback, formType, async, ingnoreDuble){
	//var data = new Object();

	//url = encodeURI(url);
	//ajax(url , data , "GET" , "" , callback);
	ajax(url, dataType, data, "GET", formType, "", callback, async, ingnoreDuble);
}


/**
 * 사업자 번호 포멧으로 변환(xxx-xx-xxxxx)
 * @param str
 * @returns
 */
function setBusiFormat(str) {
	if(str.length == 10) {
		return str.substring(0, 3) + "-" + str.substring(3, 5) + "-" + str.substring(5);
	}
	return str;
}

/**
 *
 * @param name
 * @param value
 * @param days
 * @returns
 */
function setCookie(name,value,days) {
	var expires = "";
	if (days) {
		var date = new Date();
		date.setTime(date.getTime() + (days*24*60*60*1000));
		expires = "; expires=" + date.toUTCString();
	}
	document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
/**
 *
 * @param name
 * @returns
 */
function getCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

/**
 * Get Paramter key 로 조회하여 value 를 반환
 * @param paramName
 * @returns
 */
function getParameters(paramName) {
	// 리턴값을 위한 변수 선언
	var returnValue;

	// 현재 URL 가져오기
	var url = location.href;

	// get 파라미터 값을 가져올 수 있는 ? 를 기점으로 slice 한 후 split 으로 나눔
	var parameters = (url.slice(url.indexOf('?') + 1, url.length)).split('&');

	// 나누어진 값의 비교를 통해 paramName 으로 요청된 데이터의 값만 return
	for (var i = 0; i < parameters.length; i++) {
		var varName = parameters[i].split('=')[0];
		if (varName.toUpperCase() == paramName.toUpperCase()) {
			returnValue = parameters[i].split('=')[1];
			return decodeURIComponent(returnValue);
		}
	}
};

// /**
//  * 로딩 이미지 표시
//  */
// function showLoading(){
// 	var winHeight = $(window).height();
// 	var winWidth = $(window).width();

// 	var top = (winHeight / 2) - 62;
// 	var left = (winWidth / 2) - 62;

// 	var loadingBar = "<div id=\"loading\" class=\"loadingWrap\">";
// 	loadingBar += "<div class=\"loading\" style=\"left: " + left +"px; top: " + top + "px; position: absolute; z-index:3000\">";
// 	loadingBar += "<img id=\"loading-image\" src=\"" + getContextPath() + "/assets/plugins/jquery-file-upload/img/loading.gif\" alt=\"Loading...\"/>";
// 	loadingBar += "</div>";
// 	loadingBar += "</div>";

// 	$(document.body).append(loadingBar);
// 	appendBackdrop();
// }

// /**
//  * 로딩 이미지 숨김
//  */
// function hideLoading(){
// 	$("#loading").remove();
// 	$("#_backdrop_").remove();
// }
