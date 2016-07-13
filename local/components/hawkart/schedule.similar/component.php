<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $USER, $APPLICATION;
$arResult = array();
$arTime =  \CTimeEx::getDatetime();

//get channel by code
if(empty($_REQUEST["event"]))
{
    $arFilter = array("=UF_PROG.UF_EPG_ID" => $arParams["ELEMENT_CODE"]);
}else{
    $arFilter = array("=ID" => $_REQUEST["event"]);
}
$arSelect = array(
    "ID", "UF_CATEGORY" => 'UF_PROG.UF_CATEGORY', "UF_SID" => "UF_PROG.UF_EPG_ID"
);
$result = \Hawkart\Megatv\ScheduleTable::getList(array(
    'filter' => $arFilter,
    'select' => $arSelect,
    'limit' => 1
));
if ($arResult = $result->fetch())
{
    $category = $arResult["UF_CATEGORY"];
}

//get channel by code
$arResult["PROGS"] = array();

$offset = 0;
if($_REQUEST["AJAX"]=="Y")
{
    $offset = $_REQUEST["offset"];
}else{
    $arChannelsActive = \Hawkart\Megatv\ChannelTable::getActiveIdByCityByUser();
    $arDate = \CTimeEx::getDateTimeFilter($arTime["SERVER_DATETIME"]);
    $dateStart = date("Y-m-d H:i:s");
    $dateEnd = date("Y-m-d H:i:s", strtotime($arDate["DATE_TO"]));
    
    $arFilter = array(
        "!=ID" => $arResult["ID"],
        "=UF_PROG.UF_EPG_ID" => $arResult["UF_SID"],
        "=UF_CHANNEL_ID" => $arChannelsActive,
        ">=UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'),
        "<UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateEnd, 'Y-m-d H:i:s'),
    );
    $arSelect = array(
        "ID", "UF_CODE", "UF_DATE_START", "UF_DATE_END", "UF_DATE", "UF_CHANNEL_ID", "UF_PROG_ID",
        "UF_TITLE" => "UF_PROG.UF_TITLE", "UF_SUB_TITLE" => "UF_PROG.UF_SUB_TITLE", "UF_IMG_PATH" => "UF_PROG.UF_IMG.UF_PATH",
        "UF_CHANNEL_CODE" => "UF_CHANNEL.UF_BASE.UF_CODE", "UF_ID" => "UF_PROG.UF_EPG_ID", "UF_CATEGORY" => "UF_PROG.UF_CATEGORY",
    );
    
    $result = \Hawkart\Megatv\ScheduleTable::getList(array(
        'filter' => $arFilter,
        'select' => $arSelect,
        'limit' => 6,
    ));
    while ($arSchedule = $result->fetch())
    {
        $arSchedule["UF_DATE_START"] = $arSchedule["DATE_START"] = \CTimeEx::dateOffset($arSchedule['UF_DATE_START']->toString());
        $arSchedule["UF_DATE_END"] = $arSchedule["DATE_END"] = \CTimeEx::dateOffset($arSchedule['UF_DATE_END']->toString());
        $arSchedule["UF_DATE"] = $arSchedule["DATE"] = substr($arSchedule["DATE_START"], 0, 10);
        
        $arSchedule["PROG_ID"] = $arSchedule["UF_PROG_ID"];
        $arSchedule["DETAIL_PAGE_URL"] = "/channels/".$arSchedule["UF_CHANNEL_CODE"]."/".$arSchedule["UF_ID"]."/?event=".$arSchedule["ID"];
        $arResult["PROGS"][] = $arSchedule;
    }
}

$result = \Hawkart\Megatv\ProgExternalTable::getList(array(
    'filter' => array("=UF_SERIAL.UF_EPG_ID" => $arResult["UF_SID"]),
    'select' => array("ID", "UF_TITLE", "UF_EXTERNAL_ID", "UF_THUMBNAIL_URL", "UF_JSON"),
    'order' => array("UF_DATETIME" => "DESC"),
    'limit' => $arParams["NEWS_COUNT"],
    'offset' => $offset
));
while ($row = $result->fetch())
{
    if(strpos($row["UF_THUMBNAIL_URL"], "rutube")!==false)
    {
        $row["UF_THUMBNAIL_URL"].="?size=m";
    }
    $arResult["PROGS"][] = $row;
}

$this->IncludeComponentTemplate();
?>