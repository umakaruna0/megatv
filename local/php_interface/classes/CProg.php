<?
IncludeModuleLangFile(__FILE__);
class CProg
{
    public static $cacheDir = "progs";
        
    public static function getList($arrFilter = false)
    {
        CModule::IncludeModule("iblock");
        $arProgs = array();
        $arSelect = Array("ID", "NAME", "PROPERTY_*");
        $arFilter = array("IBLOCK_ID" => PROG_IB, "ACTIVE" => "Y");
        
        if($arrFilter)
        {
            $arFilter = array_merge($arFilter, $arrFilter);
        }
        
        $CacheEx = new CCacheEx(60*60*24, self::$cacheDir);
        $arTmpChannels = $CacheEx->cacheElement( array( "SORT" => "ASC", "ID" => "DESC" ), $arFilter, "getlist"/*, false, $arSelect*/);
        foreach( $arTmpChannels as $arTmpChannel )
        {
			$arProgs[] = $arTmpChannel;
		}
        
        return $arProgs;
    }
    
    public static function add($arFields)
    {
        CModule::IncludeModule("iblock");
        $el = new CIBlockElement;
        
        $PROP = array();
        $PROP = $arFields["PROPS"];
        
        $PROP["DATE_START"] = date("d.m.Y H:i:s", strtotime($PROP["DATE_START"]));
        $PROP["DATE_END"] = date("d.m.Y H:i:s", strtotime($PROP["DATE_END"]));
        
        $arParams = array("replace_space"=>"-", "replace_other"=>"-");
        $translit = Cutil::translit(trim($arFields["FIELDS"]["NAME"]." ".$PROP["SUB_TITLE"]), "ru", $arParams);
        
        $arLoadProductArray = Array(
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"      => PROG_IB,
            "PROPERTY_VALUES"=> $PROP,
            "NAME"           => $arFields["FIELDS"]["NAME"],
            "CODE"           => $translit,   
            "ACTIVE"         => "Y",
        );
        
        $arLoadProductArray = array_merge($arLoadProductArray, $arFields["FIELDS"]);
        if(!empty($PROP["SUB_TITLE"]))
        {
            $arLoadProductArray["NAME"] = trim($arLoadProductArray["NAME"]." (".$PROP["SUB_TITLE"]).")";
        }
        //echo "<pre>"; print_r($arLoadProductArray); echo "</pre>";
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