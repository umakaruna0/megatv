<div class="standart-form" data-module="reset-overlay">
	<h3 class="form-title standart-form__form-title">Восстановление пароля</h3>

	<form action="/local/templates/megatv/components/bitrix/system.auth.forgotpasswd/.default/ajax_change_password.php" method="POST" class="form" data-redirect="/">
		<div class="js-msg-block form__msg-block msg-block"></div>
	    <input type="hidden" name="USER_EMAIL" value="">
	    <input type="hidden" name="ajax_key" value="76ce095374bbc723b7dde2bd46987d2c">
	    <input type="hidden" name="sessid" id="sessid" value="6bdbae53a3508bf4eaa782176e869539">

	    <div class="form__form-group code-container g-mt-15" autocomplete="off">
	        <input type="text" name="checkword" data-validation="code_change_pass" class="form__form-control" value="" placeholder="Код для смены пароля" autocomplete="off">
	    </div>

	    <div class="form__form-group new-pass-container" autocomplete="off">
	        <input type="text" name="password" data-validation="new_pass" class="form__form-control" value="" placeholder="Новый пароль" autocomplete="off">
	    </div>

	    <div class="form__form-actions">
	        <button type="submit" name="submit" class="form__btn g-btn g-btn--primary js-btn-multistate">
	            <span class="form__state form__state--show">Изменить пароль</span>
				<span class="form__state">Запрос отправлен</span>
				<span class="form__state"><div class="g-icon icon-msbutton-cross-circle "><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-cross-circle"></use></svg></div>Проверьте введённые данные</span>
				<span class="form__state"><div class="g-icon icon-msbutton-broken-network "><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-broken-network"></use></svg></div>Ошибка соединения с сервером</span>
	        </button>
	        <a href="#" data-modal="registerURL" class="form__subaction-link js-btnModalInit">Я вспомнил пароль</a> <span class="g-vetical-line">|</span> <a href="#" data-modal="restorePassURL" class="form__subaction-link js-btnModalInit">Запросить код ещё раз</a>
	    </div>
	</form>
</div>

<script>
	jQuery(document).ready(function(){
		var init = Box.Application.getService("startModal");
		init.start('[data-module="reset-overlay"]');
	});
</script>