<?
class CScheduleTable
{
    /**
     * array(
        "CITY" => $arParams["CITY"],
        "COUNT" => $arChannel["COUNT"],
        "PROGS" => $arChannel["PROGS"],
        "NEWS" => $arChannel["PROPERTIES"]["NEWS"]["VALUE"],
     )
    */
    public static function setIndex($arParams)
    {
        global $CACHE_MANAGER;
        
        /*$obCache = new CPHPCache;
		$cache_time = 86400;
		$cache_path = "schedule_index";
        
        $arRes = array();
        $cache_id = 'cache_'.serialize( $arParams["PROGS"] ).serialize( $arParams["CITY"]["ID"] );
		if($cache_time > 0 && $obCache->InitCache($cache_time, $cache_id, $cache_path))
        {
			$arProgs = $obCache->GetVars();
		}
        elseif($obCache->StartDataCache())
        {*/        
            $arResult = array();
            $needDelete = false;
        
            $arProgs = $arParams["PROGS"];
            unset($arParams["PROGS"]);
            
            $arParams["COUNT"] = count($arProgs);
            
            if(count($arProgs)==0)
                return false;
            
            if($arParams["NEWS"] || $arParams["COUNT"]>BROADCAT_COLS*2)
            {
                $arParts["HALF"] = BROADCAT_COLS;
                $needDelete = $arParams["COUNT"] - BROADCAT_COLS*2;
            }else{
                if($arParams["COUNT"]>=BROADCAT_COLS)  //больше 12 колонок
                {
                    $ostatok = BROADCAT_COLS*2 - $arParams["COUNT"];
                    $arParts["ONE"] = $ostatok;
                    $arParts["HALF"] = ($arParams["COUNT"]-$ostatok)/2;
                }else{
                    $double = BROADCAT_COLS - $arParams["COUNT"];
                    $arParts["DOUBLE"] = $double;
                    $arParts["ONE"] = $arParams["COUNT"]-$double;
                }
            }
            
            
            
            //Удаляем каждый 3-й
            /*if($needDelete)
            {
                $k = 0;
                foreach($arProgs as $key=>$arProg)
                {
                    if($k%3==0 && $k!=0)
                        unset($arProgs[$key]);
                    $k++;
                }
            }*/

            //echo count($arProgs);
            //CDev::pre($arParts, true, false);

            //Все ключи программ
            $allKeys = array_keys($arProgs); 
            $countHalfs = $arParts["HALF"];
            
            if(count($arParts["HALF"])>0)
            {
                $doubleKeys = self::getDoubleArray($allKeys);
                $allDoubleKeys = array_keys($doubleKeys); 
                
                while($arParts["HALF"]>0)
                {
                    $key = array_rand($allDoubleKeys, 1);
                    $keys = $doubleKeys[$key];
                    unset($allDoubleKeys[$key]);

                    $arProgs[$keys[0]]["CLASS"] = "half"; 
                    $arProgs[$keys[1]]["CLASS"] = "half";
                    $allKeys = array_diff($allKeys, array($keys[0], $keys[1]));
                    
                    //echo $keys[0]." ".$keys[1]." ".$arParts["HALF"]."<br />";
                    
                    $arParts["HALF"]--;
                }
            }
            

            if(count($arParts["DOUBLE"])>0)
            {
                while($arParts["DOUBLE"]>0)
                {
                    $key = array_rand($allKeys, 1);
                    $arProgs[$key]["CLASS"] = "double"; 
                    $allKeys = array_diff($allKeys, array($key));
                    $arParts["DOUBLE"]--;
                }
            }
            
            if(count($arParts["ONE"])>0)
            {
                while($arParts["ONE"]>0)
                {
                    $key = array_rand($allKeys, 1);
                    $arProgs[$key]["CLASS"] = "one"; 
                    $allKeys = array_diff($allKeys, array($key));
                    $arParts["ONE"]--;
                }
            }
            
            //CDev::pre($arParts, true, false);
			//$obCache->EndDataCache($arRes); 
		//} // END CACHE
        
        return $arProgs;
    }
    
    public static function setChannel($arParams)
    {
        $arProgs = $arParams["PROGS"];
        unset($arParams["PROGS"]);
        
        $arParams["COUNT"] = count($arProgs);
        
        if(count($arProgs)==0)
            return false;

        $allKeys = array_keys($arProgs);
        $key = array_rand($allKeys, 1);
        unset($allKeys[$key]);
        $arProgs[$key]["CLASS"] = "one"; 
        
        foreach($allKeys as $key)
        {
            $arProgs[$key]["CLASS"] = "half"; 
        }
        return $arProgs;
    }
    
    public static function setRecommendIndex($arParams)
    {
        $arProgs = $arParams["PROGS"];        
        $arParams["COUNT"] = count($arProgs);
        
        if(count($arProgs)==0)
            return false;
        
        foreach($arProgs as $key=>$arProg)
        {
            if($key<4)
            {
                $arProgs[$key]["CLASS"] = "quadro"; 
            }else{
                $arProgs[$key]["CLASS"] = "one"; 
            }
        }
        return $arProgs;
    }
    
    public static function getDoubleArray($array)
    {
        $doubleArray = array();
        $count = floor(count($array)/2);
        
        for($key = 0; $key<count($array); $key=$key+2)
        {
            if($key/2<$count)
            {
                $doubleArray[] = array($key, ($key+1));
            }else{
                break;
            }
        }
        
        return $doubleArray;
    }    
}