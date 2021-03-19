<?php if (!defined('_STAR_')) exit; ?>
<body>
<div class="container">
	<article class="error__page">
		<div class="img-box">
			<img src="/img/img_404.svg" alt="이미지">
		</div>
		<div class="txt-box">
			찾을 수 없는 페이지 입니다.<br/>
			요청하신 페이지가 사라졌거나, 잘못된 경로를 이용하셨어요 : )
		</div>
		<a href="/" class="error-btn">홈으로 이동</a>
	</article>
</div>
<script>
	$('.measure__tabs a').on('click', function () {

		$('.measure__tabs a').removeClass('on');
		$(this).addClass('on');
	})
</script>
</body>
</html>