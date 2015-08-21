<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

global $USER;
if($USER->IsAuthorized())
{
    $rsUser = CUser::GetByID($USER->GetID());
    $arUser = $rsUser->Fetch();
    
    $arrFilter = array(
        "IBLOCK_ID" => PASSPORT_IB,
        "ACTIVE" => "Y",
        "PROPERTY_USER_ID" => $arUser["ID"]
    );
    $arSelect = array("ID", "PROPERTY_SERIA_NUMBER", "PROPERTY_WHEN_ISSUED", "PROPERTY_CODE_DIVISION", "PREVIEW_TEXT", "DETAIL_TEXT");
    $rsRes = CIBlockElement::GetList( $arOrder, $arrFilter, false, false, $arSelect );
	$arPassport = $rsRes->GetNext();
    
    $arSocials = array();
    $arrFilter = array(
        "IBLOCK_ID" => USER_SOCIAL_IB,
        "ACTIVE" => "Y",
        "PROPERTY_USER_ID" => $arUser["ID"]
    );
    $arSelect = array("PROPERTY_SOCIAL_PROVIDER");
    $rsRes = CIBlockElement::GetList( $arOrder, $arrFilter, false, false, $arSelect );
	if( $arItem = $rsRes->GetNext() )
    {
        $arSocials[] = $arItem["PROPERTY_SOCIAL_PROVIDER_VALUE"];
	}
    
    $arResult["USER"] = $arUser;
    $arResult["USER"]["SOCIALS"] = $arSocials;
    $arResult["USER"]["PASSPORT"] = $arPassport;
    
    $arPassport = explode(" ", $arPassport["PROPERTY_SERIA_NUMBER_VALUE"]);
    $arResult["USER"]["PASSPORT"]["SERIA"] = trim($arPassport[0]);
    $arResult["USER"]["PASSPORT"]["NUMBER"] = trim($arPassport[1]);
}

$this->IncludeComponentTemplate();
?>