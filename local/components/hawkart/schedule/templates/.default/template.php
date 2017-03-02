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
            
            
            <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
            <script src="//yastatic.net/share2/share.js"></script>

            <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter,linkedin"></div>

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
            
            <div class="broadcast-status">
                <?if(!empty($arResult["DATE_START"])):?>
                    начало в <?=substr($arResult["DATE_START"], 11, 5)?>
                <?else:?>
                    передача прошла
                <?endif;?>
            </div>
            
			<h3 class="broadcast-title"><?=$arResult["UF_TITLE"]?></h3>
			<div class="info-row">
				<div class="broadcast-descr-col">
                    <p><?=strip_tags($arResult["UF_DESC"])?></p>
                    <?if(!empty($arResult["UF_SUB_DESC"])):?>
                        <p><?=strip_tags($arResult["UF_SUB_DESC"])?></p>
                    <?endif;?>
                    
                    <?if(intval($arResult["SERIAL"]["ID"])>0):?>
                        <a href="#" class="record-serial" onclick="subscribeSerial(<?=$arResult["ID"]?>); return false;" data-id="<?=$arResult["ID"]?>">Поставить на запись будующие серии</a>
                    <?endif;?>
                </div>
				<div class="broadcast-info-col-1">
					<dl class="info-list">
						<?if(!empty($arResult["UF_GANRE"])):?>
    						<dt>Жанры:</dt>
    						<dd><?=$arResult["UF_GANRE"]?></dd>
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
    						<dd>
                                <?
                                foreach($arResult["UF_DIRECTOR"] as $key=>$arActor)
                                {
                                    if($key!=0) echo ", ";
                                    ?><a href="<?=$arActor["LINK"]?>" target="_blank" rel="nofollow"><?=$arActor["NAME"]?></a><?
                                }
                                ?>
                            </dd>
                        <?endif;?>
                        <?if(!empty($arResult["UF_PRESENTER"])):?>
    						<dt>Ведущие:</dt>
    						<dd>
                            <?
                                foreach($arResult["UF_PRESENTER"] as $key=>$arActor)
                                {
                                    if($key!=0) echo ", ";
                                    ?><a href="<?=$arActor["LINK"]?>" target="_blank" rel="nofollow"><?=$arActor["NAME"]?></a><?
                                }
                                ?>
                            </dd>
                        <?endif;?>
                        <?if(!empty($arResult["UF_ACTOR"])):?>
    						<dt>В ролях:</dt>
    						<dd>
                            <?
                                foreach($arResult["UF_ACTOR"] as $key=>$arActor)
                                {
                                    if($key!=0) echo ", ";
                                    ?><a href="<?=$arActor["LINK"]?>" target="_blank" rel="nofollow"><?=$arActor["NAME"]?></a><?
                                }
                                ?>
                            </dd>
                        <?endif;?>	
                    </dl>
				</div>
			</div>
			<div class="advert-holder">
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/schedule-detail-banner.php"), false);?>
			</div>
		</div>
		<span class="channel-back-logo"><span data-icon="<?=$arResult["UF_ICON"]?>"></span></span>
        
        
        
        <div id="disqus_thread"></div>
        <script>
        
        /**
        *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
        *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
        
        var disqus_config = function () {
        //this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
        this.page.identifier = <?=$arResult["UF_PROG_ID"]?>; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
        };
        
        (function() { // DON'T EDIT BELOW THIS LINE
        var d = document, s = d.createElement('script');
        s.src = '//tvguru-com.disqus.com/embed.js';
        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
        })();
        </script>
        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

        
	</div><!-- /.block-body -->
</section><!-- /.broadcast-card -->