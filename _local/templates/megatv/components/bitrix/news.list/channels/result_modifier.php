<?
/*$arChannelIds = array();
$arResult["CHANNELS"] = array();
foreach($arResult["ITEMS"] as $arItem)
{
    $arChannelIds[] = $arItem["ID"];
    $arResult["CHANNELS"][$arItem["ID"]] = $arItem;
}
unset($arResult["ITEMS"]);

//Получим все программы текущих каналов за выбранный день
$arProgTimes = CProgTime::getList(array(
    ">=PROPERTY_DATE_START" => date("Y-m-d H:i:s", strtotime($arParams["CURRENT_DATETIME"]["DATE_FROM"])),
    "<PROPERTY_DATE_STOP" => date("Y-m-d H:i:s", strtotime($arParams["CURRENT_DATETIME"]["DATE_TO"])),
    "PROPERTY_CHANNEL" => $arChannelIds
));

unset($arChannelIds);

//BROADCAT_COLS
$arProgWithTime = array();
foreach($arProgTimes as &$arProgTime)
{
    $channel = $arProgTime["PROPERTIES"]["CHANNEL"]["VALUE"];
    $prog = $arProgTime["PROPERTIES"]["PROG"]["VALUE"];
    
    $arProg = CProg::getByID($prog);
    $arProg["DATE_START"] = self::dateOffset($arParams["CURRENT_DATETIME"]["OFFSET"], $arProgTime["PROPERTIES"]["DATE_START"]["VALUE"]);
    $arProg["DATE_END"] = self::dateOffset($arParams["CURRENT_DATETIME"]["OFFSET"], $arProgTime["PROPERTIES"]["DATE_STOP"]["VALUE"]);
    $arResult["CHANNELS"][$channel]["COUNT"]++;
    $arResult["CHANNELS"][$channel]["PROGS"][] = $arProg;
}

unset($arProgTimes);

foreach($arResult["CHANNELS"] as $channel => $arChannel )
{
    $arParts = array(); //HALF, "ONE", "DOUBLE"
    if(!$arChannel["PROPERTIES"]["NEWS"]["VALUE"])
    {
        //if($arChannel["COUNT"]>BROADCAT_COLS)
        //$arParts[""]
    }
    //$arChannel["PROGS"]
    //$arChannel["COUNT"]
}*/
?>