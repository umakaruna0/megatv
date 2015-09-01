<?
IncludeModuleLangFile(__FILE__);

class CChannel
{
    public static $cacheDir = "channels";
        
    public static function getByID($ID, $arSelect = false)
    {
        CModule::IncludeModule("iblock");
        
        if(!$ID)
            return false;
        
        if(empty($arSelect))
            $arSelect = Array("ID", "NAME");
        
        $arFilter = array("IBLOCK_ID" => CHANNEL_IB, "ACTIVE" => "Y", "=ID" => $ID);
        
        $CacheEx = new CCacheEx(60*60*24*365, self::$cacheDir);
        $arChannels = $CacheEx->cacheElement( array( "SORT" => "ASC", "ID" => "DESC" ), $arFilter, "getlist", false, $arSelect);
        
        return $arChannels[0];
    }
        
    public static function getList($arrFilter=false, $arSelect = array())
    {
        CModule::IncludeModule("iblock");
        $arChannels = array();
        
        if(empty($arSelect))
            $arSelect = Array("ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_EPG_ID");
            
        $arFilter = array("IBLOCK_ID" => CHANNEL_IB);
        if($arrFilter)
            $arFilter = array_merge($arFilter, $arrFilter);
        
        $CacheEx = new CCacheEx(60*60*24, self::$cacheDir);
        $arTmpChannels = $CacheEx->cacheElement( array( "SORT" => "ASC", "NAME" => "ASC" ), $arFilter, "getlist", false, $arSelect);
        foreach( $arTmpChannels as $arTmpChannel )
        {
            if($arTmpChannel["PROPERTY_EPG_ID_VALUE"])
                $arChannels[$arTmpChannel["PROPERTY_EPG_ID_VALUE"]] = $arTmpChannel;
            else
                $arChannels[] = $arTmpChannel;
		}
        
        return $arChannels;
    }
    
    /**
     * $arFields = array( 
            "EPG_ID" => $id,
            "NAME" => $name,
            "ICON_SRC" => $icon
        );
    */
    public static function add($arFields)
    {
        CModule::IncludeModule("iblock");
        $el = new CIBlockElement;
        
        $PROP = array();
        $PROP["EPG_ID"] = $arFields["EPG_ID"];
        
        $arParams = array("replace_space"=>"-", "replace_other"=>"-");
        $translit = CDev::translit(trim($arFields["NAME"]), "ru", $arParams);
        
        $arLoadProductArray = Array(
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"      => CHANNEL_IB,
            "PROPERTY_VALUES"=> $PROP,
            "NAME"           => trim($arFields["NAME"]),
            "CODE"           => $translit,   
            "ACTIVE"         => "Y",
            "PREVIEW_PICTURE" => CFile::MakeFileArray($arFields["ICON_SRC"])
        );
        
        if($channel_id = $el->Add($arLoadProductArray))
        {
            return $channel_id;
        }else{
            return $el->LAST_ERROR;
        }
    }
    
    public static function updateCache() 
    {
		CCacheEx::clean(self::$cacheDir);
	}
}