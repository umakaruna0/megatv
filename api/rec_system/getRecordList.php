<?
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . '/../../');

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER, $APPLICATION;
if (!is_object($USER))
    $USER=new CUser;

$arRecords = array();
$arFilter = array();

if(isset($_REQUEST["sourceId"]) && !empty($_REQUEST["sourceId"]))
{
    $arFilter["=UF_CHANNEL.UF_SOURCE_ID"] = trim($_REQUEST["sourceId"]);
}

if(isset($_REQUEST["dateFrom"]) && !empty($_REQUEST["dateFrom"]))
{
    $date = str_replace("_", "-", substr($_REQUEST["dateFrom"], 0, 10));
    $time = str_replace("_", ":", substr($_REQUEST["dateFrom"], 11));
    
    $dateStart = $date." ".$time;
    $arFilter[">=UF_DATETIME_ADD"] = new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s');
}

if(isset($_REQUEST["dateTo"]) && !empty($_REQUEST["dateTo"]))
{
    $date = str_replace("_", "-", substr($_REQUEST["dateTo"], 0, 10));
    $time = str_replace("_", ":", substr($_REQUEST["dateTo"], 11));
    
    $dateTo = $date." ".$time;
    $arFilter["<UF_DATETIME_ADD"] = new \Bitrix\Main\Type\DateTime($dateTo, 'Y-m-d H:i:s');
}

if(isset($_REQUEST["deleted"]) && !empty($_REQUEST["deleted"]))
{
    $arFilter["=UF_DELETED"] = 1;
}

$epg_ids = array();
$arSelect = array(
    "ID", "UF_DATE_START", "UF_DATE_END", "UF_EPG_ID", 
    "CHANNEL_ID" => "UF_CHANNEL.UF_EPG_ID",
    "UF_TITLE" => "UF_PROG.UF_TITLE", "UF_DELETED"
);
$result = \Hawkart\Megatv\RecordTable::getList(array(
    'filter' => $arFilter,
    'select' => $arSelect,
    'order' => array("UF_DATETIME_ADD"=>"DESC"),
));
while ($arRecord = $result->fetch())
{  
    $arItem = array(
        'ID' => $arRecord["UF_EPG_ID"],
        'START' => $arRecord['UF_DATE_START']->toString(),
        "STOP" => $arRecord['UF_DATE_END']->toString(),
        "CHANNEL_ID" => $arRecord["CHANNEL_ID"],
        'TITLE' => $arRecord["UF_TITLE"],
        'DELETED' => $arRecord["UF_DELETED"] ? 1 : 0
    );
    
    $arRecords[] = $arItem;
}

echo json_encode($arRecords);


die()
?>