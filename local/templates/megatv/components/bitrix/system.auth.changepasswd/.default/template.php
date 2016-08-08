<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="content-form">
	<div class="content-form__standart-form standart-form" data-module="change-pass-overlay">
	    <h3 class="form-title standart-form__form-title g-mb-10">Смена пароля</h3>
        <?//ShowMessage($arParams["~AUTH_RESULT"]);?>
	    <form action="<?= $templateFolder ?>/ajax.php" method="POST" id="change-pass-form" name="bform" class="form" data-redirect="/">
	        <div class="js-msg-block form__msg-block msg-block"></div>            
            <?if (strlen($arResult["BACKURL"]) > 0):?>
        		<input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
        	<?endif;?>
        	<input type="hidden" name="TYPE" value="CHANGE_PWD">
            <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
            <?=bitrix_sessid_post()?>

	        <div class="form__form-group email-container g-mt-15" autocomplete="off">
	            <input data-validation="auth_login" readonly data-type="adaptive-field" type="text" name="USER_LOGIN" class="form__form-control" placeholder="Телефон или эл. почта" autocomplete="off" value="<?=htmlspecialchars_decode($_GET['USER_LOGIN'])?>">
	        </div>
	        
            <div class="form__form-group email-container" autocomplete="off">
	            <input data-validation="auth_login" data-type="adaptive-field" type="text" name="USER_CHECKWORD" class="form__form-control" placeholder="<?=GetMessage("AUTH_CHECKWORD")?>" autocomplete="off" value="<?=$arResult["USER_CHECKWORD"]?>">
	        </div>
            <div class="form__form-group has-feedback">
	            <input data-validation="new_pass" data-type="password-field" type="password" name="USER_PASSWORD" class="form__form-control js-password-field" placeholder="Новый пароль" autocomplete="off">
	            <a href="#" data-type="password-show-toggle" class="password-show-toggle">
	                <span class="g-icon icon-password-eye "><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-password-eye"></use></svg></span>
	            </a>
	        </div>
	        <div class="form__form-group has-feedback">
	            <input data-validation="confirm_pass" data-type="confirm-password-field" type="password" name="USER_CONFIRM_PASSWORD" class="form__form-control js-password-field" placeholder="Подтвердить пароль" autocomplete="off">
	            <a href="#" data-type="password-show-toggle" class="password-show-toggle">
	                <span class="g-icon icon-password-eye "><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-password-eye"></use></svg></span>
	            </a>
	        </div>
	        <div class="form__form-actions">
	            <button type="submit" name="change_pwd" class="form__btn g-btn g-btn--primary btn-multistate js-btn-multistate is-inited" data-type="multistate-button">
	                <span class="init-state default-state">Войти</span>
	                <span class="done-state">Авторизую вас...</span>
	                <span class="fail-data-state"><div class="g-icon g-icon--small icon-msbutton-cross-circle"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-cross-circle"></use></svg></div>Проверьте введённые данные</span>
	                <span class="fail-network-state"><div class="g-icon g-icon--small icon-msbutton-broken-network"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-msbutton-broken-network"></use></svg></div>Ошибка соединения с сервером</span>
	            </button>
	        </div>
	    </form>
	</div>
</div>