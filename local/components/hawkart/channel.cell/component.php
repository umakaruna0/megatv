<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

ini_set('max_execution_time', 30);
global $USER, $APPLICATION;

$arParams = $arParams + array(
    "DATETIME" => \CTimeEx::getDatetime(),
    "AJAX" => $_REQUEST["AJAX"],
    "LIST_URL" => $APPLICATION->GetCurDir(),
);

/**
 *Get channel list
 */
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

//get subscription list
$arSubscriptionChannels = $APPLICATION->GetPageProperty("ar_subs_channels");
$arResult["CHANNELS_SHOW"] = json_decode($arSubscriptionChannels, true);

/**
 * filter channels by navigation
 */
 
$on_page = 10;
$offset = 0;
if($_REQUEST["offset_channels"])
{
    $offset = intval($_REQUEST["offset_channels"]);
}
 
$k = 1;
$arChannelIds = array();
$arResult["CHANNELS"] = array();
foreach($arResult["ITEMS"] as $key=>$arItem)
{   
    if(!in_array($arItem["UF_CHANNEL_BASE_ID"], $arResult["CHANNELS_SHOW"]) && $USER->IsAuthorized())
        continue;
    
    if($k>$offset && $k<=$offset+$on_page)
    {
        $arItem["ICON"] = $arItem["UF_ICON"];
        $arChannelIds[] = $arItem["ID"];
        $arResult["CHANNELS"][$arItem["ID"]] = $arItem;
    }else{
        unset($arResult["ITEMS"][$key]);
    }
    $k++;
}

$total_channels = $k-1;

unset($arResult["ITEMS"]);


/**
 * Create list date & channels with schedules
 */
$arResult["DATES"] = array();
$arParams["CURRENT_DATE"] = date("d.m.Y");

if(!isset($_REQUEST["date"]))
{
    $arDates = array($arParams["CURRENT_DATE"]);
}else{
    $arDates = $_REQUEST["date"];
}

if(!is_array($arDates))
{
    $arDates = array($arDates);
}

$currentDateTime = date("Y-m-d H:i:s", strtotime($arParams["DATETIME"]["SERVER_DATETIME_WITH_OFFSET"]));

$arData = array();
$arData["channels"] = $arResult["CHANNELS"];
$arData["time"] = date("d.m.Y H:i:s", strtotime($currentDateTime));
$arData["auth"] = $USER->IsAuthorized();
$arData["channels_total"] = $total_channels;

/**
 * Get records statuses by user
 */
$arRecordsStatuses = \Hawkart\Megatv\RecordTable::getListStatusesByUser();

foreach($arDates as $curDate)
{
    $arDate = \CTimeEx::getDateFilter($curDate);
    $dateStart = date("Y-m-d H:i:s", strtotime($arDate["DATE_FROM"]));
    $dateEnd = date("Y-m-d H:i:s", strtotime($arDate["DATE_TO"]));
    
    $arData["broadcasts"][$curDate] = array();
    $arData["broadcasts"][$curDate]["currentDay"] = \CTimeEx::dateToStrWithDay($curDate);
    
    if($arParams["CURRENT_DATE"]!=$curDate)
    {
        $currentDateTime = date("Y-m-d 15:00:00", strtotime(\CTimeEx::dateOffset($curDate.date(" H:i:s"))));
    }
    
    $arScheduleList = array();
    foreach($arResult["CHANNELS"] as $arChannel)
    {
        $arScheduleList[$arChannel["ID"]] = \Hawkart\Megatv\ScheduleCell::getByChannelAndTime($arChannel["ID"], $currentDateTime);
        
        $is_half = 0;
        $arHalf = array();
        
        foreach($arScheduleList[$arChannel["ID"]] as &$arProg)
        {
            $time = substr($arProg['UF_DATE_START'], 11, 5);       
            $arStatus = \Hawkart\Megatv\CScheduleTemplate::status($arProg, $arRecordsStatuses);
            $status = $arStatus["status"];
            
            if(intval($arProg["UF_BASE_FORBID_REC"])==1 && $USER->IsAuthorized())
            {
                $status = "";
            }
            
            if($arParams["CURRENT_DATE"]!=$curDate)
            {
                $arProg["TIME_POINTER"] = false;
            }
            
            if($arProg["IS_ADV"])
            {
                $img_path = $arProg["PICTURE"];
            }else{
                if($arProg["CLASS"]=="one")
                {
                    $img_path = \Hawkart\Megatv\CFile::getCropedPath($arProg["UF_IMG_PATH"], array(288, 288));
                }else if($arProg["CLASS"]=="double"){
                    $img_path = \Hawkart\Megatv\CFile::getCropedPath($arProg["UF_IMG_PATH"], array(576, 288));
                }else{
                    $img_path = \Hawkart\Megatv\CFile::getCropedPath($arProg["UF_IMG_PATH"], array(288, 144));
                }
                
                if($status=="viewed")
                {
                    $img_path = SITE_TEMPLATE_PATH."/ajax/img_grey.php?&path=".urlencode($_SERVER["DOCUMENT_ROOT"].$img_path);
                }
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
        		"on_air" => $arProg["TIME_POINTER"],
                "image" => $img_path,
                "status" => "status-".$status,
                "rating" => $arProg["UF_RATING"],
                "is_clone" => $arProg["CLONE"],
                "is_adv" => $arProg["IS_ADV"]                
            );
            
            if($arProg["CLASS"]=="half")
            {
                $is_half++;
                
                $arHalf[] = $_arRecord;
                
                if($is_half==2)
                {
                    $arData["broadcasts"][$curDate]["channels"][$arChannel["ID"]][] = array($arProg["CLASS"] => $arHalf);
                    $is_half = 0;
                    $arHalf = array();
                }
            }else{
                $arData["broadcasts"][$curDate]["channels"][$arChannel["ID"]][] = array($arProg["CLASS"] => $_arRecord);
            }
        }
    }
    
    unset($arScheduleList);
}

if($_REQUEST["AJAX_JSON"]=="Y")
{    
    $APPLICATION->RestartBuffer();
    echo json_encode($arData); die();
}else{
    $arResult["DATA"] = json_encode($arData);
}


$arResult["FIRST_DATE"] = date('d.m.Y', strtotime($dateStart));

//\CDev::pre($arScheduleList);
//$arResult["SCHEDULE_LIST"] = $arScheduleList;

$this->IncludeComponentTemplate();
?>