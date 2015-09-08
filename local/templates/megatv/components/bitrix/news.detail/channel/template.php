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
<section class="channel-card" data-module="channel-card">
    <script type="text/x-config">
    {
        "recordingURL": "<?=SITE_TEMPLATE_PATH?>/ajax/to_record.php"
    }
    </script>
	<div class="channel-info">
		<div class="channel-logo">
			<span data-icon="<?=$arResult["PROPERTIES"]["ICON"]["VALUE"]?>" data-size="small"></span>
		</div>
		<div class="channel-descr">
			<?=htmlspecialchars_decode($arResult["DETAIL_TEXT"])?>
			<p><a href="<?=$arResult["PROPERTIES"]["SITE"]["VALUE"]?>">Официальный сайт</a></p>
		</div>
	</div>
	<div class="channel-broadcasts">
		<div class="broadcasts-list">
            <?
            foreach($arResult["PROGS"] as $arProg)
            {
                echo CProgTime::getProgInfoChannel($arProg, $arParams);
            }
            ?>
		</div>
	</div>
</section>