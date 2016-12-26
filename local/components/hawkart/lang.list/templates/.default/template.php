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

<div class="box-left__box-lang box-lang">
    <div class="js-langdd-init selectdd langdd-init" data-module="lang-select" data-class="langdd-container">
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
        <form action="" method="POST" class="js-lang-select-form">
            <div class="selectdd__titledd js-jdd-open langdd-init__titledd">
                <span class="selectdd__name"><?=$arResult["CUR_LANG"]["UF_ISO"]?></span>
                <span class="selectdd__corner"></span>
            </div>
            <input type="hidden" name="lang-id" value="<?=$arParams["CUR_LANG"]["ID"]?>" class="js-lang-select-value">
            <?=bitrix_sessid_post()?>
        </form>
    </div>
</div>