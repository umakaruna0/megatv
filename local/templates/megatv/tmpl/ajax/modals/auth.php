<? sleep(1); ?>
<div class="standart-form" data-module="signin-overlay">
	<h3 class="form-title standart-form__form-title">Войти</h3>

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

	<form action="/local/templates/megatv/components/bitrix/system.auth.form/auth_ajax/ajax.php" method="POST" id="auth-form" class="form" data-redirect="/">
		<div class="js-msg-block form__msg-block msg-block"></div>
	    <input type="hidden" name="AUTH_FORM" value="Y">
	    <input type="hidden" name="TYPE" value="AUTH">
	    <input type="hidden" name="ajax_key" value="">
	    <input type="hidden" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y">
	    <input type="hidden" name="sessid" id="sessid" value="">

	    <div class="form__form-group email-container g-mt-15" autocomplete="off">
	        <input data-validation="auth_login" data-type="adaptive-field" type="text" name="USER_LOGIN" class="form__form-control" placeholder="Телефон или эл. почта" autocomplete="off">
	    </div>

	    <div class="form__form-group has-feedback">
	        <input data-validation="auth_pass" data-type="password-field" type="password" name="USER_PASSWORD" class="form__form-control js-password-field" placeholder="Пароль" autocomplete="off">
            <a href="#" data-type="password-show-toggle" class="password-show-toggle">
                <span class="g-icon icon-password-eye "><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-password-eye"></use></svg></span>
            </a>
	    </div>

	    <div class="form__form-actions">
	        <button type="submit" name="submit" class="form__btn g-btn g-btn--primary btn-multistate js-btn-multistate" data-type="multistate-button">
	            <span class="init-state default-state">Войти</span>
	            <span class="done-state">Авторизую вас...</span>
	            <span class="fail-data-state"><div class="g-icon g-icon--small icon-msbutton-cross-circle"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-cross-circle"></use></svg></div>Проверьте введённые данные</span>
	            <span class="fail-network-state"><div class="g-icon g-icon--small icon-msbutton-broken-network"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-broken-network"></use></svg></div>Ошибка соединения с сервером</span>
	        </button>
	        <a href="#" data-modal="restorePassURL" data-module="modal" data-type="openModal" class="form__subaction-link js-btnModalInit">Восстановить пароль</a>
	    </div>
	</form>
</div>

<script>
	jQuery(document).ready(function(){
		var form = $('[data-module="signin-overlay"]')[0];
		Box.Application.start(form);

		var btnModals = $('[data-module="modal"]');
		btnModals.each(function(){
			var $this = $(this)[0];
			Box.Application.start($this);
		});
	});
</script>