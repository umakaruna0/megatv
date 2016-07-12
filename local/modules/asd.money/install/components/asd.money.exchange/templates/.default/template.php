<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();?>

<?if ($arParams['COMISSION'] > 0) echo '<p>'.GetMessage('ASD_TPL_COMISSION', array('#VALUE#' => $arParams['COMISSION'])).'</p>';?>

<?if ($arResult['ERROR'] != '') ShowError($arResult['ERROR']);?>
<?if ($arResult['SUCCESS'] == 'Y') ShowNote(GetMessage('ASD_TPL_SUCCESS'));?>

<form method="post" action="<?= POST_FORM_ACTION_URI?>">
<input type="hidden" name="exchange_money" value="Y" />
<?= bitrix_sessid_post()?>

<table>
	<tr>
		<td><?= GetMessage('ASD_TPL_SUMM')?></td>
		<td>
			<input type="text" name="amount" value="<?= $arResult['REQUEST_AMOUNT']?>" size="3" />
			<select name="from">
				<option value=""></option>
				<?foreach ($arResult['FROM'] as $arItem):?>
				<option value="<?= $arItem['CURRENCY']?>"<?if ($arResult['REQUEST_FROM'] == $arItem['CURRENCY']){?> selected="selected"<?}?>><?= $arItem['FULL_NAME']?> (<?= $arItem['CURRENT_BUDGET_FORMATED']?>)</option>
				<?endforeach;?>
			</select>

			<?= GetMessage('ASD_TPL_TO')?>
			<select name="to">
				<option value=""></option>
				<?foreach ($arResult['TO'] as $curr => $name):?>
				<option value="<?= $curr?>"<?if ($arResult['REQUEST_TO'] == $curr){?> selected="selected"<?}?>><?= $name?></option>
				<?endforeach;?>
			</select>
		</td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" value="<?= GetMessage('ASD_TPL_SEND')?>" /></td>
	</tr>
</table>
</form>