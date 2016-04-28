<section class="subscription-services" data-module="subscription-services">
	<script type="text/x-config">
		{
			"filledDiskSpace" : "<?=$arResult["DISK_SPACE_FILLED"]?>",
			"url": "<?= $templateFolder ?>/ajax.php"
		}
	</script>
	<div class="block-header">
		<h3 class="block-title">Ваши услуги</h3>
	</div>
	<div class="block-body">
		<div class="storage-statistic">
			<span class="total-space">Всего <strong><?=round($arResult["USER"]["UF_CAPACITY"], 2)?> ГБ</strong></span>
			<span class="used-space">Занято <strong><?=round($arResult["USER"]["UF_CAPACITY_BUSY"], 2)?> ГБ</strong></span>
			<div class="progressbar-holder"></div>
		</div>
		<ul class="available-subscriptions">
            <?
            foreach($arResult["SERVICES"] as $arService)
            {
                ?>
                <li class="item<?if($arService["SELECTED"] && $arService["UF_DISK_TYPE"]):?> status-active<?endif;?>" data-service-id="<?=$arService["ID"]?>" data-type="service-item">
    				<div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
                    <?if($arService["UF_DISK_TYPE"]):?>
                        <div class="subscription-logo">
        					<span data-icon="<?=$arService["UF_TEXT"]?>"></span>
        				</div>
                    <?else:?>
                        <div class="subscription-text-logo"><?=$arService["UF_TEXT"]?></div>
                    <?endif;?>
    				
    				<div class="item-header">
    					<span class="price"><?=intval($arService["UF_PRICE"])?> Р <small>сутки</small></span>
    					<span class="item-title"><?=$arService["UF_DESC"]?></span>
    				</div>
    			</li>
                <?
            }
            ?>
		</ul>
	</div>
</section>