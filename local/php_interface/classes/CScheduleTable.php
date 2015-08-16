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
            
            $arParams["COUNT"] = count($arParams["PROGS"]);
            
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
                    $arParts["ONE"] = $arParams["COUNT"];
                }
            }
            
            //Удаляем каждый 3-й
            if($needDelete)
            {
                $k = 0;
                foreach($arParams["PROGS"] as $key=>$arProg)
                {
                    if($k%3==0 && $k!=0)
                        unset($arParams["PROGS"][$key]);
                    $k++;
                }
            }
            
            $arProgs = $arParams["PROGS"];
            unset($arParams["PROGS"]);
            
            $doubleKeys = array();
            $halfKeys = array();
            $oneKeys = array();
            
            $countHalfs = $arParts["HALF"];
            $countDoubles = $arParts["DOUBLE"];
            $countOnes = $arParts["ONE"];
            
            if(count($arParts["HALF"])>0)
            {
                while($arParts["HALF"]>0)
                {
                    $key = rand(0, $countHalfs);
                    if(!in_array($key, $halfKeys) && $key+1<$countHalfs)
                    {
                        $halfKeys[] = $key; 
                        $arProgs[$key]["CLASS"] = "half";
                        $halfKeys[] = $key+1; 
                        $arProgs[$key+1]["CLASS"] = "half";
                        $arParts["HALF"]-=2;
                    }
                }
            }
            
            echo $arParams["COUNT"]."<br />";
            print_r($arParts);
            echo $countDoubles."<br />";
            
            if(count($arParts["DOUBLE"])>0)
            {
                while($arParts["DOUBLE"]>0)
                {
                    $key = rand(0, $countDoubles);
                    if(!in_array($key, $doubleKeys) && !in_array($key, $halfKeys))
                    {
                        $doubleKeys[] = $key;
                        $arProgs[$key]["CLASS"] = "double";
                        $arParts["DOUBLE"]--;
                    }
                }
            }
            
            /*
            if(count($arParts["ONE"])>0)
            {
                while($arParts["ONE"]>0)
                {
                    $key = rand(0, $countOnes);
                    if(!in_array($key, $doubleKeys) && !in_array($key, $halfKeys) && !in_array($key, $oneKeys))
                    {
                        $oneKeys[] = $key; 
                        $arProgs[$key]["CLASS"] = "one";
                        $arParts["ONE"]--;
                    }
                }
            }*/
            
			//$obCache->EndDataCache($arRes); 
		//} // END CACHE
        
        return $arProgs;
    }
}