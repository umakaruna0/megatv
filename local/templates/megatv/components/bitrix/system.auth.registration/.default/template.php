<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<div class="standart-form" data-module="signup-overlay">
	<h3 class="form-title standart-form__form-title"><?=GetMessage('AUTH_REGISTER')?></h3>
    <?require($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/include/social-auth.php");?>
    <div class="divider"><span class="divider__span"><?=GetMessage('AUTH_OR')?></span></div>
	<div class="steps">
		<div class="steps__step js-step step--active" style="display:block">
			<form action="<?= $templateFolder ?>/ajax.php" method="POST" target="_top" id="register-form" class="form" data-redirect="/">
                <div class="js-msg-block form__msg-block msg-block"></div>
                <?if (strlen($arResult["BACKURL"]) > 0):?>
            		<input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
            	<?endif;?>
                <input type="hidden" name="AUTH_FORM" value="Y"/>
            	<input type="hidden" name="TYPE" value="REGISTRATION"/>
                <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
                <?=bitrix_sessid_post()?>
                
                <div class="form__form-group form__login g-mt-15" autocomplete="off">
			        <input type="text" name="USER_EMAIL" data-validation="phone_and_email" class="form__form-control js-user-email" value="" placeholder="<?=GetMessage('AUTH_PHONE_OR_EMAIL')?>" autocomplete="off" data-type="adaptive-field">
			    </div>
                
                <div class="form__form-group has-feedback">
        	        <input data-validation="auth_pass" data-type="password-field" type="password" name="USER_PASSWORD" class="form__form-control js-password-field" placeholder="<?=GetMessage('AUTH_PASSWORD')?>" autocomplete="off">
                    <a href="#" data-type="password-show-toggle" class="password-show-toggle">
                        <span class="g-icon icon-password-eye"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-password-eye"></use></svg></span>
                    </a>
        	    </div>
                
                <div class="form__form-group form__agree">
			        <div class="form__group-checkbox">
						<input id="checkbox-agree" data-validation="agree" value="on" type="checkbox" name="AGREE" class="form__checkbox checkbox-agree"><label for="checkbox-agree" class="form__label"><span class="form__checkbox-imitation"></span><span class="form__label-title"><?=GetMessage('AUTH_AGREE')?> <a href="#"><?=GetMessage('AUTH_AGREE_OFERTA')?></a></span></label>
					</div>
			    </div>
                
                <div class="form__form-actions">
			        <button type="submit" name="submit" class="form__btn g-btn g-btn--primary btn-multistate js-btn-multistate" data-type="multistate-button">
			            <span class="init-state default-state"><?=GetMessage('AUTH_REGISTER_BUTTON')?></span>
			            <span class="fail-data-state"><div class="g-icon g-icon--small icon-msbutton-cross-circle"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-cross-circle"></use></svg></div><?=GetMessage('AUTH_CHECK_ENTER_DATA')?></span>
			            <span class="fail-network-state"><div class="g-icon g-icon--small icon-msbutton-broken-network"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-broken-network"></use></svg></div><?=GetMessage('AUTH_ERROR_SERVER_CONNECT')?></span>
			        </button>
			        <a href="#" data-module="modal" data-modal="restorePassURL" data-type="openModal" class="form__subaction-link js-btnModalInit"><?=GetMessage('AUTH_RECOVERY_PASSWORD')?></a>
			    </div>
			</form>
		</div>
		<div class="steps__step js-step">
			<p class="g-mt-20 text-center"><?=GetMessage('AUTH_CONFIRM_TEXT_1')?></p>
            <?$APPLICATION->IncludeComponent(
                "bitrix:system.auth.confirmation",
                ".default",
                Array()
            );?>
		</div>
	</div>
</div>