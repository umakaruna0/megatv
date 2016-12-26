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

<div class="box-left__box-city">
    <div class="js-citydd-init selectdd" data-class="citydd-container" data-module="city-select">
        <script class="js-citydd-json" type="text/x-config">
            {
                "url": "<?=$arResult["URL"]?>",
                "cities": [
                    <?foreach($arResult["ITEMS"] as $key=>$arItem):?>
                        { "id": <?=$arItem["ID"]?>, "text": "<?=$arItem["UF_TITLE"]?>" }<?if($key<count($arResult["ITEMS"])-1):?>,<?endif;?>
                    <?endforeach;?>
                ],
                "showCityRequestPopover": false, 
                "consts" : {
                    "titleNotFound": "Не найдено!"
                }
            }
        </script>
        <form action="<?=$arResult["URL"]?>" method="POST" class="js-city-select-form">
            <div class="selectdd__titledd js-jdd-open citydd">
                <span class="selectdd__name js-jdd-title"><?=$arResult["CUR_CITY"]["UF_TITLE"]?></span>
                <span class="selectdd__corner"></span>
            </div>
            <input type="hidden" name="city-id" value="<?=$arParams["CITY_GEO"]["ID"]?>" class="js-city-select-value">
            <?=bitrix_sessid_post()?>
        </form>
    </div>
</div>