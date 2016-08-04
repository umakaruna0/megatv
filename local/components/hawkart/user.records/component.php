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

$key = 0;
$result = \Hawkart\Megatv\RecordTable::getList(array(
    'filter' => array(
        "=UF_USER_ID" => $USER->GetID(), 
        "!UF_URL" => false
    ),
    'select' => array(
        "ID", "UF_DATE_START", "UF_DATE_END", "UF_PROG_ID", "UF_WATCHED", "UF_PROGRESS_PERS",
        "UF_TITLE" => "UF_PROG.UF_TITLE", "UF_SUB_TITLE" => "UF_PROG.UF_SUB_TITLE", "UF_IMG_PATH" => "UF_PROG.UF_IMG.UF_PATH",
        "UF_CATEGORY" => "UF_PROG.UF_CATEGORY", "UF_URL", "UF_CHANNEL_CODE" => "UF_CHANNEL.UF_CODE",
        "UF_PROG_CODE" => "UF_PROG.UF_CODE",
    ),
    'order' => array("UF_DATE_END"=>"DESC"),
    //'limit' => intval($arParams["NEWS_COUNT"]),
    //'offset' => $offset
));
while ($arRecord = $result->fetch())
{
    $arRecord["UF_NAME"] = \Hawkart\Megatv\ProgTable::getName($arRecord);
    $arRecord["PICTURE"]["SRC"] = \Hawkart\Megatv\CFile::getCropedPath($arRecord["UF_IMG_PATH"], array(300, 300), true);
    $arRecord["DETAIL_PAGE_URL"] = "/channels/".$arRecord["UF_CHANNEL_CODE"]."/".$arRecord["UF_PROG_CODE"]."/";
    
    if(!empty($arRecord["UF_CATEGORY"]))
        $arCats[] = $arRecord["UF_CATEGORY"];
    
    if($_REQUEST["categoryID"]!=$arRecord["UF_CATEGORY"] && !empty($_REQUEST["categoryID"]) && $_REQUEST["categoryID"]!="Все" && $_REQUEST["categoryID"]!="false")
        continue;
    
    if($key>=$offset && $key<($offset+intval($arParams["NEWS_COUNT"])))
    {  
        $arResult["RECORDS"][] = $arRecord;
    }
    $key++;
}

$arCats = array_unique($arCats);

$arResult["CATEGORIES"] = array();
foreach($arCats as $category)
{
    $arParams = array("replace_space"=>"-", "replace_other"=>"-");
    $str = \CDev::translit($category, "ru", $arParams);
    $arResult["CATEGORIES"][$category] = $str; 
}


if($_REQUEST["AJAX"]=="Y")
{
    $APPLICATION->RestartBuffer();
    
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
    		"link" => $arRecord["UF_URL"],
    		"name" => $arRecord["UF_NAME"],
    		"image" => $path,
    		"category" => array(
                "link" => $arResult["CATEGORIES"][$arRecord["UF_CATEGORY"]],
                "name" => $arRecord["UF_CATEGORY"]
            )
        );
    
        $arRecords[] = $_arRecord;
        unset($_arRecord);
    }
    
    echo json_encode($arRecords);

    die();
}

$this->IncludeComponentTemplate();
?>