<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class CBilling
{
    /**
     * Billing by all users at 3-30
     */
    public static function dailyAllUsers()
    {
        global $DB;
        $table = \Hawkart\Megatv\SubscribeTable::getTableName();
        $time_update = "03:30:00";
        $datetime = new \Bitrix\Main\Type\Datetime(date("Y-m-d ".$time_update), 'Y-m-d H:i:s');
        $datetime_next_date = new \Bitrix\Main\Type\Datetime(date('Y-m-d'.$time_update, strtotime('+1 day')), 'Y-m-d H:i:s');
        $datetime_from_db = date("d.m.Y ".$time_update);
        $datetime_to_db = date("d.m.Y ".$time_update, strtotime('+1 day'));
        
        
        /**
         * Get all users
         */
        $arUsers = array();
        $arFilter = array("ACTIVE" => "Y");
        $rsUsers = \CUser::GetList(($by="LAST_NAME"), ($order="asc"), $arFilter);
        while($arUser = $rsUsers->GetNext())
        {
            $arUsers[$arUser["ID"]] = $arUser;
        }
        
        /**
         * Get all priced subscribe for all users
         */
        $arSubscribeUsers = array(); 
        $result = \Hawkart\Megatv\SubscribeTable::getList(array(
            'filter' => array(
                "UF_ACTIVE" => 1,
                ">UF_CHANNEL_ID" => 0,
                ">UF_CHANNEL.UF_PRICE_H24" => 0,
                "<=UF_DATETIME_TO" => $datetime
            ),
            'select' => array("ID", "PRICE" => "UF_CHANNEL.UF_PRICE_H24", "UF_USER_ID")
        ));
        while ($arSub = $result->fetch())
        {
            $arSubscribeUsers[$arSub["UF_USER_ID"]][] = array(
                "ID" => $arSub["ID"],
                "PRICE" => $arSub["PRICE"]
            );
        }

        $result = \Hawkart\Megatv\SubscribeTable::getList(array(
            'filter' => array(
                "UF_ACTIVE" => 1,
                ">UF_SERVICE_ID" => 0,
                ">UF_SERVICE.UF_PRICE" => 0,
                //"<=UF_DATETIME_TO" => $datetime
            ),
            'select' => array("ID", "PRICE" => "UF_SERVICE.UF_PRICE", "UF_USER_ID")
        ));
        while ($arSub = $result->fetch())
        {
            $arSubscribeUsers[$arSub["UF_USER_ID"]][] = array(
                "ID" => $arSub["ID"],
                "PRICE" => $arSub["PRICE"]
            );
        }
        
        /**
         * Make transaction for 1 day for all users
         */
        foreach($arSubscribeUsers as $user_id => $arSubscribes)
        {
            $daily_price = 0;
            foreach($arSubscribes as $arSubscribe)
            {
                $daily_price += $arSubscribe["PRICE"];
            }
            
            $balance = \CUserEx::getBudget($user_id);
            
            if($balance > 0 && $daily_price>0)
            {
                \CSaleAccountEx::transaction((-1)*$daily_price, $user_id, "Оплата услуг за ".date('d.m.Y'));
                $strSql = "UPDATE ".$table." SET UF_DATETIME_TO=curdate() + INTERVAL 1 DAY + INTERVAL 3 HOUR + INTERVAL 30 MINUTE WHERE UF_USER_ID=".$user_id;
                $res = $DB->Query($strSql, false, $err_mess.__LINE__);
                
                //Send email to user to add money to budget for 3 days
                if($balance < $daily_price*2)
                {
                    \CEvent::SendImmediate("NOTIFICATION_PAY_AFTER_DAYS", SITE_ID, array(
                        "EMAIL_TO" => $arUsers[$user_id]["EMAIL"],
                        "PRICE" => $daily_price*5
                    ));
                }
            }
        }
        
    }
}