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

<?$APPLICATION->IncludeComponent("hawkart:schedule", "", Array(
    "ELEMENT_CODE" => $arResult["VARIABLES"]["SCHEDULE_CODE"],
    "CHANNEL_CODE" => $arResult["VARIABLES"]["CHANNEL_CODE"],
), false);?>

<?$APPLICATION->IncludeComponent("hawkart:schedule.similar", "", Array(
    "CHANNEL_CODE" => $arResult["VARIABLES"]["CHANNEL_CODE"],
    "ELEMENT_CODE" => $arResult["VARIABLES"]["SCHEDULE_CODE"], 
    "TITLE" => "Похожие передачи",
    "AJAX" => $_REQUEST["AJAX"],
    "LIST_URL" => $APPLICATION->GetCurPage(),
    "NEWS_COUNT" => "12"
), false);?>