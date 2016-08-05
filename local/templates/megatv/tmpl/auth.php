<?
    require("include/header.php");
?>
<section class="section-h1">
    <h1>Вход в личный кабинет</h1>
</section>
<section class="jumbotron">
	<h2 class="jumbotron__title-h2">Страница доступна</h2>
	<h3 class="jumbotron__title-h3">только зарегистрированным пользователям.</h3>
	<div class="jumbotron__btns">
		<button data-module="modal" data-modal="authURL" data-type="openModal" class="g-btn g-btn--primary jumbotron__btn js-btnModalInit">Войти</button>
		<button data-module="modal" data-modal="registerURL" data-type="openModal" class="g-btn g-btn--info jumbotron__btn js-btnModalInit">Регистрация</button>
	</div>
</section>
<?
	$js = [
		"js/main.js"
	];
	require("include/footer.php");
?>nn