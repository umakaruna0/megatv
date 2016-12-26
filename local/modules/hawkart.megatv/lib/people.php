<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class PeopleTable extends Entity\DataManager
{
	/**
	 * Returns DB table name for entity
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'hw_people';
	}
    
    public static function getKinopoiskLinkByName($name)
    {
        $result = \Hawkart\Megatv\PeopleTable::getList(array(
            'filter' => array("=UF_TITLE" => $name),
            'select' => array("UF_KINOPOISK_LINK")
        ));
        if ($row = $result->fetch())
        {
            return $row["UF_KINOPOISK_LINK"];
        }
        
        return false;
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
			'UF_TITLE' => array(
				'data_type' => 'string',
                'required'  => true
			),
            'UF_EPG_ID' => array(
				'data_type' => 'string',
                'required'  => true
			),
            'UF_ROLE_ID' => array(
				'data_type' => 'integer',
                'required'  => true
			),
            'UF_ROLE' => array(
				'data_type' => 'Local\Hawkart\Megatv\RoleTable',
				'reference' => array('=this.UF_ROLE_ID' => 'ref.ID'),
			),
            'UF_KINOPOISK_LINK' => array(
				'data_type' => 'string',
			),
		);
	}
}