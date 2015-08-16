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

function getProgInfoIndex($arProg)
{
    ob_start();
    ?>
    <div class="item-image-holder" style="background-image: url(<?=$arProg["PICTURE"]["SRC"]?>"></div>
	<span class="item-status-icon">
		<span data-icon="icon-recordit"></span>
	</span>
	<div class="item-header">
		<time><?=substr($arProg["DATE_START"], 10, 5)?></time>
		<a href="<?=$arProg["DETAIL_PAGE_URL"]?>"><?=$arProg["NAME"]?></a>
	</div>
    <?
    $content = ob_get_contents();  
    ob_end_clean();
    
    return $content;
}
?>

<section class="broadcast-results" data-module="broadcast-results">
	<div class="categories-logos">
        <?foreach($arResult["CHANNELS"] as $arItem):?>
        	<?
        	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        	?>
    		<a class="category-logo" href="<?=$arItem["DETAIL_PAGE_URL"]?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
    			<span data-icon="icon-<?=$arItem["PROPERTIES"]["ICON"]["VALUE"]?>-channel"></span>
                <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" />
    		</a>
        <?endforeach?>
	</div>
	<div class="categories-items">
        <div class="row-wrap">
            <?foreach($arResult["CHANNELS"] as $arItem):?>
                <div class="category-row">
                    <?
                    foreach($arItem["PROGS"] as $key=>$arProg)
                    {
                        //CDev::pre($arProg);
                        if($arProg["CLASS"]=="one")
                        {
                            ?>
                            <div class="item status-recordable is-noimage">
                                <?=getProgInfoIndex($arProg)?>
            					<?/*<div class="item-image-holder" style="background-image: url(<?=$arProg["PICTURE"]["SRC"]?>"></div>
            					<span class="item-status-icon">
        							<span data-icon="icon-recordit"></span>
        						</span>
            					<div class="item-header">
            						<time><?=substr($arProg["DATE_START"], 10, 5)?></time>
            						<a href="<?=$arProg["DETAIL_PAGE_URL"]?>"><?=$arProg["NAME"]?></a>
            					</div>*/?>
            				</div>
                            <?
                        }
                        
                        if($arProg["CLASS"]=="double")
                        {
                            $arProgNext = $arItem["PROGS"][$key+1];
                            ?>
                            <div class="pair-container">
                                <div class="item status-recordable is-noimage" data-type="draggable" data-target="drop-area">
                					<div class="item-image-holder" style="background-image: url(<?=$arProg["PICTURE"]["SRC"]?>"></div>
                					<span class="item-status-icon">
            							<span data-icon="icon-recordit"></span>
            						</span>
                					<div class="item-header">
                						<time><?=substr($arProg["DATE_START"], 10, 5)?></time>
                						<a href="<?=$arProg["DETAIL_PAGE_URL"]?>"><?=$arProg["NAME"]?></a>
                					</div>
                				</div>
                                <div class="item status-recordable is-noimage" data-type="draggable" data-target="drop-area">
                					<div class="item-image-holder" style="background-image: url(<?=$arProgNext["PICTURE"]["SRC"]?>"></div>
                					<span class="item-status-icon">
            							<span data-icon="icon-recordit"></span>
            						</span>
                					<div class="item-header">
                						<time><?=substr($arProgNext["DATE_START"], 10, 5)?></time>
                						<a href="<?=$arProgNext["DETAIL_PAGE_URL"]?>"><?=$arProgNext["NAME"]?></a>
                					</div>
                				</div>
            				</div>
                            <?
                            $key++;
                        }
                        
                        if($arProg["CLASS"]=="half")
                        {
                            ?>
                            <div class="item double-item status-recordable is-noimage" data-type="draggable" data-target="drop-area">
            					<div class="item-image-holder" style="background-image: url(<?=$arProg["PICTURE"]["SRC"]?>"></div>
            					<span class="item-status-icon">
        							<span data-icon="icon-recordit"></span>
        						</span>
            					<div class="item-header">
            						<time><?=substr($arProg["DATE_START"], 10, 5)?></time>
            						<a href="<?=$arProg["DETAIL_PAGE_URL"]?>"><?=$arProg["NAME"]?></a>
            					</div>
            				</div>
                            <?
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