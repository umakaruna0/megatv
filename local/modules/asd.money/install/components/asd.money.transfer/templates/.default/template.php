<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();?>

<p><?= GetMessage('ASD_TPL_ABOUT')?> <?if ($arParams['COMISSION'] > 0) echo '<br/>'.GetMessage('ASD_TPL_COMISSION', array('#VALUE#' => $arParams['COMISSION']));?></p>

<?if ($arResult['ERROR'] != '') ShowError($arResult['ERROR']);?>
<?if ($arResult['SUCCESS'] == 'Y') ShowNote(GetMessage('ASD_TPL_SUCCESS'));?>

<form method="post" action="<?= POST_FORM_ACTION_URI?>">
<input type="hidden" name="send_money" value="Y" />
<?= bitrix_sessid_post()?>
<table class="data-table">
	<tr>
		<td><?= GetMessage('ASD_TPL_SUMM')?></td>
		<td><input type="text" name="amount" value="<?= $arResult['REQUEST_AMOUNT']?>" size="3" /></td>
		<td>
			<?= GetMessage('ASD_TPL_SUMM_FROM')?>
			<select name="account">
				<option value=""></option>
				<?foreach ($arResult['FROM_ACCOUNT'] as $arItem):?>
				<option value="<?= $arItem['CURRENCY']?>"<?if ($arResult['REQUEST_ACCOUNT'] == $arItem['CURRENCY']){?> selected="selected"<?}?>><?= $arItem['CURRENT_BUDGET_FORMATED']?></option>
				<?endforeach;?>
			</select>
		</td>
		<td><?= GetMessage('ASD_TPL_SUMM_TO')?><input type="text" name="user" value="<?= $arResult['REQUEST_USER']?>" size="10" /></td>
	</tr>
	<tr>
		<td><?= GetMessage('ASD_TPL_DESC')?>:</td>
		<td colspan="3">
			<input type="text" name="comment" value="<?= $arResult['REQUEST_COMMENT']?>" size="40" />
			<i><?= GetMessage('ASD_TPL_DESC_NOTE')?></i>
		</td>
	</tr>
	<tr>
		<td></td>
		<td colspan="3">
			<?if (!empty($arResult['TO_USER'])):?>
				<table>
					<tr>
						<td><?= GetMessage('ASD_TPL_OFF')?>:</td>
						<td><?= $arResult['MONEY_OFF']?></td>
					</tr>
					<tr>
						<td><?= GetMessage('ASD_TPL_TO_USER')?>:</td>
						<td>
							<?if ($arParams['PATH_TO_USER']!=''){?>[<a href="<?= str_replace('#ID#', $arResult['TO_USER']['ID'], $arParams['PATH_TO_USER'])?>"><?= $arResult['TO_USER']['ID']?></a>]<?} else {?>[<?= $arResult['TO_USER']['ID']?>]<?}?>
							(<?= $arResult['TO_USER']['LOGIN']?>)
							<?= $arResult['TO_USER']['NAME']?> <?= $arResult['TO_USER']['LAST_NAME']?>
						</td>
					</tr>
				</table>
				<br/><br/>
				<input type="submit" name="send_money_now" value="<?= GetMessage('ASD_TPL_SEND_USER_SELECT')?>" />
				<input type="submit" value="<?= GetMessage('ASD_TPL_SEND_CHANGE')?>" />
			<?else:?>
				<input type="submit" value="<?= GetMessage('ASD_TPL_SEND')?>" />
			<?endif;?>
		</td>
	</tr>
</table>
</form>