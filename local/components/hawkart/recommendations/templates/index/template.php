<section class="recomended-broadcasts" data-module="recomended-broadcasts">
    <script type="text/x-config">
    {
        "recordingURL": "<?=SITE_TEMPLATE_PATH?>/ajax/to_record.php"
    }
    </script>
	<div class="block-header">
		<h3 class="block-title">Мы рекомендуем</h3>
	</div>
	<div class="block-body">
		<div class="broadcasts-list">
            <?
            foreach($arResult["PROGS"] as $key=>$arProg)
            {
                if($key==0)
                {
                    ?>
                    <div class="quadro-container">
				        <div class="quadro-container-items-wrap">
                    <?
                }
                
                echo CProgTime::getProgInfoRecommendIndex($arProg, $arParams, $key);
                
                if($key==3)
                {
                    ?>
                        </div>
                    </div>
                    <?
                }
            }
            ?>
		</div>
	</div><!-- /.block-body -->
</section><!-- /.recomended-broadcasts -->