<?
class CSubscribeEx
{
    public static function getUserList($USER_ID = false, $arrFilter = false, $arSelect = false)
    {
        global $USER;
        CModule::IncludeModule('highloadblock');
        CModule::IncludeModule('iblock');
        $arSubs = array();
        
        if(!$USER_ID)
            $USER_ID = $USER->GetID();
        
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(SUBSCRIBE_HL)->fetch();
        $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity( $hlblock );
        $entity_data_class = $entity->getDataClass();
        
        //BRANDS
        $arFilter = array(
            "UF_ACTIVE" => "Y",
            "UF_USER" => $USER_ID
        );
        
        if($arrFilter)
            $arFilter = array_merge($arFilter, $arrFilter);
        
        if(empty($arSelect))
            $arSelect = array("UF_CHANNEL", "UF_DATE_FROM");
        
        $arOrder = array("UF_DATE_FROM" => "ASC");
        
        $rsData = $entity_data_class::getList(array(
        	'filter' => $arFilter,
        	'select' => $arSelect,
        	'limit' => false,
        	'order' => $arOrder,
        ));
        while($arTmp = $rsData->Fetch()) 
        {
            $arSubs[] = $arTmp;
        }
        
        return $arSubs;
    }
    
    public static function setUserSubscribe($CHANNEL_ID, $USER_ID = false )
    {
        global $USER;
        CModule::IncludeModule('highloadblock');
        CModule::IncludeModule('iblock');
        
        if(!$USER_ID)
            $USER_ID = $USER->GetID();
        
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(SUBSCRIBE_HL)->fetch();
        $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity( $hlblock );
        $entity_data_class = $entity->getDataClass();
        
        $dt = new DateTime();                     
        $result = $entity_data_class::add(array(
           'UF_CHANNEL' => $CHANNEL_ID,
           'UF_USER' => $USER_ID,
           'UF_DATE_FROM' => $dt,
        ));
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