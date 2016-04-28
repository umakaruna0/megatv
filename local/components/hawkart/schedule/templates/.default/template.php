<section class="broadcast-card" data-module="broadcast-card">
    <script type="text/x-config">
    {
        "recordingURL": "<?=SITE_TEMPLATE_PATH?>/ajax/to_record.php"
    }
    </script>
	<div class="block-header">
		<a class="back-link" href="<?=$arParams["BACK_URL"]?>"><span data-icon="icon-backlink-arrow"></span><span>Вернуться</span></a>
	</div>
    
    <?
    $status = $arResult["STATUS"]["status"];
    $status_icon = $arResult["STATUS"]["status-icon"];
    ?>
		
	<div class="block-body">
    
        <div class="image-col item <?if($status):?> status-<?=$status?><?endif;?>" data-type="broadcast" data-broadcast-id="<?=$arResult["ID"]?>"> 

            <img src="<?=$arResult["PICTURE"]["SRC"]?>" alt="" width="480">
            
            <?=$status_icon?>
            <?\Hawkart\Megatv\CScheduleTemplate::driveNotifyMessage()?>
            
			<?/*
			<ul class="action-panel">
				<li>
					<a href="#"><span data-icon="icon-yadisk-service" data-size="small"></span>Сохранить на Яндекс.Диск</a>
				</li>
				<li>
					<a href="#"><span data-icon="icon-gdisk-service" data-size="small"></span>Сохранить на Гугл.Диск</a>
				</li>
			</ul>
            */?>
		</div>
    
		<div class="right-col">
			<div class="broadcast-status">начало в <?=substr($arResult["DATE_START"], 11, 5)?></div>
			<h3 class="broadcast-title"><?=$arResult["UF_TITLE"]?></h3>
			<div class="info-row">
				<div class="broadcast-descr-col">
                    <p><?=strip_tags($arResult["UF_DESC"])?></p>
                    <?if(!empty($arResult["UF_SUB_DESC"])):?>
                        <p><?=strip_tags($arResult["UF_SUB_DESC"])?></p>
                    <?endif;?>	
                </div>
				<div class="broadcast-info-col-1">
					<dl class="info-list">
						<?if(!empty($arResult["UF_TOPIC"])):?>
    						<dt>Жанры:</dt>
    						<dd><?=$arResult["UF_TOPIC"]?></dd>
                        <?endif;?>
                        <?if(!empty($arResult["DURATION"])):?>
    						<dt>Длительность:</dt>
    						<dd><?=$arResult["DURATION"]?></dd>
                        <?endif;?>
					</dl>
                    <span data-icon="icon-<?=CDev::ageToSvg((int)$arResult["UF_YEAR_LIMIT"])?>-age-rating"></span>
				</div>
				<div class="broadcast-info-col-2">
					<dl class="info-list">
						<?if(!empty($arResult["UF_COUNTRY"])):?>
    						<dt>Произведено:</dt>
    						<dd><?=$arResult["UF_COUNTRY"]?></dd>
                        <?endif;?>
                        <?if(!empty($arResult["UF_YEAR"])):?>
    						<dt>Год:</dt>
    						<dd><?=$arResult["UF_YEAR"]?></dd>
                        <?endif;?>
					</dl>
				</div>
				<div class="broadcast-stuff-col">
					<dl class="stuff-list">
					   <?if(!empty($arResult["UF_DIRECTOR"])):?>
    						<dt>Режиссеры:</dt>
    						<dd><?=$arResult["UF_DIRECTOR"]?></dd>
                        <?endif;?>
                        <?if(!empty($arResult["UF_PRESENTER"])):?>
    						<dt>Ведущие:</dt>
    						<dd><?=$arResult["UF_PRESENTER"]?></dd>
                        <?endif;?>
                        <?if(!empty($arResult["UF_ACTOR"])):?>
    						<dt>В ролях:</dt>
    						<dd><?=$arResult["UF_ACTOR"]?></dd>
                        <?endif;?>	
                    </dl>
				</div>
			</div>
			<div class="advert-holder">
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/schedule-detail-banner.php"), false);?>
			</div>
		</div>
		<span class="channel-back-logo"><span data-icon="<?=$arResult["UF_ICON"]?>"></span></span>
	</div><!-- /.block-body -->
</section><!-- /.broadcast-card -->