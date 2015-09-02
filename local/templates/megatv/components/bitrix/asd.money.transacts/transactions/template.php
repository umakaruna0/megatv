<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult["ITEMS"])):?>
    <div class="balance-history">
    	<div class="block-header">
    		<h4 class="block-title">Последние списания / пополнения</h4>
    	</div>
    	<div class="block-body">
    		<ul class="events-list">
                <?foreach ($arResult["ITEMS"] as $arItem):?>
                    <?
                    $arr = ParseDateTime($arItem["TRANSACT_DATE"], FORMAT_DATETIME);
                    ?>
                    <li class="event">
        				<span data-icon="icon-<?= $arItem["DEBIT"]=="Y" ? "incoming"  :"outcoming"?>-arrow"></span>
        				<span class="event-date"><?= $arr["DD"]." ".ToLower(GetMessage("MONTH_".intval($arr["MM"])."_S"))." ".$arr["YYYY"];?></span>
        				<span class="event-title"><?= $arItem["DEBIT"]=="Y" ? "Пополнение счета"  :"Списание со счета"?></span>
        				<span class="event-cost"><?= $arItem["DEBIT"]=="Y" ? "+"  :"—"?> <?=number_format($arItem["AMOUNT"], 0, "", " ")?> Р</span>
        			</li>
                <?endforeach;?>
    		</ul>
    	</div>
    </div>
<?endif;?>

<?/*if (!empty($arResult["ITEMS"])):?>
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
<?endif;*/?>