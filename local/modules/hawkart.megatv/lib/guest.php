<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class GuestTable extends Entity\DataManager
{
	/**
	 * Returns DB table name for entity
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'hw_guest';
	}

	/**
	 * Returns entity map definition
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true
			),
            'UF_GUEST_IP' => array(
				'data_type' => 'string',
                'required'  => true
			),
            'UF_CITY_ID' => array(
				'data_type' => 'integer',
                'required'  => true
			),
		);
	}
    
    public static function setCity($city_id)
    {
        $guest_id = $_SESSION["SESS_IP"];
        
        if(!empty($guest_id))
        {
            $arFilter = array("=UF_GUEST_IP" => $guest_id);
            $arSelect = array("ID");
            $result = self::getList(array(
                'filter' => $arFilter,
                'select' => $arSelect,
            ));
            if ($arGuest = $result->fetch())
            {
                self::update($arGuest["ID"], ["UF_CITY_ID" => $city_id]);
            }else{
                self::add([
                    "UF_GUEST_IP" => $guest_id,
                    "UF_CITY_ID" => $city_id
                ]);
            }
        }
    }
    
    public static function getCity($guest_id=false)
    {
        if(!$guest_id)
            $guest_id = $_SESSION["SESS_IP"];
        
        $arFilter = array("=UF_GUEST_IP" => $guest_id);
        $arSelect = array("UF_CITY_ID");
        $result = self::getList(array(
            'filter' => $arFilter,
            'select' => $arSelect,
        ));
        $arGuest = $result->fetch();
        return intval($arGuest["UF_CITY_ID"]);
    }
}