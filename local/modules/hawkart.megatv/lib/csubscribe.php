<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class CSubscribe
{
    function __construct($type)
    {
        $this->type = $type;
    }

    public function setUserSubscribe($SUBSCRIBE_TO, $USER_ID = false )
    {
        global $USER;

        if(!$USER_ID)
            $USER_ID = $USER->GetID();

        $dt = new \Bitrix\Main\Type\DateTime(date('Y-m-d H:i:s', time()),'Y-m-d H:i:s');
        $data = array(
           'UF_USER_ID' => $USER_ID,
           'UF_DATE_FROM' => $dt,
           'UF_ACTIVE' => 1,
           'UF_DATETIME_TO' => new \Bitrix\Main\Type\Datetime(date('Y-m-d 03:30:00', strtotime('+1 day')), 'Y-m-d H:i:s')
        );
        
        if($this->type=="CHANNEL")
        {
            $data['UF_CHANNEL_ID'] = $SUBSCRIBE_TO;
        }else{
            $data['UF_SERVICE_ID'] = $SUBSCRIBE_TO;
        }
        
        //снимаемм деньги
        if(!$this->pay($SUBSCRIBE_TO, $USER_ID))
            return false;
        
        //добавляем ГБ
        if($this->type!="CHANNEL")
            $this->capacityAdd($SUBSCRIBE_TO, $USER_ID); 
        
        
        $result = \Hawkart\Megatv\SubscribeTable::add($data);
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
        /*if($arFields["UF_ACTIVE"]=="Y")
            $arFields["UF_ACTIVE"] = 1;
            
        if($arFields["UF_ACTIVE"]=="N")
            $arFields["UF_ACTIVE"] = 0;*/
        
        $result = \Hawkart\Megatv\SubscribeTable::update($ID, $arFields);    
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
        if(!$USER_ID)
            $USER_ID = $USER->GetID();
            
        if($this->type=="CHANNEL")
        {
            $result = \Hawkart\Megatv\ChannelTable::getById($SUBSCRIBE_TO);
            if ($arService = $result->fetch())
            {
                $price = IntVal($arService["UF_PRICE_H24"]);
            }
        }else{
            $result = \Hawkart\Megatv\ServiceTable::getById($SUBSCRIBE_TO);
            if ($arService = $result->fetch())
            {
                $price = IntVal($arService["UF_PRICE"]);
            }
        }
        
        if($price==0)
            return true; 
        
        if($price>0 && \CSaleAccountEx::budget($USER_ID)>$price)
        {
            $comment = "Оплата подписки на ".$arService["TTILE"];
            if(!\CSaleAccountEx::transaction((-1)*$price, $USER_ID, $comment))
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
        $result = \Hawkart\Megatv\ServiceTable::getById($SUBSCRIBE_TO);
        if ($arService = $result->fetch())
        {
            $gb = IntVal($arService["UF_TEXT"]);
        }
        
        $gb = preg_replace("/[^0-9]/", '', $gb);
        
        if(intval($gb)>0)
        {
            \CUserEx::capacityAdd($USER_ID, $gb);
        }
    }
}