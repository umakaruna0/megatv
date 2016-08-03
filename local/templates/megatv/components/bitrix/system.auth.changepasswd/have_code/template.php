<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="standart-form" data-module="reset-overlay">
	<h3 class="form-title standart-form__form-title">Восстановление пароля</h3>
	    <form action="<?= $templateFolder ?>/ajax.php" method="POST" method="POST" class="form" data-redirect="/">
	        <div class="js-msg-block form__msg-block msg-block"></div>            
            <?if (strlen($arResult["BACKURL"]) > 0):?>
        		<input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
        	<?endif;?>
        	<input type="hidden" name="TYPE" value="CHANGE_PWD">
            <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
            <?=bitrix_sessid_post()?>

	        <div class="form__form-group email-container" autocomplete="off">
	            <input data-validation="auth_login" data-type="adaptive-field" type="text" name="USER_LOGIN" class="form__form-control" placeholder="Телефон или эл. почта" autocomplete="off" value="<?=$_GET['USER_LOGIN']?>">
	        </div>
	        
            <div class="form__form-group code-container" autocomplete="off">
    	        <input type="text" name="USER_CHECKWORD" data-validation="code_change_pass" class="form__form-control" value="" placeholder="Код для смены пароля" autocomplete="off">
    	    </div>
    
    	    <div class="form__form-group new-pass-container" autocomplete="off">
    	        <input type="text" name="USER_PASSWORD" data-validation="new_pass" class="form__form-control" value="" placeholder="Новый пароль" autocomplete="off">
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
</div>