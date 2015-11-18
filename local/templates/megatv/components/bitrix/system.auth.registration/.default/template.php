<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<div class="authorize-overlay is-signup-overlay" data-module="signup-overlay">
	<div class="overlay-content">
		<h4 class="overlay-title">Регистрация</h4>

        <?require($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/include/social-auth.php");?>
        
		<span class="divider"><span>или</span></span>
		<div class="steps">
			<div class="step fade in active">
				<form action="<?= $templateFolder ?>/ajax.php" method="post">
                    <?if (strlen($arResult["BACKURL"]) > 0):?>
                		<input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                	<?endif;?>
                    <input type="hidden" name="AUTH_FORM" value="Y"/>
                	<input type="hidden" name="TYPE" value="REGISTRATION"/>
                    <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
                    <?=bitrix_sessid_post()?>
                    
					<div class="form-group">
						<label for="" class="sr-only">Телефон или эл. почта</label>
						<input type="text" name="USER_EMAIL" id="" class="form-control" placeholder="Телефон или эл. почта" data-type="adaptive-field">
					</div>
					<div class="checkbox">
						<label for="_id-singup--chackbox"><input type="checkbox" name="AGREE" id="_id-singup--chackbox"><span>Я принимаю условия <a href="#">договора оферты</a></span></label>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-primary btn-block btn-multistate" data-type="multistate-button">
							<span class="default-state init-state">Зарегистрироваться</span>
							<span class="fail-data-state"><span data-icon="icon-msbutton-cross-circle"></span>Проверьте введённые данные</span>
							<span class="fail-network-state"><span data-icon="icon-msbutton-broken-network"></span>Ошибка соединения с сервером</span>
						</button>
						<a href="#" class="form-subaction-link" data-type="reset-handler-link">Восстановить пароль</a>
					</div>
				</form>
			</div>
			<div class="step fade">
				<p>Зайдите на почту и перейдите по ссылке<br> или введите код активации здесь:</p>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:system.auth.confirmation",
                    ".default",
                    Array()
                );?>
			</div>
		</div>
	</div>
</div>


<?/*
<form action="<?= $templateFolder ?>/ajax.php"  method="post" class="singup-userdata-form" id="register-form">
    <script type="text/x-config">
		{
			"dateMask": "99/99/9999",
			"phoneMask": "+7 (999) 999-99-99"
		}
	</script>
    <?if (strlen($arResult["BACKURL"]) > 0):?>
		<input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
	<?endif;?>
    <input type="hidden" name="AUTH_FORM" value="Y"/>
	<input type="hidden" name="TYPE" value="REGISTRATION"/>
	<input type="text" class="api-mf-antibot" value="" name="ANTIBOT[NAME]">
    
    <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
    <?=bitrix_sessid_post()?>
    
	<label for="">Введите ваши данные и мы вышлем<br> вам пароль на электронную почту</label>
	<div class="form-group">
		<input type="text" name="USER_NAME" id="" class="form-control" placeholder="Имя" value="<?= $arResult["USER_NAME"] ?>">
	</div>
	<div class="form-group">
		<label for="" class="sr-only">Фамилия</label>
		<input type="text" name="USER_LAST_NAME" id="" class="form-control" placeholder="Фамилия" value="<?= $arResult["USER_LAST_NAME"] ?>">
	</div>
	<div class="form-group">
		<label for="" class="sr-only">Отчество</label>
		<input type="text" name="USER_SECOND_NAME" id="" class="form-control" placeholder="Отчество" value="<?= $arResult["USER_SECOND_NAME"]?>">
	</div>
	<div class="form-group has-feedback">
		<label for="" class="sr-only">Дата рождения</label>
		<input type="text" name="USER_PERSONAL_BIRTHDAY" id="" class="form-control" placeholder="Дата рождения"  value="<?=$arResult["USER_PERSONAL_BIRTHDAY"]?>" data-type="masked-birthdate-input">
	</div>
	<div class="form-group">
		<label for="" class="sr-only">Эл. почта</label>
		<input type="text" name="USER_EMAIL" id="" class="form-control" placeholder="Эл. почта" value="<?=$arResult["USER_EMAIL"]?>">
	</div>
	<div class="checkbox">
		<label for="_id-singup-userdata-form--chackbox">
            <input type="checkbox" name="AGREE" id="_id-singup-userdata-form--chackbox">
            <span>Я принимаю условия <a href="#">договора оферты</a></span>
        </label>
	</div>
	<button type="submit" name="Register" class="btn btn-primary btn-block">Зарегистрироваться</button>
</form>
*/?>