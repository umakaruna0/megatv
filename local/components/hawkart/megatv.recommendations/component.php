<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

ini_set('max_execution_time', 10);

global $USER;
$arTime =  \CTimeEx::getDatetime();

function getRecommendForAll($arFilter, $arSelect, $limit, $offset)
{
    $arProgs = array();
    $result = \Hawkart\Megatv\ScheduleTable::getList(array(
        'filter' => $arFilter,
        'select' => $arSelect,
        'order' => array("UF_PROG.UF_RATING" => "DESC"),
        'limit' => 12,
        'offset' => $offset
    ));
    while ($arSchedule = $result->fetch())
    {   
        $arSchedule["UF_DATE_START"] = $arSchedule["DATE_START"] = \CTimeEx::dateOffset($arSchedule['UF_DATE_START']->toString());
        $arSchedule["UF_DATE_END"] = $arSchedule["DATE_END"] = \CTimeEx::dateOffset($arSchedule['UF_DATE_END']->toString());
        $arSchedule["UF_DATE"] = $arSchedule["DATE"] = substr($arSchedule["DATE_START"], 0, 10);
        $arSchedule["DETAIL_PAGE_URL"] = "/channels/".$arSchedule["UF_CHANNEL_CODE"]."/".$arSchedule["UF_PROG_CODE"]."/?event=".$arSchedule["ID"];
        $arProgs[] = $arSchedule;
    }
    
    return $arProgs;
}

$arResult["PROGS"] = array();
$countPerPage = intval($arParams["NEWS_COUNT"]);
$count = 0;
$offset = 0;
$prog_ids = array();
$limit = intval($arParams["NEWS_COUNT"]);

if($_REQUEST["AJAX"]=="Y")
{
    $offset = intval($_REQUEST["offset"]);
    $dateStart = substr($_REQUEST["date"], 0, 10).date(" H:i:s");
    $dateStart = date("Y-m-d H:i:s", strtotime($dateStart));
    //$dateEnd = date("Y-m-d H:i:s", strtotime("+1 day", strtotime($dateStart)));
}else{
    $arDate = \CTimeEx::getDateFilter($arTime["SERVER_DATETIME"]);
    $dateStart = date("Y-m-d H:i:s");
    //$dateEnd = date("Y-m-d H:i:s", strtotime($arDate["DATE_TO"]));
}

$dateEnd = date("Y-m-d H:i:s", strtotime("+1 day", strtotime($dateStart)));

$arFilter = array(
    "=UF_PROG.UF_ACTIVE" => 1,
    ">=UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'),
    "<UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateEnd, 'Y-m-d H:i:s'),
    "!UF_CATEGORY" => "Новости"
);

$arSelect = array(
    "ID", "UF_DATE_START", "UF_DATE_END", "UF_DATE", "UF_CHANNEL_ID", "UF_PROG_ID",
    "UF_TITLE" => "UF_PROG.UF_TITLE", "UF_SUB_TITLE" => "UF_PROG.UF_SUB_TITLE", "UF_IMG_PATH" => "UF_PROG.UF_IMG.UF_PATH",
    "UF_CHANNEL_CODE" => "UF_CHANNEL.UF_BASE.UF_CODE", "UF_CATEGORY" => "UF_PROG.UF_CATEGORY",
    "UF_ID" => "UF_PROG.UF_EPG_ID", "UF_PROG_CODE" => "UF_PROG.UF_CODE"
);

if($USER->IsAuthorized() && $_REQUEST["AJAX"]!="Y")
{
    $arProgByUsers = array();

    $arRecords = $APPLICATION->GetPageProperty("ar_record_status");
    $arRecordsStatuses = json_decode($arRecords, true);
    
    $recording_ids = array();
    foreach($arRecordsStatuses["RECORDING"] as $schedule_id => $arRecord)
    {
        $recording_ids[] = $schedule_id;
    }
    
    $arFilter["=UF_CHANNEL_ID"] = \Hawkart\Megatv\ChannelTable::getActiveIdByCityByUser();
    
    if(count($recording_ids)>0)
        $arFilter["!=ID"] = $recording_ids;
    
    $arRecommend = \Hawkart\Megatv\CStat::getRecommend($USER->GetID()); 
    
    $arProgs = array();
    $arRecommendSorted = array();
    foreach($arRecommend as $by_what=>$epg_ids)
    {
        $count = 0;
        
        if(count($epg_ids)>0)
        {
            $uf_ids = array();
            
            $result = \Hawkart\Megatv\ScheduleTable::getList(array(
                'filter' => $arFilter + array("=UF_PROG.UF_EPG_ID" => $epg_ids),
                'select' => $arSelect,
                'order' => array("UF_PROG.UF_RATING" => "DESC"),
                //'limit' => 200
            ));
            while ($arSchedule = $result->fetch())
            {
                $arSchedule["UF_DATE_START"] = $arSchedule["DATE_START"] = \CTimeEx::dateOffset($arSchedule['UF_DATE_START']->toString());
                $arSchedule["UF_DATE_END"] = $arSchedule["DATE_END"] = \CTimeEx::dateOffset($arSchedule['UF_DATE_END']->toString());
                $arSchedule["UF_DATE"] = $arSchedule["DATE"] = substr($arSchedule["DATE_START"], 0, 10);
    
                $arSchedule["DETAIL_PAGE_URL"] = "/channels/".$arSchedule["UF_CHANNEL_CODE"]."/".$arSchedule["UF_PROG_CODE"]."/?event=".$arSchedule["ID"];
                $arProgs[$arSchedule["UF_ID"]] = $arSchedule;
            }
            
            //sort by recommend
            foreach($epg_ids as $uf_id)
            {
                if(in_array($uf_id, $uf_ids))
                    continue;
                
                $uf_ids[] = $uf_id;
                
                $arSchedule = $arProgs[$uf_id];
                
                if(intval($arSchedule["ID"])>0)
                {
                    $arRecommendSorted[$by_what][] = $arSchedule;
                    $count++;
                }
                
                unset($arSchedule);
                
                if($count==$countPerPage*2)
                    break;
            }
            unset($uf_ids);
            unset($arProgs);
        }
    }
    
    //print_r($arRecommendSorted);
    
    //from 3 array get 10 items
    $notRepeatIds = array();
    $count = 0;
    
    if($countPerPage>count($arRecommendSorted["by_users"]) && count($arRecommendSorted["by_users"])>0)
        $countPerPage = count($arRecommendSorted["by_users"]);
    
    //echo $countPerPage;
    
    while($count<$countPerPage)
    {
        foreach(array("by_users", "by_records", "by_ganres") as $by_what)
        {
            $added = false;
            
            if(count($arRecommendSorted[$by_what])==0)
                continue;
            
            while(!$added)
            {
                $arSchedule = $arRecommendSorted[$by_what][0];//array_shift($arRecommendSorted[$by_what]);
                
                if(!in_array($arSchedule["UF_ID"], $notRepeatIds))
                {
                    $notRepeatIds[] = $arSchedule["UF_ID"];
                    
                    if($count<$countPerPage)
                    {
                        $arResult["PROGS"][] = $arSchedule;
                    }
                    
                    $added = true;
                    $count++;
                }
                
                unset($arRecommendSorted[$by_what][0]);
                $arRecommendSorted[$by_what] = array_values($arRecommendSorted[$by_what]);

                if(count($arRecommendSorted[$by_what])==0)
                    $added = true;
            }
        }
    }   
}else{    
    if(!$USER->IsAuthorized())
    {
        $arFilter["=UF_CHANNEL_ID"] = \Hawkart\Megatv\ChannelTable::getActiveIdByCity();
        $arResult["PROGS"] = getRecommendForAll($arFilter, $arSelect, $limit, $offset);
    }
}

$arResult["CATEGORIES"] = array();
if($USER->IsAuthorized())
{
    $arStat = \Hawkart\Megatv\CStat::getByUser($USER->GetID());
    foreach($arStat["CATS"] as $category => $id)
    {
        $str = \CDev::translit($category, "ru", array("replace_space"=>"-", "replace_other"=>"-"));
        $arResult["CATEGORIES"][$category] = $str; 
    }
}else{
    foreach($arResult["PROGS"] as $key=>$arProg)
    {
        $category = $arProg["UF_CATEGORY"];
        $str = \CDev::translit($category, "ru", array("replace_space"=>"-", "replace_other"=>"-"));
        $arResult["CATEGORIES"][$category] = $str;
    }
}

if($_REQUEST["AJAX"]=="Y")
{
    $APPLICATION->RestartBuffer();
    
    /**
     * Get records statuses by user
     */
    $arRecordsStatuses = \Hawkart\Megatv\RecordTable::getListStatusesByUser();
    
    foreach($arResult["PROGS"] as $key=>$arProg)
    {
        $arProg["CAT_CODE"] = $arResult["CATEGORIES"][$arProg["UF_CATEGORY"]];
    }
    
    $arRecords = array();
    
    foreach($arResult["PROGS"] as $arRecord)
    {
        $datetime = $arRecord['UF_DATE_START'];
        $date = substr($datetime, 0, 10);
        $time = substr($datetime, 11, 5);
        
        $arStatus = \Hawkart\Megatv\CScheduleTemplate::status($arRecord, $arRecordsStatuses);
        $status = $arStatus["status"];
        $status_icon = $arStatus["status-icon"];
        
        $img = \Hawkart\Megatv\CFile::getCropedPath($arRecord["UF_IMG_PATH"], array(288, 288));
        
        ob_start();
        if($status=="viewed")
        {
            $path = $_SERVER["DOCUMENT_ROOT"].$arProg["PICTURE"]["SRC"];
            ?>
            <div class="item-image-holder" style="background-image: url(<?=SITE_TEMPLATE_PATH?>/ajax/img_grey.php?path=<?=urlencode($img)?>)"></div>
            <span class="item-status-icon">
    			<span data-icon="icon-viewed"></span>
    			<span class="status-desc">Просмотрено</span>
    		</span>
            <?
            $img = SITE_TEMPLATE_PATH."/ajax/img_grey.php?path=".urlencode($img);
        }else{
            ?><div class="item-image-holder" style="background-image: url(<?=$img?>)"></div><?
        }
        
        echo $status_icon;
        echo \Hawkart\Megatv\CScheduleTemplate::driveNotifyMessage();
        
        if($status=="recording"):?>
        <div class="recording-notify">
            <div class="recording-notify-text-wrap">
                <div class="recording-notify-icon">
                </div>
                <p>Ваша любимая передача<br> поставлена на запись</p>
            </div>
        </div>
        <?endif;
        $content = ob_get_contents();  
        ob_end_clean();
        
        $_arRecord = array(
            "id" => $arRecord["ID"],
            "time" => $time,
    		"date" => $date,
    		"link" => $arRecord["DETAIL_PAGE_URL"],
    		"name" => \Hawkart\Megatv\CScheduleTemplate::cutName(\Hawkart\Megatv\ProgTable::getName($arRecord), 35),
    		"image" => $img,
    		"category" => array(
                "link" => $arResult["CATEGORIES"][$arRecord["UF_CATEGORY"]],
                "name" => $arRecord["UF_CATEGORY"]
            ),
            "button" => $content,
            "status" => "status-".$status,
        );
    
        $arRecords[] = $_arRecord;
        unset($_arRecord);
    }
    
    //$arRecords["date"] = $date;
    
    echo json_encode($arRecords);

    die();
}

if(count($arResult["PROGS"])>0)
{
    $arResult["PROGS"] = \Hawkart\Megatv\CScheduleView::setIndex(array(
        "PROGS" => $arResult["PROGS"],
    ));
}

$this->IncludeComponentTemplate();
?>