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
    
    public static function countRate($action, $recommendations = false)
    {
        switch($action)
        {
            case "record":
                $arRate = array(
                    "CATS" => \COption::GetOptionString("grain.customsettings", "STAT_RECORD_CATS_COEFF"),//4
                    "TAGS" => \COption::GetOptionString("grain.customsettings", "STAT_RECORD_TAGS_COEFF"),//0.4, 
                    "CHANNELS" => \COption::GetOptionString("grain.customsettings", "STAT_RECORD_CHANNELS_COEFF"),//1,
                    "SERIALS" => \COption::GetOptionString("grain.customsettings", "STAT_RECORD_SERIALS_COEFF"),//1,
                    "GANRES" => \COption::GetOptionString("grain.customsettings", "STAT_RECORD_GANRES_COEFF"),//1
                    "TOPICS" => \COption::GetOptionString("grain.customsettings", "STAT_RECORD_GANRES_COEFF")
                );
                
                if($recommendations)
                {
                    foreach($arRate as &$value)
                    {
                        $value = $value + \COption::GetOptionString("grain.customsettings", "STAT_RECORD_COEFF_ADDITION");
                    }
                }
                
            break;
            
            case "channelShow":
                $arRate = array(
                    "CHANNELS" => \COption::GetOptionString("grain.customsettings", "STAT_CHANNEL_SHOW_CHANNELS_COEFF"),//1
                );
            break;
            
            case "scheduleShow":
                $arRate = array(
                    "CATS" => \COption::GetOptionString("grain.customsettings", "STAT_SCHEDULE_SHOW_CATS_COEFF"),//1
                    "TAGS" => \COption::GetOptionString("grain.customsettings", "STAT_SCHEDULE_SHOW_TAGS_COEFF"),//0.1,
                    "GANRES" => \COption::GetOptionString("grain.customsettings", "STAT_SCHEDULE_SHOW_GANRES_COEFF"),//1
                    "TOPICS" => \COption::GetOptionString("grain.customsettings", "STAT_SCHEDULE_SHOW_GANRES_COEFF")
                );
                
                if($recommendations)
                {
                    foreach($arRate as &$value)
                    {
                        $value = $value + \COption::GetOptionString("grain.customsettings", "STAT_SCHEDULE_SHOW_COEFF_ADDITION");
                    }
                }
            break;
            
            case "quaterShow_1":
                $arRate = array(
                    "CATS" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_1_CATS_COEFF"),//1,
                    "TAGS" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_1_TAGS_COEFF"),//0.5,
                    "CHANNELS" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_1_CHANNELS_COEFF"),//0.5, 
                    "SERIALS" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_1_SERIALS_COEFF"),//1,
                    "GANRES" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_1_GANRES_COEFF"),//1
                    "TOPICS" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_1_GANRES_COEFF"),//1
                );
            break;
            
            case "quaterShow_2":
                $arRate = array(
                    "CATS" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_2_CATS_COEFF"),//2,
                    "TAGS" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_2_TAGS_COEFF"),//0.5,
                    "CHANNELS" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_2_CHANNELS_COEFF"),//1, 
                    "SERIALS" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_2_SERIALS_COEFF"),//1,
                    "GANRES" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_2_GANRES_COEFF"),//1
                    "" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_2_GANRES_COEFF"),//1
                );
            break;
            
            case "quaterShow_3":
                $arRate = array(
                    "CATS" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_3_CATS_COEFF"),//3,
                    "TAGS" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_3_TAGS_COEFF"),//0.5,
                    "CHANNELS" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_3_CHANNELS_COEFF"),//1, 
                    "SERIALS" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_3_SERIALS_COEFF"),//1.5,
                    "GANRES" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_3_GANRES_COEFF"),//1
                    "TOPICS" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_3_GANRES_COEFF"),//1
                );
            break;
            
            case "quaterShow_4":
                $arRate = array(
                    "CATS" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_4_CATS_COEFF"),//4,
                    "TAGS" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_4_TAGS_COEFF"),//0.5,
                    "CHANNELS" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_4_CHANNELS_COEFF"),//1, 
                    "SERIALS" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_4_SERIALS_COEFF"),//2,
                    "GANRES" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_4_GANRES_COEFF"),//1
                    "TOPICS" => \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_4_GANRES_COEFF"),//1
                );
            break;
        }

        if(strpos($action, "quaterShow")!==false && $recommendations)
        {
            foreach($arRate as &$value)
            {
                $value = $value + \COption::GetOptionString("grain.customsettings", "STAT_QUATERSHOW_COEFF_ADDITION");
            }
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
                "UF_CHANNEL_BASE_ID" => "UF_CHANNEL.UF_BASE_ID", "UF_CATEGORY" => "UF_PROG.UF_CATEGORY", 
                "UF_SERIAL" => "UF_PROG.UF_EPG_ID", "UF_GANRE" => "UF_PROG.UF_GANRE", "UF_TOPIC" => "UF_PROG.UF_TOPIC"
            );
            $result = RecordTable::getList(array(
                'filter' => array("=ID" => $prog_id),
                'select' => $arSelect,
                'limit' => 1
            ));
            $arSchedule = $result->fetch();
            
            $arCount = self::countRate($action);
            
            if(!empty($arSchedule["UF_CHANNEL_BASE_ID"]))
                $arStatistic["CHANNELS"][trim($arSchedule["UF_CHANNEL_BASE_ID"])] += floatval($arCount["CHANNELS"]);
            
            if(!empty($arSchedule["UF_CATEGORY"]))
                $arStatistic["CATS"][trim($arSchedule["UF_CATEGORY"])] += floatval($arCount["CATS"]);
            
            if(!empty($arSchedule["UF_SERIAL"])) 
                $arStatistic["SERIALS"][trim($arSchedule["UF_SERIAL"])] += floatval($arCount["SERIALS"]);
                
            if(!empty($arSchedule["UF_GANRE"]))
            {
                $arGanres = explode(",", $arSchedule["UF_GANRE"]);
                foreach($arGanres as $ganre)
                {
                    $arStatistic["GANRES"][trim($ganre)] += floatval($arCount["GANRES"])/count($arGanres);
                }
            } 
            
            if(!empty($arSchedule["UF_TOPIC"]))
            {
                $arTopics = explode(",", $arSchedule["UF_TOPIC"]);
                foreach($arTopics as $ganre)
                {
                    $arStatistic["TOPICS"][trim($ganre)] += floatval($arCount["TOPICS"])/count($arTopics);
                }
            }
                
            self::save($arStatistic);
        }
    }
    
    public static function addByShedule($prog_id, $action = false, $recommendations = false)
    {
        global $USER;
        if($USER->IsAuthorized())
        {
            $arStatistic = self::getByUser();
            
            $arSelect = array(
                "UF_CHANNEL_BASE_ID" => "UF_CHANNEL.UF_BASE_ID", "UF_CATEGORY" => "UF_PROG.UF_CATEGORY", 
                "UF_SERIAL" => "UF_PROG.UF_EPG_ID", "UF_GANRE" => "UF_PROG.UF_GANRE", "UF_TOPIC" => "UF_PROG.UF_TOPIC"
            );
            $result = ScheduleTable::getList(array(
                'filter' => array("=ID" => $prog_id),
                'select' => $arSelect,
                'limit' => 1
            ));
            $arSchedule = $result->fetch();
            
            $arCount = self::countRate($action, $recommendations);
            
            if(!empty($arSchedule["UF_CHANNEL_BASE_ID"]))
                $arStatistic["CHANNELS"][trim($arSchedule["UF_CHANNEL_BASE_ID"])] += floatval($arCount["CHANNELS"]);
            
            if(!empty($arSchedule["UF_CATEGORY"]))
                $arStatistic["CATS"][trim($arSchedule["UF_CATEGORY"])] += floatval($arCount["CATS"]);
            
            if(!empty($arSchedule["UF_SERIAL"])) 
                $arStatistic["SERIALS"][trim($arSchedule["UF_SERIAL"])] += floatval($arCount["SERIALS"]);
            
            if(!empty($arSchedule["UF_GANRE"]))
            {
                $arGanres = explode(",", $arSchedule["UF_GANRE"]);
                foreach($arGanres as $ganre)
                {
                    $arStatistic["GANRES"][trim($ganre)] += floatval($arCount["GANRES"])/count($arGanres);
                }
            }
            
            if(!empty($arSchedule["UF_TOPIC"]))
            {
                $arTopics = explode(",", $arSchedule["UF_TOPIC"]);
                foreach($arTopics as $ganre)
                {
                    $arStatistic["TOPICS"][trim($ganre)] += floatval($arCount["TOPICS"])/count($arTopics);
                }
            }
            
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
    
    /**
     * $arProgs = array("UF_ID", "ID", "UF_GANRE", "UF_TOPIC")
     */
    public static function getProgsByGanre($arProgs, $arStatistic)
    {
        $arProgsByRating = array();
        foreach($arProgs as $arProg)
        {
            $count = count($arProg["UF_GANRE"]);
            $total_rating = 0;
            foreach($arProg["UF_GANRE"] as $ganre)
            {
                $ganre_rating = $arStatistic["GANRES"][$ganre];
                $total_rating+= (1/$count) * $ganre_rating;
            }
            $arProgsByRating[$arProg["UF_EPG_ID"]] = $total_rating;
        }
        
        uasort($arProgsByRating, function($a, $b){
            return $b - $a;
        }); 
        $arProgsByRatingSorted = array_keys($arProgsByRating);
        unset($arProgsByRating);
        
        return $arProgsByRatingSorted;
    }
    
    /**
     * $arProgs = array("UF_ID", "ID", "UF_GANRE", "UF_TOPIC")
     */
    public static function getProgsByTopic($arProgs, $arStatistic)
    {
        $arProgsByRating = array();
        foreach($arProgs as $arProg)
        {
            $count = count($arProg["UF_TOPIC"]);
            $total_rating = 0;
            foreach($arProg["UF_TOPIC"] as $ganre)
            {
                $ganre_rating = $arStatistic["UF_TOPIC"][$ganre];
                $total_rating+= (1/$count) * $ganre_rating;
            }
            $arProgsByRating[$arProg["UF_EPG_ID"]] = $total_rating;
        }
        
        uasort($arProgsByRating, function($a, $b){
            return $b - $a;
        }); 
        $arProgsByRatingSorted = array_keys($arProgsByRating);
        unset($arProgsByRating);
        
        return $arProgsByRatingSorted;
    }
    
    public static function getTopRateSerialByUser($user_id, $arStatistic)
    {
        uasort($arStatistic["SERIALS"], function($a, $b){
            return $b - $a;
        }); 
        $arStatistic["SERIALS"] = array_keys($arStatistic["SERIALS"]);
        
        $ids = array();
        $dateStart = date("Y-m-d H:i:s", time() - 86400 * 7); 
        $result = RecordTable::getList(array(
            'filter' => array(
                "=UF_USER_ID" => $user_id, 
                "!UF_URL" => false,
                ">UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'),
            ),
            'select' => array(
                "UF_ID" => "UF_PROG.UF_EPG_ID"
            ),
            'order' => array("UF_DATE_END" => "DESC")
        ));
        while ($arRecord = $result->fetch())
        {  
            $ids[] = $arRecord["UF_ID"];
        }
        
        $sortedIds = array();
        foreach($arStatistic["SERIALS"] as $id)
        {
            if(in_array($id, $ids))
            {
                $key = array_search ($id, $ids);
                $sortedIds[] = $id;
                unset($ids[$key]);
            }
        }
        
        if(count($ids)>0)
            $sortedIds = array_merge($sortedIds, $ids);
        
        unset($ids);
        $sortedIds = array_unique($sortedIds);
        
        return $sortedIds;
    }
    
    public static function getTopRateProg($arProgsByRating)
    {
        $ids = array();
        foreach($arProgsByRating as $arProg)
        {
            $ids[] = $arProg["UF_EPG_ID"];
        }
        
        /*
        $arIds = array();
        $selectedChannels = array();
        $result = \Hawkart\Megatv\SubscribeTable::getList(array(
            'filter' => array("=UF_ACTIVE" => 1, "=UF_USER_ID" => $user_id, ">UF_CHANNEL_ID" => 0),
            'select' => array("UF_CHANNEL_ID")
        ));
        while ($arSub = $result->fetch())
        {
            $selectedChannels[] = $arSub["UF_CHANNEL_ID"];
        }    
        
        $dateStart = date("Y-m-d H:i:s");
        $result = \Hawkart\Megatv\ScheduleTable::getList(array(
            'filter' => array(
                "=UF_CHANNEL_ID" => $selectedChannels,
                "=UF_PROG.UF_ACTIVE" => 1,
                ">UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'),
            ),
            'select' => array(
                "ID", "UF_ID" => "UF_PROG.UF_EPG_ID"
            ),
            'order' => array("UF_PROG.UF_RATING" => "DESC"),
        ));
        while ($arSchedule = $result->fetch())
        {
            $arIds[] = $arSchedule["UF_ID"];
        }*/
        
        return $ids;
    }
    
    public static function saveRecommend($user_id, $json)
    {
        $file = realpath(dirname(__FILE__) . '/../../../')."/data/statistic/".$user_id.".json";
        file_put_contents($file, $json);
    }
    
    public static function getRecommend($user_id)
    {
        $file = realpath(dirname(__FILE__) . '/../../../')."/data/statistic/".$user_id.".json";
        $data = file_get_contents($file, FILE_USE_INCLUDE_PATH);
        return json_decode($data, true);
    }
    
    public static function saveRecommendSchedules($user_id, $json, $DOCUMENT_ROOT = false)
    {
        //$file = realpath(dirname(__FILE__) . '/../../../')."/data/statistic/schedule_".$user_id.".json";
        $file = $DOCUMENT_ROOT."/local/data/statistic/schedule_".$user_id.".json";
        file_put_contents($file, $json);
    }
    
    public static function getRecommendSchedules($user_id)
    {
        $file = realpath(dirname(__FILE__) . '/../../../')."/data/statistic/schedule_".$user_id.".json";
        $data = file_get_contents($file, FILE_USE_INCLUDE_PATH);
        return json_decode($data, true);
    }
}