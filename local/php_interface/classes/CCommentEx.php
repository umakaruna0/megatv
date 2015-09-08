<?
class CCommentEx
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
        
        $arFilter = array();
        
        if($arrFilter)
            $arFilter = array_merge($arFilter, $arrFilter);
        
        $arOrder = array("UF_DATETIME" => "DESC");
        
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
    
    public static function create($arFields)
    {
        CModule::IncludeModule('highloadblock');
        global $USER;
        
        if(!$USER_ID)
            $USER_ID = $USER->GetID();
        
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(6)->fetch();
        $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity( $hlblock );
        $entity_data_class = $entity->getDataClass();
        
        $dt = new \Bitrix\Main\Type\DateTime(date('Y-m-d H:i:s', strtotime(date("d.m.Y H:i:s"))), 'Y-m-d H:i:s');
    
        $data = array(
           'UF_USER_ID' => $USER_ID,
           'UF_DATETIME' => $dt,
           'UF_TEXT' => $arFields["TEXT"],
           'UF_PROG_ID' => $arFields["PROG_ID"]
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
}