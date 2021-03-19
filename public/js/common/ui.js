// $(window).on('scroll', function () {

// 	var _current = $(document).scrollTop();

// 	if (_current > 50) {
// 		$('header').addClass('active');
// 	} else {
// 		$('header').removeClass('active');
// 	}


// });


// 팝업 닫기
function closePop() {
	$('.regPop').hide();
	$('.container').removeClass('overlay');
}


$(function (){

	var _current = $(document).scrollTop();

	if (_current > 50) {
		$('header').addClass('active');
	} else {
		$('header').removeClass('active');
	}

})


// 모바일 메뉴 열기
function mobileMenuOpen() {
	$('.container').addClass('overlay')
	$('header').addClass('active');
	$('nav.mobile-menu').show("slide", {direction: "up"});

	scrollDisable();


}

$('.depth1 .depth1-txt').on('click',function (){
	$(this).toggleClass('on');
})

// 모바일 메뉴 닫기
function mobileMenuClose() {

	$('nav.mobile-menu').hide("slide", {direction: "up"});
	$('.container').removeClass('overlay');
	$('header').removeClass('active');

	scrollAble();

}



function scrollDisable(){
	$('html, body').addClass('scrollHidden').on('scroll touchmove mousewheel', function(e){
		e.preventDefault();
	});
}
function scrollAble(){
	$('html, body').removeClass('scrollHidden');
}

// 숫자만 입력
$(document).on('keyup', '.onlyNum', function(event){
	if (!(event.keyCode >=37 && event.keyCode<=40)) {
		var inputVal = $(this).val();
		$(this).val(inputVal.replace(/[^0-9]/gi, ''));
	}
});

// 숫자만 입력
$(document).on('keyup', '.onlyNumPeriod', function(event){
	if (!(event.keyCode >=37 && event.keyCode<=40)) {
		var inputVal = $(this).val();
		$(this).val(inputVal.replace(/[^0-9.]/gi, ''));
	}
});

// 숫자, 슬래시(/)만 입력
$(document).on('keyup', '.onlyNumSlash', function(event){
	if (!(event.keyCode >=37 && event.keyCode<=40)) {
		var inputVal = $(this).val();
		$(this).val(inputVal.replace(/[^0-9/]/gi, ''));
	}
});

// 숫자, 닷(.), 하이픈(-) 만 입력
$(document).on('keyup', '.onlyNumDotHipen', function(event){
	// if (!(event.keyCode >=37 && event.keyCode<=40)) {
	// 	var inputVal = $(this).val();
	// 	$(this).val(inputVal.replace(/[^0-9.-/]/gi, ''));
	// }
	var inputVal = $(this).val();
	$(this).val(inputVal.replace(/[^0-9-./]/gi, ''));
});


// 천단위 콤마
$(document).on('keyup', '.setComma', function(event){
	let inputVal		=	$(this).val().trim();
	inputVal = inputVal.replace(/[^0-9]/g,'');
	inputVal = inputVal.replace(/,/g,'');

	$(this).val(inputVal.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
});