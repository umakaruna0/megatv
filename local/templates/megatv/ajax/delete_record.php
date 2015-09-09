<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();

global $USER;
if(!is_object($USER))
    $USER = new CUser;
    
$status = false;

$record_id = intval($_REQUEST["broadcastID"]);
if($USER->IsAuthorized() && $record_id>0 && $_REQUEST["delete"])
{
    $arRecord = CRecordEx::getByID($record_id);
    if($arRecord["UF_USER"]==$USER->GetID())
    {
        CRecordEx::delete($record_id);
        
        //Возможно нужно сделать апи для отмены в сотале + вернуть пространство свободное
        
        $status = "success";
    }
}

exit(json_encode(array("status"=>$status)));
?>