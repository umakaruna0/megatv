<?
class CSubscribeEx
{
    function __construct($type)
    {
        $this->type = $type;
    }
    
    public static function getList($arFilter = false, $arSelect = false)
    {
        global $USER;
        CModule::IncludeModule('highloadblock');
        CModule::IncludeModule('iblock');
        $arSubs = array();
        
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(SUBSCRIBE_HL)->fetch();
        $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity( $hlblock );
        $entity_data_class = $entity->getDataClass();
        
        if(empty($arSelect))
            $arSelect = array("UF_".$this->type, "UF_DATE_FROM");
        
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
    
    public function setUserSubscribe($SUBSCRIBE_TO, $USER_ID = false )
    {
        global $USER;
        CModule::IncludeModule('highloadblock');
        
        if(!$USER_ID)
            $USER_ID = $USER->GetID();
        
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(SUBSCRIBE_HL)->fetch();
        $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity( $hlblock );
        $entity_data_class = $entity->getDataClass();
        
        $dt = new Bitrix\Main\Type\DateTime(date('Y-m-d H:i:s', time()),'Y-m-d H:i:s');
        $data = array(
           'UF_USER' => $USER_ID,
           'UF_DATE_FROM' => $dt,
           'UF_ACTIVE' => 'Y'
        );
        
        if($this->type=="CHANNEL")
        {
            $data['UF_CHANNEL'] = $SUBSCRIBE_TO;
        }else{
            $data['UF_SERVICE'] = $SUBSCRIBE_TO;
        }
        
        //снимаемм деньги
        if(!$this->pay($SUBSCRIBE_TO, $USER_ID))
            return false;
        
        //добавляем ГБ
        if($this->type!="CHANNEL")
            $this->capacityAdd($SUBSCRIBE_TO, $USER_ID); 
          
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
    
    public function updateUserSubscribe($ID, $arFields)
    {
        CModule::IncludeModule('highloadblock');
        
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(SUBSCRIBE_HL)->fetch();
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
    
    //Оплата подписки
    public function pay($SUBSCRIBE_TO, $USER_ID=false)
    {
        global $USER;
        CModule::IncludeModule('iblock');
        
        if(!$USER_ID)
            $USER_ID = $USER->GetID();
            
        $IB = $this->type=="CHANNEL" ? CHANNEL_IB : SERVICE_IB;
        
        $db_props = CIBlockElement::GetProperty($IB, $SUBSCRIBE_TO, array("sort" => "asc"), Array("CODE"=>"PRICE"));
        if($ar_props = $db_props->Fetch())
        {
            $price = IntVal($ar_props["VALUE"]);
        }
        
        $res = CIBlockElement::GetByID($SUBSCRIBE_TO);
        $ar_res = $res->GetNext();
        
        if($price==0)
            return true; 
        
        if($price>0 && CSaleAccountEx::budget($USER_ID)>$price)
        {
            $comment = "Оплата подписки на ".$ar_res["NAME"];
            if(!CSaleAccountEx::transaction((-1)*$price, $USER_ID, $comment))
            {
                return false;
            }
        }else{
            return false;
        }   
        
        return true;     
    }
    
    public function capacityAdd($SUBSCRIBE_TO, $USER_ID)
    {
        CModule::IncludeModule('iblock');
        
        $db_props = CIBlockElement::GetProperty(SERVICE_IB, $SUBSCRIBE_TO, array("sort" => "asc"), Array("CODE"=>"TEXT"));
        if($ar_props = $db_props->Fetch())
        {
            $gb = $ar_props["VALUE"];
        }
        
        $gb = preg_replace("/[^0-9]/", '', $gb);
        
        if(intval($gb)>0)
        {
            CUserEx::capacityAdd($USER_ID, $gb);
        }
    }
}