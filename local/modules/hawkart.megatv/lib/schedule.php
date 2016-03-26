<?php

//namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class ScheduleTable extends Entity\DataManager
{

	/**
	 * Returns DB table name for entity
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'hw_schedule';
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
			'ACTIVE' => array(
				'data_type' => 'boolean',
				'title'     => Localization\Loc::getMessage('schedule_entity_active_field'),
				'values'    => array('N', 'Y'),
				'required'  => true
			),
			'TITLE' => array(
				'data_type' => 'string',
				'title'     => Localization\Loc::getMessage('schedule_entity_title_field'),
                'required'  => true
			),
			'DESC' => array(
				'data_type' => 'text',
				'title'     => Localization\Loc::getMessage('schedule_entity_desc_field'),
			),
            'EPG_ID' => array(
				'data_type' => 'integer',
				'title'     => Localization\Loc::getMessage('schedule_entity_epg_id_field'),
                'required'  => true
			),
            'CHANNEL_ID' => array(
				'data_type' => 'integer',
				'title'     => Localization\Loc::getMessage('schedule_entity_channel_id_field'),
                'required'  => true
			),
            'CHANNEL' => array(
				'data_type' => 'Local\Hawkart\Megatv\Channel',
				'reference' => array('=this.CHANNEL_ID' => 'ref.ID'),
			),
            'PROG_ID' => array(
				'data_type' => 'integer',
				'title'     => Localization\Loc::getMessage('schedule_entity_prog_id_field'),
                'required'  => true
			),
            'PROG' => array(
				'data_type' => 'Local\Hawkart\Megatv\Prog',
				'reference' => array('=this.PROG_ID' => 'ref.ID'),
			),
            'DATE' => array(
				'data_type' => 'date',
				'title'     => Localization\Loc::getMessage('schedule_entity_date_field'),
                'required'  => true
			),
            'DATE_START' => array(
				'data_type' => 'datetime',
				'title'     => Localization\Loc::getMessage('schedule_entity_date_start_field'),
                'required'  => true
			),
            'DATE_END' => array(
				'data_type' => 'datetime',
				'title'     => Localization\Loc::getMessage('schedule_entity_date_end_field'),
                'required'  => true
			),
            'CODE' => array(
				'data_type' => 'string',
				'title'     => Localization\Loc::getMessage('schedule_entity_code_field'),
                'required'  => true
			),
			'SORT' => array(
				'data_type' => 'integer',
				'title'     => Localization\Loc::getMessage('schedule_entity_sort_field'),
			),
		);
	}
}