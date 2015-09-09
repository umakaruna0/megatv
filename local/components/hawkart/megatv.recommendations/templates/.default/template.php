<section class="broadcast-results" data-module="broadcast-results">
    <div class="categories-logos">
        <a class="category-logo" href="#">
    		<span data-icon="icon-megatv-recommendations"></span>
    		<span class="category-title">Мега ТВ рекомендует</span>
    	</a>
    </div>
    <div class="categories-items">
        <div class="row-wrap">
            <div class="category-row">
                <?
                $notShow = array();
                foreach($arResult["PROGS"] as $key=>$arProg)
                {
                    if(in_array($key, $notShow))
                        continue;
                        
                    if($arProg["CLASS"]=="one" || $arProg["CLASS"]=="double")
                    {
                        echo CProgTime::getProgInfoIndex($arProg);
                    }
                    
                    if($arProg["CLASS"]=="half")
                    {
                        $arProgNext = $arResult["PROGS"][$key+1];
                        ?>
                        <div class="pair-container">
                            <?=CProgTime::getProgInfoIndex($arProg)?>
                            <?=CProgTime::getProgInfoIndex($arProgNext)?>
        				</div>
                        <?
                        $notShow[]=$key+1;
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>