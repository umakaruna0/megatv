<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $USER, $APPLICATION;
$arResult = array();

$arFilter = array("=UF_EPG_ID" => $_REQUEST["EPG_ID"]);
$arSelect = array("ID", "UF_TITLE", "UF_DESC");
$obCache = new \CPHPCache;
if( $obCache->InitCache(86400, serialize($arFilter).serialize($arSelect), "/serialByEpgID/"))
{
	$arResult["SERIAL"] = $obCache->GetVars();
}
elseif($obCache->StartDataCache())
{
    $result = \Hawkart\Megatv\SerialTable::getList(array(
        'filter' => $arFilter,
        'select' => $arSelect
    ));
    if ($row = $result->fetch())
    {
        $arResult["SERIAL"] = $row;
    }
    $obCache->EndDataCache($arResult["SERIAL"]); 
}

$arFilter = array("=UF_SERIAL.UF_EPG_ID" => $_REQUEST["EPG_ID"]);
$arSelect = array("ID", "UF_TITLE", "UF_EXTERNAL_ID", "UF_THUMBNAIL_URL");
$obCache = new \CPHPCache;
if( $obCache->InitCache(86400, serialize($arFilter).serialize($arSelect), "/serialProgExList/"))
{
	$arResult["ITEMS"] = $obCache->GetVars();
}
elseif($obCache->StartDataCache())
{
    $result = \Hawkart\Megatv\ProgExternalTable::getList(array(
        'filter' => $arFilter,
        'select' => $arSelect
    ));
    while ($row = $result->fetch())
    {
        if(strpos($row["UF_THUMBNAIL_URL"], "rutube")!==false)
        {
            $row["UF_THUMBNAIL_URL"].="?size=m";
        }
        $arResult["ITEMS"][] = $row;
    }
    $obCache->EndDataCache($arResult["ITEMS"]); 
}
$APPLICATION->SetTitle($arResult["SERIAL"]["UF_TITLE"]);
$APPLICATION->SetPageProperty($arResult["SERIAL"]["UF_TITLE"]);
$APPLICATION->SetPageProperty("description", TruncateText($arResult["SERIAL"]["UF_DESC"], 256));

$this->IncludeComponentTemplate();
?>