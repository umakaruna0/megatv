<?php

namespace Hawkart\Megatv;

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
     * Change data before adding
     * 
     * @return object 
     */
    public static function onBeforeAdd(Entity\Event $event)
    {
        $result = new Entity\EventResult;
        $data = $event->getParameter("fields");

        if (isset($data['UF_CODE']))
        {
            $arParams = array("replace_space"=>"-", "replace_other"=>"-");
            $code = \CDev::translit(trim($data["UF_CODE"]), "ru", $arParams);
            $result->modifyFields(array('UF_CODE' => $code));
        }

        return $result;
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
				'title'     => Localization\Loc::getMessage('schedule_entity_active_field'),
				'values'    => array(0, 1),
				'required'  => true
			),
            'UF_EPG_ID' => array(
				'data_type' => 'string',
				'title'     => Localization\Loc::getMessage('schedule_entity_epg_id_field'),
                'required'  => true
			),
            'UF_CHANNEL_ID' => array(
				'data_type' => 'integer',
				'title'     => Localization\Loc::getMessage('schedule_entity_channel_id_field'),
                'required'  => true
			),
            'UF_CHANNEL' => array(
				'data_type' => '\Hawkart\Megatv\ChannelTable',
				'reference' => array('=this.UF_CHANNEL_ID' => 'ref.ID'),
			),
            'UF_PROG_ID' => array(
				'data_type' => 'integer',
				'title'     => Localization\Loc::getMessage('schedule_entity_prog_id_field'),
                'required'  => true
			),
            'UF_PROG' => array(
				'data_type' => '\Hawkart\Megatv\ProgTable',
				'reference' => array('=this.UF_PROG_ID' => 'ref.ID'),
			),
            'UF_EPG_FILE_ID' => array(
				'data_type' => 'integer',
			),
            'UF_EPG_FILE' => array(
				'data_type' => '\Hawkart\Megatv\EpgTable',
				'reference' => array('=this.UF_EPG_FILE_ID' => 'ref.ID'),
			),
            'UF_DATE' => array(
				'data_type' => 'date',
				'title'     => Localization\Loc::getMessage('schedule_entity_date_field'),
                'required'  => true
			),
            'UF_DATE_START' => array(
				'data_type' => 'datetime',
				'title'     => Localization\Loc::getMessage('schedule_entity_date_start_field'),
                'required'  => true
			),
            'UF_DATE_END' => array(
				'data_type' => 'datetime',
				'title'     => Localization\Loc::getMessage('schedule_entity_date_end_field'),
                'required'  => true
			),
            'UF_CODE' => array(
				'data_type' => 'string',
				'title'     => Localization\Loc::getMessage('schedule_entity_code_field'),
                'required'  => true
			),
            'UF_DATETIME_CREATE' => array(
				'data_type' => 'datetime'
			),
            'UF_DATETIME_EDIT' => array(
				'data_type' => 'datetime'
			)
		);
	}
    
    /**
     * Delete schedule before prev day
     */
    public static function deleteOld()
    {
        $result = self::getList(array(
            'filter' => array(
                "<UF_DATE" => new \Bitrix\Main\Type\Date(date('Y-m-d', strtotime('-2 day')), 'Y-m-d')
            ),
            'select' => array("ID")
        ));
        while ($row = $result->fetch())
        {
            ScheduleTable::delete($row["ID"]);
        }
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
    
    public static function connectByTitle()
    {
        $last_title = false;
        $schedule_id = false;
        $need_update = false;
        $result = self::getList(array(
            'filter' => array("=UF_CHANNEL_ID" => 98),
            'select' => array("ID", "UF_DATE_END", "UF_PROG_TITLE" => "UF_PROG.UF_TITLE"),
            'order' => array("UF_DATE_START" => "ASC")
        ));
        while ($row = $result->fetch())
        {   
            self::update($row["ID"], array(
                "UF_ACTIVE" => 1
            ));
            
            if($last_title==$row["UF_PROG_TITLE"])
            {
                $need_update = true;
                
                self::update($row["ID"], array(
                    "UF_ACTIVE" => 0
                ));
            }else{
                
                if($need_update)
                {
                    self::update($schedule_id, array(
                        "UF_DATE_END" => new \Bitrix\Main\Type\DateTime( date("Y-m-d H:i:s", strtotime($date_end)), 'Y-m-d H:i:s')
                    ));
                }
                   
                $schedule_id = false;
                $need_update = false;
            }  
            
            $last_title = $row["UF_PROG_TITLE"];
            $date_end = $row["UF_DATE_END"]->toString();
            
            if(!$schedule_id)
                $schedule_id = $row["ID"];
        }
    }
}