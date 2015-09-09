<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

global $USER;
CModule::IncludeModule("iblock");
$arResult["CHANNELS"] = array();

$selectedServices = array();
$CSubscribeEx = new CSubscribeEx("SERVICE");
$arServices = $CSubscribeEx->getList(array("UF_ACTIVE"=>"Y", "UF_USER"=>$USER->GetID()), array("UF_SERVICE"));
foreach($arServices as $arService)
{
    $selectedServices[] = $arService["UF_SERVICE"];
}

$arServices = CServiceEx::getList(array("ACTIVE"=>"Y"), array("ID", "NAME", "PREVIEW_TEXT", "PROPERTY_TEXT", "PROPERTY_PRICE", "PROPERTY_DISK"));
foreach($arServices as &$arService)
{
    if(in_array($arService["ID"], $selectedServices))
    {
        $arService["SELECTED"] = true;
    }
}

$arResult["SERVICES"] = $arServices;

$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();

$arResult["USER"] = $arUser;

if(floatval($arResult["USER"]["UF_CAPACITY_BUSY"])==0 || floatval($arResult["USER"]["UF_CAPACITY"])==0)
{
    $arResult["DISK_SPACE_FILLED"] = 0;
}else{
    $arResult["DISK_SPACE_FILLED"] = round(floatval($arResult["USER"]["UF_CAPACITY_BUSY"])/floatval($arResult["USER"]["UF_CAPACITY"]), 4);
}

$this->IncludeComponentTemplate();
?>