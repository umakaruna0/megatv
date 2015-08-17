<?
ini_set('max_execution_time', 30);
$channel = $arResult["PROPERTIES"]["CHANNEL"]["VALUE"];
$prog = $arResult["PROPERTIES"]["PROG"]["VALUE"];

$arResult["CHANNEL"] = CChannel::getList(array("=ID"=>$channel), array("DETAIL_PAGE_URL", "PROPERTY_ICON"));
$arResult["CHANNEL"] = array_shift($arResult["CHANNEL"]);

$arProg = CProg::getByID($prog, array(
    "NAME", "DETAIL_TEXT", "PREVIEW_PICTURE", "PROPERTY_YEAR", "PROPERTY_SUB_TITLE", "PROPERTY_HD",
    "PROPERTY_DIRECTOR", "PROPERTY_ACTOR", "PROPERTY_COUNTRY", "PROPERTY_TOPIC", 
    "PROPERTY_YEAR_LIMIT", "PROPERTY_PRESENTER", "PROPERTY_RATING"
));

$arResult = array_merge($arResult, $arProg);
$arResult["DETAIL_PICTURE"] = $arProg["PREVIEW_PICTURE"];

//$arResult["DATE_START"] = CTimeEx::dateOffset($arParams["CURRENT_DATETIME"]["OFFSET"], $arResult["PROPERTIES"]["DATE_START"]["VALUE"]);
//$arResult["DATE_END"] = CTimeEx::dateOffset($arParams["CURRENT_DATETIME"]["OFFSET"], $arResult["PROPERTIES"]["DATE_END"]["VALUE"]);

$arResult["DATE_START"] = $arResult["PROPERTIES"]["DATE_START"]["VALUE"];
$arResult["DATE_END"] = $arResult["PROPERTIES"]["DATE_END"]["VALUE"];

$sec = strtotime($arResult["DATE_END"]) - strtotime($arResult["DATE_START"]);
$arResult["DURATION"] = CTimeEx::secToStr($sec);