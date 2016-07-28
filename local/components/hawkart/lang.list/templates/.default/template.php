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
global $USER;
?>
<<<<<<< HEAD

<div class="lang-select box-left__box-lang box-lang" data-module="lang-select">
=======
<div class="lang-select" data-module="lang-select">
>>>>>>> 23cd50036497da64b44e0bc4c24882eeb021600e
    <script type="text/x-config">
		{
			"url": "<?=$arResult["URL"]?>",
			"languages": [
                <?foreach($arResult["ITEMS"] as $key=>$arItem):?>
				    { "id": <?=$arItem["ID"]?>, "text": "<?=$arItem["UF_ISO"]?>" }<?if($key<count($arResult["ITEMS"])-1):?>,<?endif;?>
                <?endforeach;?>
			]
		}
	</script>
	<form action="" id="lang-select-form" method="POST">
		<input type="hidden" name="lang-id" value="<?=$arParams["CUR_LANG"]["ID"]?>" id="lang-select-value">

        <select name="lang-select" id="lang-select">
            <option value="<?=$arResult["CUR_LANG"]["ID"]?>" selected>
                <?=$arResult["CUR_LANG"]["UF_ISO"]?>
            </option>
        </select>
        
        <?=bitrix_sessid_post()?>
	</form>
</div>