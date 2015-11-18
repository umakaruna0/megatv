<?
class CStatChannel
{
    public static function getList($arrFilter = false, $arSelect = false)
    {
        global $USER;
        CModule::IncludeModule('highloadblock');
        CModule::IncludeModule('iblock');
        $arRecords = array();
        
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(7)->fetch();
        $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity( $hlblock );
        $entity_data_class = $entity->getDataClass();
        
        $arFilter = array();
        
        if($arrFilter)
            $arFilter = array_merge($arFilter, $arrFilter);
        
        $arOrder = array("UF_RATING" => "DESC");
        
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
    
    public static function add($arFields)
    {
        CModule::IncludeModule('highloadblock');
        global $USER;
        
        if(!$USER_ID)
            $USER_ID = $USER->GetID();
        
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(7)->fetch();
        $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity( $hlblock );
        $entity_data_class = $entity->getDataClass();
        
        $data = array(
           'UF_USER' => $arFields["UF_USER"],
           'UF_RATING' => 1,
           'UF_CHANNEL' => $arFields["UF_CHANNEL"],
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
        
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(7)->fetch();
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
    
    public static function delete($ID)
    {
        CModule::IncludeModule('highloadblock');
        
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(7)->fetch();
        $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity( $hlblock );
        $entity_data_class = $entity->getDataClass();
                   
        $result = $entity_data_class::delete($ID);       
    }
}