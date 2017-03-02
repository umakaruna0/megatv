<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class SerialSubscribeTable extends Entity\DataManager
{
	/**
	 * Returns DB table name for entity
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'hw_serial_subscribe';
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
            'UF_USER_ID' => array(
				'data_type' => 'integer',
                'required'  => true
			),
            'UF_ACTIVE' => array(
				'data_type' => 'boolean',
				'values'    => array(0, 1),
				'required'  => true
			),
            'UF_SERIAL_ID' => array(
				'data_type' => 'integer',
			),
            'UF_SERIAL' => array(
				'data_type' => '\Hawkart\Megatv\SerialTable',
				'reference' => array('=this.UF_SERIAL_ID' => 'ref.ID'),
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