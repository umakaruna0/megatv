<?
IncludeModuleLangFile(__FILE__);
class CProg
{
    public static $cacheDir = "progs";
    
    public static function generateUnique($arFields)
    {
        $str = $arFields["CHANNEL"]."|".$arFields["NAME"];
        return $str;
    }
    
    public static function getByID($ID)
    {
        CModule::IncludeModule('highloadblock');
        
        if(!$ID)
            return false;
        
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(2)->fetch();
        $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity( $hlblock );
        $entity_data_class = $entity->getDataClass();
        
        //BRANDS
        $arFilter = array(
            "UF_XML_ID" => $xmlId
        );
        $rsData = $entity_data_class::GetByID($ID);
        if($arBrand = $rsData->Fetch()) 
        {
            return $arBrand["UF_NAME"];
        }
        return $arProg;
    }
    
    public static function getList($arOrder = array('UF_DATE_START' => 'ASC'), $arrFilter = false,  $arSelect = array("*"))
    {
        CModule::IncludeModule("highloadblock");
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(PROG_HL_IB)->fetch();
        $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity( $hlblock );
        $entity_data_class = $entity->getDataClass();
        
        //BRANDS
        $arFilter = array(
            "UF_ACTIVE" => "Y"
        );
        
        if($arrFilter)
        {
            $arFilter = array_merge($arFilter, $arrFilter);
        }
        
        $rsData = $entity_data_class::getList(array(
        	'filter' => $arFilter,
        	'select' => $arSelect,
        	'limit' => false,
        	'order' => $arOrder,
        ));
        while($arTmpProg = $rsData->Fetch()) 
        {
            $unique = self::generateUnique(array(
                "CHANNEL" => $arTmpProg["UF_CHANNEL"],
                "NAME" => $arTmpProg["UF_NAME"],
                "DESC" => $arTmpProg["UF_PREVIEW_TEXT"]
            ));
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
        
        //$arParams = array("replace_space"=>"-", "replace_other"=>"-");
        //$translit = CDev::translit(trim($arFields["FIELDS"]["NAME"]." ".$PROP["SUB_TITLE"]." ".$PROP["CHANNEL"]), "ru", $arParams);
        
        $arLoadProductArray = Array(
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"      => PROG_IB,
            "PROPERTY_VALUES"=> $PROP,
            "NAME"           => $arFields["FIELDS"]["NAME"],
            //"CODE"           => $translit,   
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