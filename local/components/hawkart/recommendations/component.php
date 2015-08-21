<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

//Params
if ($arParams["CACHE_TYPE"] == "Y" || ($arParams["CACHE_TYPE"] == "A" && COption::GetOptionString("main", "component_cache_on", "Y") == "Y"))
	$arParams["CACHE_TIME"] = intval($arParams["CACHE_TIME"]);
else
	$arParams["CACHE_TIME"] = 0;
    
$arResult["TOPICS"] = array(
    array(
        "ICON" => "megatv",
        "TITLE" => "Мега ТВ рекомендует",
        "FILTER" => array(
            "!PROPERTY_RECOMMEND_VALUE" => false
        ),
    ),
    array(
        "ICON" => "popular-among-users",
        "TITLE" => "Популярное <br>у пользователей",
        "FILTER" => $arFilter,
    ),
    array(
        "ICON" => "best-sport",
        "TITLE" => "Спорт",
        "FILTER" => $arFilter,
    ),
    array(
        "ICON" => "best-cartoons",
        "TITLE" => "Популярные<br>мультики",
        "FILTER" => $arFilter,
    ),
    array(
        "ICON" => "premieres",
        "TITLE" => "Премьеры",
        "FILTER" => $arFilter,
    ),
);

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


$arProgTimes = CProgTime::getList(array(
    "PROPERTY_DATE" => date("Y-m-d", strtotime($arTime["SELECTED_DATE"])),
), array("ID", "CODE", "PROPERTY_DATE_START", "PROPERTY_DATE_END", "PROPERTY_PROG", "PROPERTY_CHANNEL"));

foreach($arResult["TOPICS"] as &$arTopic)
{
    $arPoperty = $arTopic["PROPERTY"];
    
    $arFilter = $arTopic["FILTER"];
    $arFilter["PROPERTY_CHANNEL"] = $ids;
    
    $arSelect = array("ID", "NAME", "PROPERTY_CHANNEL", "PROPERTY_SUB_TITLE");
    
    //получим все программы
    $arProgs = CProg::getList($arFilter, $arSelect);
    
    $arProgsSorted = array();
    foreach($arProgs as $arProg)
    {
        $arProgsSorted[$arProg["ID"]] = $arProg;
    }
    unset($arProgs);
    
    $key = 0;
    foreach($arProgTimes as $arSchedule)
    {
        $progID = $arSchedule["PROPERTY_PROG_VALUE"];
        if(isset($arProgsSorted[$progID]))
        {
            $channel = $arSchedule["PROPERTY_CHANNEL_VALUE"];
            $arProg = $arProgsSorted[$progID];
            $arProg["DATE_START"] = CTimeEx::dateOffset($arTime["OFFSET"], $arSchedule["PROPERTY_DATE_START_VALUE"]);
            $arProg["DATE_END"] = CTimeEx::dateOffset($arTime["OFFSET"], $arSchedule["PROPERTY_DATE_END_VALUE"]);
            $arProg["DETAIL_PAGE_URL"] = $arResult["CHANNELS"][$channel]["DETAIL_PAGE_URL"].$arSchedule["CODE"]."/";
            $arTopic["PROGS"][] = $arProg;
            
            $key++;
            if($key>48)
                break;
        }
    }   
    
    $arProgs = CScheduleTable::setIndex(array(
        "PROGS" => $arTopic["PROGS"],
    ));
    
    $arTopic["PROGS"] = $arProgs; 
}

$this->IncludeComponentTemplate();
?>