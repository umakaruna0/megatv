<?
ini_set('max_execution_time', 30);
$countCols = 5; 
$channel = $arResult["ID"];

//ОШИБКА с дата в Ростове и показа в эфире


//fail bitrix offset !!! -3
//$filterDateStart = date("Y-m-d H:i:s", strtotime($datetime));
//$filterDateEnd = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($datetime)));

$datetime_real = CTimeEx::dateOffset($arParams["CURRENT_DATETIME"]["OFFSET"], $arParams["CURRENT_DATETIME"]["DATETIME_REAL"]);

//Если день выбрали другой, то показываем с самого начала дня
if(substr($arParams["CURRENT_DATETIME"]["DATETIME_REAL"], 0, 10)!=substr($arParams["CURRENT_DATETIME"]["DATETIME_CURRENT"], 0, 10))
{   
    $filterDateStart = date("Y-m-d H:i:s", strtotime("-3 hour", strtotime($arParams["CURRENT_DATETIME"]["DATE_FROM"])));
    $filterDateEnd = date('Y-m-d H:i:s', strtotime("-3 hour", strtotime($arParams["CURRENT_DATETIME"]["DATE_TO"])));
    
    $arFilter = array(
        "PROPERTY_CHANNEL" => $channel,
        ">=PROPERTY_DATE_START" => $filterDateStart,
        "<PROPERTY_DATE_END" => $filterDateEnd,
    );

}else{
   $datetime = CTimeEx::dateOffset($arParams["CURRENT_DATETIME"]["OFFSET"], $arParams["CURRENT_DATETIME"]["DATETIME_CURRENT"]);
   $filterDateStart = date("Y-m-d H:i:s", strtotime("-3 hour", strtotime($datetime)));
   $filterDateEnd = date('Y-m-d H:i:s', strtotime("+1 day -3 hour", strtotime($datetime)));
   
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
    
    $arProg["DATE_START"] = CTimeEx::dateOffset($arParams["CURRENT_DATETIME"]["OFFSET"], $arProgTime["PROPERTY_DATE_START_VALUE"]);
    $arProg["DATE_END"] = CTimeEx::dateOffset($arParams["CURRENT_DATETIME"]["OFFSET"], $arProgTime["PROPERTY_DATE_END_VALUE"]);
    $arProg["DATE"] = $arProgTime["PROPERTY_DATE_VALUE"];

    $arProg["DETAIL_PAGE_URL"] = $arResult["CHANNELS"][$channel]["DETAIL_PAGE_URL"].$arProgTime["CODE"]."/";
    $arResult["PROGS"][] = $arProg;
}
unset($arProgTimes);

//CDev::pre($arResult["PROGS"]);

$arProgs = CScheduleTable::setChannel(array(
    "CITY" => $arParams["CITY"],
    "PROGS" => $arResult["PROGS"],
));

$arResult["PROGS"] = $arProgs;