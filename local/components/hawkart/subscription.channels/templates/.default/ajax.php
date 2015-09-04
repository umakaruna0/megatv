<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();

global $USER;
if(!is_object($USER))
    $USER = new CUser;

$result = array();
$channelID = intval($_REQUEST["channelID"]);
$status = htmlspecialcharsbx($_REQUEST["status"]);

$selectedChannels = array();
$CSubscribeEx = new CSubscribeEx("CHANNEL");
$arChannels = $CSubscribeEx->getList(array("UF_USER"=>$USER->GetID()), array("UF_CHANNEL", "ID"));
foreach($arChannels as $arChannel)
{
    $selectedChannels[$arChannel["UF_CHANNEL"]] = $arChannel["ID"];
}

if(!isset($selectedChannels[$channelID]))
{
    $result = $CSubscribeEx->setUserSubscribe($channelID);
    
}else{
    if($status=="enable")
    {
        $active = "Y";
    }else{
        $active = "N";
    }
    
    $subscribeID = $selectedChannels[$channelID];
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
 
exit(json_encode(array("status"=>$status, "error"=>$selectedChannels)));
?>