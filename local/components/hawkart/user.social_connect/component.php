<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

global $USER;
CModule::IncludeModule("iblock");
$arResult["SOCIALS"] = array();

if($USER->IsAuthorized())
{
    $arSocials = array();
    $arrFilter = array(
        "IBLOCK_ID" => USER_SOCIAL_IB,
        "ACTIVE" => "Y",
        "PROPERTY_USER_ID" => $USER->GetID()
    );
    $arSelect = array("PROPERTY_SOCIAL_PROVIDER");
    $rsRes = CIBlockElement::GetList( $arOrder, $arrFilter, false, false, $arSelect );
	while( $arItem = $rsRes->GetNext() )
    {
        $arSocials[] = strtolower($arItem["PROPERTY_SOCIAL_PROVIDER_VALUE"]);
	}
}

$arrFilter = array(
    "IBLOCK_ID" => SOCIAL_CONFIG_IB,
    "ACTIVE" => "Y",
    //"!PROPERTY_SECRET" => false,
    //"!PROPERTY_PROVIDER" => false,
    //"!PROPERTY_ID" => false,
);
$arSelect = array("PROPERTY_PROVIDER", "PROPERTY_ICON", "PROPERTY_COUNT_GB");
$rsRes = CIBlockElement::GetList( $arOrder, $arrFilter, false, false, $arSelect );
while( $arItem = $rsRes->GetNext() )
{
    $arSocial = array();
    $checked = false;
    if(in_array(strtolower($arItem["PROPERTY_PROVIDER_VALUE"]), $arSocials))
    {
        $checked = "Y";
    }
    
    $arSocial = array(
        "ICON"  => $arItem["PROPERTY_ICON_VALUE"],
        "PROVIDER"  => $arItem["PROPERTY_PROVIDER_VALUE"],
        "CHECKED" => $checked,
        "GB" => $arItem["PROPERTY_COUNT_GB_VALUE"],
    );
    $arResult["SOCIALS"][] = $arSocial;
}

$this->IncludeComponentTemplate();
?>