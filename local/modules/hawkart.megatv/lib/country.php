<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class CountryTable extends Entity\DataManager
{
    protected static $rus_id = 15;
    
	/**
	 * Returns DB table name for entity
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'hw_country';
	}
    
    public static function setCountry($country_id)
    {
        global $currentGeo;
        $arSelect = array("ID", "UF_TITLE", "UF_TIMEZONE", "UF_COUNTRY_ID", "COUNTRY_ISO" => "UF_COUNTRY.UF_ISO");
        
        $result = CityTable::getList(array(
            'filter' => array(
                "=UF_COUNTRY_ID" => $country_id,
                "=UF_ACTIVE" => 1,
                "=UF_DEFAULT" => 1
            ),
            'select' => $arSelect,
            'limit' => 1
        ));
        $currentGeo = $_SESSION["USER_GEO"] = $result->fetch();
        
        return $_SESSION["USER_GEO"];
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
            'UF_ACTIVE' => array(
				'data_type' => 'boolean',
				'values'    => array(0, 1)
			),
			'UF_TITLE' => array(
				'data_type' => 'string',
                'required'  => true
			),
            'UF_EPG_ID' => array(
				'data_type' => 'string'
			),
            'UF_ISO' => array(
				'data_type' => 'string'
			),
            'UF_EXIST' => array(
				'data_type' => 'boolean',
				'values'    => array(0, 1)
			)
		);
	}
}