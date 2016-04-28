<?php

namespace Hawkart\Megatv;

class CStat
{
    public static function getByUser($user_id = false)
    {
        global $USER;
        
        if($USER->IsAuthorized() && !$user_id)
            $user_id = $USER->GetID();
        
        $rsUser = \CUser::GetByID($user_id);
        $arUser = $rsUser->Fetch();
        
        return json_decode($arUser["UF_STATISTIC"], true);
    }
    
    public static function countRate($action)
    {
        switch($action)
        {
            case "record":
                $arRate = array(
                    "CATS" => 4,
                    "TAGS" => 0.4, 
                    "CHANNELS" => 1,
                    "SERIALS" => 1
                );
            break;
            
            case "channelShow":
                $arRate = array(
                    "CHANNELS" => 1
                );
            break;
            
            case "scheduleShow":
                $arRate = array(
                    "CATS" => 1,
                    "TAGS" => 0.1,
                );
            break;
            
            case "quaterShow_1":
                $arRate = array(
                    "CATS" => 1,
                    "TAGS" => 0.5,
                    "CHANNELS" => 0.5, 
                    "SERIALS" => 1
                );
            break;
            
            case "quaterShow_2":
                $arRate = array(
                    "CATS" => 2,
                    "TAGS" => 0.5,
                    "CHANNELS" => 1, 
                    "SERIALS" => 1
                );
            break;
            
            case "quaterShow_3":
                $arRate = array(
                    "CATS" => 3,
                    "TAGS" => 0.5,
                    "CHANNELS" => 1, 
                    "SERIALS" => 1.5
                );
            break;
            
            case "quaterShow_4":
                $arRate = array(
                    "CATS" => 4,
                    "TAGS" => 0.5,
                    "CHANNELS" => 1, 
                    "SERIALS" => 2
                );
            break;
        }
        
        return $arRate;
    }
    
    public static function addByRecord($prog_id, $action = false)
    {
        global $USER;
        if($USER->IsAuthorized())
        {
            $arStatistic = self::getByUser();
            
            $arSelect = array(
                "UF_CHANNEL_ID", "UF_CATEGORY" => "UF_PROG.UF_CATEGORY", 
                "UF_SERIAL" => "UF_PROG.UF_EPG_ID"
            );
            $result = \Hawkart\Megatv\RecordTable::getList(array(
                'filter' => array("=ID" => $prog_id),
                'select' => $arSelect,
                'limit' => 1
            ));
            $arSchedule = $result->fetch();
            
            $arCount = self::countRate($action);
            
            if(!empty($arSchedule["UF_CHANNEL_ID"]))
                $arStatistic["CHANNELS"][$arSchedule["UF_CHANNEL_ID"]] += floatval($arCount["CHANNELS"]);
            
            if(!empty($arSchedule["UF_CATEGORY"]))
                $arStatistic["CATS"][$arSchedule["UF_CATEGORY"]] += floatval($arCount["CATS"]);
            
            if(!empty($arSchedule["UF_SERIAL"])) 
                $arStatistic["SERIALS"][$arSchedule["UF_SERIAL"]] += floatval($arCount["SERIALS"]);
            
            self::save($arStatistic);
        }
    }
    
    public static function addByShedule($prog_id, $action = false)
    {
        global $USER;
        if($USER->IsAuthorized())
        {
            $arStatistic = self::getByUser();
            
            $arSelect = array(
                "UF_CHANNEL_ID", "UF_CATEGORY" => "UF_PROG.UF_CATEGORY", 
                "UF_SERIAL" => "UF_PROG.UF_EPG_ID"
            );
            $result = \Hawkart\Megatv\ScheduleTable::getList(array(
                'filter' => array("=ID" => $prog_id),
                'select' => $arSelect,
                'limit' => 1
            ));
            $arSchedule = $result->fetch();
            
            $arCount = self::countRate($action);
            
            if(!empty($arSchedule["UF_CHANNEL_ID"]))
                $arStatistic["CHANNELS"][$arSchedule["UF_CHANNEL_ID"]] += floatval($arCount["CHANNELS"]);
            
            if(!empty($arSchedule["UF_CATEGORY"]))
                $arStatistic["CATS"][$arSchedule["UF_CATEGORY"]] += floatval($arCount["CATS"]);
            
            if(!empty($arSchedule["UF_SERIAL"])) 
                $arStatistic["SERIALS"][$arSchedule["UF_SERIAL"]] += floatval($arCount["SERIALS"]);
            
            self::save($arStatistic);
        }
    }
    
    public static function channelAdd($channel_id, $action = false)
    {
        if(!$action)
            $action = "channelShow";
        
        global $USER;
        if($USER->IsAuthorized())
        {
            $arStatistic = self::getByUser();
            $arCount = self::countRate($action);
            $arStatistic["CHANNELS"][$channel_id] += intval($arCount["CHANNELS"]);
            self::save($arStatistic);
        }
    }
    
    public static function save($arStatistic, $user_id = false)
    {
        global $USER;
        
        if($USER->IsAuthorized() && !$user_id)
            $user_id = $USER->GetID();
        
        $cuser = new \CUser;
        $cuser->Update($user_id, array(
            "UF_STATISTIC" => json_encode($arStatistic)
        ));
    }
}