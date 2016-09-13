<div class="standart-form" data-module="reset-overlay">
	<h3 class="form-title standart-form__form-title g-mb-10">Восстановление пароля</h3>
	<div class="steps">
		<div class="steps__step step--active js-step" style="display:block">
			<form action="/local/templates/megatv/components/bitrix/system.auth.forgotpasswd/.default/ajax.php" method="POST" id="restore-pass-form" class="form" data-redirect="/">
			    <input type="hidden" name="backurl" value="/">
			    <input type="hidden" name="TYPE" value="SEND_PWD">
			    <input type="hidden" name="ajax_key" value="">
			    <input type="hidden" name="sessid" value="">

			    <p class="g-mt-10 g-text-center g-mb-10">Напишите свой телефон или адрес эл. почты,<br> и через 30 сек. мы вышлем новый.</p>
				<div class="js-msg-block form__msg-block msg-block"></div>

			    <div class="g-mt-15 form__form-group email-container" autocomplete="off">
			        <input data-type="adaptive-field" type="text" name="USER_EMAIL" class="form__form-control js-user-email" value="" placeholder="Телефон или эл. почта" autocomplete="off" data-validation="phone_and_email">
			    </div>

			   	<div class="form__form-actions">
			        <button type="submit" name="submit" class="form__btn g-btn g-btn--primary btn-multistate js-btn-multistate" data-type="multistate-button">
			            <span class="init-state default-state">Выслать пароль</span>
			            <span class="done-state">Авторизую вас...</span>
			            <span class="fail-data-state"><div class="g-icon g-icon--small icon-msbutton-cross-circle"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-cross-circle"></use></svg></div>Проверьте введённые данные</span>
			            <span class="fail-network-state"><div class="g-icon g-icon--small icon-msbutton-broken-network"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-broken-network"></use></svg></div>Ошибка соединения с сервером</span>
			        </button>
			        <a href="#" data-type="openModal" data-module="modal" data-modal="authURL" class="form__subaction-link js-btnModalInit">Я вспомнил пароль</a> <span class="g-vetical-line">|</span> <a href="#" data-type="reset-code-handler-link" class="form__subaction-link js-btnModalInit">У меня уже есть код</a>
			    </div>
			</form>
		</div>
		<div class="steps__step js-step">
			<form action="/local/templates/megatv/components/bitrix/system.auth.forgotpasswd/.default/ajax_change_password.php" data-type="reset-code-form" method="POST" id="have-code" class="form" data-redirect="/">
				<div class="js-msg-block form__msg-block msg-block"></div>
			    <input type="hidden" name="USER_EMAIL" class="js-user-email-paste" value="" data-type="adaptive-field-hidden">
			    <input type="hidden" name="ajax_key" value="">
			    <input type="hidden" name="sessid" value="">

			    <div class="form__form-group code-container g-mt-15" autocomplete="off">
			        <input type="text" data-type="code-field" name="checkword" data-validation="code_change_pass" class="form__form-control" value="" placeholder="Код для смены пароля" autocomplete="off">
			    </div>

			    <div class="form__form-group new-pass-container" autocomplete="off">
			        <input type="text" data-type="password-field" name="password" data-validation="new_pass" class="form__form-control js-password-field" value="" placeholder="Новый пароль" autocomplete="off">
		            <a href="#" data-type="password-show-toggle" class="password-show-toggle">
		                <span class="g-icon icon-password-eye "><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-password-eye"></use></svg></span>
		            </a>
			    </div>

			    <div class="form__form-actions">
			        <button type="submit" name="submit" class="form__btn g-btn g-btn--primary btn-multistate js-btn-multistate" data-type="multistate-button">
			            <span class="init-state default-state">Изменить пароль</span>
						<span class="done-state">Запрос отправлен</span>
						<span class="fail-data-state"><div class="g-icon icon-msbutton-cross-circle "><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-cross-circle"></use></svg></div>Проверьте введённые данные</span>
						<span class="fail-network-state"><div class="g-icon icon-msbutton-broken-network "><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-broken-network"></use></svg></div>Ошибка соединения с сервером</span>
			        </button>
			        <a href="#" data-modal="registerURL" data-module="modal" data-type="openModal" class="form__subaction-link js-btnModalInit">Я вспомнил пароль</a> <span class="g-vetical-line">|</span> <a href="#" data-type="reset-handler-link" class="form__subaction-link js-btnModalInit">Запросить код ещё раз</a>
			    </div>
			</form>
		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function(){
		var init = Box.Application.getService("startModal");
		init.start('[data-module="reset-overlay"]');
	});
</script>