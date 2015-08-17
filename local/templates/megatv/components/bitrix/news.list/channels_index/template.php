<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<section class="broadcast-results" data-module="broadcast-results">
	<div class="categories-logos">
        <?foreach($arResult["CHANNELS"] as $arItem):?>
        	<?
        	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        	?>
    		<a class="category-logo" href="<?=$arItem["DETAIL_PAGE_URL"]?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
    			<span data-icon="<?=$arItem["PROPERTIES"]["ICON"]["VALUE"]?>"></span>
                <?/*<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" />*/?>
    		</a>
        <?endforeach?>
	</div>
	<div class="categories-items">
        <div class="row-wrap">
            <?foreach($arResult["CHANNELS"] as $arItem):?>
                <div class="category-row">
                    <?
                    $notShow = array();
                    foreach($arItem["PROGS"] as $key=>$arProg)
                    {
                        if(in_array($key, $notShow))
                            continue;
                            
                        if($arProg["CLASS"]=="one" || $arProg["CLASS"]=="double")
                        {
                            echo CProgTime::getProgInfoIndex($arProg);
                        }
                        
                        if($arProg["CLASS"]=="half")
                        {
                            $arProgNext = $arItem["PROGS"][$key+1];
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
            <?endforeach?>
        </div>
    </div><!-- /.categories-items -->
</section>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>