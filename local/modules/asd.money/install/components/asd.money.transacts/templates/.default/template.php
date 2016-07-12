<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?$bFilter = $arResult["REQUEST_CURR"]!="" || $arResult["REQUEST_DATE_FROM"]!="";?>

<form method="get" action="<?= POST_FORM_ACTION_URI?>" name="transact">
<table class="data-table">
	<tr>
		<td><?= GetMessage("ASD_TPL_F_DATE")?>:</td>
		<td class="transact-date">
			<?$APPLICATION->IncludeComponent(
				"bitrix:main.calendar",
				"",
				array(
					"SHOW_INPUT" => "Y",
					"FORM_NAME" => "transact",
					"INPUT_NAME" => "date_from",
					"INPUT_VALUE" => $arResult["REQUEST_DATE_FROM"],
					"SHOW_TIME" => "Y"
				),
				null,
				array("HIDE_ICONS" => "Y")
			);?>
			...
			<?$APPLICATION->IncludeComponent(
				"bitrix:main.calendar",
				"",
				array(
					"SHOW_INPUT" => "Y",
					"FORM_NAME" => "transact",
					"INPUT_NAME" => "date_to",
					"INPUT_VALUE" => $arResult["REQUEST_DATE_TO"],
					"SHOW_TIME" => "Y"
				),
				null,
				array("HIDE_ICONS" => "Y")
			);?>
		</td>
	</tr>
	<?if (!empty($arResult["CURRENCY"])):?>
	<tr>
		<td><?= GetMessage("ASD_TPL_F_CURR")?>:</td>
		<td>
			<select name="currency">
				<option value=""></option>
				<?foreach ($arResult["CURRENCY"] as $curr => $name):?>
				<option value="<?= $curr?>"<?if ($curr == $arResult["REQUEST_CURR"]){?> selected="selected"<?}?>><?= $name?></option>
				<?endforeach;?>
			</select>
		</td>
	</tr>
	<?endif;?>
	<tr>
		<td></td>
		<td>
			<input type="submit" value="<?= GetMessage("ASD_TPL_F_SUBMIT")?>" />
			<?if ($bFilter):?>
			<input type="submit" name="reset" value="<?= GetMessage("ASD_TPL_F_RESET")?>" />
			<?endif;?>
		</td>
	</tr>
</table>
</form>
<br/>

<?if (!empty($arResult["ITEMS"])):?>
<table class="data-table">
	<tr>
		<th width="20%"><?echo GetMessage("ASD_TPL_DATE")?></th>
		<th><?echo GetMessage("ASD_TPL_SUM")?></th>
		<th><?echo GetMessage("ASD_TPL_DESCRIPTION")?></th>
	</tr>
	<?foreach ($arResult["ITEMS"] as $arItem):?>
	<tr valign="top">
		<td><small><?= $arItem["TRANSACT_DATE"]?></small></td>
		<td><font color="<?= $arItem["DEBIT"]=="Y" ? "green"  :"red"?>"><?= $arItem["DEBIT"]=="Y" ? "+"  :"-"?><?= $arItem["AMOUNT_FORMATED"]?></font></td>
		<td>
			<?
			$systemDescriptions = GetMessage("ASD_TPL_".$arItem["DESCRIPTION"]);
			if ($systemDescriptions != "")
				echo $systemDescriptions;
			else
				echo $arItem["DESCRIPTION"];
			?>
			<?if ($arItem["ORDER_ID"] > 0):?>
				<br/>
				<?= GetMessage("ASD_TPL_ORDER")?>:
				<?if ($arParams["PATH_TO_ORDER"] != ""):?>
					<a href="<?= str_replace("#ORDER_ID#", $arItem["ORDER_ID"], $arParams["PATH_TO_ORDER"])?>" title="<?= GetMessage("ASD_TPL_SHOW_DETAIL_ORDER")?>"><?= $arItem["ORDER_ID"]?></a>
				<?else:?>
					<?= $arItem["ORDER_ID"]?>
				<?endif;?>
			<?endif;?>
			<?if ($arItem["NOTES"] != ""):?>
				<br/>
				<small><?echo GetMessage("ASD_TPL_NOTE")?>: <?= $arItem["NOTES"]?></small>
			<?endif;?>
		</td>
	</tr>
	<?endforeach;?>
</table>
<?elseif ($bFilter):?>
	<?= ShowNote(GetMessage("ASD_TPL_NOT_TRANSACTS_FILTER"));?>
<?else:?>
	<?= ShowNote(GetMessage("ASD_TPL_NOT_TRANSACTS"));?>
<?endif;?>

<?if ($arResult["NAV_STRING"] != ''):?>
	<p><?= $arResult["NAV_STRING"]?></p>
<?endif;?>