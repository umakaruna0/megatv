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
<div class="city-select" data-module="city-select">
    <script type="text/x-config">
		{
			"url": "<?=$arResult["URL"]?>",
			"cities": [
                <?foreach($arResult["ITEMS"] as $key=>$arItem):?>
				    { "id": <?=$arItem["ID"]?>, "text": "<?=$arItem["UF_TITLE"]?>" }<?if($key<count($arResult["ITEMS"])-1):?>,<?endif;?>
                <?endforeach;?>
			],
			"showCityRequestPopover": false
		}
    </script>
    <form action="<?=$arResult["URL"]?>" method="POST" id="city-select-form">
        <select name="city-select" id="_id-city-select">
            <option value="<?=$arResult["CUR_CITY"]["ID"]?>" selected>
                <?=$arResult["CUR_CITY"]["UF_TITLE"]?>
            </option>
        </select>
        <input type="hidden" name="city-id" value="<?=$arParams["CITY_GEO"]["ID"]?>" id="city-select-value" />
        <?=bitrix_sessid_post()?>
    </form>
</div>