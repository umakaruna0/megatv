<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * Bitrix vars
 *
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 * @var array $arParams
 * @var array $arResult
 * @var array $arLangMessages
 * @var array $templateData
 *
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $parentTemplateFolder
 * @var string $templateName
 * @var string $componentPath
 *
 * @var CDatabase $DB
 * @var CUser $USER
 * @var CMain $APPLICATION
 */
if(method_exists($this, 'setFrameMode')) $this->setFrameMode(TRUE);
?>
<div class="standart-form" data-module="reset-overlay">
	<h3 class="form-title standart-form__form-title g-mb-10"><?=GetMessage('AUTH_RECOVERY_PASSWORD')?></h3>
	<div class="steps">
		<div class="steps">
			<div class="steps__step step--active js-step" style="display:block">
        		<form action="<?= $templateFolder ?>/ajax.php" method="POST" id="restore-pass-form" class="form" data-redirect="/">
                    <?if (strlen($arResult["BACKURL"]) > 0):?>
                		<input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                	<?endif;?>
                	<input type="hidden" name="TYPE" value="SEND_PWD">
                    <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
                    <?=bitrix_sessid_post()?>
                    
                    <p class="g-mt-10 g-text-center g-mb-10"><?=GetMessage('AUTH_RECOVERY_TITLE_TEXT')?></p>
				    <div class="js-msg-block form__msg-block msg-block"></div>
                    
                    <div class="g-mt-15 form__form-group email-container" autocomplete="off">
    			        <input data-type="adaptive-field" type="text" name="USER_EMAIL" class="form__form-control js-user-email" value="" placeholder="<?=GetMessage('AUTH_PHONE_OR_EMAIL')?>" autocomplete="off" data-validation="phone_and_email">
    			    </div>
                    
                    <div class="form__form-actions">
    			        <button type="submit" name="submit" class="form__btn g-btn g-btn--primary btn-multistate js-btn-multistate" data-type="multistate-button">
    			            <span class="init-state default-state"><?=GetMessage('AUTH_SEND_PASS')?></span>
    			            <span class="done-state">Авторизую вас...</span>
    			            <span class="fail-data-state"><div class="g-icon g-icon--small icon-msbutton-cross-circle"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-cross-circle"></use></svg></div><?=GetMessage('AUTH_CHECK_ENTER_DATA')?></span>
    			            <span class="fail-network-state"><div class="g-icon g-icon--small icon-msbutton-broken-network"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-broken-network"></use></svg></div><?=GetMessage('AUTH_ERROR_SERVER_CONNECT')?></span>
    			        </button>
    			        <a href="#" data-type="openModal" data-module="modal" data-modal="authURL" class="form__subaction-link js-btnModalInit">Я вспомнил пароль</a> <span class="g-vetical-line">|</span> <a href="#" data-type="reset-code-handler-link" class="form__subaction-link js-btnModalInit"><?=GetMessage('AUTH_HAVE_CODE')?></a>
    			    </div>
        		</form>
            </div>
            
            <div class="steps__step js-step">
				<form action="<?= $templateFolder ?>/ajax_change_password.php" data-type="reset-code-form" method="POST" id="have-code" class="form" data-redirect="/">
                    <div class="js-msg-block form__msg-block msg-block"></div>
                    <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
                    <input type="hidden" name="USER_EMAIL" value="" data-type="adaptive-field-hidden" />
                    <?=bitrix_sessid_post()?>
                    
                    
                    <div class="form__form-group email-container g-mt-15" autocomplete="off">
        	            <input data-type="adaptive-field" type="text" name="USER_EMAIL" class="form__form-control" placeholder="<?=GetMessage('AUTH_PHONE_OR_EMAIL')?>" autocomplete="off" data-validation="phone_and_email">
        	        </div>
                            
                    <div class="form__form-group code-container" autocomplete="off">
    			        <input type="text" data-type="code-field" name="checkword" data-validation="code_change_pass" class="form__form-control" value="" placeholder="<?=GetMessage('AUTH_CODE_TO_CHANGE_PASS')?>" autocomplete="off">
    			    </div>
    
    			    <div class="form__form-group new-pass-container" autocomplete="off">
    			        <input type="text" data-type="password-field" name="password" data-validation="new_pass" class="form__form-control js-password-field" value="" placeholder="<?=GetMessage('AUTH_NEW_PASS')?>" autocomplete="off">
    		            <a href="#" data-type="password-show-toggle" class="password-show-toggle">
    		                <span class="g-icon icon-password-eye "><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-password-eye"></use></svg></span>
    		            </a>
    			    </div>
                    
                    <div class="form__form-actions">
    			        <button type="submit" name="submit" class="form__btn g-btn g-btn--primary btn-multistate js-btn-multistate" data-type="multistate-button">
    			            <span class="init-state default-state"><?=GetMessage('AUTH_CHANGE_PASS')?></span>
    						<span class="done-state"><?=GetMessage('AUTH_REQUEST_SEND')?></span>
    						<span class="fail-data-state"><div class="g-icon icon-msbutton-cross-circle "><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-cross-circle"></use></svg></div><?=GetMessage('AUTH_CHECK_ENTER_DATA')?></span>
    						<span class="fail-network-state"><div class="g-icon icon-msbutton-broken-network "><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-broken-network"></use></svg></div><?=GetMessage('AUTH_ERROR_SERVER_CONNECT')?></span>
    			        </button>
    			        <a href="#" data-modal="registerURL" data-module="modal" data-type="openModal" class="form__subaction-link js-btnModalInit"><?=GetMessage('AUTH_REMEMBER_PASS')?></a> <span class="g-vetical-line">|</span> <a href="#" data-type="reset-handler-link" class="form__subaction-link js-btnModalInit"><?=GetMessage('AUTH_REQUEST_SEND_AGAIN')?></a>
    			    </div>
				</form>
			</div>

		</div>
	</div>
</div>