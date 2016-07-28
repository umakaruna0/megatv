<div class="standart-form" data-module="signup-overlay">
	<h3 class="form-title standart-form__form-title">Регистрация</h3>

	<ul class="social-authorize-list">
	    <li class="social-authorize-list__li-social li-social">
	        <a href="http://tvguru.com/vendor/hybridauth/hybridauth/?provider=yandex" class="social-authorize-list__link social-signin-link">
	            <span class="icon-ya-social g-icon">
	            	<svg class="g-icon__icon-cnt">
	                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-ya-social"></use>
	                </svg>
	            </span>
	        </a>
	    </li>
	    <li class="social-authorize-list__li-social li-social">
	        <a href="http://tvguru.com/vendor/hybridauth/hybridauth/?provider=odnoklassniki" class="social-authorize-list__link social-signin-link">
	            <span class="icon-ok-social g-icon">
	            	<svg class="g-icon__icon-cnt">
	                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-ok-social"></use>
	                </svg>
	            </span>
	        </a>
	    </li>
	    <li class="social-authorize-list__li-social li-social">
	        <a href="http://tvguru.com/vendor/hybridauth/hybridauth/?provider=google" class="social-authorize-list__link social-signin-link">
	            <span class="icon-gp-social g-icon">
	            	<svg class="g-icon__icon-cnt">
	                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-gp-social"></use>
	                </svg>
	            </span>
	        </a>
	    </li>
	    <li class="social-authorize-list__li-social li-social">
	        <a href="http://tvguru.com/vendor/hybridauth/hybridauth/?provider=linkedin" class="social-authorize-list__link social-signin-link">
	            <span class="icon-in-social g-icon">
	            	<svg class="g-icon__icon-cnt">
	                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-in-social"></use>
	                </svg>
	            </span>
	        </a>
	    </li>
	    <li class="social-authorize-list__li-social li-social">
	        <a href="http://tvguru.com/vendor/hybridauth/hybridauth/?provider=vkontakte" class="social-authorize-list__link social-signin-link">
	            <span class="icon-vk-social g-icon">
	            	<svg class="g-icon__icon-cnt">
	                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-vk-social"></use>
	                </svg>
	            </span>
	        </a>
	    </li>
	    <li class="social-authorize-list__li-social li-social">
	        <a href="http://tvguru.com/vendor/hybridauth/hybridauth/?provider=twitter" class="social-authorize-list__link social-signin-link">
	            <span class="icon-tw-social g-icon">
	            	<svg class="g-icon__icon-cnt">
	                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-tw-social"></use>
	                </svg>
	            </span>
	        </a>
	    </li>
	    <li class="social-authorize-list__li-social li-social">
	        <a href="http://tvguru.com/vendor/hybridauth/hybridauth/?provider=instagram" class="social-authorize-list__link social-signin-link">
	            <span class="icon-im-social g-icon">
	            	<svg class="g-icon__icon-cnt">
	                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-im-social"></use>
	                </svg>
	            </span>
	        </a>
	    </li>
	    <li class="social-authorize-list__li-social li-social">
	        <a href="http://tvguru.com/vendor/hybridauth/hybridauth/?provider=facebook" class="social-authorize-list__link social-signin-link">
	            <span class="icon-fb-social g-icon">
	            	<svg class="g-icon__icon-cnt">
	                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-fb-social"></use>
	                </svg>
	            </span>
	        </a>
	    </li>
	</ul>
	<div class="divider"><span class="divider__span">или</span></div>
	<div class="steps">
		<div class="steps__step js-step step--active" style="display:block">
			<form action="/local/templates/megatv/components/bitrix/system.auth.registration/.default/ajax.php" method="POST" target="_top" id="register-form" class="form" data-redirect="/">
				<div class="js-msg-block form__msg-block msg-block"></div>
				<input type="hidden" name="backurl" value="/">
			    <input type="hidden" name="AUTH_FORM" value="Y">
			    <input type="hidden" name="TYPE" value="REGISTRATION">
			    <input type="hidden" name="sessid" value="">
			    <input type="hidden" name="ajax_key" value="">

			    <div class="form__form-group form__login g-mt-15" autocomplete="off">
			        <input type="text" name="USER_EMAIL" data-validation="phone_and_email" class="form__form-control js-user-email" value="" placeholder="Телефон или эл. почта" autocomplete="off" data-type="adaptive-field">
			    </div>

			    <div class="form__form-group form__agree">
			        <div class="form__group-checkbox">
						<input id="checkbox-agree" data-validation="agree" value="on" type="checkbox" name="AGREE" class="form__checkbox checkbox-agree"><label for="checkbox-agree" class="form__label"><span class="form__checkbox-imitation"></span><span class="form__label-title">Я принимаю условия <a href="#">договора оферты</a></span></label>
					</div>
			    </div>

			    <div class="form__form-actions">
			        <button type="submit" name="submit" class="form__btn g-btn g-btn--primary btn-multistate js-btn-multistate" data-type="multistate-button">
			            <span class="init-state default-state">Зарегистрироваться</span>
			            <span class="fail-data-state"><div class="g-icon g-icon--small icon-msbutton-cross-circle"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-cross-circle"></use></svg></div>Проверьте введённые данные</span>
			            <span class="fail-network-state"><div class="g-icon g-icon--small icon-msbutton-broken-network"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-broken-network"></use></svg></div>Ошибка соединения с сервером</span>
			        </button>
			        <a href="#" data-module="modal" data-modal="restorePassURL" data-type="openModal" class="form__subaction-link js-btnModalInit">Восстановить пароль</a>
			    </div>
			</form>
		</div>
		<div class="steps__step js-step">
            <p class="g-mt-20 text-center">Зайдите на почту и перейдите по ссылке<br> или введите код активации здесь:</p>
    		<form action="/local/templates/megatv/components/bitrix/system.auth.confirmation/.default/ajax.php" data-type="signup-code-form" id="signup-code-form">
				<div class="js-msg-block form__msg-block msg-block"></div>
				<input type="hidden" name="USER_EMAIL" class="js-user-email-paste" value="" data-type="adaptive-field-hidden">
                <input type="hidden" name="ajax_key" value="">
                <input type="hidden" name="sessid" value="">  

                <div class="form__form-group form__code g-mt-10">
					<label for="" class="sr-only">Код активации</label>
					<input type="text" name="CHECKWORD" data-validation="checkword" class="form-control" placeholder="Код активации" data-type="code-field">
				</div>    
    			<div class="form__form-actions g-mt-10">
    				<button type="submit" name="submit" class="form__btn g-btn g-btn--primary btn-multistate js-btn-multistate" data-type="multistate-button">
    					<span class="default-state init-state">Активировать</span>
						<span class="done-state">Активирую ваш аккаунт...</span>
    					<span class="fail-data-state"><div class="g-icon g-icon--small icon-msbutton-cross-circle "><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-cross-circle"></use></svg></div>Проверьте введённые данные</span>
    					<span class="fail-network-state"><div class="g-icon g-icon--small icon-msbutton-broken-network "><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-broken-network"></use></svg></div>Ошибка соединения с сервером</span>
    				</button>
			        <a href="#" data-module="modal" data-modal="authURL" data-type="openModal" class="form__subaction-link js-btnModalInit">У меня есть аккаунт</a>
    			</div>
    		</form>
		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function(){
		var form = $('[data-module="signup-overlay"]')[0];
		Box.Application.start(form);

		var btnModals = $('[data-module="modal"]');
		btnModals.each(function(){
			var $this = $(this)[0];
			Box.Application.start($this);
		});
	});
</script>
