<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

global $USER;
CModule::IncludeModule("iblock");
$arResult["CHANNELS"] = array();

$selectedServices = array();
$arServices = array();

//get service subscribe list
$result = \Hawkart\Megatv\SubscribeTable::getList(array(
    'filter' => array("UF_ACTIVE"=>1, "=UF_USER_ID" => $USER->GetID()),
    'select' => array("UF_SERVICE_ID")
));
while ($arSub = $result->fetch())
{
    $selectedServices[] = $arSub["UF_CHANNEL_ID"];
}

//get all services
$result = \Hawkart\Megatv\ServiceTable::getList(array(
    'filter' => array("UF_ACTIVE" => 1),
    'select' => array("ID", "UF_TITLE", "UF_TEXT", "UF_PRICE", "UF_DISK_TYPE", "UF_DESC")
));
while ($arService = $result->fetch())
{
    if(in_array($arService["ID"], $selectedServices))
        $arService["SELECTED"] = true;

    $arServices[] = $arService;
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