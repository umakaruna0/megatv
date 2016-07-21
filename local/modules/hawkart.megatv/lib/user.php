<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class UserTable extends Entity\DataManager
{
    /**
	 * Set primary key from 1
	 *
	 * @return string
	 */
    public static function updatePrimary()
    {
        return "ALTER TABLE ".self::getTableName()." AUTO_INCREMENT=1";
    }

	/**
	 * Returns DB table name for entity
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'hw_user_stat';
	}
    
    /**
	 * Returns entity map definition
	 *
	 * @return array
	 */
	public static function getMap()
	{
        //ALTER TABLE hw_user_stat MODIFY UF_RECOMMEND LONGTEXT;
       
		return array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true
			),
            'UF_USER_ID' => array(
				'data_type' => 'integer',
				'required'  => true
			),
            'UF_RECOMMEND' => array(
				'data_type' => 'text',
			),
            'UF_STATISTIC' => array(
				'data_type' => 'text',
			)
		);
	}
    
    /**
     * Clear table
     */
    public static function deleteAll()
    {
        global $DB;
        $DB->Query("DELETE FROM ".self::getTableName(), false);
        $DB->Query("ALTER TABLE ".self::getTableName()." AUTO_INCREMENT=1", false);
    }
}