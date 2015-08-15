<?
IncludeModuleLangFile(__FILE__);

class CChannel
{
    public static $cacheDir = "channels";
        
    public static function getList($arrFilter = false)
    {
        CModule::IncludeModule("iblock");
        $arChannels = array();
        $arSelect = Array("ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_*");
        $arFilter = array("IBLOCK_ID" => CHANNEL_IB, "ACTIVE" => "Y");
        
        if($arrFilter)
        {
            $arFilter = array_merge($arFilter, $arrFilter);
        }
        
        $CacheEx = new CCacheEx(60*60*24, self::$cacheDir);
        $arTmpChannels = $CacheEx->cacheElement( array( "SORT" => "ASC", "ID" => "DESC" ), $arFilter, "getlist"/*, false, $arSelect*/);
        foreach( $arTmpChannels as $arTmpChannel )
        {
			$arChannels[$arTmpChannel["PROPERTIES"]["EPG_ID"]["VALUE"]] = $arTmpChannel;
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