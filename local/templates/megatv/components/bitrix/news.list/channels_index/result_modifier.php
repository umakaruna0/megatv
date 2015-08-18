<?
ini_set('max_execution_time', 30);

//$filterDateStart = date("Y-m-d H:i:s", strtotime($arParams["CURRENT_DATETIME"]["DATE_FROM"]));
//$filterDateEnd = date('Y-m-d H:i:s', strtotime($arParams["CURRENT_DATETIME"]["DATE_TO"]));

//$filterDateStart = date("Y-m-d H:i:s", strtotime("-3 hour", strtotime($arParams["CURRENT_DATETIME"]["DATE_FROM"])));
//$filterDateEnd = date('Y-m-d H:i:s', strtotime("-3 hour", strtotime($arParams["CURRENT_DATETIME"]["DATE_TO"])));

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
        "ID", "NAME", "PROPERTY_PICTURE_DOUBLE", "PROPERTY_PICTURE_HALF", "PREVIEW_PICTURE", "PROPERTY_YEAR", "PROPERTY_SUB_TITLE"
    ));
    
    $arProg["DATE_START"] = CTimeEx::dateOffset($arParams["DATETIME"]["OFFSET"], $arProgTime["PROPERTY_DATE_START_VALUE"]);
    $arProg["DATE_END"] = CTimeEx::dateOffset($arParams["DATETIME"]["OFFSET"], $arProgTime["PROPERTY_DATE_END_VALUE"]);
    
    //$arProg["DATE_START"] = $arProgTime["PROPERTY_DATE_START_VALUE"];
    //$arProg["DATE_END"] = $arProgTime["PROPERTY_DATE_END_VALUE"];
    $arProg["DATE"] = $arProgTime["PROPERTY_DATE_VALUE"];

    $arProg["DETAIL_PAGE_URL"] = $arResult["CHANNELS"][$channel]["DETAIL_PAGE_URL"].$arProgTime["CODE"]."/";
    $arResult["CHANNELS"][$channel]["PROGS"][] = $arProg;
    $arResult["CHANNELS"][$channel]["TIME"][] = $arProgTime;
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
?>

<?if($_REQUEST['AJAX']=='Y' && $_REQUEST["AJAX_TYPE"]=="CHANNELS") $APPLICATION->RestartBuffer();?>