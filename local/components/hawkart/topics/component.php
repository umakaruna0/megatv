<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

//Params
if ($arParams["CACHE_TYPE"] == "Y" || ($arParams["CACHE_TYPE"] == "A" && COption::GetOptionString("main", "component_cache_on", "Y") == "Y"))
	$arParams["CACHE_TIME"] = intval($arParams["CACHE_TIME"]);
else
	$arParams["CACHE_TIME"] = 0;
 
CModule::IncludeModule("iblock");
 
$arResult["TOPICS"] = array();
$arrFilter = array("IBLOCK_ID" => 12, "ACTIVE" => "Y");
$arSelect = array("NAME", "PROPERTY_ICON", 'PREVIEW_TEXT', "PROPERTY_FILTER_BY");
$rsRes = CIBlockElement::GetList( $arOrder, $arrFilter, false, false, $arSelect );
while( $arItem = $rsRes->GetNext() )
{
    if($arItem["PROPERTY_FILTER_BY_VALUE"]=="Категории")
    {
        $code = "CATEGORY";
    }else{
        $code = "TOPIC";
    }
    
    $topics = array();
    $arTopicsExp = explode(",", $arItem["PREVIEW_TEXT"]);
    foreach($arTopicsExp as $key=>$topic)
    {
        if(!empty($topic) && !in_array($topic, $cats))
            $topics[] = trim($topic);
    }
    
    $topics = array_unique($topics);
    
    $arResult["TOPICS"][] = array(
        "ICON" => $arItem["PROPERTY_ICON_VALUE"],
        "TITLE" => $arItem["NAME"],
        "PROPERTY" => array(
            "CODE" => $code, 
            "VALUE" => $topics
        )
    );
    
}

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
    ">=PROPERTY_DATE" => date("Y-m-d", strtotime($arTime["SELECTED_DATE"])),
), array("ID", "CODE", "PROPERTY_DATE_START", "PROPERTY_DATE_END", "PROPERTY_PROG", "PROPERTY_CHANNEL"));

foreach($arResult["TOPICS"] as &$arTopic)
{
    $arPoperty = $arTopic["PROPERTY"];
    
    //получим все программы
    if(!empty($arPoperty["VALUE"]))
    {
        $arProgs = CProg::getList(array(
                "?PROPERTY_".$arPoperty["CODE"]=> $arPoperty["VALUE"],
                "PROPERTY_CHANNEL" => $ids
            ), 
            array(
                "ID", "NAME", "PROPERTY_CHANNEL", "PROPERTY_SUB_TITLE", "PREVIEW_PICTURE", "PROPERTY_PICTURE_DOUBLE", "PROPERTY_PICTURE_HALF", "PROPERTY_TOPIC"
            )
        );
        
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
                $arProg["SCHEDULE_ID"] = $arSchedule["ID"];
                $arProg["CHANNEL_ID"] = $channel;
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
    }
    
    
    $arTopic["PROGS"] = $arProgs; 
}
//CDev::pre($arResult["TOPICS"]);


//******************************************************************
/*$arProgs = CProg::getList(false, 
    array(
        "ID", "NAME", "PROPERTY_CHANNEL", "PROPERTY_SUB_TITLE", "PROPERTY_TOPIC", "PROPERTY_CATEGORY"
    )
);
$cats = array();
$themes = array();
foreach($arProgs as $arProg)
{
    $arTopicsExp = explode(",", $arProg["PROPERTY_TOPIC_VALUE"]);
    foreach($arTopicsExp as $key=>$topic)
    {
        if(!empty($topic) && !in_array($topic, $themes))
            $themes[] = trim($topic);
    }
}

foreach($arProgs as $arProg)
{
    $arTopicsExp = explode(",", $arProg["PROPERTY_CATEGORY_VALUE"]);
    foreach($arTopicsExp as $key=>$topic)
    {
        if(!empty($topic) && !in_array($topic, $cats))
            $cats[] = trim($topic);
    }
}

$cats = array_unique($cats);
sort($cats);
$themes = array_unique($themes);
sort($themes);

//CDev::pre($cats);
//CDev::pre($themes);*/

$this->IncludeComponentTemplate();
?>