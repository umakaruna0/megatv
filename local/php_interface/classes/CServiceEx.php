<?
IncludeModuleLangFile(__FILE__);

class CServiceEx
{
    public static $cacheDir = "services";
        
    public static function getByID($ID, $arSelect = false)
    {
        CModule::IncludeModule("iblock");
        
        if(!$ID)
            return false;
        
        if(empty($arSelect))
            $arSelect = Array("ID", "NAME");
        
        $arFilter = array("IBLOCK_ID" => SERVICE_IB, "ACTIVE" => "Y", "=ID" => $ID);
        
        $CacheEx = new CCacheEx(60*60*24*365, self::$cacheDir);
        $arServices = $CacheEx->cacheElement( array( "SORT" => "ASC", "ID" => "DESC" ), $arFilter, "getlist", false, $arSelect);
        
        return $arServices[0];
    }
        
    public static function getList($arrFilter=array(), $arSelect = array())
    {
        CModule::IncludeModule("iblock");
        $arServices = array();
        
        $arFilter = array("IBLOCK_ID" => SERVICE_IB);
        if($arrFilter)
            $arFilter = array_merge($arFilter, $arrFilter);
        
        $CacheEx = new CCacheEx(60*60*24, self::$cacheDir);
        $arServices = $CacheEx->cacheElement( array( "SORT" => "ASC", "NAME" => "ASC" ), $arFilter, "getlist", false, $arSelect);
        return $arServices;
    }
    
    public static function updateCache() 
    {
		CCacheEx::clean(self::$cacheDir);
	}
}