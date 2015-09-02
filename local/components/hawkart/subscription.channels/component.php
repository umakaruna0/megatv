<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

global $USER;
CModule::IncludeModule("iblock");
$arResult["CHANNELS"] = array();

$selectedChannels = array();
$CSubscribeEx = new CSubscribeEx("CHANNEL");
$arChannels = $CSubscribeEx->getList(array("UF_ACTIVE"=>"Y", "UF_USER"=>$USER->GetID()), array("UF_CHANNEL"));
foreach($arChannels as $arChannel)
{
    $selectedChannels[] = $arChannel["UF_CHANNEL"];
}

$arChannels = CChannel::getList(array("ACTIVE"=>"Y"), array("ID", "NAME", "PROPERTY_ICON", "PROPERTY_PRICE"));
foreach($arChannels as &$arChannel)
{
    if(in_array($arChannel["ID"], $selectedChannels))
    {
        $arChannel["SELECTED"] = true;
    }
}

$arResult["CHANNELS"] = $arChannels;

$this->IncludeComponentTemplate();
?>