<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arResult["PROGS"] = array();
$arTime = \CTimeEx::getDatetime();

//get progs by rating
$arParams["CURRENT_DATETIME"] = date("d.m.Y H:i:s", strtotime($arTime["SERVER_DATETIME_WITH_OFFSET"]));
$dateStart = date("Y-m-d H:i:s", strtotime($arParams["CURRENT_DATETIME"]));
$result = \Hawkart\Megatv\ScheduleTable::getList(array(
    'filter' => array(
        "UF_CHANNEL.UF_ACTIVE" => 1,
        "UF_PROG.UF_ACTIVE" => 1,
        //">UF_PROG.UF_RATING" => 0,
        ">UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'),
    ),
    'select' => array(
        "ID", "UF_CODE", "UF_DATE_START", "UF_DATE_END", "UF_DATE", "UF_CHANNEL_ID", "UF_PROG_ID",
        "UF_TITLE" => "UF_PROG.UF_TITLE", "UF_SUB_TITLE" => "UF_PROG.UF_SUB_TITLE", "UF_IMG_PATH" => "UF_PROG.UF_IMG.UF_PATH",
        "UF_ICON" => "UF_CHANNEL.UF_ICON", "UF_CHANNEL_CODE" => "UF_CHANNEL.UF_CODE", "UF_ID" => "UF_PROG.UF_EPG_ID"
    ),
    'order' => array("UF_PROG.UF_RATING" => "DESC"),
    'limit' => 24
));
while ($arSchedule = $result->fetch())
{
    $arSchedule["UF_DATE_START"] = $arSchedule["DATE_START"] = $arSchedule['UF_DATE_START']->toString();
    $arSchedule["UF_DATE_END"] = $arSchedule["DATE_END"] = $arSchedule['UF_DATE_END']->toString();
    $arSchedule["UF_DATE"] = $arSchedule["DATE"] = $arSchedule['UF_DATE']->toString();
    $arSchedule["DETAIL_PAGE_URL"] = "/channels/".$arSchedule["UF_CHANNEL_CODE"]."/".$arSchedule["UF_ID"]."/?event=".$arSchedule["ID"];
    $arResult["PROGS"][] = $arSchedule;
}

//CDev::pre($arResult["PROGS"]);

if($arParams["TEMPLATE"]=="MAIN_PAGE")
{
    $arResult["PROGS"] = \Hawkart\Megatv\CScheduleView::setRecommendIndex(array(
        "PROGS" => $arResult["PROGS"],
    ));
}

$this->IncludeComponentTemplate();
?>