<?
if(count($arResult["PROGS"])==0)
    return false;
?>

<?/*<section class="recomended-broadcasts is-single-items" data-module="recomended-broadcasts">*/?>
<section class="recommended-broadcasts" data-module="recomended-broadcasts">
    <script type="text/x-config">
    {
        "recordingURL": "<?=SITE_TEMPLATE_PATH?>/ajax/to_record.php"
    }
    </script>
    <div class="broadcasts-loader broadcasts-loader--loaded"><div class="broadcasts-loader__title"><p style="font-size:30px">Подождите,</p> <p>идёт загрузка элементов...</p></div><div class="broadcasts-loader__divimg"><img src="/local/templates/megatv/img/loader.gif" alt="" class="broadcasts-loader__img"></div></div>
	<div class="block-header">
		<h3 class="block-title"><?=$arParams["TITLE"]?></h3>
	</div>
    <div class="block-body">
	   <div class="broadcasts-list broadcast-results" data-url="<?=$arParams["LIST_URL"]?>" data-nop="<?=$arParams["NEWS_COUNT"]?>" data-activate="true">
			<?
            foreach($arResult["PROGS"] as $key=>$arProg)
            {                
                if(!empty($arProg["UF_EXTERNAL_ID"])):
                ?>
                <div class="item status-recorded status-social-v"
                    data-type="broadcast" data-broadcast-id="<?=$arProg["UF_EXTERNAL_ID"]?>"
                >
                    <div class="inner">
                       
                        <div class="item-image-holder">
                            <img class="lazy-img swiper-lazy" src="<?=$arProg["UF_THUMBNAIL_URL"]?>">
                        </div>
                        
                        <div class="broadcast__wrap-status">
                            <span class='broadcast__status'>
                                <span data-icon='icon-recorded'></span>
                                <span class='bs-status__title'>Смотреть</span>
                            </span>
                        </div>
                        
                    	<div class="item-header">
                            <div class="meta"></div>
                            <div class="title">
                        		<a href="#"><?=$arProg["UF_TITLE"]?></a>
                            </div>
                    	</div>
                    </div>
                </div>
                <?
                else:
                    echo \Hawkart\Megatv\CScheduleTemplate::getProgInfoRecommend($arProg);
                    //echo \Hawkart\Megatv\CScheduleTemplate::getProgSimilar($arProg, $arParams);
                endif;
            }
            ?>
		</div>
	</div>
</section>