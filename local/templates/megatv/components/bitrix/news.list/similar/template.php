<section class="related-brodcasts" data-module="related-broadcasts">
	<div class="block-header">
		<h3 class="block-title">Похожие передачи</h3>
	</div>
	<div class="block-body">
		<ul class="broadcasts-list">
			<?
            foreach($arResult["PROGS"] as $key=>$arProg)
            {                
                echo CProgTime::getProgSimilar($arProg, $arParams);
            }
            ?>
		</ul>
	</div>
</section>