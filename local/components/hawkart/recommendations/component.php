<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

$arResult["PROGS"] = array();
$arTime = CTimeEx::getDatetime();

//активные каналы
$activeChannels = CChannel::getList(array("ACTIVE"=>"Y"), array("ID", "DETAIL_PAGE_URL", "PROPERTY_ICON"));
$ids = array();
$arResult["CHANNELS"] = array();
foreach($activeChannels as $activeChannel)
{
    $ids[] = $activeChannel["ID"];
    $arResult["CHANNELS"][$activeChannel["ID"]] = $activeChannel;
}

$arrFilter = array(
    "IBLOCK_ID" => PROG_IB,
    "ACTIVE" => "Y",
    "!PROPERTY_RATING"  => false,
    "PROPERTY_CHANNEL" => CIBlockElement::SubQuery(
        "ID",
        array(
            "IBLOCK_ID" => CHANNEL_IB,
            "ACTIVE" => "Y",
        )
    ));

$arSelect = array("ID", "NAME", "PROPERTY_CHANNEL", "PROPERTY_SUB_TITLE", "PREVIEW_PICTURE", "PROPERTY_PICTURE_DOUBLE", "PROPERTY_PICTURE_HALF");
$rsRes = CIBlockElement::GetList( array("PROPERTY_RATING" => "DESC"), $arrFilter, false, false, $arSelect );
while( $arItem = $rsRes->GetNext() )
{
    $arProgs[] = $arItem;
}

//CDev::pre($arProgs);

if(count($arProgs)>0)
{
    $progIds = array();
    foreach($arProgs as $arProg)
    {
        $progIds[] = $arProg["ID"];
    }
    
    $arProgTimes = CProgTime::getList(array(
        ">=PROPERTY_DATE_START" => CTimeEx::datetimeForFilter(date("Y-m-d H:i:s")),
        "PROPERTY_PROG" => $progIds,
    ), array("ID", "CODE", "PROPERTY_DATE_START", "PROPERTY_DATE_END", "PROPERTY_PROG", "PROPERTY_CHANNEL"));
    foreach($arProgTimes as $arProgTime)
    {
        if(!isset($arResult["PROGTIMES"][$arProgTime["PROPERTY_PROG_VALUE"]]))
            $arResult["PROGTIMES"][$arProgTime["PROPERTY_PROG_VALUE"]] = $arProgTime;
    }
    
    $key = 0;
    foreach($arProgs as $arProg)
    {
        if(isset($arResult["PROGTIMES"][$arProg["ID"]]))
        {
            $channel = $arProg["PROPERTY_CHANNEL_VALUE"];
            $arSchedule = $arResult["PROGTIMES"][$arProg["ID"]];
            $arProg["SCHEDULE_ID"] = $arSchedule["ID"];
            $arProg["CHANNEL_ID"] = $channel;
            $arProg["DATE_START"] = CTimeEx::dateOffset($arTime["OFFSET"], $arSchedule["PROPERTY_DATE_START_VALUE"]);
            $arProg["DATE_END"] = CTimeEx::dateOffset($arTime["OFFSET"], $arSchedule["PROPERTY_DATE_END_VALUE"]);
            $arProg["DETAIL_PAGE_URL"] = $arResult["CHANNELS"][$channel]["DETAIL_PAGE_URL"].$arSchedule["CODE"]."/";
            
            $arResult["PROGS"][] = $arProg;
            
            $key++;
            if($key>48) break;
        }
    }
    
    if($arParams=="MAIN_PAGE")
    {
        $arResult["PROGS"] = CScheduleTable::setRecommendIndex(array(
            "PROGS" => $arResult["PROGS"],
        ));
    }
}

$this->IncludeComponentTemplate();
?>