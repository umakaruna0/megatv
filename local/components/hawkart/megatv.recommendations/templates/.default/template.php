<div class="broadcasts-categories" data-module="broadcasts-categories">
	<script type="text/x-config">
		{
			"url": "/recommendations/"
		}
	</script>
	<div class="items">
		<a href="#" class="item active" data-type="item" data-category="all">
			Все
		</a>
        <?
        foreach($arResult["CATEGORIES"] as $category=> $translit)
        {
            ?>
            <a href="#<?=$translit?>" class="item" data-type="item" data-category="<?=$translit?>">
    			<?=$category?>
    		</a>
            <?
        }
        ?>
	</div>
	<div class="more" data-type="more">
		<span data-icon="icon-close"></span>
		<div class="circle"></div>
		<div class="circle"></div>
		<div class="circle"></div>
	</div>
</div>

<section class="recommended-broadcasts" data-module="broadcast-results">
    <script type="text/x-config">
    {
        "recordingURL": "<?=SITE_TEMPLATE_PATH?>/ajax/to_record.php"
    }
    </script>
    <div class="broadcasts-list">
        <?
        $notShow = array();
        if(count($arResult["PROGS"])>0)
        {
            foreach($arResult["PROGS"] as $key=>$arProg)
            {
                $arProg["CAT_CODE"] = $arResult["CATEGORIES"][$arProg["UF_CATEGORY"]];
                echo \Hawkart\Megatv\CScheduleTemplate::getProgInfoRecommend($arProg);
            }
        }else{
            ?>
            <div class="empty-content">
	    		<h1 class="empty-content__title">Список рекомендаций пуст...</h1>
	    	</div>
            <?
        }
        ?>
    </div>
</section>