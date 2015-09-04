<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();

global $USER;
if(!is_object($USER))
    $USER = new CUser;
    
$status = false;

$prog_time = intval($_REQUEST["broadcastID"]);
if($USER->IsAuthorized() && $prog_time>0)
{
    //Получим список всех каналов, на которые подписан пользователь
    /*$selectedChannels = array();
    $CSubscribeEx = new CSubscribeEx("CHANNEL");
    $arChannels = $CSubscribeEx->getList(array("UF_ACTIVE"=>"Y", "UF_USER"=>$USER->GetID()), array("UF_CHANNEL"));
    foreach($arChannels as $arChannel)
    {
        $selectedChannels[] = $arChannel["UF_CHANNEL"];
    }
    
    //Проверим, принадлежит ли запись этому каналу
    $arProgTime = CProgTime::getByID($prog_time, array("ID", "PROPERTY_CHANNEL"));
    if(in_array($arProgTime["PROPERTY_CHANNEL_VALUE"], $selectedChannels))
    {
        $USER_ID = $USER->GetID();
        $Sotal = new CSotal($USER_ID);
        $Sotal->register();     //регистрируем пользователя, если нужно
        $Sotal->getSubscriberToken();   //получим ключ для использования в запросах
        $record_id = $Sotal->putRecord($prog_time); //ставим на запись программу
        
        if($record_id>0)
        {
            //сохраняем инфу о записе
            CRecordEx::create(array(
                "UF_SOTAL_ID" => $record_id,
                "UF_SCHEDULE" => $prog_time
            ));
            
            $status = "success";
        } 
    }*/  
}

exit(json_encode(array("status"=>$status)));
?>