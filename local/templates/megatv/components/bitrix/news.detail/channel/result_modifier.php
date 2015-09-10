<?
ini_set('max_execution_time', 30);
$countCols = 20; 
$channel = $arResult["ID"];

//Если день выбрали другой, то показываем с самого начала дня
if(substr($arParams["DATETIME"]["SERVER_DATETIME"], 0, 10)!=substr($arParams["DATETIME"]["SELECTED_DATE"], 0, 10))
{   
    $arFilter = array(
        "PROPERTY_CHANNEL" => $channel,
        "PROPERTY_DATE" =>  date("Y-m-d", strtotime($arParams["DATETIME"]["SELECTED_DATE"]))
    );

}else{
   $filterDateStart = CTimeEx::datetimeForFilter($arParams["DATETIME"]["SELECTED_DATETIME"]);
   $filterDateEnd = CTimeEx::datetimeForFilter($arParams["DATETIME"]["SELECTED_DATETIME"], "+1 day");

   $arFilter = Array(
        "PROPERTY_CHANNEL" => $channel,
        array(
            "LOGIC" => "OR",
            array(
                ">PROPERTY_DATE_START" => $filterDateStart,
                "<PROPERTY_DATE_END" => $filterDateEnd
            ),
            array(
                "<PROPERTY_DATE_START" => $filterDateStart, 
                ">PROPERTY_DATE_END" => $filterDateStart
            ),
        )
   );
}

//echo $filterDateStart."<br />";
//echo $filterDateEnd."<br />";

$arSelect = array(
    "ID", "NAME", "CODE", "PROPERTY_DATE_START", "PROPERTY_DATE_END", "PROPERTY_PROG", "PROPERTY_DATE"
);
$arProgTimes = CProgTime::getList($arFilter, $arSelect);

//BROADCAT_COLS
$k = 1;
foreach($arProgTimes as $arProgTime)
{
    if($countCols>=$k)
    {
        $k++;
    }else{
        break;
    }
    
    $prog = $arProgTime["PROPERTY_PROG_VALUE"];
    
    $arProg = CProg::getByID($prog, array(
        "ID", "NAME", "PREVIEW_TEXT", "PROPERTY_PICTURE_VERTICAL_DOUBLE", 
        "PROPERTY_PICTURE_VERTICAL", "PROPERTY_YEAR", "PROPERTY_SUB_TITLE", "PROPERTY_HD"
    ));
    
    $arProg["DATE_START"] = CTimeEx::dateOffset($arParams["DATETIME"]["OFFSET"], $arProgTime["PROPERTY_DATE_START_VALUE"]);
    $arProg["DATE_END"] = CTimeEx::dateOffset($arParams["DATETIME"]["OFFSET"], $arProgTime["PROPERTY_DATE_END_VALUE"]);    
    $arProg["DATE"] = $arProgTime["PROPERTY_DATE_VALUE"];
    $arProg["SCHEDULE_ID"] = $arProgTime["ID"];
    $arProg["CHANNEL_ID"] = $channel;
    $arProg["DETAIL_PAGE_URL"] = $arResult["CHANNELS"][$channel]["DETAIL_PAGE_URL"].$arProgTime["CODE"]."/";
    
    $arResult["PROGS"][] = $arProg;
}
unset($arProgTimes);

$arProgs = CScheduleTable::setChannel(array(
    "CITY" => $arParams["CITY"],
    "PROGS" => $arResult["PROGS"],
));

$arResult["PROGS"] = $arProgs;