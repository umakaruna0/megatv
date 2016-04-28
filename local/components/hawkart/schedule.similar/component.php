<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $USER, $APPLICATION;
$arResult = array();
$arParams = $arParams + array(
    "DATETIME" => \CTimeEx::getDatetime(),
);

//get channel by code
if(empty($_REQUEST["event"]))
{
    $arFilter = array("=UF_PROG.UF_EPG_ID" => $arParams["ELEMENT_CODE"]);
}else{
    $arFilter = array("=ID" => $_REQUEST["event"]);
}
$result = \Hawkart\Megatv\ScheduleTable::getList(array(
    'filter' => $arFilter, //array("=UF_CODE" => $arParams["ELEMENT_CODE"]),
    'select' => array(
        "ID", "UF_CATEGORY" => 'UF_PROG.UF_CATEGORY'
    ),
    'limit' => 1
));
if ($arResult = $result->fetch())
{
    $category = $arResult["UF_CATEGORY"];
}

//get channel by code
$prog_ids = array();
$arResult["PROGS"] = array();
$arParams["CURRENT_DATETIME"] = date("d.m.Y H:i:s", strtotime($arParams["DATETIME"]["SERVER_DATETIME_WITH_OFFSET"]));
$dateStart = date("Y-m-d H:i:s", strtotime($arParams["CURRENT_DATETIME"]));
$result = \Hawkart\Megatv\ScheduleTable::getList(array(
    'filter' => array(
        "!ID" => $arResult["ID"],
        "=UF_PROG.UF_CATEGORY" => $category,
        "UF_CHANNEL.UF_ACTIVE" => 1,
        ">UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'),
    ),
     'select' => array(
        "ID", "UF_CODE", "UF_DATE_START", "UF_DATE_END", "UF_DATE", "UF_CHANNEL_ID", "UF_PROG_ID",
        "UF_TITLE" => "UF_PROG.UF_TITLE", "UF_SUB_TITLE" => "UF_PROG.UF_SUB_TITLE", "UF_IMG_PATH" => "UF_PROG.UF_IMG.UF_PATH",
        "UF_CHANNEL_CODE" => "UF_CHANNEL.UF_CODE", "UF_ID" => "UF_PROG.UF_EPG_ID"
    )
));
while ($arSchedule = $result->fetch())
{
    if(in_array($arSchedule["UF_PROG_ID"], $prog_ids)) continue;
    
    $prog_ids[] = $arSchedule["UF_PROG_ID"];    //for unrepeat
    
    $arSchedule["UF_DATE_START"] = $arSchedule["DATE_START"] = $arSchedule['UF_DATE_START']->toString();
    $arSchedule["UF_DATE_END"] = $arSchedule["DATE_END"] = $arSchedule['UF_DATE_END']->toString();
    $arSchedule["UF_DATE"] = $arSchedule['UF_DATE']->toString();
    $arSchedule["DATE"] = $arSchedule["UF_DATE"];
    $arSchedule["PROG_ID"] = $arSchedule["UF_PROG_ID"];
    $arSchedule["DETAIL_PAGE_URL"] = "/channels/".$arSchedule["UF_CHANNEL_CODE"]."/".$arSchedule["UF_ID"]."/?event=".$arSchedule["ID"];
    $arResult["PROGS"][] = $arSchedule;
}

$this->IncludeComponentTemplate();
?>