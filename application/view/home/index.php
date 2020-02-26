<div class="container">
    <textarea id="before"></textarea>
	<br>
	<button id="textchange">빈칸제거</button>
	<p id="same"></p>
	<br>
	<textarea id="after"></textarea>
</div>
<script>
	var array_after			=	[];
	$(document).on('click', '#textchange', function(){
		var before			=	$('#before').val().replace(/(\s*)/g, "");
		var after			=	$('#after').val();

		//해당 데이터가 array_after의 값과 일치하는지 확인
		//array_after는 최대 10개까지 저장하며 10개가 넘어갈 경우 오래된 값부터 제거

		$('#same').text('');

		//전체배열을 확인하고 동일한 값이 있을 경우 이전내용과 동일 표시 
		for(var i = 0; i < array_after.length; i++){
			var after_		=	array_after[i];

			//현재 입력된값과 과거 10개 값중 같은 값이 존재할 경우
			if(after_ == before){
				$('#same').text('이전 내용과 동일한 내용입니다. 패스하셔도 될듯');
			}
		}

		//배열의 끝에 현재 입력값 저장
		array_after.push(before);


		//해당 배열이 10개 이상일 경우 첫번째 배열 삭제
		if(array_after.length > 10){
			array_after.shift();
		}
		
		$('#after').val(before);
	});
</script>
