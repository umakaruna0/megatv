<?
if(count($arResult["PROGS"])==0)
    return false;
?>

<section class="recomended-broadcasts is-single-items" data-module="recomended-broadcasts">
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
                echo \Hawkart\Megatv\CScheduleTemplate::getProgSimilar($arProg, $arParams);
            }
            ?>
		</div>
	</div>
</section>