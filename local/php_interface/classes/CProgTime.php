<?
IncludeModuleLangFile(__FILE__);
class CProgTime
{
    public static $cacheDir = "prog_time";
    
    public static function generateUnique($arFields)
    {
        $str = $arFields["CHANNEL"].$arFields["DATE_START"];
        return $str;
    }
    
    public static function getList($arrFilter = false)
    {
        CModule::IncludeModule("iblock");
        $arProgTimes = array();
        $arSelect = Array("ID", "NAME", "PROPERTY_*");
        $arFilter = array("IBLOCK_ID" => PROG_TIME_IB, "ACTIVE" => "Y");
        
        if($arrFilter)
        {
            $arFilter = array_merge($arFilter, $arrFilter);
        }
        
        $CacheEx = new CCacheEx(60*60*24*2, self::$cacheDir);
        $arTmpProgTimes = $CacheEx->cacheElement( array( "SORT" => "ASC", "ID" => "DESC" ), $arFilter, "getlist");
        foreach( $arTmpProgTimes as $arTmpProgTime )
        {
            $unique = self::generateUnique(array(
                "CHANNEL" => $arTmpProg["PROPERTIES"]["CHANNEL"]["VALUE"],
                "DATE_START" => $arTmpProg["PROPERTIES"]["DATE_START"]["VALUE"],
            ));
			$arProgTimes[] = $arTmpProgTime;
		}
        
        return $arProgTimes;
    }
    
    public static function add($arFields)
    {
        CModule::IncludeModule("iblock");
        $el = new CIBlockElement;
        
        $PROP = array();
        $PROP = $arFields["PROPS"];
        $PROP["DATE_START"] = date("d.m.Y H:i:s", strtotime($PROP["DATE_START"]));
        $PROP["DATE_END"] = date("d.m.Y H:i:s", strtotime($PROP["DATE_END"]));
        
        $datetime = date("dmY-Hi", strtotime($PROP["DATE_START"]));
        $arParams = array("replace_space"=>"-", "replace_other"=>"-");
        $translit = Cutil::translit($arFields["FIELDS"]["NAME"]."-".$datetime, "ru", $arParams);
        
        $arLoadProductArray = Array(
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"      => PROG_TIME_IB,
            "PROPERTY_VALUES"=> $PROP,
            "NAME"           => $arFields["FIELDS"]["NAME"],
            "CODE"           => $translit,   
            "ACTIVE"         => "Y",
        );
        
        $arLoadProductArray = array_merge($arLoadProductArray, $arFields["FIELDS"]);
        $progTimeID = $el->Add($arLoadProductArray);
        if($progTimeID)
        {
            return $progTimeID;
        }else{
            return $el->LAST_ERROR;
        }
    }
    
    public static function updateCache() 
    {
		CCacheEx::clean(self::$cacheDir);
	}
}