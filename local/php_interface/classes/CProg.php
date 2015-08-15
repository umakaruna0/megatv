<?
IncludeModuleLangFile(__FILE__);
class CProg
{
    public static $cacheDir = "progs";
    
    public static function generateUnique($arFields)
    {
        $str = $arFields["CHANNEL"]."-".htmlspecialchars_decode($arFields["NAME"]); //!!!!!!
        
        $arParams = array("replace_space"=>"-", "replace_other"=>"-");
        $str = CDev::translit($str, "ru", $arParams);
        
        return $str;
    }
    
    public static function getByID($ID, $arSelect = false)
    {
        CModule::IncludeModule("iblock");
        
        if(!$ID)
            return false;
        
        if(empty($arSelect))
            $arSelect = Array("ID", "NAME", "PROPERTY_CHANNEL");
        
        $arProgs = array();
        $arFilter = array("IBLOCK_ID" => PROG_IB, "ACTIVE" => "Y", "=ID" => $ID);
        
        $CacheEx = new CCacheEx(60*60*24*365, self::$cacheDir);
        $arProg = $CacheEx->cacheElement( array( "SORT" => "ASC", "ID" => "DESC" ), $arFilter, "getlist", false, $arSelect);
        
        return $arProg;
    }
    
    public static function getList($arrFilter=false, $arSelect = array())
    {
        CModule::IncludeModule("iblock");
        $arProgs = array();
        
        if(empty($arSelect))
            $arSelect = Array("ID", "NAME", "PREVIEW_TEXT", "PROPERTY_CHANNEL");
            
        $arFilter = array("IBLOCK_ID" => PROG_IB, "ACTIVE" => "Y");
        if($arrFilter)
            $arFilter = array_merge($arFilter, $arrFilter);
        
        $CacheEx = new CCacheEx(60*60*24*365, self::$cacheDir);
        $arTmpProgs = $CacheEx->cacheElement( array( "SORT" => "ASC", "ID" => "DESC" ), $arFilter, "getlist", false, $arSelect);
        foreach( $arTmpProgs as $arTmpProg )
        {
            $unique = self::generateUnique(array(
                "CHANNEL" => $arTmpProg["PROPERTY_CHANNEL_VALUE"],
                "NAME" => $arTmpProg["NAME"],
                "DESC" => $arTmpProg["PREVIEW_TEXT"]
            ));
            
            //Для множественного свойства
            /*if(isset($arProgs[$unique]))
            {
                $arTmpProg = array_merge_recursive($arProgs[$unique], $arTmpProg);
            }*/
            
			$arProgs[$unique] = $arTmpProg;
		}
        
        return $arProgs;
    }
    
    public static function add($arFields)
    {
        CModule::IncludeModule("iblock");
        $el = new CIBlockElement;
        
        $PROP = array();
        $PROP = $arFields["PROPS"];
        
        $arLoadProductArray = Array(
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"      => PROG_IB,
            "PROPERTY_VALUES"=> $PROP,
            "NAME"           => $arFields["FIELDS"]["NAME"],
            "ACTIVE"         => "Y",
        );
        
        $arLoadProductArray = array_merge($arLoadProductArray, $arFields["FIELDS"]);
        if(!empty($PROP["SUB_TITLE"]))
        {
            $arLoadProductArray["NAME"] = $arLoadProductArray["NAME"]." (".$PROP["SUB_TITLE"].")";
        }
        
        $prog_id = $el->Add($arLoadProductArray);
        if($prog_id)
        {
            return $prog_id;
        }else{
            return $el->LAST_ERROR;
        }
    }
    
    public static function updateCache() 
    {
		CCacheEx::clean(self::$cacheDir);
	}
}