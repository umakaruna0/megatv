<?
/**
 * Get adv list
 */
 
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . '/../../');

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER, $APPLICATION;
if (!is_object($USER))
    $USER=new CUser;

/*
if(isset($_REQUEST["sourceId"]) && !empty($_REQUEST["sourceId"]))
{
    $arFilter["=UF_CHANNEL_ID"] = trim($_REQUEST["sourceId"]);
}
*/

$arFilter = array();

if(isset($_REQUEST["dateFrom"]) && !empty($_REQUEST["dateFrom"]))
{
    $date = str_replace("_", "-", substr($_REQUEST["dateFrom"], 0, 10));
    $time = str_replace("_", ":", substr($_REQUEST["dateFrom"], 11));
    
    $dateStart = $date." ".$time;
    $dateStart = date('Y-m-d H:i:s', strtotime("-3 hours", strtotime($dateStart)));
    $arFilter[">=UF_START"] = new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s');
}

if(isset($_REQUEST["dateTo"]) && !empty($_REQUEST["dateTo"]))
{
    $date = str_replace("_", "-", substr($_REQUEST["dateTo"], 0, 10));
    $time = str_replace("_", ":", substr($_REQUEST["dateTo"], 11));
    
    $dateTo = $date." ".$time;
    $dateTo = date('Y-m-d H:i:s', strtotime("-3 hours", strtotime($dateTo)));
    $arFilter["<UF_START"] = new \Bitrix\Main\Type\DateTime($dateTo, 'Y-m-d H:i:s');
}

$arRecords = array();
$arSelect = array(
    "ID", "UF_START", "UF_END", "UF_EX_ID"
);
$result = \Hawkart\Megatv\TimeTable::getList(array(
    'filter' => $arFilter,
    'select' => $arSelect,
    'order' => array("ID" => "DESC")
));
while ($arRecord = $result->fetch())
{      
    $arRecord['UF_START'] = date('d.m.Y H:i:s', strtotime("+3 hours", strtotime($arRecord['UF_START']->toString())));
    $arRecord['UF_END'] = date('d.m.Y H:i:s', strtotime("+3 hours", strtotime($arRecord['UF_END']->toString())));
    
    $arRecords[] = $arRecord;
}

echo json_encode($arRecords);

die();
?>