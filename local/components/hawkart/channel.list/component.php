<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

ini_set('max_execution_time', 30);
global $USER, $APPLICATION;

$arParams = $arParams + array(
    "DATETIME" => \CTimeEx::getDatetime(),
    "AJAX" => $_REQUEST["AJAX"],
    "LIST_URL" => $APPLICATION->GetCurDir(),
);

//Get dates
$arResult["CONFIG_DATES"] = array();
$fisrt_date = date('d.m.Y', strtotime(\CTimeEx::getCurDate()));
for($i = 0; $i<\CTimeEx::getCalendarDays()+2; $i++)
{
    $date_confing = date('d.m.Y', strtotime("+".$i." day", strtotime($fisrt_date)));
    $arResult["CONFIG_DATES"][] = $date_confing;
}

//Get channel list
$arChannels = array();
$arResult["ITEMS"] = \Hawkart\Megatv\ChannelTable::getActiveByCity();
foreach($arResult["ITEMS"] as $arChannel)
{
    $arChannels[$arChannel["UF_CHANNEL_BASE_ID"]] = $arChannel;
}
$arResult["ITEMS"] = $arChannels;

/**
 * sort channels for user according statistics
 */
if($USER->IsAuthorized())
{
    $arItems = array();
    
    $arStatistic = \Hawkart\Megatv\CStat::getByUser();
    //sort channels by raiting
    uasort($arStatistic["CHANNELS"], function($a, $b){
        return strcmp($b, $a);
    });

    foreach($arStatistic["CHANNELS"] as $channel_id => $rating)
    {
        if(!empty($arResult["ITEMS"][$channel_id]))
        {
            $arItems[] = $arResult["ITEMS"][$channel_id];
            unset($arResult["ITEMS"][$channel_id]);
        }
    }
    
    if(count($arResult["ITEMS"])>0)
        $arItems = array_merge($arItems, $arResult["ITEMS"]);   

    $arResult["ITEMS"] = $arItems;
    unset($arItems);
}


/**
 * pagenavigation
 */
if(!isset($_REQUEST["PAGEN_1"]))
{
    $arResult["NAV_RESULT"]->NavPageNomer = 1;
}else{
    $arResult["NAV_RESULT"]->NavPageNomer = intval($_REQUEST["PAGEN_1"]);
}
$start = ($arResult["NAV_RESULT"]->NavPageNomer-1)*intval($arParams["NEWS_COUNT"])+1;
$end = $arResult["NAV_RESULT"]->NavPageNomer*intval($arParams["NEWS_COUNT"]);
$totalPages = $arResult["NAV_RESULT"]->NavPageCount = ceil(count($arResult["ITEMS"])/$arParams["NEWS_COUNT"]);


//get subscription list
$arSubscriptionChannels = $APPLICATION->GetPageProperty("ar_subs_channels");
$arResult["CHANNELS_SHOW"] = json_decode($arSubscriptionChannels, true);

/**
 * filter channels by navigation
 */
$k = 1;
$arChannelIds = array();
$arResult["CHANNELS"] = array();
foreach($arResult["ITEMS"] as $key=>$arItem)
{   
    if($k>=$start && $k<=$end)
    {
        $arItem["ICON"] = $arItem["UF_ICON"];
        $arChannelIds[] = $arItem["ID"];
        $arResult["CHANNELS"][$arItem["ID"]] = $arItem;
    }else{
        unset($arResult["ITEMS"][$key]);
    }
    $k++;
}
unset($arResult["ITEMS"]);


/**
 * Create list date & channels with schedules
 */
$arResult["DATES"] = array();
//Получим все программы текущих каналов за выбранный день
if(!isset($_REQUEST["date"]))
{
    $arParams["CURRENT_DATE"] = date("d.m.Y");
}else{
    $arParams["CURRENT_DATE"] = $_REQUEST["date"];
}

$arDate = \CTimeEx::getDateFilter($arParams["CURRENT_DATE"]);
$dateStart = date("Y-m-d H:i:s", strtotime($arDate["DATE_FROM"]));
$dateEnd = date("Y-m-d H:i:s", strtotime($arDate["DATE_TO"]));

$currentDateTime = date("Y-m-d H:i:s", strtotime($arParams["DATETIME"]["SERVER_DATETIME_WITH_OFFSET"]));

$arFilter = array(
    "=UF_CHANNEL_ID" => $arChannelIds,
    "=UF_ACTIVE" => 1,
    array(
        "LOGIC" => "OR",
        array(
            ">=UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'),
            "<UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateEnd, 'Y-m-d H:i:s'),
        ),
        array(
            "<UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'),
            ">UF_DATE_END" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'),
            //"<UF_DATE_START" => new \Bitrix\Main\Type\DateTime($currentDateTime, 'Y-m-d H:i:s'),
            //">UF_DATE_END" => new \Bitrix\Main\Type\DateTime($currentDateTime, 'Y-m-d H:i:s'),
        )
    )
    
);
$arSelect = array(
    "ID", "UF_DATE_START", "UF_DATE_END", "UF_DATE", "UF_CHANNEL_ID", "UF_PROG_ID",
    "UF_TITLE" => "UF_PROG.UF_TITLE", "UF_SUB_TITLE" => "UF_PROG.UF_SUB_TITLE", "UF_IMG_PATH" => "UF_PROG.UF_IMG.UF_PATH",
    "UF_RATING" => "UF_PROG.UF_RATING", "UF_PROG_CODE" => "UF_PROG.UF_CODE",
    'UF_BASE_FORBID_REC' => 'UF_CHANNEL.UF_BASE.UF_FORBID_REC'
);
$obCache = new \CPHPCache;
if( $obCache->InitCache(86400, serialize($arFilter).serialize($arSelect), "/index-schedules/"))
{
	$arResult["DATES"] = $obCache->GetVars();
}
elseif($obCache->StartDataCache())
{
	$result = \Hawkart\Megatv\ScheduleTable::getList(array(
        'filter' => $arFilter,
        'select' => $arSelect,
        'order' => array("UF_DATE_START" => "ASC")
    ));
    while ($arSchedule = $result->fetch())
    {
        $channel = $arSchedule["UF_CHANNEL_ID"];
        $arSchedule["UF_DATE_START"] = $arSchedule["DATE_START"] = \CTimeEx::dateOffset($arSchedule['UF_DATE_START']->toString());
        $arSchedule["UF_DATE_END"] = $arSchedule["DATE_END"] = \CTimeEx::dateOffset($arSchedule['UF_DATE_END']->toString());
        $arSchedule["UF_DATE"] = $arSchedule["DATE"] = substr($arSchedule["DATE_START"], 0, 10);
        
        $arSchedule["PROG_ID"] = $arSchedule["UF_PROG_ID"];
        $arSchedule["DETAIL_PAGE_URL"] = $arResult["CHANNELS"][$channel]["DETAIL_PAGE_URL"].$arSchedule["UF_PROG_CODE"]."/?event=".$arSchedule["ID"];
        //$arResult["DATES"][$arSchedule["UF_DATE"]][$channel][] = $arSchedule;
        $arResult["DATES"][$arParams["CURRENT_DATE"]][$channel][] = $arSchedule;
        
    }
	$obCache->EndDataCache($arResult["DATES"]); 
}
unset($arChannelIds);


/**
 * Create json view for future algorithm
 */
if($_REQUEST["AJAX_JSON"]=="Y")
{
    $APPLICATION->RestartBuffer();
}
    
    /**
     * Get records statuses by user
     */
    $arRecordsStatuses = \Hawkart\Megatv\RecordTable::getListStatusesByUser();
    
    $arDates = array();
    foreach($arResult["DATES"] as $date => &$arChannels)
    {
        if($arParams["TEMPLATE"]=="NEW")
        {
            $arChannels = \Hawkart\Megatv\CScheduleView::setIndexNew($arChannels, $arResult["CHANNELS"]);
        }
        
        foreach($arResult["CHANNELS"] as $arChannel)
        {
            $channel = $arChannel["ID"];
            $arProgs = $arChannels[$channel];
            
            if(!in_array($arChannel["UF_CHANNEL_BASE_ID"], $arResult["CHANNELS_SHOW"]) && $USER->IsAuthorized())
                continue;

            foreach($arProgs as $key=>$arProg)
            {                
                $time = substr($arProg['UF_DATE_START'], 11, 5);
                
                $arStatus = \Hawkart\Megatv\CScheduleTemplate::status($arProg, $arRecordsStatuses);
                $status = $arStatus["status"];
                
                if(intval($arProg["UF_BASE_FORBID_REC"])==1 && $USER->IsAuthorized())
                {
                    $status = "";
                }
                
                $start = $arProg["DATE_START"];
                $end = $arProg["DATE_END"];
                $datetime = $arParams["DATETIME"]["SERVER_DATETIME_WITH_OFFSET"];
                $time_pointer = false;
                if(\CTimeEx::dateDiff($start, $datetime) && \CTimeEx::dateDiff($datetime, $end))
                {
                    $time_pointer = true;
                }
                
                $_arRecord = array(
                    "id" => $arProg["ID"],
                    "channel_id" => $arProg["UF_CHANNEL_ID"],
                    "time" => $time,
            		"date" => substr($arProg["DATE_START"], 0, 10),//$date,
                    "date_start" => $arProg["DATE_START"],
                    "date_end" => $arProg["DATE_END"],
            		"link" => $arProg["DETAIL_PAGE_URL"],
            		"name" => \Hawkart\Megatv\CScheduleTemplate::cutName(\Hawkart\Megatv\ProgTable::getName($arProg), 35),
            		"images" => array(
                        'one' => \Hawkart\Megatv\CFile::getCropedPath($arProg["UF_IMG_PATH"], array(288, 288)),
                        'double' => \Hawkart\Megatv\CFile::getCropedPath($arProg["UF_IMG_PATH"], array(576, 288)),
                        'half' => \Hawkart\Megatv\CFile::getCropedPath($arProg["UF_IMG_PATH"], array(288, 144))
                    ),
                    'badge' => $time_pointer,
                    "status" => "status-".$status,
                    "rating" => $arProg["UF_RATING"],
                    "user_authorized" => $USER->IsAuthorized(),
                    "class" => $arProg["CLASS"] //,
                    //"forbidden_recording" => $arProg["UF_BASE_FORBID_REC"]                   
                );
                
                foreach($_arRecord["images"] as $type => $value)
                {
                    $_arRecord["images"][$type."_bad"] = SITE_TEMPLATE_PATH."/ajax/img_grey.php?quality=1&grey=false&path=".urlencode($_SERVER["DOCUMENT_ROOT"].$value);
                    if($status=="viewed")
                    {
                        $_arRecord["images"][$type] = ITE_TEMPLATE_PATH."/ajax/img_grey.php?&path=".urlencode($_SERVER["DOCUMENT_ROOT"].$value);
                        $_arRecord["images"][$type."_bad"] = str_replace("&grey=false", "", $_arRecord["images"][$type."_bad"]);
                    }
                }
                
                $arDates[$date][$channel][] = $_arRecord;
            }
        }
    }

if($_REQUEST["AJAX_JSON"]=="Y")
{    
    echo json_encode(array(
        "CHANNELS" => $arResult["CHANNELS"],
        "DATES" => $arDates,
        "TIME" => date("Y-m-d H:i:s", strtotime(\CTimeEx::dateOffset(date("Y-m-d H:i:s"))))
    ));

    die();
}else{
    $arResult["DATA"] = json_encode(array(
        "CHANNELS" => $arResult["CHANNELS"],
        "DATES" => $arDates,
        "TIME" => date("Y-m-d H:i:s", strtotime(\CTimeEx::dateOffset(date("Y-m-d H:i:s"))))
    ));
}



/**
 * Create view through template
 */
/*$arResult["FIRST_DATE"] = false;
foreach($arResult["DATES"] as $date => $arChannels )
{
    if(!$arResult["FIRST_DATE"])
        $arResult["FIRST_DATE"] = $date;
    
    foreach($arChannels as $channel=>$arSchedules)
    {
        $arSchedules = \Hawkart\Megatv\CScheduleView::setIndex(array(
            "PROGS" => $arSchedules,
            "NEWS" => $arResult["CHANNELS"][$channel]["UF_IS_NEWS"],
        ));
        
        $arResult["DATES"][$date][$channel] = $arSchedules;
    }
    
    //add social schedule
    //$arResult["DATES"][$date]["YOUTUBE"] = \YoutubeClient::dailyShow();
    //$arResult["DATES"][$date]["VK"] = \VkClient::dailyShow();
}*/

/**
 * Add social channels
 */
/*$arResult["SOCIAL_CHANNELS"] = array(
    array(
        "ID" => "YOUTUBE",
        "NAME" => "Youtube",
        "UF_ICON" => "icon-youtube-channel"
    ),
    array(
        "ID" => "VK",
        "NAME" => "Vk",
        "UF_ICON" => "icon-vk-channel"
    )
);*/

$this->IncludeComponentTemplate();
?>