<?
class CCityEx
{
    public static $cacheDir = "city";
    public static $defaultCityID = 33;
    
    public static function getByID($ID)
    {
        CModule::IncludeModule("iblock");
        
        if(!$ID)
            return false;
        
        $arSelect = Array("ID", "NAME", "PROPERTY_*");
        $arFilter = array("IBLOCK_ID" => CITY_IB, "ACTIVE" => "Y", "=ID" => $ID);
        
        $CacheEx = new CCacheEx(60*60*24*365, self::$cacheDir);
        $arCity = $CacheEx->cacheElement( array( "SORT" => "ASC", "ID" => "DESC" ), $arFilter, "getId");
        
        return $arCity;
    }
    
    public static function getList($arrFilter = false)
    {
        CModule::IncludeModule("iblock");
        $arCities = array();
        $arSelect = Array("ID", "NAME", "PROPERTY_*");
        $arFilter = array("IBLOCK_ID" => CITY_IB, "ACTIVE" => "Y");
        
        if($arrFilter)
        {
            $arFilter = array_merge($arFilter, $arrFilter);
        }
        
        $CacheEx = new CCacheEx(60*60*24*365, self::$cacheDir);
        $arTmpCities = $CacheEx->cacheElement( array( "SORT" => "ASC", "ID" => "DESC" ), $arFilter, "getlist");
        foreach( $arTmpCities as $arTmpCity )
        {
			$arCities[] = $arTmpCity;
		}
        
        return $arCities;
    }
    
    public static function getGeoCity()
    {
        global $currentGeo;
        if(!$_SESSION["USER_GEO"] || empty($_SESSION["USER_GEO"]))
        {
            $arGeo = \Olegpro\IpGeoBase\IpGeoBase::getInstance()->getRecord();

            if(!empty($arGeo))
            {
                $arCities = self::getList(array("NAME"=>$arGeo["city"]));
                if(!empty($arCities[0]))
                {
                    $_SESSION["USER_GEO"] = $arCities[0];
                }
                
            }else{
                $_SESSION["USER_GEO"] = self::getByID(array("ID"=>self::$defaultCityID));
            }
        }
        
        $currentGeo = $_SESSION["USER_GEO"];
        
        return $_SESSION["USER_GEO"];
    }
    
    public static function setGeoCity($ID)
    {
        global $currentGeo;
        
        if(intval($ID)==$_SESSION["USER_GEO"]["ID"])
            return $_SESSION["USER_GEO"];
            
        if(intval($ID)>0)
        {
            $_SESSION["USER_GEO"] = self::getByID(array("=ID"=>$ID));
        }else{
            $_SESSION["USER_GEO"] = self::getByID(array("=ID"=>self::$defaultCityID));
        }
        
        $currentGeo = $_SESSION["USER_GEO"];
        
        return $_SESSION["USER_GEO"];
    }
    
    public static function updateCache() 
    {
		CCacheEx::clean(self::$cacheDir);
	}
}