<?
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . '/../');
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Content-Type: text/html; charset=utf-8');
ini_set('mbstring.func_overload', '2');
ini_set('mbstring.internal_encoding', 'UTF-8');

global $USER, $APPLICATION;
if (!is_object($USER))
    $USER=new \CUser;

$arSerials = array();
$result = \Hawkart\Megatv\SerialTable::getList(array(
    'filter' => array("!UF_EPG_ID" => false),
    'select' => array("UF_EPG_ID", "ID", "UF_CHANNEL_ID")
));
while ($row = $result->fetch())
{
    $arSerials[$row["UF_EPG_ID"]] = $row;
}

$arSerialChannels = array();
$result = \Hawkart\Megatv\ScheduleTable::getList(array(
    'filter' => array(
        "=UF_CHANNEL.UF_BASE.UF_ACTIVE" => 1,
        "=UF_PROG.UF_ACTIVE" => 1,
    ),
    'select' => array(
        "UF_ID" => "UF_PROG.UF_EPG_ID", "UF_BASE_CHANNEL_ID" => "UF_CHANNEL.UF_BASE_ID"
    )
));
while ($arSchedule = $result->fetch())
{ 
    $arSerialChannels[$arSchedule["UF_ID"]][] = $arSchedule["UF_BASE_CHANNEL_ID"];
}

foreach($arSerialChannels as $epg_id => $arChannels)
{
    $arSerial = $arSerials[$epg_id];
    
    if(intval($arSerial["ID"])>0)
    {
        $channel_ids = array_merge((array)$arSerial["UF_CHANNEL_ID"], $arChannels);
        $channel_ids = array_unique($channel_ids);
        \Hawkart\Megatv\SerialTable::Update($arSerial["ID"], array("UF_CHANNEL_ID" => $channel_ids));
    }
}

die();
?>