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
        ">=PROPERTY_DATE_START" => date("Y-m-d H:i:s", strtotime($arParams["CURRENT_DATETIME"]["DATE_FROM"])),
        "<PROPERTY_DATE_STOP" => date("Y-m-d H:i:s", strtotime($arParams["CURRENT_DATETIME"]["DATE_TO"])),
        "PROPERTY_CHANNEL" => $arChannelIds
    ),
    array(
        "ID", "NAME", "PROPERTY_DATE_START", "PROPERTY_DATE_STOP"
    )
);

unset($arChannelIds);

//BROADCAT_COLS
$arProgWithTime = array();
foreach($arProgTimes as &$arProgTime)
{
    $channel = $arProgTime["PROPERTY_CHANNEL_VALUE"];
    $prog = $arProgTime["PROPERTY_PROG_VALUE"];
    
    $arProg = CProg::getByID($prog);
    $arProg["DATE_START"] = CTimeEx::dateOffset($arParams["CURRENT_DATETIME"]["OFFSET"], $arProgTime["PROPERTY_DATE_START_VALUE"]);
    $arProg["DATE_END"] = CTimeEx::dateOffset($arParams["CURRENT_DATETIME"]["OFFSET"], $arProgTime["PROPERTY_DATE_STOP_VALUE"]);
    $arResult["CHANNELS"][$channel]["COUNT"]++;
    $arResult["CHANNELS"][$channel]["PROGS"][] = $arProg;
}

unset($arProgTimes);

foreach($arResult["CHANNELS"] as $channel => $arChannel )
{
    $arParts = array(); //HALF, "ONE", "DOUBLE"
    if($arChannel["PROPERTIES"]["NEWS"]["VALUE"])
    {
        //if($arChannel["COUNT"]>BROADCAT_COLS)
        //$arParts[""]
    }else{
        if($arChannel["COUNT"]>BROADCAT_COLS)
        {
            $ostatok = $arChannel["COUNT"]%BROADCAT_COLS;
        }else{
            
        }
    }
    //$arChannel["PROGS"]
    //$arChannel["COUNT"]
}
?>