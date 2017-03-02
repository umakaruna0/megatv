<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $USER, $APPLICATION, $arSite;
$arResult = array();
$arParams = $arParams + array(
    "DATETIME" => \CTimeEx::getDatetime(),
    "LIST_URL" => $APPLICATION->GetCurDir(),
    "BACK_URL" => $_SERVER['HTTP_REFERER']
);

//Get prog detail
$arFilter = array("=UF_CODE" => $arParams["ELEMENT_CODE"]);
$arSelect = array(
    "ID", "UF_TITLE", "UF_SUB_TITLE", "UF_IMG_PATH" => "UF_IMG.UF_PATH",
    "UF_RATING", "UF_DESC", "UF_SUB_DESC", "UF_GANRE", "UF_YEAR_LIMIT", "UF_COUNTRY",
    "UF_YEAR", "UF_DIRECTOR", "UF_PRESENTER", "UF_ACTOR", "UF_CATEGORY", "UF_EPG_ID"
);
$obCache = new \CPHPCache;
if( $obCache->InitCache(86400, serialize($arFilter).serialize($arSelect), "/prog-detail/"))
{
	$arResult = $obCache->GetVars();
}
elseif($obCache->StartDataCache())
{
    $result = \Hawkart\Megatv\ProgTable::getList(array(
        'filter' => $arFilter,
        'select' => $arSelect,
        'limit' => 1
    ));
    if ($arResult = $result->fetch())
    {
        $arResult["UF_PROG_ID"] = $arResult["ID"];
        $arResult["PICTURE"]["SRC"] = \Hawkart\Megatv\CFile::getCropedPath($arResult["UF_IMG_PATH"], array(600, 600));
        $arResult["KEYWORDS"] = array($arResult["UF_CATEGORY"], $arResult["UF_GANRE"]);
    }
    $obCache->EndDataCache($arResult); 
}


//Get Shedule inform for prog
if(empty($_REQUEST["event"]))
{
    $arDate = \CTimeEx::getDateTimeFilter($arParams["DATETIME"]["SERVER_DATETIME"]);
    $dateStart = date("Y-m-d H:i:s");
    $arFilter = array(
        "=UF_PROG.UF_CODE" => $arParams["ELEMENT_CODE"],
        ">=UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'),
    );
}else{
    $arFilter = array(
        "=UF_PROG.UF_CODE" => $arParams["ELEMENT_CODE"],
        "=ID" => $_REQUEST["event"]
    );
}
$arSelect = array(
    "ID", "UF_DATE_START", "UF_DATE_END", "UF_DATE", "UF_CHANNEL_ID", "UF_ICON" => "UF_CHANNEL.UF_BASE.UF_ICON",
);
$obCache = new \CPHPCache;
if( $obCache->InitCache(86400, serialize($arFilter).serialize($arSelect), "/shedule-detail/"))
{
	$arResult = $obCache->GetVars();
}
elseif($obCache->StartDataCache())
{
    //get channel by code
    $result = \Hawkart\Megatv\ScheduleTable::getList(array(
        'filter' => $arFilter,
        'select' => $arSelect,
        'limit' => 1
    ));
    if ($arShedule = $result->fetch())
    {
        $arResult["ID"] = $arShedule["ID"];
        $arResult["UF_ICON"] = $arShedule["UF_ICON"];
        $arResult["UF_CHANNEL_ID"] = $arShedule["UF_CHANNEL_ID"];
        $arResult["UF_DATE_START"] = $arResult["DATE_START"] = \CTimeEx::dateOffset($arShedule['UF_DATE_START']->toString());
        $arResult["UF_DATE_END"] = $arResult["DATE_END"] = \CTimeEx::dateOffset($arShedule['UF_DATE_END']->toString());
        $arResult["UF_DATE"] = $arResult["DATE"] = substr($arShedule["DATE_START"], 0, 10);
        $sec = strtotime($arResult["DATE_END"]) - strtotime($arResult["DATE_START"]);
        $arResult["DURATION"] = \CTimeEx::secToStr($sec);
    }
    $obCache->EndDataCache($arResult); 
}

//redirect if error
if(intval($arResult["ID"])==0)
{
    if(!empty($arFilter["=UF_PROG.UF_CODE"]))
    {
        CHTTP::SetStatus("404 Not Found");
        @define("ERROR_404", "Y");
    }else{
        LocalRedirect($APPLICATION->GetCurDir(), false, "301 Moved Permanently");
    }
}else{
    //FOR SEO
    $url_params = parse_url($_SERVER["REQUEST_URI"]);
    if(substr($url_params["path"], -1)!="/")
    {
        $url = $url_params["path"]."/";
        if(!empty($url_params["query"]))
            $url.= "?".$url_params["query"];
        
        LocalRedirect($url, false, "301 Moved permanently");
    }
}

//SEO
$arResult["UF_DESC"] = str_replace(array("TVguru", "MegaTV"), $arSite["NAME"], $arResult["UF_DESC"]);
$APPLICATION->SetTitle($arResult["UF_TITLE"]);
$APPLICATION->SetPageProperty("title", trim($arResult["UF_TITLE"]. " ".$arResult["UF_SUB_TITLE"]));
$APPLICATION->SetPageProperty("keywords", implode(", ", $arResult["KEYWORDS"]));
$APPLICATION->SetPageProperty("description", TruncateText($arResult["UF_DESC"], 256));
$APPLICATION->SetDirProperty('og_image', $arResult["PICTURE"]["SRC"]);
$APPLICATION->SetDirProperty('og_type', 'album');

//get status schedule
$arResult["STATUS"] = \Hawkart\Megatv\CScheduleTemplate::status(array(
    "ID" => $arResult["ID"],
    "UF_CHANNEL_ID" => $arResult["UF_CHANNEL_ID"],
    "DATE_START" => $arResult["DATE_START"],
    "DATE_END" => $arResult["DATE_END"]
));

foreach(array("UF_DIRECTOR", "UF_PRESENTER", "UF_ACTOR") as $type)
{
    $_arResult[$type] = array();
    $arPeoples = explode(",", $arResult[$type]);

    foreach($arPeoples as $actor)
    {
        $actor = trim($actor);
        if(!empty($actor))
        {
            $link = \Hawkart\Megatv\PeopleTable::getKinopoiskLinkByName($actor);
            $link = str_replace("//name", "/name", $link);
            if(empty($link)) $link = "#";
            $_arResult[$type][] = array(
                "NAME" => $actor,
                "LINK" => $link
            );
        }
    }
    $arResult[$type] = $_arResult[$type];
    unset($_arResult[$type]);
}

$result = \Hawkart\Megatv\SerialTable::getList(array(
    'filter' => array("=UF_EPG_ID" => $arResult["UF_EPG_ID"]),
    'select' => array("ID"),
    'limit' => 1
));
$arResult["SERIAL"] = $result->fetch();

global $USER;
if($USER->IsAuthorized() && $arResult["SERIAL"]["ID"]>0)
{
    $result = \Hawkart\Megatv\SerialSubscribeTable::getList(array(
        'filter' => array(
            "=UF_USER_ID" => $USER->GetID(), 
            "=UF_SERIAL_ID" => $arResult["SERIAL"]["ID"], 
        ),
        'select' => array("ID", "UF_ACTIVE"),
        'limit' => 1
    ));
    if ($arRecord = $result->fetch())
    {
        $arResult["SERIAL"] = array();
    }
}

/**
 * Add data to statistics
 */
//\Hawkart\Megatv\CStat::channelAdd($arResult["UF_CHANNEL_ID"]);
$back_recommendations = false;
if(strpos($_SERVER['HTTP_REFERER'], "/recommendations/")!==false)
    $back_recommendations = true;
    
\Hawkart\Megatv\CStat::addByShedule($arResult["ID"], "scheduleShow", $back_recommendations);

$this->IncludeComponentTemplate();
?>