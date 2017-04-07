<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class ScheduleCell
{
    /**
     * Generate cell for all cities to closest 3 day
     */
    public static function generateForWeek($DOCUMENT_ROOT = false)
    {
        if(!$DOCUMENT_ROOT)
            $DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
        
        //\CDev::deleteDirectory($DOCUMENT_ROOT.'/bitrix/cache', 0);
        BXClearCache(true);
        \CDev::deleteOldFiles($DOCUMENT_ROOT."/upload/cell/", 86400*2);

        $arCities = CityTable::getLangCityList(15); //RU
        $fisrt_date = date('d.m.Y', strtotime(\CTimeEx::getCurDate()));
        /*$arCities = array(
            array("id"=>80)
        );*/
        foreach($arCities as $arCity)
        {
            for($day=0; $day<3; $day++)
            {
                $curDate = date('d.m.Y', strtotime("+".$day." day", strtotime($fisrt_date)));
                self::generate($curDate, $arCity["id"]);
            }
        }
    }
    
    /**
     * Cell by 5 minutes interval for all cities
     */
    public static function generate($curDate, $city_id = false)
    {
        global $USER, $APPLICATION;
        
        if(!$curDate)
        {
            $curDate = date("Y-m-d H:i:s");
        }
        
        if(!$city_id)
        {
            $arGeo = CityTable::getGeoCity();
            $city_id = $arGeo["ID"];
        }else{
            \Hawkart\Megatv\CityTable::setGeoCity($city_id);
            $_SESSION["USER_GEO"]["ID"] = $city_id;
        }
        
        //print_r($_SESSION["USER_GEO"]); die();
        
        /**
         * Get channels by current city
         */
        $arChannels = array();
        $arChannelIds = array();
        $arResult["CHANNELS"] = array();
        
        $arResult["ITEMS"] = ChannelTable::getActiveByCity();
        foreach($arResult["ITEMS"] as $arChannel)
        {
            $arChannels[$arChannel["UF_CHANNEL_BASE_ID"]] = $arChannel;
        }
        $arResult["ITEMS"] = $arChannels;
        
        foreach($arResult["ITEMS"] as $key=>$arItem)
        {               
            $arItem["ICON"] = $arItem["UF_ICON"];
            $arChannelIds[] = $arItem["ID"];
            $arResult["CHANNELS"][$arItem["ID"]] = $arItem;
        }
        unset($arResult["ITEMS"]);
        
        $arResult["SCHEDULE_LIST"] = array();
        
        $arDate = \CTimeEx::getDateFilter($curDate);
        $dateStart = date("Y-m-d H:i:s", strtotime($arDate["DATE_FROM"]));
        $dateEnd = date("Y-m-d H:i:s", strtotime($arDate["DATE_TO"]));
        
        $arFilter = array(
            "=UF_CHANNEL_ID" => \Hawkart\Megatv\ChannelTable::getActiveIdByCity(),
            "=UF_ACTIVE" => 1,
            array(
                "LOGIC" => "OR",
                array(
                    ">=UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'),
                    "<UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateEnd, 'Y-m-d H:i:s'),
                ),
                array(
                    "<UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'),
                    ">UF_DATE_END" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'),
                )
            )
        );
        
        $arSelect = array(
            "ID", "UF_DATE_START", "UF_DATE_END", "UF_DATE", "UF_CHANNEL_ID", "UF_PROG_ID",
            "UF_TITLE" => "UF_PROG.UF_TITLE", "UF_SUB_TITLE" => "UF_PROG.UF_SUB_TITLE", "UF_IMG_PATH" => "UF_PROG.UF_IMG.UF_PATH",
            "UF_RATING" => "UF_PROG.UF_RATING", "UF_PROG_CODE" => "UF_PROG.UF_CODE",
            'UF_BASE_FORBID_REC' => 'UF_CHANNEL.UF_BASE.UF_FORBID_REC'
        );
        $obCache = new \CPHPCache;
        if( $obCache->InitCache(36000, serialize($arFilter).serialize($arSelect), "/cell-generate/"))
        {
        	$arResult["SCHEDULE_LIST"] = $obCache->GetVars();
        }
        elseif($obCache->StartDataCache())
        {
        	$result = ScheduleTable::getList(array(
                'filter' => $arFilter,
                'select' => $arSelect,
                'order' => array("UF_DATE_START" => "ASC")
            ));
            while ($arSchedule = $result->fetch())
            {
                $channel_id = $arSchedule["UF_CHANNEL_ID"];
                $arSchedule["UF_DATE_START"] = $arSchedule["DATE_START"] = \CTimeEx::dateOffset($arSchedule['UF_DATE_START']->toString());
                $arSchedule["UF_DATE_END"] = $arSchedule["DATE_END"] = \CTimeEx::dateOffset($arSchedule['UF_DATE_END']->toString());
                $arSchedule["UF_DATE"] = $arSchedule["DATE"] = substr($arSchedule["DATE_START"], 0, 10);
                
                $arSchedule["PROG_ID"] = $arSchedule["UF_PROG_ID"];
                $arSchedule["DETAIL_PAGE_URL"] = $arResult["CHANNELS"][$channel_id]["DETAIL_PAGE_URL"].$arSchedule["UF_PROG_CODE"]."/?event=".$arSchedule["ID"];
                $arResult["SCHEDULE_LIST"][$channel_id][] = $arSchedule;
                
            }
        	$obCache->EndDataCache($arResult["SCHEDULE_LIST"]); 
        }
        //print_r($arResult["SCHEDULE_LIST"]);
        $datetime = $dateStart;
        
        $addMinutes = 0;
        $addMinuteCount = 0;
        while($addMinuteCount<288)
        {
            $currentDateTime = date('d.m.Y H:i:s', strtotime("+".$addMinutes." minutes", strtotime($datetime)));
            //$currentDateTime = "04.04.2017 13:50:00";
            $currentDateTime = \CTimeEx::dateOffset($currentDateTime);
            
            $arScheduleList = self::generateForTime($currentDateTime, $arResult["SCHEDULE_LIST"], $arResult["CHANNELS"]);
            
            foreach($arResult["CHANNELS"] as $arChannel)
            {
                $channel_id = $arChannel["ID"];
                $arChannelSchedules = $arScheduleList[$channel_id];
                self::save($channel_id, $arChannelSchedules, $currentDateTime, $city_id);
            }
            
            $addMinutes+=5;
            $addMinuteCount++;
            //die();
        }
    }
    
    /**
     * 
     * @param string $datetime дата, для которой генерируется сетка
     * @param array $arChannelProgs список программ разбитый по каналам
     * @param array $arChannels каналы
     * 
     * @return array $arProgs программы с ключами
     */
    public static function generateForTime($datetime, $arChannelProgs, $arChannels)
    {
        $maxPointer = 0;    //Берем самый большой номер из столбцов в эфире
        $arTimePointers = array();
        foreach($arChannels as $arChannel)
        {
            $channel_id = $arChannel["ID"];
            $key = 1;

            foreach($arChannelProgs[$channel_id] as &$arSchedule)
            {
                $start = $arSchedule["DATE_START"];
                $end = $arSchedule["DATE_END"];
                
                if(strtotime($start)<=strtotime($datetime) && strtotime($datetime)<strtotime($end))
                {
                    $arTimePointers[$arChannel["ID"]][0] = $key;    //Обходим все каналы и получаем столбцы передачи в эфире.
                    $arSchedule["TIME_POINTER"] = true;
                    
                    if($maxPointer<$key)
                       $maxPointer = $key;
                }
                
                $key++;
            }
            
            if(empty($arTimePointers[$arChannel["ID"]][0]))
                $arTimePointers[$arChannel["ID"]][0] = 1;
            
            $arTimePointers[$arChannel["ID"]][1] = count($arChannelProgs[$channel_id]);
        }
        
        unset($arSchedule);
        
        //print_r($arTimePointers);
        //echo $maxPointer."\r\n"; die();
        
        $arData = self::setKeysToColsNew($maxPointer, $arTimePointers, $datetime);
        //\CDev::pre($arData);
        
        $arKeys = $arData["keys"];
        $arProgs = array();
        foreach($arChannels as $arChannel)
        {
            $channel_id = $arChannel["ID"];
            $keys = $arKeys[$channel_id];
            
            $key = 1;
            foreach($arChannelProgs[$channel_id] as $arSchedule)
            {
                $arSchedule["GENERATE"] = $datetime;
                $arSchedule["CLASS"] = $keys[$key];                
                $arProgs[$channel_id][] = $arSchedule;

                $key++;
            }
        }
        
        unset($arChannelProgs);
        
        return $arProgs;
    }
    
    
    /**
     * 
     * @param string $pointerColNumber номер столбца по вертикали
     */
    public static function setKeysToColsNew($maxPointer, $arTimePointers, $datetime)
    {
        //Находим слева и справа самую большую разницу.
        $maxLeft = 0;   //макс кол-во столбцов слева
        $maxRight = 0;  //макс кол-во столбцов справа
        foreach($arTimePointers as $channel_id => $array)
        {
            $progPointer = $array[0];    //Порядковый номер программы в столбце                
            $progsNumber = $array[1];    //Общее кол-во программ
            
            $diffLeft = 0;
            $diffRight = 0;
            if($maxPointer>0)
                $diffLeft = $maxPointer - 1;    //кол-во столбцов слева
                
            $diffRight = $progsNumber-$progPointer;//$maxPointer;  //кол-во оставшихся столбцов справа
            
            if($maxLeft<$diffLeft)
                $maxLeft = $diffLeft;
                
            if($maxRight<$diffRight)
                $maxRight = $diffRight;
        }
        
        if($maxRight>0)
            $maxRight = ceil($maxRight/2);
        
        if($maxLeft>0)
            $maxLeft = ceil($maxLeft/2);
        
        //Подсчитываем кол-во столбцов = макс слева + макс. справа + 1.
        $totalColsNumber = $maxLeft+$maxRight+1;
        
        $arKeys = array();
        foreach($arTimePointers as $channel_id => $array)
        {
            $progPointer = $array[0];    //Порядковый номер программы в столбце                
            $progsNumber = $array[1];    //Общее кол-во программ
            $arKeys[$channel_id][$progPointer] = "one"; //на месте столбца $maxPointer
            
            $leftCols = 0;
            if($progPointer>1)
            {
                $leftCols = $progPointer-1;
                $leftArray = range(1, $leftCols);
                if(count($leftArray)>0)
                {
                    $keysResult = self::putArrayIntoQuantityNew($leftArray, $maxLeft);
                    $arKeys[$channel_id]+= $keysResult;
                }
            }else{
                if($maxLeft>0)
                {
                    if($maxLeft==1)
                    {
                        $arKeys[$channel_id][$progPointer] = "double";
                    }else{
                        $arKeys[$channel_id][$progPointer] = $maxLeft+1;
                    }
                }
            }
            
            if($progsNumber>$progPointer)
            {
                if($progPointer+1==$progsNumber)
                {
                    $rightArray = array($progPointer+1);
                }else{
                    $rightArray = range($progPointer+1, $progsNumber);
                }
                
                if(count($rightArray)>0)
                {
                    $keysResult2 = self::putArrayIntoQuantityNew($rightArray, $maxRight);
                    $arKeys[$channel_id]+= $keysResult2;
                }
            }else{
                if($maxRight>0)
                {
                    if($maxRight==1)
                    {
                        $arKeys[$channel_id][$progPointer] = "double";
                    }else{
                        $arKeys[$channel_id][$progPointer] = $maxRight+1;
                    }
                }
            }

            //check generated cell for channel
            $count=self::countByClasses($arKeys[$channel_id]);
            
            //if(count($arKeys[$channel_id])!=$progsNumber)
            echo $channel_id."\r\n";
            //print_r($arKeys[$channel_id]);
            if($count!=$totalColsNumber)
            {
                echo $channel_id."\r\n";
                echo "maxpointer=".$maxPointer."\r\n";
                print_r($arKeys[$channel_id]);
                echo $count."  ".$totalColsNumber."\r\n";
                echo "number=".$progsNumber."\r\n";
                echo "pointer=".$progPointer."\r\n";
                echo "maxleft=".$maxLeft."\r\n";
                echo "maxright=".$maxRight."\r\n";
                print_r($rightArray);
                print_r($keysResult2);
                /*echo "left=\r\n";
                \CDev::pre($leftArray);
                \CDev::pre($keysResult);
                echo "right=\r\n";
                \CDev::pre($rightArray);
                \CDev::pre($keysResult2);
                
                
                \CDev::pre($arKeys[$channel_id]);*/
                
                //die();
            }
        }
        return array(
            "keys" => $arKeys,
            "clones" => array()
        );
    }
    
    public static function putArrayIntoQuantityNew($keys, $numCols)
    {
        $keysResult = array();
        $count = count($keys);
        
        //Если элементов > столбцов
        if($count >= $numCols)
        {
            $ostatok = $numCols*2 - $count;
            $arParts["ONE"] = $ostatok;
            if($count-$ostatok > 0)
                $arParts["HALF"] = ($count-$ostatok)/2;
        }else{
            if($numCols > $count*2)
            {
                /*$c = ceil($numCols/$count);
                $arParts[$c] = $count-1;
                
                $diff = $numCols - ($count-1) * $c;
                
                if($diff==1)
                {
                    $arParts["ONE"] = 1;
                }elseif ($diff==2){
                    $arParts["DOUBLE"] = 1;
                }elseif($diff>0){
                    $arParts[$diff] = 1;
                }*/
                $arParts = self::splitNumber($count, $numCols);
            }else{
                $double = $numCols - $count;
                $arParts["DOUBLE"] = $double;
                if($count-$double > 0)
                    $arParts["ONE"] = $count-$double;
            }
        }
        
        echo "count=".$count."  cols=".$numCols."\r\n";
        print_r($arParts);
        
        if(count($arParts["HALF"])>0)
        {
            while($arParts["HALF"]>0)
            {
                $key = array_shift($keys);
                $key_2 = array_shift($keys);
                $keysResult[$key] = "half";
                $keysResult[$key_2] = "half";
                $keys = array_diff($keys, array($key, $key_2));
                $arParts["HALF"]--;
            }
            unset($arParts["HALF"]);
        }
            
        if(count($keys)>0)
        {
            foreach($arParts as $class=>$countParts)
            {
                if($class=="ONE" || $class=="DOUBLE")
                {
                    $class = strtolower($class);
                }
                while($countParts>0)
                {
                    $key = array_shift($keys);
                    $keysResult[$key] = $class;
                    $keys = array_diff($keys, array($key));
                    $countParts--;
                }
            }
        }
        
        unset($arParts);
        return $keysResult;
    }
    
    public static function splitNumber($cols, $total)
    {
        $array = range(1, $total);
        $min   = floor($total / $cols);  //  минимальное количество элементов в столбце
        $extra = $total - $min * $cols;  //  "лишние" элементы
        
        $prevCol = 0;          //  количество элементов, которое уже распределено по колонкам
        $colNums = array();
        for($q = 0; $q < $cols; $q++)
        {
            //  если еще есть лишние элементы, то добавляем один из них к текущей колонке
            $colNum = $extra-- > 0 ? $min + 1 : $min;
            $colNums[$colNum]++;
        }
        
        if(intval($colNums[1])>0)
        {
            $colNums["ONE"] = intval($colNums[1]);
            unset($colNums[1]);
        }
        
        if(intval($colNums[2])>0)
        {
            $colNums["DOUBLE"] = intval($colNums[2]);
            unset($colNums[2]);
        }
        
        return $colNums;
    }

    public static function countByClasses($keysResult)
    {
        $count = 0;
        foreach($keysResult as $class)
        {
            $class = strtolower($class);
            if($class=="one"){
                $count+=1;
            }else if($class=="double"){
                $count+=2;
            }else if($class=="half"){
                $count+=0.5;
            }else{
                $count+=intval($class);
            }
        }
        
        return $count;
    }
    
    public static function makeFiveMinutes($currentDateTime)
    {
        $currentDateTime[17] = 0;
        $currentDateTime[18] = 0;
        
        if($currentDateTime[15]!=0 || $currentDateTime[15]!=5)
        {
            if($currentDateTime[15]>=5)
            {
                $currentDateTime[15] = 5;
            }else{
                $currentDateTime[15] = 0;
            }
        }
        
        return $currentDateTime;
    }
    
    public static function generateFileName($channel_id, $currentDateTime, $city_id = false)
    {
        if(!$city_id)
        {
            $arGeo = CityTable::getGeoCity();
            $city_id = $arGeo["ID"];
        }
        
        $currentDateTime = self::makeFiveMinutes($currentDateTime);     
        $dir = $_SERVER["DOCUMENT_ROOT"]."/upload/cell/".$city_id."/".$channel_id;
        
        if (!file_exists($dir))
            mkdir($dir, 0777, true);
        
        $path = $dir."/".strtotime($currentDateTime).".json";
        chmod($path, 0777);
        return $path;
    }
    
    public static function save($channel_id, $arChannelSchedules, $currentDateTime, $city_id = false)
    {
        if(!$city_id)
        {
            $arGeo = CityTable::getGeoCity();
            $city_id = $arGeo["ID"];
        }
        $currentDateTime = self::makeFiveMinutes($currentDateTime);
        $path = self::generateFileName($channel_id, $currentDateTime, $city_id);
        chmod($path, 0777);
        file_put_contents($path, json_encode($arChannelSchedules));
    }
    
    public static function getByChannelAndTime($channel_id, $currentDateTime, $city_id = false)
    {        
        if(!$city_id)
        {
            $arGeo = CityTable::getGeoCity();
            $city_id = $arGeo["ID"];
        }
        $path = self::generateFileName($channel_id, $currentDateTime, $city_id);

        $str = file_get_contents($path);
        return json_decode($str, true);
    }
    
    public static function clearOld($root_path = false)
    {
        if(!$root_path)
            $root_path = $_SERVER["DOCUMENT_ROOT"];
        
        $arCities = CityTable::getLangCityList(15);
        $fisrt_date = date('d.m.Y', strtotime(\CTimeEx::getCurDate()));
        /*$arCities = array(
            array("id"=>80)
        );*/
        foreach($arCities as $arCity)
        {
            for($ch=1; $ch<200; $ch++)
            {
                if (file_exists($root_path."/upload/cell/".$arCity["id"]."/".$ch))
                {
                    \CDev::deleteOldFiles($root_path."/upload/cell/".$arCity["id"]."/".$ch."/", 86400*2);
                }
            }   
        }
    }
}