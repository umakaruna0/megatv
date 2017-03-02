<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();

global $USER;
if(!is_object($USER))
    $USER = new CUser;

$result = array();
$result["status"] = "error"; 

$prog_time = intval($_REQUEST["broadcastID"]);
if($USER->IsAuthorized() && $prog_time>0)
{
    //Get serial epg id by schedule's prog
    $res = \Hawkart\Megatv\ScheduleTable::getList(array(
        'filter' => array("=ID" => $prog_time),
        'select' => array(
            "SEPGID" => "UF_PROG.UF_EPG_ID",
        ),
        'limit' => 1
    ));
    $arSchedule = $res->fetch();
    
    $result = \Hawkart\Megatv\SerialTable::subscribeByEpg($arSchedule["SEPGID"]);
}

exit(json_encode($result));
?>