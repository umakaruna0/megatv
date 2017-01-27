<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class ScheduleCell
{
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
        }
        
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
            "=UF_CHANNEL_ID" => $arChannelIds,
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
            //$currentDateTime = "15.11.2016 23:40:00";
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
        
        //\CDev::pre($arProgs);
        
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
                $arKeys[$channel_id][$progPointer] = $maxRight;
            }
        }
        
        if(count($arKeys[$channel_id])!=$progsNumber)
        {
            echo $channel_id."<br />";
            echo "number=".$progsNumber."<br />";
            echo "pointer=".$progPointer."<br />";
            echo "maxleft=".$maxLeft."<br />";
            echo "maxright=".$maxRight."<br />";
            echo "left=<br />";
            \CDev::pre($leftArray);
            \CDev::pre($keysResult);
            echo "right=<br />";
            \CDev::pre($rightArray);
            \CDev::pre($keysResult2);
            
            
            \CDev::pre($arKeys[$channel_id]);
            
            //die();
        }
        
        return array(
            "keys" => $arKeys,
            "clones" => array()
        );
    }
    
    public static function putArrayIntoQuantityNew($keys, $numCols)
    {
        $count = count($keys);
        
        if($numCols > $count*2)
        {
            $arParts["DOUBLE"] = $count-1;
            $q = $numCols - ($count-1)*2;
            $arParts[$q] = 1;
        }else{
            $double = $numCols - $count;
            $arParts["DOUBLE"] = $double;
            $arParts["ONE"] = $count-$double;
        }
        
        if(count($arParts["DOUBLE"])>0)
        {
            while($arParts["DOUBLE"]>0)
            {
                $key = array_shift($keys);
                $keysResult[$key] = "double";
                $keys = array_diff($keys, array($key));
                $arParts["DOUBLE"]--;
            }
        }
        
        if(count($arParts["ONE"])>0)
        {
            while($arParts["ONE"]>0)
            {
                $key = array_shift($keys);
                $keysResult[$key] = "one"; 
                $keys = array_diff($keys, array($key));
                $arParts["ONE"]--;
            }
        }
        
        if(count($keys)>0)
        {
            foreach($arParts as $class=>$c)
            {
                if($class!="ONE" && $class!="DOUBLE")
                {
                    $key = array_shift($keys);
                    $keysResult[$key] = $class;
                }
            }
        }
        
        unset($arParts);
        return $keysResult;
    }

    /**
     * 
     * @param string $pointerColNumber номер столбца по вертикали
     */
    public static function setKeysToCols($pointerColNumber, $arTimePointers, $datetime)
    {
        //Чтобы больше варяций по возможным столбцам было
        $pointers = array($pointerColNumber, $pointerColNumber-1, $pointerColNumber+1);
        if($pointerColNumber==1)
        {
            $pointers = array($pointerColNumber, $pointerColNumber+1, $pointerColNumber+2);
        }
        if($pointerColNumber==0)
        {
            $pointers = array($pointerColNumber+2, $pointerColNumber+1);
        }
        
        if($pointerColNumber>2)
        {
            $pointers[] = $pointerColNumber-2;
        }
        if($pointerColNumber>3)
        {
            $pointers[] = $pointerColNumber-3;
        }
        if($pointerColNumber>4)
        {
            $pointers[] = $pointerColNumber-4;
        }
        
        $pointers = range(1, BROADCAT_COLS);
 
        //Разброс от возможного столбца делаем в 1 столбец влево/вправо
        foreach($pointers as $pointerColNumber)
        {
            $success = true;
            $arKeys = array();
            $arClones = array();
            
            foreach($arTimePointers as $channel_id => $array)
            {
                $leftArray = array();
                $rightArray = array();
                $keysResult = array();
                $keysResult2 = array();
                $progPointerPrev = false;
                $progPointerNext = false;
                $arKeys[$channel_id] = array();
                $arClones[$channel_id] = array();
                
                $progPointer = $array[0];    //Порядковый номер программы в столбце                
                $progsNumber = $array[1];    //Общее кол-во программ
                
                //Если первый столбец, то массив пустой
                if($progPointer==1)
                {
                    $leftArray = array();
                }else{
                    $leftArray = range(1, $progPointer-1);
                }
                
                /*if($progsNumber<$pointerColNumber)
                {
                    $success = false;
                    $arKeys = array();
                    break;
                }*/
                
                //Если последний столбец, то массив пустой
                if($pointerColNumber==BROADCAT_COLS)
                {
                    if($progPointer+1==$progsNumber)
                    {
                        $rightArray = array($progPointer+1);
                    }else{
                        $rightArray = array();
                    }
                }else{
                    
                    if($progPointer+1>$progsNumber)
                    {
                        $rightArray = array();
                    }else{
                        $rightArray = range($progPointer+1, $progsNumber);
                    }
                }
                
                $leftCols = $pointerColNumber-1;    //кол-во столбцов слева
                $rightCols = BROADCAT_COLS-$pointerColNumber;   //кол-во столбцов справа
                
                $progPointerClass = "one";
                
                if((count($leftArray)+1)/($leftCols+1)==2)//Проверяем, вмещается и без половинок
                {
                    $progPointerClass = "half";
                    $progPointerPrev = "half";
                    $leftArray = range(1, $progPointer-2);
                }
                //если программа*2 меньше, чем столбцов, то ставим двойную картинку
                else if(count($leftArray)*2<$leftCols)
                {
                    $leftCols = $pointerColNumber-2;             
                    $progPointerClass = "double";
                } 
                else if(count($leftArray)/2>$leftCols) //Если количество передач/2 больше, чем столбцов
                {
                    $success = false;
                    $arKeys = array();
                    break;
                }
                
                //Если количество передач/2 больше, чем столбцов
                if(count($rightArray)/2>$rightCols)
                {
                    $success = false;
                    $arKeys = array();
                    break;
                }
                else if($rightCols==0 && (count($rightArray)+1)/($rightCols+1)==2  && $progPointerClass!="half")
                {
                    $progPointerClass = "half";
                    $progPointerNext = "half";
                    
                    if($progPointer+2>$progsNumber)
                    {
                        $rightArray = array();
                    }else{
                        $rightArray = array($progPointer+2, $progsNumber);
                    }
                }
                else if(count($rightArray)*2<$rightCols && $progPointerClass!="half")
                {
                    $rightCols = BROADCAT_COLS-$pointerColNumber-1;
                    $progPointerClass = "double";
                }
                
                
                if(count($leftArray)>0)
                {
                    $keysResult = self::putArrayIntoQuantity($leftArray, $leftCols);
                    $arKeys[$channel_id] = $keysResult;
                }
                
                $arClonesLeft = self::checkAddClone($leftArray, $leftCols, $keysResult, $progPointer);
                
                $keysColomn = array($progPointer => $progPointerClass);
                if($progPointerPrev)
                    $keysColomn[$progPointer-1] = $progPointerPrev;
                    
                if($progPointerNext)
                    $keysColomn[$progPointer+1] = $progPointerNext;
                    
                $arKeys[$channel_id]+=$keysColomn;
                
                if(count($rightCols)>0)
                {
                    $keysResult2 = self::putArrayIntoQuantity($rightArray, $rightCols);
                    $arKeys[$channel_id]+=$keysResult2;
                }
                
                $arClonesRight = self::checkAddClone($rightArray, $rightCols, $keysResult2, $progPointer);
                
                if($arClonesLeft)
                {
                    $arClones[$channel_id] = $arClonesLeft;
                }
                
                if($arClonesRight)
                {
                    $arClones[$channel_id]+= $arClonesRight;
                }
                
                $count = 0;
                $count += self::countByClasses($arKeys[$channel_id]);

                if(count($arClones[$channel_id])>0)
                {
                    //$count+=self::countByClasses($arClones[$channel_id]);
                    foreach($arClones[$channel_id] as $arClone)
                    {
                        if($arClone["class"]=="one")
                        {
                            $count+=1;
                        }else if($arClone["class"]=="double"){
                            $count+=2;
                        }
                    }
                }
                
                if($count!=BROADCAT_COLS)
                {
                    /*echo "<h1>".$datetime."</h1><hr>";
                    echo "col=".$pointerColNumber."<br />";
                    \CDev::pre($array);
                    echo "COUNT=". $count."<br />";
                    echo "progPointer=".$progPointer."<br />";
                    echo "progPointerClass=".$progPointerClass."<br />";
                    
                    
                    echo "leftCols=".$leftCols."<br />";
                    echo "rightCols=".$rightCols."<br />";
                    
                    echo "<h1>LEFT</h1><hr>";
                    \CDev::pre($leftArray);
                    \CDev::pre($keysResult);
                    echo "clones="; 
                    print_r($arClonesLeft);                    
                    //\CDev::pre($arKeys);
                    
                    
                    echo "<h1>RIGHT</h1><hr>";
                    \CDev::pre($rightArray);
                    \CDev::pre($keysResult2);
                    echo "clones=";
                    print_r($arClonesRight);*/ 
                    
                    $success = false;
                    $arKeys = array();
                    break;
                }
            }
            
            if($success)
                break;
        }
        
        if(!$success)
        {
            echo "<h1>".$datetime."</h1><hr>";
                    echo "col=".$pointerColNumber."<br />";
                    \CDev::pre($array);
                    echo "COUNT=". $count."<br />";
                    echo "progPointer=".$progPointer."<br />";
                    echo "progPointerClass=".$progPointerClass."<br />";
                    
                    
                    echo "leftCols=".$leftCols."<br />";
                    echo "rightCols=".$rightCols."<br />";
                    
                    echo "<h1>LEFT</h1><hr>";
                    \CDev::pre($leftArray);
                    \CDev::pre($keysResult);
                    echo "clones="; 
                    print_r($arClonesLeft);                    
                    //\CDev::pre($arKeys);
                    
                    
                    echo "<h1>RIGHT</h1><hr>";
                    \CDev::pre($rightArray);
                    \CDev::pre($keysResult2);
                    echo "clones=";
                    print_r($arClonesRight);
            //\CDev::pre($pointers);
            //\CDev::pre($arTimePointers);
            //die();
        }
        
        return array(
            "keys" => $arKeys,
            "clones" => $arClones
        );
    }
    
    public static function checkAddClone($array, $cols, $keysResult, $progPointer)
    {
        $clones = array();
        $count = 0;
        
        if(count($keysResult)>0)
        {
            $count+=self::countByClasses($keysResult);
        }
        
        if($cols>$count)
        {
            $diff = $cols-$count;
            
            $countArray = count($array);
            
            $c = 0;
            while($c!=$diff)
            {
                if($diff-$c>=2)
                {
                    $key = array_shift($array);
                    if($countArray>0 && $key>=0)
                    {
                        $clones[] = array("key"=>$key, "class" => "double");
                    }else{
                        $clones[] = array("key"=>$progPointer, "class" => "double");
                        //$clones[$progPointer] = "double";
                    }
                    
                    $c+=2;
                }
                else
                {
                    $key = array_shift($array);
                    if($countArray>0 && $key>=0)
                    {
                        $clones[] = array("key"=>$key, "class" => "one");
                        //$clones[$key] = "one";
                    }else{
                        $clones[] = array("key"=>$progPointer, "class" => "one");
                        //$clones[$progPointer] = "one";
                    }
                    $c+=1;
                }
            }
            
            return $clones;
        }else{
            return false;
        }
    }
    
    public static function putArrayIntoQuantity($keys, $numCols)
    {
        $count = count($keys);
        if($count>$numCols*2)
        {
            $arParts["HALF"] = $numCols;
            $needDelete = $count - $numCols*2;
        }else{
            if($count>=$numCols)  //больше 12 колонок
            {
                $ostatok = $numCols*2 - $count;
                $arParts["ONE"] = $ostatok;
                $arParts["HALF"] = ($count-$ostatok)/2;
            }else{
                if($numCols>$count*2)
                {
                    $arParts["DOUBLE"] = $count;//floor($count/2);
                    $arParts["ONE"] = $count - $arParts["DOUBLE"];
                }else{
                    $double = $numCols - $count;
                    $arParts["DOUBLE"] = $double;
                    $arParts["ONE"] = $count-$double;
                }
            }
        }
        
        $keysResult = array();
        if(count($arParts["HALF"])==0)
        {
            if(count($arParts["DOUBLE"])>0)
            {
                while($arParts["DOUBLE"]>0)
                {
                    $key = array_shift($keys);
                    $keysResult[$key] = "double";
                    $keys = array_diff($keys, array($key));
                    $arParts["DOUBLE"]--;
                }
            }
            
            if(count($arParts["ONE"])>0)
            {
                while($arParts["ONE"]>0)
                {
                    $key = array_shift($keys);
                    $keysResult[$key] = "one"; 
                    $keys = array_diff($keys, array($key));
                    $arParts["ONE"]--;
                }
            }
        }else{
            $allKeysReverse = array_reverse($keys);
            
            //Получили ключи с худшим рейтингом в количестве = количеству нужных половин * 2
            $halfKeys = array();
            for($i=0; $i<$arParts["HALF"]*2; $i++)
            {
                $halfKeys[] = $allKeysReverse[$i];
            }
            
            //проверим сколько из этих ключей являются парами
            $countHalfs = 0;
            $halfKeysDelete = array();
            sort($halfKeys);    //отсортируем
            for($i=1; $i<count($halfKeys); $i=$i+2)
            {
                if(abs($halfKeys[$i]-$halfKeys[$i-1])==1)
                {
                    $keys = array_diff($keys, array($halfKeys[$i], $halfKeys[$i-1]));
                    $halfKeysDelete[] = $halfKeys[$i];
                    $halfKeysDelete[] = $halfKeys[$i-1];
                    $keysResult[$halfKeys[$i]] = "half";
                    $keysResult[$halfKeys[$i-1]] = "half";
                    $countHalfs++;
                }
            }
            $halfKeys = array_diff($halfKeys, $halfKeysDelete);

            //если остались половинуи, то берем соседние половинок
            $diff = 0;
            $halfKeysDelete = array();
            if(count($arParts["HALF"])>$countHalfs)
            {
                $diff = $arParts["HALF"]-$countHalfs;

                foreach($halfKeys as $key)
                {
                    $key_1 = $key-1;
                    $key_2 = $key+1;
                    if(in_array($key_1, $keys))
                    {
                        $keysResult["CLASS"] = "half"; 
                        $keysResult["CLASS"] = "half";
                        $halfKeysDelete[] = $key;
                        $halfKeysDelete[] = $key_1;
                        $keys = array_diff($keys, array($key, $key_1));
                        $diff--;
                    }
                    elseif(in_array($key_2, $keys))
                    {
                        $keysResult[$key] = "half"; 
                        $keysResult[$key_2] = "half";
                        $halfKeysDelete[] = $key;
                        $halfKeysDelete[] = $key_2;
                        $keys = array_diff($keys, array($key, $key_2));
                        $diff--;
                    }
                    
                    if($diff==0)
                        break;
                }
                $halfKeys = array_diff($halfKeys, $halfKeysDelete);
            }
            
            //если остались половинки, то берем соседние половинок
            if($diff>0)
            {
                if(count($halfKeys)>0)
                {
                    foreach($halfKeys as $key)
                    {
                        $keysResult[$key] = "one"; 
                        $keys = array_diff($keys, array($key));
                        $arParts["ONE"]--;
                    }
                }
            }
            
            if(count($arParts["DOUBLE"])>0)
            {
                while($arParts["DOUBLE"]>0)
                {
                    $key = array_shift($keys);
                    $keysResult[$key] = "double"; 
                    $keys = array_diff($keys, array($key));
                    $arParts["DOUBLE"]--;
                }
            }
            
            if(count($arParts["ONE"])>0)
            {
                while($arParts["ONE"]>0)
                {
                    $key = array_shift($keys);
                    $keysResult[$key] = "one"; 
                    $keys = array_diff($keys, array($key));
                    $arParts["ONE"]--;
                }
            }
            
        }                   
        
        unset($arParts);
        return $keysResult;
    }
    
    public static function countByClasses($keysResult)
    {
        $count = 0;
        foreach($keysResult as $class)
        {
            if($class=="one"){
                $count+=1;
            }else if($class=="double"){
                $count+=2;
            }else{
                $count+=0.5;
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
        $dir = $_SERVER["DOCUMENT_ROOT"]."/upload/cell/".$city_id."-".$channel_id;
        
        if (!file_exists($dir))
            mkdir($dir, 0777, true);
        
        $path = $dir."/".strtotime($currentDateTime).".json";
        
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
}