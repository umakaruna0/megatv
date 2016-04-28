<section class="user-subscription-channels" data-module="user-subscription-channels">
	<script type="text/x-config">
		{
			"url": "<?= $templateFolder ?>/ajax.php"
		}
	</script>
	<div class="block-header">
		<h3 class="block-title">Подписки на каналы</h3>
	</div>
	<div class="block-body">
		<ul class="channels-list">
            <?
            foreach($arResult["CHANNELS"] as $arChannel)
            {
                ?>
                <li class="item<?if($arChannel["SELECTED"]):?> status-active<?endif;?>" data-channel-id="<?=$arChannel["ID"]?>" data-type="channel-item">
					<div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
					<div class="subscription-logo">
						<span data-icon="<?=$arChannel["UF_ICON"]?>"></span>
					</div>
					<div class="item-header">
						<span class="price"><?=intval($arChannel["UF_PRICE_H24"])?> Р <small>сутки</small></span>
						<span class="item-title"><?=$arChannel["UF_TITLE"]?></span>
					</div>
				</li>
                <?
            }
            ?>
		</ul>
	</div>
</section>