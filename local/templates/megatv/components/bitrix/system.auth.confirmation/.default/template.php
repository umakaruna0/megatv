<?
if(isset($_GET["confirm_user_id"]) && isset($_GET["confirm_code"]))
{
    $chekword = trim($_GET["confirm_code"]);
    $rsUser = CUser::GetByID(intval($_GET["confirm_user_id"]));
	if($arResult["USER"] = $rsUser->GetNext())
	{
        if(strlen($chekword) > 0 && $chekword == $arResult["USER"]["~CONFIRM_CODE"])
        {
            global $USER;
            $USER->Authorize($arResult["USER"]["ID"], true);
            LocalRedirect("/");
        }
    }
}
?>


<form action="<?= $templateFolder ?>/ajax.php" data-type="signup-code-form" id="signup-code-form">
    <div class="js-msg-block form__msg-block msg-block"></div>
    <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
    <input type="hidden" name="USER_EMAIL" value="" class="js-user-email-paste" value="" data-type="adaptive-field-hidden">
    <?=bitrix_sessid_post()?>
    
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