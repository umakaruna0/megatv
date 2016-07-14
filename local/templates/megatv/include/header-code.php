<?
global $USER;
session_start();

$host = $_SERVER['SERVER_NAME'];
if(strpos($host, "http://")==false)
{
    $host = "http://".$host;
}

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
        $redirect_url = "http://tvguru.com";
    }else{
        $redirect_url = "http://".strtolower($arGeo["COUNTRY_ISO"])."."."tvguru.com";
    }
    
    if(strtolower(LANGUAGE_ID) != strtolower($arGeo["COUNTRY_ISO"]))
    {
        LocalRedirect($redirect_url.$APPLICATION->GetCurPage()); die();
    }
}
else
{
    $arGeo = \Hawkart\Megatv\CityTable::getGeoCity();
    if(strtolower(LANGUAGE_ID) != strtolower($arGeo["COUNTRY_ISO"]))
    {
        \Hawkart\Megatv\CountryTable::setCountryByIso(LANGUAGE_ID);
    }
}

if($USER->IsAuthorized())
{           
    $countRecorded = 0;
    $countInRec = 0;
    $count = 0;
    $arStatusRecording = array();   //записывается
    $arStatusRecorded = array();    //записана, можно просмотреть
    $arStatusViewed = array();    //просмотренна
    $result = \Hawkart\Megatv\RecordTable::getList(array(
        'filter' => array("=UF_USER_ID" => $USER->GetID()),
        'select' => array("ID", "UF_URL", "UF_SCHEDULE_ID", "UF_WATCHED", "UF_PROG_ID"),
    ));
    while ($arRecord = $result->fetch())
    {
        $shedule_id = $arRecord["UF_SCHEDULE_ID"];
        
        if($arRecord["UF_WATCHED"]==1)
        {
            $countRecorded++;
            $arStatusViewed[$shedule_id] = $arRecord;
        }
        else if(empty($arRecord["UF_URL"]))
        {
            $countInRec++;
            $arStatusRecording[$shedule_id] = $arRecord;
        }
        else if(!empty($arRecord["UF_URL"]))
        {
            $countRecorded++;
            $arStatusRecorded[$shedule_id] = $arRecord;
        }
        $count++;
    }
    $arRecordStatus = array(
        "RECORDING" => $arStatusRecording,
        "RECORDED"  => $arStatusRecorded,
        "VIEWED"    => $arStatusViewed
    );
    $APPLICATION->SetPageProperty("ar_record_status", json_encode($arRecordStatus));
    $APPLICATION->SetPageProperty("ar_record_in_rec", $countInRec);
    $APPLICATION->SetPageProperty("ar_record_recorded", $countRecorded);
    $APPLICATION->SetPageProperty("ar_record_total", $count);
    
    
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