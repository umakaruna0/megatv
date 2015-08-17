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
    
    $arProg["DATE_START"] = CTimeEx::dateOffset($arParams["CURRENT_DATETIME"]["OFFSET"], $arProgTime["PROPERTIES"]["DATE_START"]["VALUE"]);
    $arProg["DATE_END"] = CTimeEx::dateOffset($arParams["CURRENT_DATETIME"]["OFFSET"], $arProgTime["PROPERTIES"]["DATE_END"]["VALUE"]);
    $arProg["DATE"] = $arProgTime["PROPERTIES"]["DATE"]["VALUE"];

    $arProg["DETAIL_PAGE_URL"] = $arProg["CHANNEL"]["DETAIL_PAGE_URL"].$arProgTime["CODE"]."/";
    $arResult["PROGS"][] = $arProg;
}

//CDev::pre($arResult["PROGS"]);
?>