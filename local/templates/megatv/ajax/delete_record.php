<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();

global $USER;
if(!is_object($USER))
    $USER = new \CUser;
    
$status = false;

$record_id = intval($_REQUEST["broadcastID"]);
if($USER->IsAuthorized() && $record_id>0 && $_REQUEST["delete"])
{
    $result = \Hawkart\Megatv\RecordTable::getById($record_id);
    $arRecord = $result->fetch();
    
    if($arRecord["UF_USER_ID"]==$USER->GetID())
    {
        $USER_ID = $USER->GetID();
        $rsUser = \CUser::GetByID($USER_ID);
        $arUser = $rsUser->Fetch();

        $arRecord["UF_DATE_START"] = $arRecord['UF_DATE_START']->toString();
        $arRecord["UF_DATE_END"] = $arRecord['UF_DATE_END']->toString();
        $duration = strtotime($arRecord["UF_DATE_END"])-strtotime($arRecord["UF_DATE_START"]);
        $minutes = ceil($duration/60);
        $gb = $minutes*(18.5/1024);
        
        $busy = floatval($arUser["UF_CAPACITY_BUSY"])-$gb; 
        $user = new \CUser;
        $user->Update($arUser["ID"], array("UF_CAPACITY_BUSY"=>$busy));
        
        \Hawkart\Megatv\RecordTable::update($record_id, array(
            "UF_DELETED" => 1
        ));

        $status = "success";
    }
}

exit(json_encode(array("status"=>$status)));
?>