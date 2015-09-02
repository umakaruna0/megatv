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
			<span class="total-space">Всего <strong><?=intval($arResult["USER"]["UF_CAPACITY"])?> ГБ</strong></span>
			<span class="used-space">Занято <strong><?=intval($arResult["USER"]["UF_CAPACITY_BUSY"])?> ГБ</strong></span>
			<div class="progressbar-holder"></div>
		</div>
		<ul class="available-subscriptions">
            <?
            foreach($arResult["SERVICES"] as $arService)
            {
                ?>
                <li class="item<?if($arService["SELECTED"]):?> status-active<?endif;?>" data-service-id="<?=$arService["ID"]?>" data-type="service-item">
    				<div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
                    <?if($arService["PROPERTY_DISK_VALUE"]):?>
                        <div class="subscription-logo">
        					<span data-icon="<?=$arService["PROPERTY_TEXT_VALUE"]?>"></span>
        				</div>
                    <?else:?>
                        <div class="subscription-text-logo"><?=$arService["PROPERTY_TEXT_VALUE"]?></div>
                    <?endif;?>
    				
    				<div class="item-header">
    					<span class="price"><?=intval($arService["PROPERTY_PRICE_VALUE"])?> Р <small>сутки</small></span>
    					<span class="item-title"><?=$arService["PREVIEW_TEXT"]?></span>
    				</div>
    			</li>
                
                <?
            }
            ?>
		</ul>
	</div>
</section>