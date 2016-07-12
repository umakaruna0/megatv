<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $USER, $APPLICATION;
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
$arSort = array("UF_CHANNEL.UF_BASE.UF_SORT" => "ASC");
$result = \Hawkart\Megatv\ChannelCityTable::getList(array(
    'filter' => $arFilter,
    'select' => $arSelect,
    'limit' => 1,
));
if ($arResult = $result->fetch())
{
    $arResult["ID"] = $arResult["UF_CHANNEL_ID"];
    $arResult["DETAIL_PAGE_URL"] = "/channels/".$arResult['UF_CODE']."/";
    $title = $arResult["UF_TITLE"]." -  телепрограмма на сегодня, программа телепередач канала ".$arResult["UF_H1"]." на МегаТВ";
    $APPLICATION->SetTitle($title);
    //$APPLICATION->SetPageProperty("keywords", $arResult["UF_KEYWORDS"]);
    $APPLICATION->SetPageProperty("description", TruncateText($arResult["UF_DESCRIPTION"], 256));
    $APPLICATION->SetDirProperty("h1", $arResult["UF_H1"] ? $arResult["UF_H1"] : $arResult["UF_TITLE"]);
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

$arResult["PROGS"] = array();
$result = \Hawkart\Megatv\ProgExternalTable::getList(array(
    'filter' => array("UF_SERIAL.UF_CHANNEL_ID" => '%"'.$arResult['UF_CHANNEL_BASE_ID'].'"%'),
    'select' => array("ID", "UF_TITLE", "UF_EXTERNAL_ID", "UF_THUMBNAIL_URL", "UF_JSON"),
    'order' => array("UF_DATETIME" => "DESC")
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