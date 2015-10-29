<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
ini_set('max_execution_time', 30);
global $USER;

$arParams = $arParams + array(
    "DATETIME" => CTimeEx::getDatetime(),
    "CITY" => CCityEx::getGeoCity(),
    "AJAX" => $_REQUEST["AJAX"],
    "LIST_URL" => $APPLICATION->GetCurDir(),
    "AJAX_TYPE" => $_REQUEST["AJAX_TYPE"]
);

$arResult["ITEMS"] = CChannel::getList(array("ACTIVE"=>"Y"), array("ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_ICON"));
if($USER->IsAuthorized())
{
    //Отсортируем каналы в зависимости от рейтинга для пользователя
    $arStats = CStatChannel::getList(array("UF_USER"=>$USER->GetID()), array("UF_CHANNEL"));
    
    $arItems = array();
    foreach($arStats as $arStat)
    {   
        foreach($arResult["ITEMS"] as $key=>$arItem)
        {
            if($arStat["UF_CHANNEL"]==$arItem["ID"])
            {
                $arItems[] = $arItem;
                unset($arResult["ITEMS"][$key]);
                break;
            }
        }
    }
        
    if(count($arResult["ITEMS"])>0)
    {
        foreach($arResult["ITEMS"] as $arItem)
        {
            $arItems[] = $arItem; 
        }
    }    
    
    /* 
    $arItems = array();
    foreach($arResult["ITEMS"] as $arItem)
    {
        $added = false;
        foreach($arStats as $arStat)
        {
            if($arStat["UF_CHANNEL"]==$arItem["ID"])
            {
                $arItems[] = $arItem;
                $added = true;
                break;
            }
        }
        
        if(!$added)
        {
            $arItems[] = $arItem;
        }
        
    }
    $arResult["ITEMS"] = $arItems;
    */
    $arResult["ITEMS"] = $arItems;
    unset($arItems);
}

// номер текущей страницы
if(!isset($_REQUEST["PAGEN_1"]))
{
    $arResult["NAV_RESULT"]->NavPageNomer = 1;
}else{
    $arResult["NAV_RESULT"]->NavPageNomer = intval($_REQUEST["PAGEN_1"]);
}

$start = ($arResult["NAV_RESULT"]->NavPageNomer-1)*intval($arParams["NEWS_COUNT"])+1;
$end = $arResult["NAV_RESULT"]->NavPageNomer*intval($arParams["NEWS_COUNT"]);

//echo $start."<br />".$end; 

// всего страниц - номер последней страницы
$totalPages = $arResult["NAV_RESULT"]->NavPageCount = ceil(count($arResult["ITEMS"])/$arParams["NEWS_COUNT"]);

$arChannelIds = array();
$arResult["CHANNELS"] = array();
$k = 1; 
foreach($arResult["ITEMS"] as $key=>$arItem)
{   
    if($k>=$start && $k<=$end)
    {
        $arItem["PROPERTIES"]["ICON"]["VALUE"] = $arItem["PROPERTY_ICON_VALUE"];
        $arChannelIds[] = $arItem["ID"];
        $arResult["CHANNELS"][$arItem["ID"]] = $arItem;
    }else{
        unset($arResult["ITEMS"][$key]);
    }
    $k++;
}
unset($arResult["ITEMS"]);

//Получим все программы текущих каналов за выбранный день
$arProgTimes = CProgTime::getList(
    array(
        "PROPERTY_DATE" => date("Y-m-d", strtotime($arParams["DATETIME"]["SELECTED_DATE"])),
        "PROPERTY_CHANNEL" => $arChannelIds
    ),
    array(
        "ID", "NAME", "CODE", "PROPERTY_DATE_START", "PROPERTY_DATE_END", "PROPERTY_PROG", "PROPERTY_CHANNEL", "PROPERTY_DATE"
    )
);

unset($arChannelIds);

//BROADCAT_COLS
$arProgWithTime = array();
foreach($arProgTimes as &$arProgTime)
{
    $channel = $arProgTime["PROPERTY_CHANNEL_VALUE"];
    $prog = $arProgTime["PROPERTY_PROG_VALUE"];
    
    $arProg = CProg::getByID($prog, array(
        "ID", "NAME", "PROPERTY_PICTURE_DOUBLE", "PROPERTY_PICTURE_HALF", "PREVIEW_PICTURE", "PROPERTY_YEAR", "PROPERTY_SUB_TITLE", "PROPERTY_RATING"
    ));
    
    $arProg["DATE_START"] = CTimeEx::dateOffset($arParams["DATETIME"]["OFFSET"], $arProgTime["PROPERTY_DATE_START_VALUE"]);
    $arProg["DATE_END"] = CTimeEx::dateOffset($arParams["DATETIME"]["OFFSET"], $arProgTime["PROPERTY_DATE_END_VALUE"]);
    $arProg["DATE"] = $arProgTime["PROPERTY_DATE_VALUE"];
    $arProg["SCHEDULE_ID"] = $arProgTime["ID"];
    $arProg["CHANNEL_ID"] = $channel;
    $arProg["DETAIL_PAGE_URL"] = $arResult["CHANNELS"][$channel]["DETAIL_PAGE_URL"].$arProgTime["CODE"]."/";
    
    $arResult["CHANNELS"][$channel]["PROGS"][] = $arProg;
}

unset($arProgTimes);

foreach($arResult["CHANNELS"] as $channel => &$arChannel )
{
    $arProgs = CScheduleTable::setIndex(array(
        "CITY" => $arParams["CITY"],
        "PROGS" => $arChannel["PROGS"],
        "NEWS" => $arChannel["PROPERTIES"]["NEWS"]["VALUE"],
    ));
    
    $arChannel["PROGS"] = $arProgs;
}

$this->IncludeComponentTemplate();
?>