<?
$arChannelIds = array();
$arResult["CHANNELS"] = array();
foreach($arResult["ITEMS"] as $arItem)
{
    $arChannelIds[] = $arItem["ID"];
    $arResult["CHANNELS"][$arItem["ID"]] = $arItem;
}
unset($arResult["ITEMS"]);

//Получим все программы текущих каналов за выбранный день
$arProgTimes = CProgTime::getList(
    array(
        //">=PROPERTY_DATE_START" => date("Y-m-d H:i:s", strtotime($arParams["CURRENT_DATETIME"]["DATE_FROM"])),
        //"<PROPERTY_DATE_STOP" => date("Y-m-d H:i:s", strtotime($arParams["CURRENT_DATETIME"]["DATE_TO"])),
        "PROPERTY_DATE" => substr(date("Y-m-d", strtotime($arParams["CURRENT_DATETIME"]["DATE_FROM"])), 0, 10),
        "PROPERTY_CHANNEL" => $arChannelIds
    ),
    array(
        "ID", "NAME", "CODE", "PROPERTY_DATE_START", "PROPERTY_PROG", "PROPERTY_CHANNEL"
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
        "ID", "NAME",
    ));
    
    $arProg["DATE_START"] = CTimeEx::dateOffset($arParams["CURRENT_DATETIME"]["OFFSET"], $arProgTime["PROPERTY_DATE_START_VALUE"]);
    //$arProg["DATE_END"] = CTimeEx::dateOffset($arParams["CURRENT_DATETIME"]["OFFSET"], $arProgTime["PROPERTY_DATE_STOP_VALUE"]);
    $arProg["DETAIL_PAGE_URL"] = $arResult["CHANNELS"][$channel]["DETAIL_PAGE_URL"].$arProgTime["CODE"]."/";
    $arResult["CHANNELS"][$channel]["PROGS"][] = $arProg;
    $arResult["CHANNELS"][$channel]["TIME"][] = $arProgTime;
}

unset($arProgTimes);

foreach($arResult["CHANNELS"] as $channel => &$arChannel )
{
    echo "<h1>".$channel.'</h1>';
    
    
    /*$arProgs = CScheduleTable::setIndex(array(
        "CITY" => $arParams["CITY"],
        "PROGS" => $arChannel["PROGS"],
        "NEWS" => $arChannel["PROPERTIES"]["NEWS"]["VALUE"],
    ));*/
    
    CDev::pre($arChannel["TIME"]);
    
    //$arChannel["PROGS"] = $arProgs;
}
?>