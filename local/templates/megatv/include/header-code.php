<?
global $USER;
session_start();

$site = "megatv.su";

//city change
if(isset($_POST["city-id"]) && intval($_POST["city-id"])>0 && check_bitrix_sessid())
{
    $arGeo = \Hawkart\Megatv\CityTable::setGeoCity(intval($_POST["city-id"]));
    header("Location: ".$APPLICATION->GetCurPage());
}
else if(isset($_POST["lang-id"]) && intval($_POST["lang-id"])>0 && check_bitrix_sessid())
{
    $arGeo = \Hawkart\Megatv\CountryTable::setCountry(intval($_POST["lang-id"]));
    
    if(strtoupper($arGeo["COUNTRY_ISO"])==LANGUAGE_DEFAULT) //if ru
    {
        $redirect_url = "https://".$site;
    }else{
        $redirect_url = "https://".strtolower($arGeo["COUNTRY_ISO"]).".".$site;
    }
    
    if(strtolower(LANGUAGE_ID) != strtolower($arGeo["COUNTRY_ISO"]))
    {
        LocalRedirect($redirect_url.$APPLICATION->GetCurPage()); die();
    }
}
else
{
    $arGeo = \Hawkart\Megatv\CityTable::getGeoCity();
    if(strtolower(LANGUAGE_ID) != strtolower($arGeo["COUNTRY_ISO"]) || empty($arGeo["COUNTRY_ISO"]))
    {
        \Hawkart\Megatv\CountryTable::setCountryByIso(LANGUAGE_ID);
    }
}

if($USER->IsAuthorized())
{     
    /**
     * Get records statuses by user
     */
    $arRecordStatus = \Hawkart\Megatv\RecordTable::getListStatusesByUser();
    
    /**
     * User subscribe channel list. Add global property
     */
    $selectedChannels = array();
    $result = \Hawkart\Megatv\SubscribeTable::getList(array(
        'filter' => array("UF_ACTIVE"=>1, "=UF_USER_ID" => $USER->GetID(), ">UF_CHANNEL_ID" => 0),
        'select' => array("UF_CHANNEL_ID")
    ));
    while ($arSub = $result->fetch())
    {
        $selectedChannels[] = $arSub["UF_CHANNEL_ID"];
    }
    $APPLICATION->SetPageProperty("ar_subs_channels", json_encode($selectedChannels));
}