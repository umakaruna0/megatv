<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class SerialTable extends Entity\DataManager
{
    protected static $serials_dir = "/upload/serials/";
    
	/**
	 * Returns DB table name for entity
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'hw_serial';
	}
    
    /**
     * Connect serials & channels by prog schedules
     */
    public static function connectToChannels()
    {
        $arSerials = array();
        $result = self::getList(array(
            'filter' => array("!UF_EPG_ID" => false),
            'select' => array("UF_EPG_ID", "ID", "UF_CHANNEL_ID")
        ));
        while ($row = $result->fetch())
        {
            $arSerials[$row["UF_EPG_ID"]] = $row;
        }
        
        $arSerialChannels = array();
        $result = ScheduleTable::getList(array(
            'filter' => array(
                "=UF_CHANNEL.UF_BASE.UF_ACTIVE" => 1,
                "=UF_PROG.UF_ACTIVE" => 1,
            ),
            'select' => array(
                "SID" => "UF_PROG.UF_EPG_ID", "CHANNEL_BASE_ID" => "UF_CHANNEL.UF_BASE_ID"
            )
        ));
        while ($arSchedule = $result->fetch())
        { 
            $arSerialChannels[$arSchedule["SID"]][] = $arSchedule["CHANNEL_BASE_ID"];
        }
        
        foreach($arSerialChannels as $epg_id => $arChannels)
        {
            $arSerial = $arSerials[$epg_id];
            
            if(intval($arSerial["ID"])>0)
            {
                $channel_ids = array_merge((array)$arSerial["UF_CHANNEL_ID"], $arChannels);
                $channel_ids = array_unique($channel_ids);
                self::update($arSerial["ID"], array("UF_CHANNEL_ID" => $channel_ids));
            }
        }
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
            'UF_CODE' => array(
				'data_type' => 'string',
                'required'  => true
			),
			'UF_DESC' => array(
				'data_type' => 'text',
				'title'     => Localization\Loc::getMessage('prog_entity_desc_field'),
			),
            'UF_EPG_ID' => array(
				'data_type' => 'string',
                'required'  => true
			),
            'UF_IMG_ID' => array(
				'data_type' => 'integer'
			),
            'UF_IMG' => array(
				'data_type' => '\Hawkart\Megatv\ImageTable',
				'reference' => array('=this.UF_IMG_ID' => 'ref.ID'),
			),
            'UF_CHANNEL_ID' => array(
				'data_type' => 'string',
                'serialized' => true
			),
            'UF_CHANNEL' => array(
				'data_type' => '\Hawkart\Megatv\ChannelTable',
				'reference' => array('=this.UF_CHANNEL_ID' => 'ref.ID'),
			),
            'UF_EXTERNAL_TITLE' => array(
				'data_type' => 'string'
			),
            'UF_EXTERNAL_URL' => array(
				'data_type' => 'string'
			),
            'UF_SOURCES' => array(
				'data_type' => 'text'
			)
		);
	}
    
    public static function getFilePathBySerial($serial_epg_id)
    {
        return $_SERVER["DOCUMENT_ROOT"].self::$serials_dir.$serial_epg_id.".json";
    }
    
    public static function saveToFile($array, $file)
    {
        file_put_contents($file, json_encode($array));
    }
    
    public static function getListByFile($file)
    {
        $txt = file_get_contents($file);
        $json = json_decode($txt, true);
        
        return $json;
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