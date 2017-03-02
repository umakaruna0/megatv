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
    
    public static function subscribeByEpg($serial_epg_id)
    {
        global $USER;
        $status = "error";
        $USER_ID = $USER->GetID();
        $rsUser = \CUser::GetByID($USER_ID);
        $arUser = $rsUser->Fetch();
        
        //money check
        $budget = \CUserEx::getBudget($USER_ID);
        if($budget<0)
        {
            return array("status"=>"error", "message"=> "Для записи передачи пополните счет.");
        }
        
        //Список записей пользователя
        $arRecords = array();
        $result = \Hawkart\Megatv\RecordTable::getList(array(
            'filter' => array(
                "=UF_USER_ID" => $USER_ID,
            ),
            'select' => array("ID", "SID" => "UF_PROG.UF_EPG_ID", "UF_EPG_ID"),
            'limit' => 1
        ));
        while ($arRecord = $result->fetch())
        {
            $arRecords[] = $arRecord["UF_EPG_ID"];
        }
        
        //Получим ид сериала для подписки
        $result = \Hawkart\Megatv\SerialTable::getList(array(
            'filter' => array("=UF_EPG_ID" => $serial_epg_id),
            'select' => array("ID"),
            'limit' => 1
        ));
        $arSerial = $result->fetch();
        $SID = $arSerial["ID"];
        
        //Проверим подисан ли пользователь на сериал
        $result = \Hawkart\Megatv\SerialSubscribeTable::getList(array(
            'filter' => array(
                "=UF_USER_ID" => $USER_ID, 
                "=UF_SERIAL_ID" => $SID, 
            ),
            'select' => array("ID", "UF_ACTIVE"),
            'limit' => 1
        ));
        if ($arRecord = $result->fetch())
        {
            if(intval($arRecord["UF_ACTIVE"])==1)
            {
                return array("status"=>"error", "message"=> "Вы уже подписаны на данную передачу.");
            }
        }else{
    
            //Add subscribe to serial
            \Hawkart\Megatv\SerialSubscribeTable::add(array(
                'UF_USER_ID' => $USER_ID,
                'UF_SERIAL_ID' => $SID,
                'UF_ACTIVE' => 1
            ));
            
            $result = \Hawkart\Megatv\ScheduleTable::getList(array(
                'filter' => array(
                    "=UF_PROG.UF_EPG_ID" => $serial_epg_id
                ),
                'select' => array(
                    "ID", "UF_DATE_START", "UF_DATE_END", "UF_DATE", "UF_CHANNEL_BASE_ID" => "UF_CHANNEL.UF_BASE_ID", "UF_PROG_ID",
                    "UF_CHANNEL_EPG_ID" => "UF_CHANNEL.UF_BASE.UF_EPG_ID", "UF_IMG_PATH" => "UF_PROG.UF_IMG.UF_PATH",
                    "UF_PROG_EPG_ID" => "UF_PROG.UF_EPG_ID", "UF_EPG_ID", "UF_CHANNEL_ID"
                )
            ));
            while($arSchedule = $result->fetch())
            {
                $arSchedule["UF_DATE_START"] = $arSchedule['UF_DATE_START']->toString();
                $arSchedule["UF_DATE_END"] = $arSchedule['UF_DATE_END']->toString();
                
                if(!in_array($arSchedule["UF_EPG_ID"], $arRecords))
                {
                    $duration = strtotime($arSchedule["UF_DATE_END"])-strtotime($arSchedule["UF_DATE_START"]);
                    $minutes = ceil($duration/60);
                    $gb = $minutes*18.5/1024;
                    $busy = floatval($arUser["UF_CAPACITY_BUSY"])+$gb;
                    
                    if($busy<floatval($arUser["UF_CAPACITY"]))
                    {
                        \Hawkart\Megatv\RecordTable::create($arSchedule);
                                 
                        //Inc rating for prog
                        \Hawkart\Megatv\ProgTable::addByEpgRating($arSchedule["UF_PROG_EPG_ID"], 1);
                        
                        //change capacity for user 
                        $cuser = new \CUser;
                        $cuser->Update($arUser["ID"], array("UF_CAPACITY_BUSY"=>$busy));
                        $arUser["UF_CAPACITY_BUSY"] = $busy;
                        
                        /**
                         * Данные в статистику
                         */                
                        \Hawkart\Megatv\CStat::addByShedule($arSchedule["ID"], "record");
            
                        $status = "success";
                    }
                }
            }
        } 
        
        return array("status"=>$status);
    }
    
    /**
     * Добавляем на запись все программы сериалов, на которые подписан пользователь
     */
    public static function subscribeForUsers($USER_ID)
    {
        global $USER;
        $status = "error";
        $rsUser = \CUser::GetByID($USER_ID);
        $arUser = $rsUser->Fetch();
        $busy = floatval($arUser["UF_CAPACITY_BUSY"]);
        
        $budget = \CUserEx::getBudget($USER_ID);
        if($budget<0)
        {
            return array("status"=>"error", "message"=> "Для записи передачи пополните счет.");
        }
        
        //Список записей пользователя
        $arRecords = array();
        $result = \Hawkart\Megatv\RecordTable::getList(array(
            'filter' => array(
                "=UF_USER_ID" => $USER_ID,
            ),
            'select' => array("UF_EPG_ID"),
            'limit' => 1
        ));
        while ($arRecord = $result->fetch())
        {
            $arRecords[] = $arRecord["UF_EPG_ID"];
        }
        
        //Находим все подписки пользователя на сериал
        $resultSerial = \Hawkart\Megatv\SerialSubscribeTable::getList(array(
            'filter' => array(
                "=UF_USER_ID" => $USER_ID,
                "=UF_ACTIVE" => 1
            ),
            'select' => array("ID", "EPG_ID" => "UF_SERIAL.UF_EPG_ID"),
            'limit' => 1
        ));
        while ($arSerial = $resultSerial->fetch())
        {        
            $serial_epg_id = $arSerial["EPG_ID"];
            
            $result = \Hawkart\Megatv\ScheduleTable::getList(array(
                'filter' => array(
                    "=UF_PROG.UF_EPG_ID" => $serial_epg_id
                ),
                'select' => array(
                    "ID", "UF_DATE_START", "UF_DATE_END", "UF_DATE", "UF_CHANNEL_BASE_ID" => "UF_CHANNEL.UF_BASE_ID", "UF_PROG_ID",
                    "UF_CHANNEL_EPG_ID" => "UF_CHANNEL.UF_BASE.UF_EPG_ID", "UF_IMG_PATH" => "UF_PROG.UF_IMG.UF_PATH",
                    "UF_PROG_EPG_ID" => "UF_PROG.UF_EPG_ID", "UF_EPG_ID", "UF_CHANNEL_ID"
                )
            ));
            while($arSchedule = $result->fetch())
            {
                $arSchedule["UF_DATE_START"] = $arSchedule['UF_DATE_START']->toString();
                $arSchedule["UF_DATE_END"] = $arSchedule['UF_DATE_END']->toString();
                
                //проверяем поставлена ли на запись
                if(!in_array($arSchedule["UF_EPG_ID"], $arRecords))
                {
                    $duration = strtotime($arSchedule["UF_DATE_END"])-strtotime($arSchedule["UF_DATE_START"]);
                    $minutes = ceil($duration/60);
                    $gb = $minutes*18.5/1024;
                    $busy += $gb;
                    
                    if($busy<floatval($arUser["UF_CAPACITY"]))
                    {
                        \Hawkart\Megatv\RecordTable::create($arSchedule);
                                 
                        //Inc rating for prog
                        \Hawkart\Megatv\ProgTable::addByEpgRating($arSchedule["UF_PROG_EPG_ID"], 1);
                        
                        //change capacity for user 
                        $cuser = new \CUser;
                        $cuser->Update($arUser["ID"], array("UF_CAPACITY_BUSY"=>$busy));
                        $arUser["UF_CAPACITY_BUSY"] = $busy;
                        
                        /**
                         * Данные в статистику
                         */                
                        \Hawkart\Megatv\CStat::addByShedule($arSchedule["ID"], "record");
            
                        $status = "success";
                    }
                }
            }
        }
        
        $SID = $arSerial["ID"];
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