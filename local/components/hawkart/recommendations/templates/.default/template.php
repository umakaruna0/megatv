<section class="broadcast-results" data-module="broadcast-results">
    <div class="categories-logos">
        <?
        foreach($arResult["TOPICS"] as &$arTopic)
        {
            ?>
            <a class="category-logo" href="#">
        		<span data-icon="icon-<?=$arTopic["ICON"]?>-recommendations"></span>
        		<span class="category-title"><?=$arTopic["TITLE"]?></span>
        	</a>
            <?
        }
        ?>
    </div>
    <div class="categories-items">
        <div class="row-wrap">
            <?
            foreach($arResult["TOPICS"] as $arTopic)
            {
                ?>
                <div class="category-row">
                    <?
                    $notShow = array();
                    foreach($arTopic["PROGS"] as $key=>$arProg)
                    {
                        if(in_array($key, $notShow))
                            continue;
                            
                        if($arProg["CLASS"]=="one" || $arProg["CLASS"]=="double")
                        {
                            echo CProgTime::getProgInfoIndex($arProg);
                        }
                        
                        if($arProg["CLASS"]=="half")
                        {
                            $arProgNext = $arTopic["PROGS"][$key+1];
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
                <?
            }
            ?>
        </div>
    </div>
</section>