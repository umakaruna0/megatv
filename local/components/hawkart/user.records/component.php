<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

global $USER;
$arResult["RECORDS"] = array();

$offset = 0;
if($_REQUEST["AJAX"]=="Y")
{
    $offset = $_REQUEST["offset"];
}

$arDatetime = \CTimeEx::getDatetime();
$date_now = $arDatetime["SERVER_DATETIME_WITH_OFFSET"];

$key = 0;
$arFilter = array(
    "=UF_USER_ID" => $USER->GetID(), 
    "=UF_DELETED" => 0
);
if(!empty($_REQUEST["categoryID"]) && $_REQUEST["categoryID"]!="Все" && $_REQUEST["categoryID"]!="false")
{
    $arFilter["=UF_CATEGORY"] = trim($_REQUEST["categoryID"]);
}

$arSelect = array(
    "ID", "UF_DATE_START", "UF_DATE_END", "UF_PROG_ID", "UF_WATCHED", "UF_PROGRESS_PERS",
    "UF_TITLE" => "UF_PROG.UF_TITLE", "UF_SUB_TITLE" => "UF_PROG.UF_SUB_TITLE", "UF_IMG_PATH" => "UF_PROG.UF_IMG.UF_PATH",
    "UF_CATEGORY" => "UF_PROG.UF_CATEGORY", "UF_URL", "UF_CHANNEL_CODE" => "UF_CHANNEL.UF_BASE.UF_CODE",
    "UF_PROG_CODE" => "UF_PROG.UF_CODE", "UF_EPG_ID"
);
$result = \Hawkart\Megatv\RecordTable::getList(array(
    'filter' => $arFilter,
    'select' => $arSelect,
    'order' => array("UF_DATE_END"=>"DESC"),
    'limit' => intval($arParams["NEWS_COUNT"]),
    'offset' => $offset
));
while ($arRecord = $result->fetch())
{
    $arRecord["UF_NAME"] = \Hawkart\Megatv\ProgTable::getName($arRecord);
    $arRecord["PICTURE"]["SRC"] = \Hawkart\Megatv\CFile::getCropedPath($arRecord["UF_IMG_PATH"], array(300, 300), true);
    $arRecord["DETAIL_PAGE_URL"] = "/channels/".$arRecord["UF_CHANNEL_CODE"]."/".$arRecord["UF_PROG_CODE"]."/";
    
    $arRecord["STATUS"] = "";
    if(!empty($arRecord["DATE_START"]))
    {
        $arRecord["DATE_START"] = \CTimeEx::dateOffset($arRecord["UF_DATE_START"]->toString());
        $minutes = intval(strtotime($date_now)-strtotime($arRecord["UF_DATE_START"]))/60;
    }
    
    if((!\CTimeEx::dateDiff($arRecord["DATE_START"], $date_now) || $minutes<5) && !empty($arRecord["DATE_START"]) && !empty($arRecord["UF_URL"]))
    {
        $arRecord["STATUS"] = "status-recording";
    }elseif(!empty($arRecord["UF_URL"]))
    {
        $arRecord["STATUS"] = "status-recorded";
    }
    
    $arResult["RECORDS"][] = $arRecord;
}

$arResult["CATEGORIES"] = array();
$arStat = \Hawkart\Megatv\CStat::getByUser($USER->GetID());
foreach($arStat["CATS"] as $category => $id)
{
    $arParams = array("replace_space"=>"-", "replace_other"=>"-");
    $str = \CDev::translit($category, "ru", $arParams);
    $arResult["CATEGORIES"][$category] = $str; 
}


if($_REQUEST["AJAX"]=="Y")
{
    $APPLICATION->RestartBuffer();
    
    /**
     * Get records statuses by user
     */        
    $arRecords = array();
    
    foreach($arResult["RECORDS"] as $arRecord)
    {
        $datetime = $arRecord['UF_DATE_START']->toString();
        $date = substr($datetime, 0, 10);
        $time = substr($datetime, 11, 5);
        if(strlen($arRecord["UF_NAME"])>25)
        {
            $arRecord["UF_NAME"] = substr($arRecord["UF_NAME"], 0, 25)."...";
        }
        
        if($arRecord["UF_WATCHED"])
        {
            $path = $_SERVER["DOCUMENT_ROOT"].$arRecord["PICTURE"]["SRC"];
            $path = SITE_TEMPLATE_PATH."/ajax/img_grey.php?path=".urlencode($path);
        }else{
            $path = $arRecord["PICTURE"]["SRC"];
        }
        
        $_arRecord = array(
            "id" => $arRecord["ID"],
            "time" => $time,
    		"date" => $date,
    		"link" => $arRecord["DETAIL_PAGE_URL"],
    		"name" => $arRecord["UF_NAME"],
    		"image" => $path,
    		"category" => array(
                "link" => $arResult["CATEGORIES"][$arRecord["UF_CATEGORY"]],
                "name" => $arRecord["UF_CATEGORY"]
            ),
            "status" => $arRecord["STATUS"],
        );
    
        $arRecords[] = $_arRecord;
        unset($_arRecord);
    }
    
    echo json_encode($arRecords);

    die();
}

$this->IncludeComponentTemplate();
?>