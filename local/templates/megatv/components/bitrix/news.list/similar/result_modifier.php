<?
ini_set('max_execution_time', 30);

//CDev::pre($arResult["ITEMS"]);

$arResult["PROGS"] = array();
foreach($arResult["ITEMS"] as $arProgTime)
{
    $channel = $arProgTime["PROPERTIES"]["CHANNEL"]["VALUE"];
    $prog = $arProgTime["PROPERTIES"]["PROG"]["VALUE"];
    
    $arProg = CProg::getByID($prog, array(
        "ID", "NAME", "PROPERTY_PICTURE_DOUBLE", "PICTURE_HALF", "PREVIEW_PICTURE", "PROPERTY_YEAR", "PROPERTY_SUB_TITLE"
    ));
    
    $arProg["DATE_START"] = CTimeEx::dateOffset($arParams["DATETIME"]["OFFSET"], $arProgTime["PROPERTIES"]["DATE_START"]["VALUE"]);
    $arProg["DATE_END"] = CTimeEx::dateOffset($arParams["DATETIME"]["OFFSET"], $arProgTime["PROPERTIES"]["DATE_END"]["VALUE"]);
    $arProg["DATE"] = $arProgTime["PROPERTIES"]["DATE"]["VALUE"];
    $arProg["SCHEDULE_ID"] = $arProgTime["ID"];
    $arProg["CHANNEL_ID"] = $channel;
    $arProg["DETAIL_PAGE_URL"] = $arProg["CHANNEL"]["DETAIL_PAGE_URL"].$arProgTime["CODE"]."/";
    $arResult["PROGS"][] = $arProg;
}

//CDev::pre($arResult["PROGS"]);
?>