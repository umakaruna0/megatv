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
    //get subsribe channel list
    $selectedChannels = array();
    $result = \Hawkart\Megatv\SubscribeTable::getList(array(
        'filter' => array(
            "=UF_ACTIVE"=>1, 
            "=UF_USER_ID" => $USER->GetID(), 
            ">UF_CHANNEL_ID" => 0
        ),
        'select' => array("UF_CHANNEL_ID")
    ));
    while ($arSub = $result->fetch())
    {
        $selectedChannels[] = $arSub["UF_CHANNEL_ID"];
    }

    $USER_ID = $USER->GetID();
    $rsUser = \CUser::GetByID($USER_ID);
    $arUser = $rsUser->Fetch();
    
    //get inform about schedule
    $result = \Hawkart\Megatv\ScheduleTable::getList(array(
        'filter' => array("=ID" => $prog_time),
        'select' => array(
            "ID", "UF_DATE_START", "UF_DATE_END", "UF_DATE", "UF_CHANNEL_BASE_ID" => "UF_CHANNEL.UF_BASE_ID", "UF_PROG_ID",
            "UF_CHANNEL_EPG_ID" => "UF_CHANNEL.UF_BASE.UF_EPG_ID", "UF_IMG_PATH" => "UF_PROG.UF_IMG.UF_PATH",
            "UF_PROG_EPG_ID" => "UF_PROG.UF_EPG_ID", "UF_EPG_ID", "UF_CHANNEL_ID"
        ),
        'limit' => 1
    ));
    if ($arSchedule = $result->fetch())
    {
        $arSchedule["UF_DATE_START"] = $arSchedule['UF_DATE_START']->toString();
        $arSchedule["UF_DATE_END"] = $arSchedule['UF_DATE_END']->toString();
    }
    
    //check if schedule in recording yet. Deleted to recordable
    $update = false;
    $result = \Hawkart\Megatv\RecordTable::getList(array(
        'filter' => array(
            "=UF_USER_ID" => $USER_ID, 
            "=UF_SCHEDULE_ID" => $prog_time, 
        ),
        'select' => array("ID", "UF_DELETED"),
        'limit' => 1
    ));
    if ($arRecord = $result->fetch())
    {
        if(intval($arRecord["UF_DELETED"])==1)
        {
            $update = true;
            $update_id = $arRecord["ID"];
        }else{
            exit(json_encode(array("status"=>"error", "error"=> "Такая запись уже есть.")));
        }
    }
    
    //money check
    $budget = \CUserEx::getBudget($user_id);
    if($budget<0)
    {
        exit(json_encode(array("status"=>"error", "error"=> "Для записи передачи пополните счет.")));
    }
    
    //Провеим, хватит ли пространства!
    $duration = strtotime($arSchedule["UF_DATE_END"])-strtotime($arSchedule["UF_DATE_START"]);
    $minutes = ceil($duration/60);
    $gb = $minutes*18.5/1024;
    $busy = floatval($arUser["UF_CAPACITY_BUSY"])+$gb;
    
    if($busy>=floatval($arUser["UF_CAPACITY"]))
    {
        exit(json_encode(array("status"=>"require-space", "error"=> "Не достаточно места на диске для записи")));
    }else{
        if(in_array($arSchedule["UF_CHANNEL_BASE_ID"], $selectedChannels))
        {
            $log_file = "/logs/sotal/sotal_".date("d_m_Y_H").".txt";
            \CDev::log(array(
                "ACTION"  => "PUT_TO_RECORD",
                "DATA"    => array(
                    "SCHEDULE_ID"    => $prog_time,
                    "DATE"       => date("d.m.Y H:i:s")
                )
            ), false, $log_file);

            if($update)
            {
                \Hawkart\Megatv\RecordTable::update($update_id, array("UF_DELETED" => 0));
            }else{
                \Hawkart\Megatv\RecordTable::create($arSchedule);
            }
            
                            
            //Inc rating for prog
            \Hawkart\Megatv\ProgTable::addByEpgRating($arSchedule["UF_PROG_EPG_ID"], 1);
            
            //change capacity for user 
            $cuser = new \CUser;
            $cuser->Update($arUser["ID"], array("UF_CAPACITY_BUSY"=>$busy));
            
            /**
             * Данные в статистику
             */                
            \Hawkart\Megatv\CStat::addByShedule($arSchedule["ID"], "record");

            $status = "success";

        }else{
            exit(json_encode(array("status"=>"error", "error"=> "Нельзя записать")));
        }
    }    
}

exit(json_encode(array("status"=>$status)));
?>