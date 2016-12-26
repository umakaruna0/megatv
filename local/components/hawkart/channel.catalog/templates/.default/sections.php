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

<?/*$APPLICATION->IncludeComponent(
	"hawkart:channel.list",
	"",
	array(
		"NEWS_COUNT" => "45",
        "DISPLAY_BOTTOM_PAGER" => "Y",
	),
	$component,
	array("HIDE_ICONS" => "Y")
);
*/?>

<?$APPLICATION->IncludeComponent("hawkart:channel.cell", "new", 
    Array(
		"NEWS_COUNT" => "45",
        "DISPLAY_BOTTOM_PAGER" => "Y"
    ),
	false
);?>