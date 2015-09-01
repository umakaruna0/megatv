<?
class CRecordEx
{
    public static function getList($arrFilter = false, $arSelect = false)
    {
        global $USER;
        CModule::IncludeModule('highloadblock');
        CModule::IncludeModule('iblock');
        $arRecords = array();
        
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(RECORD_HL)->fetch();
        $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity( $hlblock );
        $entity_data_class = $entity->getDataClass();
        
        //BRANDS
        $arFilter = array();
        
        if($arrFilter)
            $arFilter = array_merge($arFilter, $arrFilter);
        
        $arOrder = array("ID" => "ASC");
        
        $rsData = $entity_data_class::getList(array(
        	'filter' => $arFilter,
        	'select' => $arSelect,
        	'limit' => false,
        	'order' => $arOrder,
        ));
        while($arTmp = $rsData->Fetch()) 
        {
            $arRecords[] = $arTmp;
        }
        
        return $arRecords;
    }
    
    
    public static function getBySotalID($sotal_id,$arSelect=false)
    {
        $arRecords = self::getList(array("UF_SOTAL_ID"=>$sotal_id), array("ID"));
        
        if(!empty($arRecords))
            return $arRecords[0];
        
        return false;
    }    
    
    public static function create($arFields)
    {
        CModule::IncludeModule('highloadblock');
        global $USER;
        
        if(!$USER_ID)
            $USER_ID = $USER->GetID();
        
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(RECORD_HL)->fetch();
        $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity( $hlblock );
        $entity_data_class = $entity->getDataClass();
        
        $arProgTime = CProgTime::getByID($arFields["UF_SCHEDULE"], array("PROPERTY_DATE_END", "PROPERTY_PROG"));
        $dt = new Bitrix\Main\Type\DateTime(date('Y-m-d H:i:s', strtotime($arProgTime["PROPERTY_DATE_END_VALUE"])), 'Y-m-d H:i:s');
        
        $data = array(
           'UF_USER' => $USER_ID,
           'UF_DATE_END' => $dt,
           'UF_SOTAL_ID' => $arFields["UF_SOTAL_ID"],
           'UF_SCHEDULE' => $arFields["UF_SCHEDULE"],
           'UF_PROG' => $arProgTime["PROPERTY_PROG_VALUE"]
        );
                          
        $result = $entity_data_class::add($data);
        if ($result->isSuccess()) 
        {         
            return true;        
        }
        else
        { 
            return implode(', ', $result->getErrors());
        }
    }
    
    public static function update($ID, $arFields)
    {
        CModule::IncludeModule('highloadblock');
        
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(RECORD_HL)->fetch();
        $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity( $hlblock );
        $entity_data_class = $entity->getDataClass();
                   
        $result = $entity_data_class::update($ID, $arFields);    
        if ($result->isSuccess()) 
        {         
            return true;        
        }
        else
        { 
            return implode(', ', $result->getErrors());
        }        
    }
}