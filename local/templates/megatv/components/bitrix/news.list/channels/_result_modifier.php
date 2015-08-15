<?
$arChannelIds = array();
foreach($arResult["ITEMS"] as $arItem)
{
    $arChannelIds[] = $arItem["ID"];
}

//Получим все программы текущих каналов за выбранный день
$arProgTimes = CProgTime::getList(array(
    ">=PROPERTY_DATE_START" => date("Y-m-d H:i:s", strtotime($arParams["CURRENT_DATETIME"]["DATE_FROM"])),
    "<PROPERTY_DATE_STOP" => date("Y-m-d H:i:s", strtotime($arParams["CURRENT_DATETIME"]["DATE_TO"])),
    "PROPERTY_CHANNEL" => $arChannelIds
));

//BROADCAT_COLS
$progIds = array();
$arProgWithTime = array();
foreach($arProgTimes as &$arProgTime)
{
    $arProgTime["PROPERTIES"]["DATE_START"]["VALUE"] = self::dateOffset($arParams["CURRENT_DATETIME"]["OFFSET"], $arProgTime["PROPERTIES"]["DATE_START"]["VALUE"]);
    $arProgTime["PROPERTIES"]["DATE_STOP"]["VALUE"] = self::dateOffset($arParams["CURRENT_DATETIME"]["OFFSET"], $arProgTime["PROPERTIES"]["DATE_STOP"]["VALUE"]);
    
    $channel = $arProgTime["PROPERTIES"]["CHANNEL"]["VALUE"];
    $prog = $arProgTime["PROPERTIES"]["PROG"]["VALUE"];
    
    $progIds[] = $prog;
    $arProgWithTime[$prog]["TIME"] = $arProgTime;
    
    $arResult["CHANNELS"][$channel]["COUNT"]++;
    
    /*$arProg = CProg::getByID($prog);
    $arProg["DATE_START"] = self::dateOffset($arParams["CURRENT_DATETIME"]["OFFSET"], $arProgTime["PROPERTIES"]["DATE_START"]["VALUE"]);
    $arProg["DATE_END"] = self::dateOffset($arParams["CURRENT_DATETIME"]["OFFSET"], $arProgTime["PROPERTIES"]["DATE_STOP"]["VALUE"]);
    $arResult["CHANNELS"][$channel]["COUNT"]++;
    $arResult["CHANNELS"][$channel]["PROGS"][] = $arProg;*/
}

foreach($arResult["CHANNELS"] as $channel => $count )
{
    
}

unset($arProgTimes);

$arProgs = CProg::getList(array(
    "PROPERTY_CHANNEL" => $arChannelIds,
    "ID" => $progIds
));

foreach($arProgs as $arProg)
{
    $channel = $arProg["PROPERTIES"]["CHANNEL"]["VALUE"];
    $arProgWithTime[$arProg["ID"]]["PROG"] = $arProg;
}

unset($progIds);
unset($arChannelIds);
?>