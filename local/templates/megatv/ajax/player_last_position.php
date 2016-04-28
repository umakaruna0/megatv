<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();

global $USER;
if(!is_object($USER))
    $USER = new \CUser;

$broadcastID = intval($_GET["broadcastID"]);

if($_GET["record"]=="false")
{
    $result = \Hawkart\Megatv\RecordTable::getList(array(
        'filter' => array("=UF_USER_ID" => $USER->GetID(), "=UF_SCHEDULE_ID" => $broadcastID),
        'select' => array("ID", "UF_PROGRESS_PERS", "UF_PROG_ID"),
        'limit' => 1
    ));
    if ($arRecord = $result->fetch())
    {
        $broadcastID = $arRecord["ID"];
    }
}

$arFields = array(
    "UF_PROGRESS_SECS" => intval($_GET["progressInSeconds"]),
    "UF_PROGRESS_PERS" => intval($_GET["progressPosition"])
);

if(intval($_GET["progressPosition"])>3)
    $arFields["UF_WATCHED"] = 1;

\Hawkart\Megatv\RecordTable::update($broadcastID, $arFields);

//add to statistic
//$qunatityQuanters = abs($arRecord["UF_PROGRESS_PERS"]-$arFields["UF_PROGRESS_PERS"]);
$qunatityQuanters = intval($arFields["UF_PROGRESS_PERS"]);
if($qunatityQuanters>0)
    \Hawkart\Megatv\CStat::addByRecord($broadcastID, "quaterShow_".$qunatityQuanters);

die();  
?>