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
        //"UF_PROPG.UF_RECOMMEN" => 1,
        ">UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'),
    ),
    'select' => array(
        "ID", "UF_CODE", "UF_DATE_START", "UF_DATE_END", "UF_DATE", "UF_CHANNEL_ID", "UF_PROG_ID",
        "UF_TITLE" => "UF_PROG.UF_TITLE", "UF_SUB_TITLE" => "UF_PROG.UF_SUB_TITLE", "UF_IMG_PATH" => "UF_PROG.UF_IMG.UF_PATH",
        "UF_CHANNEL_CODE" => "UF_CHANNEL.UF_CODE", "UF_CATEGORY" => "UF_PROG.UF_CATEGORY",
        "UF_ID" => "UF_PROG.UF_EPG_ID"
    ),
    'order' => array("UF_PROG.UF_RATING" => "DESC"),
    'limit' => 48
));
while ($arSchedule = $result->fetch())
{
    $arSchedule["UF_DATE_START"] = $arSchedule["DATE_START"] = $arSchedule['UF_DATE_START']->toString();
    $arSchedule["UF_DATE_END"] = $arSchedule["DATE_END"] = $arSchedule['UF_DATE_END']->toString();
    $arSchedule["UF_DATE"] = $arSchedule["DATE"] = $arSchedule['UF_DATE']->toString();
    $arSchedule["DETAIL_PAGE_URL"] = "/channels/".$arSchedule["UF_CHANNEL_CODE"]."/".$arSchedule["UF_ID"]."/?event=".$arSchedule["ID"];

    if(!empty($arSchedule["UF_CATEGORY"]))
        $arCats[] = $arSchedule["UF_CATEGORY"];
    
    $arResult["PROGS"][] = $arSchedule;
}

if(count($arResult["PROGS"])>0)
{
    $arResult["PROGS"] = \Hawkart\Megatv\CScheduleView::setIndex(array(
        "PROGS" => $arResult["PROGS"],
    ));
}

$arCats = array_unique($arCats);

$arResult["CATEGORIES"] = array();
foreach($arCats as $category)
{
    $arParams = array("replace_space"=>"-", "replace_other"=>"-");
    $str = \CDev::translit($category, "ru", $arParams);
    $arResult["CATEGORIES"][$category] = $str; 
}

$this->IncludeComponentTemplate();
?>