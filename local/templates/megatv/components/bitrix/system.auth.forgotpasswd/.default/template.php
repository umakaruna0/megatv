
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
//if(method_exists($this, 'setFrameMode')) $this->setFrameMode(TRUE);
?>

<?
//ShowMessage($arParams["~AUTH_RESULT"]);
?>
<form action="<?= $templateFolder ?>/ajax.php" class="reset-form" id="recovery-form">
    <?if (strlen($arResult["BACKURL"]) > 0):?>
		<input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
	<?endif;?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="SEND_PWD">
    
    <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
    <?=bitrix_sessid_post()?>
    
	<div class="form-group email-container">
		<label for="" class="sr-only">Эл. почта</label>
		<input type="text" name="USER_EMAIL" id="" class="form-control" placeholder="Эл. почта" value="<?= $arResult["LAST_LOGIN"] ?>"/>
	</div>
	<button type="submit" name="send_account_info" class="btn btn-primary btn-block">Восстановить пароль</button>
</form>