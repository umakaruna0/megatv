<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

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
		<?/*<span class="form-control-feedback"><span data-icon="icon-calendar"></span></span>*/?>
	</div>
	<div class="form-group">
		<label for="" class="sr-only">Эл. почта</label>
		<input type="text" name="USER_EMAIL" id="" class="form-control" placeholder="Эл. почта" value="<?=$arResult["USER_EMAIL"]?>">
	</div>
    <?/*<div class="form-group">
		<label for="" class="sr-only">Пароль (мин. 6 символа)</label>
		<input type="password" name="USER_PASSWORD" class="form-control" placeholder="Пароль">
	</div>
	<div class="form-group">
		<label for="" class="sr-only">Подтверждение пароля</label>
		<input type="password" name="USER_CONFIRM_PASSWORD" class="form-control" placeholder="Подтверждение пароля">
	</div>*/?>
	<div class="checkbox">
		<label for="_id-singup-userdata-form--chackbox">
            <input type="checkbox" name="AGREE" id="_id-singup-userdata-form--chackbox">
            <span>Я принимаю условия <a href="#">договора оферты</a></span>
        </label>
	</div>
	<button type="submit" name="Register" class="btn btn-primary btn-block">Зарегистрироваться</button>
</form>