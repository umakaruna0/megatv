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
    $selectedChannels = array();
    $CSubscribeEx = new CSubscribeEx("CHANNEL");
    $arChannels = $CSubscribeEx->getList(array("UF_ACTIVE"=>"Y", "UF_USER"=>$USER->GetID()), array("UF_CHANNEL"));
    foreach($arChannels as $arChannel)
    {
        $selectedChannels[] = $arChannel["UF_CHANNEL"];
    }
    
    $USER_ID = $USER->GetID();
    $rsUser = CUser::GetByID($USER_ID);
    $arUser = $rsUser->Fetch();
    
    //Проверим, принадлежит ли запись этому каналу
    $arProgTime = CProgTime::getByID($prog_time, array("ID", "PROPERTY_CHANNEL", "PROPERTY_PROG", "PROPERTY_DATE_END", "PROPERTY_DATE_START"));
    
    //Провеим, хватит ли пространства!
    $duration = strtotime($arProgTime["PROPERTY_DATE_END_VALUE"])<strtotime($arProgTime["PROPERTY_DATE_START_VALUE"]);
    $minutes = ceil($duration/60);
    $gb = $minutes*(18.5/1024);
    
    if(intval($arUser["UF_CAPACITY_BUSY"])+$gb>intval($arUser["UF_CAPACITY"]))
    {
        exit(json_encode(array("status"=>false, "error"=> "Не достаточно места на диске для записи")));
    }
    
    if(in_array($arProgTime["PROPERTY_CHANNEL_VALUE"], $selectedChannels))
    {
        
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
            
            //Увеличиваем рейтинг программы
            CProg::addRating($arProgTime["PROPERTY_PROG_VALUE"], 1);
            
            $status = "success";
        } 
    }
}

exit(json_encode(array("status"=>$status)));
?>