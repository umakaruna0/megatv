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
	<div class="block-header">
		<h3 class="block-title"><?=$arParams["TITLE"]?></h3>
	</div>
    <div class="block-body">
	   <div class="broadcasts-list">
			<?
            foreach($arResult["PROGS"] as $key=>$arProg)
            {                
                if(!empty($arProg["UF_EXTERNAL_ID"])):
                ?>
                <div class="item status-recorded status-social-v"
                    data-type="broadcast" data-broadcast-id="<?=$arProg["UF_EXTERNAL_ID"]?>"
                >
                    <div class="inner">
                        <div class="item-image-holder" style="background-image: url(<?=$arProg["UF_THUMBNAIL_URL"]?>)"></div>
                        
                        <span class="item-status-icon" href="#">
            				<span data-icon="icon-recorded"></span>
            				<span class="status-desc">Смотреть</span>
            			</span>
                        
                    	<div class="item-header">
                            <div class="meta">
        						
        					</div>
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