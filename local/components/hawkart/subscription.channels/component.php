<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

global $USER;
CModule::IncludeModule("iblock");
$arResult["CHANNELS"] = array();

$selectedChannels = array();
$arChannels = CSubscribeEx::getUserList($USER->GetID(), array("UF_ACTIVE"=>"Y"), array("UF_CHANNEL"));
foreach($arChannels as $arChannel)
{
    $selectedChannels[] = $arChannel["UF_CHANNEL"];
}

//CDev::pre($arChannels);

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