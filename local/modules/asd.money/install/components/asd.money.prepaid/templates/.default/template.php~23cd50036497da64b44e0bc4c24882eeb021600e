<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

CUtil::InitJSCore(array('jquery'));
?>

<?if ($arParams['COMISSION'] > 0) echo '<p>'.GetMessage('ASD_TPL_COMISSION', array('#VALUE#' => $arParams['COMISSION'])).'</p>';?>

<?if ($arResult['ERROR'] != '') ShowError($arResult['ERROR']);?>

<form method="post" action="<?= POST_FORM_ACTION_URI?>">
<input type="hidden" name="prepaid_money" value="Y" />
<input type="hidden" id="bx-asd-baseformat" value="<?= $arResult['CURRENCIES'][$arResult['LANG_CURRENCY']]['FORMAT_STRING']?>" />
<input type="hidden" id="bx-asd-comission" value="<?= $arParams['COMISSION']?>" />
<?= bitrix_sessid_post()?>

<?= GetMessage('ASD_TPL_SUMM')?>&nbsp;&nbsp;
<input type="text" name="amount" id="bx-asd-amount" value="<?= $arResult['REQUEST_AMOUNT']?>" size="7" />

&nbsp;<?= GetMessage('ASD_TPL_ACCOUNT')?>&nbsp;
<select name="account" id="bx-asd-account">
	<?if (!isset($arParams['DEFAULT_CURRENCY']) || !strlen($arParams['DEFAULT_CURRENCY'])){?><option value="" data-factor="0"></option><?}?>
	<?foreach ($arResult['ACCOUNT'] as $arItem):?>
	<option data-factor="<?= $arResult['CURRENCIES'][$arItem['CURRENCY']]['FACTOR']?>" value="<?= $arItem['CURRENCY']?>"<?if ($arResult['REQUEST_ACCOUNT'] == $arItem['CURRENCY']){?> selected="selected"<?}?>><?= $arItem['CURRENT_BUDGET_FORMATED']?></option>
	<?endforeach;?>
	&nbsp;&nbsp;
</select>

&nbsp;(<?= GetMessage('ASD_TPL_RESULT')?>: <span id="bx-asd-result"></span>)

<?if ($arParams['PAY_IMMED']):?>
	<div class="sys_methods">
	<?foreach ($arResult['PAY_SYSTEMS'] as $arSystem):?>
		<input type="radio" name="pay_system" id="asd_ps_<?= $arSystem['PAY_SYSTEM_ID']?>" value="<?= $arSystem['PAY_SYSTEM_ID']?>" <?if ($arResult['REQUEST_PAY_SYSTEM'] == $arSystem['PAY_SYSTEM_ID']){?> checked="checked"<?}?>>
		<label for="asd_ps_<?= $arSystem['PAY_SYSTEM_ID']?>"><?= htmlspecialcharsbx($arSystem['NAME'])?></label>
		<br/>
	<?endforeach;?>
	</div>
<?endif;?>

<input type="submit" value="<?= GetMessage('ASD_TPL_SEND')?>" />

</form>