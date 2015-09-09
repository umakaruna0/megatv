<section class="broadcast-card">
    <script type="text/x-config">
    {
        "recordingURL": "<?=SITE_TEMPLATE_PATH?>/ajax/to_record.php"
    }
    </script>
	<div class="block-header">
		<a class="back-link" href="<?=$arResult["CHANNEL"]["DETAIL_PAGE_URL"]?>"><span data-icon="icon-backlink-arrow"></span><span>Вернуться в канал</span></a>
	</div>
	<div class="block-body">
    
        <?
        $arStatus = CProgTime::status(array(
            "SCHEDULE_ID" => $arResult["ID"],
            "CHANNEL_ID" => $arResult["PROPERTIES"]["CHANNEL"]["VALUE"]
        ));
        $status = $arStatus["status"];
        $status_icon = $arStatus["status-icon"];
        ?>
		<div class="image-col<?if($status):?> status-<?=$status?><?endif;?>" data-type="broadcast" data-broadcast-id="<?=$arResult["ID"]?>"> 
            
            <?//=$status_icon?>
            <img src="<?=$arResult["PICTURE"]["SRC"]?>" alt="" width="480">
            
			<?/*<a class="item-status-icon" href="broadcast-card-player.html">
				<span data-icon="icon-recorded"></span>
				<span class="status-desc">Смотреть</span>
			</a>
			<ul class="action-panel">
				<li>
					<a href="#"><span data-icon="icon-yadisk-service" data-size="small"></span>Сохранить на Яндекс.Диск</a>
				</li>
				<li>
					<a href="#"><span data-icon="icon-gdisk-service" data-size="small"></span>Сохранить на Гугл.Диск</a>
				</li>
			</ul>*/?>
		</div>
		<div class="right-col">
			<div class="info-row">
				<div class="broadcast-descr-col">
					<div class="broadcast-status">начало в <?=substr($arResult["DATE_START"], 11, 5)?></div>
					<h3 class="broadcast-title"><?=$arResult["NAME"]?> <?if($arResult["PROPERTY_SUB_TITLE_VALUE"]):?><small>| <?=$arResult["PROPERTY_SUB_TITLE_VALUE"]?></small><?endif;?></h3>
					<div class="broadcast-descr">
						<p><?=strip_tags($arResult["DETAIL_TEXT"])?></p>
					</div>
				</div>
				<div class="broadcast-info-col">
					<dl class="info-list">
                        <?if(!empty($arResult["PROPERTY_COUNTRY_VALUE"])):?>
    						<dt>Произведено:</dt>
    						<dd><?=$arResult["PROPERTY_COUNTRY_VALUE"]?></dd>
                        <?endif;?>
                        <?if(!empty($arResult["PROPERTY_YEAR_VALUE"])):?>
    						<dt>Год:</dt>
    						<dd><?=$arResult["PROPERTY_YEAR_VALUE"]?></dd>
                        <?endif;?>
                        <?if(!empty($arResult["PROPERTY_TOPIC_VALUE"])):?>
    						<dt>Жанры:</dt>
    						<dd><?=$arResult["PROPERTY_TOPIC_VALUE"]?></dd>
                        <?endif;?>
                        <?if(!empty($arResult["DURATION"])):?>
    						<dt>Длительность:</dt>
    						<dd><?=$arResult["DURATION"]?></dd>
                        <?endif;?>
					</dl>
					<span data-icon="icon-<?=CDev::ageToSvg($arResult["POROPERTY_YEAR_LIMIT_VALUE"])?>-age-rating"></span>
				</div>
				<div class="broadcast-stuff-col">
					<dl class="stuff-list">
                        <?if(!empty($arResult["PROPERTY_DIRECTOR_VALUE"])):?>
    						<dt>Режиссеры:</dt>
    						<dd><?=$arResult["PROPERTY_DIRECTOR_VALUE"]?></dd>
                        <?endif;?>
                        <?if(!empty($arResult["PROPERTY_PRESENTER_VALUE"])):?>
    						<dt>Ведущие:</dt>
    						<dd><?=$arResult["PROPERTY_PRESENTER_VALUE"]?></dd>
                        <?endif;?>
                        <?if(!empty($arResult["PROPERTY_ACTOR_VALUE"])):?>
    						<dt>В ролях:</dt>
    						<dd><?=$arResult["PROPERTY_ACTOR_VALUE"]?></dd>
                        <?endif;?>
					</dl>
				</div>
			</div>
			<div class="advert-holder">
                <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/schedule-detail-banner.php"), false);?>
			</div>
		</div>
		<span class="channel-back-logo"><span data-icon="<?=$arResult["CHANNEL"]["PROPERTY_ICON_VALUE"]?>"></span></span>
	</div><!-- /.block-body -->
</section><!-- /.broadcast-card -->