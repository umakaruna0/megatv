<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $USER, $APPLICATION, $arSite; 
$arResult = array();
$arParams = $arParams + array(
    "DATETIME" => \CTimeEx::getDatetime(),
    "LIST_URL" => $APPLICATION->GetCurDir(),
);


//get channel by code
$arFilter = array(
    "=UF_CHANNEL.UF_BASE.UF_ACTIVE" => 1,
    "=UF_CHANNEL.UF_BASE.UF_CODE" => $arParams["ELEMENT_CODE"],
    "=UF_CITY_ID" => $_SESSION["USER_GEO"]["ID"]
);
$arSelect = array(
    'ID', 'UF_CHANNEL_ID', 'UF_CHANNEL_BASE_ID' => 'UF_CHANNEL.UF_BASE.ID', 
    'UF_TITLE' => 'UF_CHANNEL.UF_BASE.UF_TITLE', 'UF_ICON' => 'UF_CHANNEL.UF_BASE.UF_ICON',
    'UF_CODE' => 'UF_CHANNEL.UF_BASE.UF_CODE', "UF_IS_NEWS" => 'UF_CHANNEL.UF_BASE.UF_IS_NEWS',
    'UF_DESC' => 'UF_CHANNEL.UF_BASE.UF_DESC', 'UF_H1' => 'UF_CHANNEL.UF_BASE.UF_H1',
    'UF_DESCRIPTION' => 'UF_CHANNEL.UF_BASE.UF_DESCRIPTION', 'UF_KEYWORDS' => 'UF_CHANNEL.UF_BASE.UF_KEYWORDS'
);
$obCache = new \CPHPCache;
if( $obCache->InitCache(86400, serialize($arFilter).serialize($arSelect), "/channel-detail/"))
{
	$arResult = $obCache->GetVars();
}
elseif($obCache->StartDataCache())
{
    $arResult = array();
    $result = \Hawkart\Megatv\ChannelCityTable::getList(array(
        'filter' => $arFilter,
        'select' => $arSelect,
        'limit' => 1,
    ));
    if ($arResult = $result->fetch())
    {
        $arResult["ID"] = $arResult["UF_CHANNEL_ID"];
        $arResult["DETAIL_PAGE_URL"] = "/channels/".$arResult['UF_CODE']."/";
        $title = $arResult["UF_TITLE"]." -  телепрограмма на сегодня, программа телепередач канала ".$arResult["UF_H1"]." на ".$arSite["NAME"];
        if($arResult["UF_H1"]=="5 канал")
            $title = str_replace("канала ", "", $title);
        
        $title = str_replace("TvGuru", $arSite["NAME"], $title);
        
        $arResult["PAGE_TITLE"] = $title;
    }
    $obCache->EndDataCache($arResult); 
}


//get subscription list
$arSubscriptionChannels = $APPLICATION->GetPageProperty("ar_subs_channels");
$arResult["CHANNELS_SHOW"] = json_decode($arSubscriptionChannels, true);


//show error page SEO
if(intval($arResult["ID"])==0 || (!in_array($arResult['UF_CHANNEL_BASE_ID'], $arResult["CHANNELS_SHOW"]) && $USER->IsAuthorized()))
{
    CHTTP::SetStatus("404 Not Found");
    @define("ERROR_404", "Y");
}else{
    
    $APPLICATION->SetTitle($arResult["PAGE_TITLE"]);
    $APPLICATION->SetPageProperty("description", TruncateText($arResult["UF_DESCRIPTION"], 256));
    $APPLICATION->SetDirProperty("h1", $arResult["UF_H1"] ? $arResult["UF_H1"] : $arResult["UF_TITLE"]);
    
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

$offset = 0;
if($_REQUEST["AJAX"]=="Y")
{
    $offset = $_REQUEST["offset"];
}

$arResult["PROGS"] = array();
$result = \Hawkart\Megatv\ProgExternalTable::getList(array(
    'filter' => array("UF_SERIAL.UF_CHANNEL_ID" => '%"'.$arResult['UF_CHANNEL_BASE_ID'].'"%'),
    'select' => array("ID", "UF_TITLE", "UF_EXTERNAL_ID", "UF_THUMBNAIL_URL", "UF_JSON"),
    'order' => array("UF_DATETIME" => "DESC"),
    'limit' => $arParams["NEWS_COUNT"],
    'offset' => $offset
));
while ($row = $result->fetch())
{
    if(strpos($row["UF_THUMBNAIL_URL"], "rutube")!==false)
    {
        $row["UF_THUMBNAIL_URL"].="?size=m";
    }
    $arResult["ITEMS"][] = $row;
}

/**
 * Add data to statistics
 */
//\Hawkart\Megatv\CStat::channelAdd($arResult["ID"]);

$this->IncludeComponentTemplate();
?>