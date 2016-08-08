<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $USER, $APPLICATION;
$arResult = array();

$arResult = array();
$arFilter = array("!UF_EPG_ID" => false, "!UF_EXTERNAL_URL" => false);
$arSelect = array("ID", "UF_TITLE", "UF_EPG_ID", "UF_DESC");
$arOrder = array("UF_TITLE" => "ASC");
$obCache = new \CPHPCache;
if( $obCache->InitCache(86400, serialize($arFilter).serialize($arSelect).serialize($arOrder), "/serialList/"))
{
	$arResult["ITEMS"] = $obCache->GetVars();
}
elseif($obCache->StartDataCache())
{
    $result = \Hawkart\Megatv\SerialTable::getList(array(
        'filter' => $arFilter,
        'select' => $arSelect,
        'order' => $arOrder
    ));
    while ($row = $result->fetch())
    {
        $arResult["ITEMS"][] = $row;
    }
    $obCache->EndDataCache($arResult["ITEMS"]);
}

$this->IncludeComponentTemplate();
?>