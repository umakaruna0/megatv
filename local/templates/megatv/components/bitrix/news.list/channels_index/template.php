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

<?
/*** В настройки компонента
** "AJAX" => $_REQUEST["AJAX"],
** "LIST_URL" => $APPLICATION->GetCurDir()
**/

// номер текущей страницы
$curPage = $arResult["NAV_RESULT"]->NavPageNomer;
// всего страниц - номер последней страницы
$totalPages = $arResult["NAV_RESULT"]->NavPageCount;
// номер постраничной навигации на странице
$navNum = $arResult["NAV_RESULT"]->NavNum;
$curPage++;
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
	<div class="categories-items kinetic-active">
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
            <?if($totalPages<$curPage):?>
                <script>
                    var el = document.getElementById('channels-show-ajax-link');
                    el.parentNode.removeChild(el);
                </script>
            <?endif;?>
        </div>
    </div><!-- /.categories-items -->
    <?if($arParams["DISPLAY_BOTTOM_PAGER"] == "Y" && $totalPages>1):?>
        <a href="#" class="more-link" id="channels-show-ajax-link" data-load="<?=$arParams["LIST_URL"]?>" data-page="<?=$curPage?>" data-ajax-type="CHANNELS" data-type="fetch-results-link"><span data-icon="icon-show-more-arrow"></span> Показать еще каналы</a>
    <?endif;?>
</section>