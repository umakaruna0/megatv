<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $APPLICATION;
$arResult = array();
$arResult["URL"] = $APPLICATION->GetCurDir();
$arResult["GEO"] = \Hawkart\Megatv\CityTable::getGeoCity();
$arResult["ITEMS"] = array();
$arResult["CUR_CITY"] = array();

$arFilter = array(
    "=UF_ACTIVE" => 1
);
$arSelect = array("ID", "UF_TITLE", "UF_ISO");
$obCache = new \CPHPCache;
if( $obCache->InitCache(86400, serialize($arFilter).serialize($arSelect), "/langList/"))
{
	$arResult["ITEMS"] = $obCache->GetVars();
}
elseif($obCache->StartDataCache())
{
    $result = \Hawkart\Megatv\CountryTable::getList(array(
        'filter' => $arFilter,
        'select' => $arSelect,
        'order' => array("UF_TITLE" => "ASC")
    ));
    while ($arLang = $result->fetch())
    {
        $arResult["ITEMS"][] = $arLang;
    }
	$obCache->EndDataCache($arResult["ITEMS"]); 
}

foreach($arResult["ITEMS"] as $arLang)
{
    if($arResult["GEO"]["UF_COUNTRY_ID"]==$arLang["ID"]) $arResult["CUR_LANG"] = $arLang;
}


$this->IncludeComponentTemplate();
?>