<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();

global $USER;
if(!is_object($USER))
    $USER = new CUser;

$result = array();
$error = false;
$ServiceID = intval($_REQUEST["serviceID"]);
$status = htmlspecialcharsbx($_REQUEST["status"]);
$selectedServices = array();
$CSubscribeEx = new CSubscribeEx("SERVICE");
$arServices = $CSubscribeEx->getList(array("UF_USER"=>$USER->GetID()), array("UF_SERVICE", "ID"));
foreach($arServices as $arService)
{
    $selectedServices[$arService["UF_SERVICE"]] = $arService["ID"];
}

if(!isset($selectedServices[$ServiceID]))
{
    $result = $CSubscribeEx->setUserSubscribe($ServiceID);
}else{
    if($status=="enable")
    {
        $active = "Y";
    }else{
        $active = "N";
    }
    
    $subscribeID = $selectedServices[$ServiceID];
    $result = $CSubscribeEx->updateUserSubscribe($subscribeID, array("UF_ACTIVE"=>$active));
}

if(!$result)
{
    $error = "Ошибка";
    if($status=="enable")
    {
        $status = "disable";
    }else{
        $status = "enable";
    }
}
 
exit(json_encode(array("status"=>$status, "error"=>$error)));
?>