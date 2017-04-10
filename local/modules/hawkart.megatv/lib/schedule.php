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
            /*'UF_EPG_FILE_ID' => array(
				'data_type' => 'integer',
			),
            'UF_EPG_FILE' => array(
				'data_type' => '\Hawkart\Megatv\EpgTable',
				'reference' => array('=this.UF_EPG_FILE_ID' => 'ref.ID'),
			),*/
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
			),
            'UF_IS_PART' => array(
				'data_type' => 'boolean',
				'values'    => array(0, 1)
			),
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
    
    public static function slice()
    {
        $last_title = false;
        $schedule_id = false;
        $need_update = false;
        $result = self::getList(array(
            'filter' => array("=UF_ACTIVE" => 1),
            'select' => array("ID", "UF_DATE_END", "UF_DATE_START", "UF_TITLE" => "UF_PROG.UF_TITLE"),
            'order' => array("UF_DATE_START" => "ASC")
        ));
        while ($row = $result->fetch())
        {   
            $date_end = $row["UF_DATE_END"]->toString();
            $date_start = $row["UF_DATE_START"]->toString();
            $diff = (strtotime($date_end) - strtotime($date_start))/3600;
            if($diff>3)
            {
                $date_start_h2 = strtotime("+2 hours", strtotime($date_start));
                $date_start_h2 = date("d.m.Y H:i:s", $date_start_h2);
                
                self::update($row["ID"], array(
                    "UF_DATE_END" => new \Bitrix\Main\Type\DateTime( date("Y-m-d H:i:s", strtotime($date_start_h2)), 'Y-m-d H:i:s')
                ));
                //echo $date_start." ".$date_end."<br />";
                //echo $date_start_h2."<br />";
                
                do
                {
                    $date_start_h2 = strtotime("+2 hours", strtotime($date_start));
                    $date_start_h2 = date("d.m.Y H:i:s", $date_start_h2);
                    
                    $date_end_h2 = strtotime("+2 hours", strtotime($date_start_h2));
                    $date_end_h2 = date("d.m.Y H:i:s", $date_end_h2);
                    
                    if(strtotime($date_end_h2)>=strtotime($date_end))
                    {
                        $date_end_h2 = $date_end;
                    }
                    
                    //echo $date_start_h2." ".$date_end_h2."<br />"; die();
                    
                    self::copy($row["ID"], array(
                        "UF_DATE_START" => new \Bitrix\Main\Type\DateTime( date("Y-m-d H:i:s", strtotime($date_start_h2)), 'Y-m-d H:i:s'),
                        "UF_DATE_END" => new \Bitrix\Main\Type\DateTime( date("Y-m-d H:i:s", strtotime($date_end_h2)), 'Y-m-d H:i:s'),
                        "UF_IS_PART" => 1,
                        "UF_ACTIVE" => 1,
                        "UF_CODE" => $row["UF_TITLE"]." - ". date("Y-m-d H:i:s", strtotime($date_start_h2)),
                    ));
                    
                    $date_start = $date_start_h2;
                    
                    
                } while(strtotime($date_end_h2)<strtotime($date_end));
            }
        }
    }
    
    public static function copy($id, $arFields)
    {
        $result = self::getById($id);
        $row = $result->fetch();
        $row = array_merge($row, $arFields);
        unset($row["ID"]);

        $result = self::add($row);
        if ($result->isSuccess())
        {
            $result->getId();
        }else{
            print_r($result->getErrorMessages());
        }
    }
    
    public static function getRecommend($request)
    {
        global $USER;
        $arResult["PROGS"] = array();
        $dateStart = substr($request["date"], 0, 10).date(" H:i:s");
        $dateStart = date("Y-m-d H:i:s", strtotime($dateStart));
        $dateEnd = date("Y-m-d H:i:s", strtotime("+1 day", strtotime($dateStart)));

        $arFilter = array(
            "=UF_PROG.UF_ACTIVE" => 1,
            ">=UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'),
            "<UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateEnd, 'Y-m-d H:i:s'),
            "!=UF_PROG.UF_CATEGORY" => "Новости"
        );
        
        if(!empty($request["category"]))
        {
            $arFilter["=UF_PROG.UF_CATEGORY"] = htmlspecialcharsbx(urldecode($request["category"]));
        }
        
        if(!$USER->IsAuthorized())
        {
            $arFilter["=UF_CHANNEL_ID"] = ChannelTable::getActiveIdByCity();
        }
        else
        {
            $arProgByUsers = array();
            $arRecordStatus = RecordTable::getListStatusesByUser();
            $recording_ids = array();
            foreach($arRecordStatus["RECORDING"] as $schedule_id => $arRecord)
            {
                $recording_ids[] = $schedule_id;
            }
            
            $arFilter = array(">=UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'));
            $arFilter["=UF_CHANNEL_ID"] = ChannelTable::getActiveIdByCityByUser();
    
            if(count($recording_ids)>0)
                $arFilter["!=ID"] = $recording_ids;
                
            $arFilter["=ID"] = \Hawkart\Megatv\CStat::getRecommendSchedules($USER->GetID());
            
            if(!empty($request["category"]))
            {
                $arFilter["=UF_PROG.UF_CATEGORY"] = htmlspecialcharsbx(urldecode($request["category"]));
            }
            
            /*$obCache = new \CPHPCache;
            if( $obCache->InitCache(86400, "userStatSorted-".$USER->GetID(), "/user-stat/"))
            {
            	$arRecommendSorted = $obCache->GetVars();
            }
            elseif($obCache->StartDataCache())
            {
                $arRecommend = CStat::getRecommend($USER->GetID());
                
                $arRecommendSorted = array();
                $key = 0;
                
                if(count($arRecommend)>0)
                {
                    do {
                        
                        $countThree = 0;
                        foreach($arRecommend as $by_what=>$epg_ids)
                        {
                            $value = str_replace('"', "", $epg_ids[$key]);
                            if(intval($value)>0 && !in_array($value, $arRecommendSorted))
                            {
                                $arRecommendSorted[] = $value;
                            }
                            
                            if(intval($value)>0)
                            {
                                $countThree++;
                            }
                        }
    
                        $key++;
       
                    } while ($countThree > 0);
                }
                $obCache->EndDataCache($arRecommendSorted); 
            }
            $arFilter["=UF_PROG.UF_EPG_ID"] = $arRecommendSorted;
            $arFilter[] = new \Bitrix\Main\DB\SqlExpression('@ IN (UF_PROG.UF_EPG_ID)', $arRecommendSorted);*/
        }
        
        $arNav = array(
            "limit" => intval($request["limit"]), 
            "offset" => intval($request["offset"])
        );
        $arOrder = array("UF_PROG.UF_RATING" => "DESC");
        $arGroup = array('UF_PROG.UF_EPG_ID');
        return self::getListModel($arFilter, $arNav, $arOrder, $arGroup);
    }
    
    public static function getSimilar($id, $arParams = array())
    {
        /**
         * Get sid & category for schedule
         */
        $arFilter = array("=ID" => $id);
        $arSelect = array(
            "CATEGORY" => "UF_PROG.UF_CATEGORY", "SID" => "UF_PROG.UF_EPG_ID"
        );
        $result = self::getList(array(
            'filter' => $arFilter,
            'select' => $arSelect,
            'limit' => 1
        ));
        $arProg = $result->fetch();
        
        /**
         * Get similar list
         */
        $arDate = \CTimeEx::getDateTimeFilter($arTime["SERVER_DATETIME"]);
        $dateStart = date("Y-m-d H:i:s");
        $arChannelsActive = ChannelTable::getActiveIdByCityByUser();
        $arFilter = array(
            array(
                "LOGIC" => "OR",
                array("UF_PROG.UF_EPG_ID" => $arProg["SID"]),
                array("UF_PROG.UF_CATEGORY" => $arProg["CATEGORY"]),
            ),
            //"=UF_PROG.UF_EPG_ID" => $arProg["SID"],
            "=UF_CHANNEL_ID" => $arChannelsActive,
            ">=UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'),
        );
        $arNav = array(
            "limit" => intval($arParams["limit"]), 
            "offset" => intval($arParams["offset"])
        );
        return self::getListModel($arFilter, $arNav);
    }
    
    public static function getSimilarByProgId($id, $arParams = array())
    {
        /**
         * Get sid & category for schedule
         */
        $arFilter = array("=ID" => $id);
        $arSelect = array(
            "CATEGORY" => "UF_CATEGORY", "SID" => "UF_EPG_ID"
        );
        $result = ProgTable::getList(array(
            'filter' => $arFilter,
            'select' => $arSelect,
            'limit' => 1
        ));
        $arProg = $result->fetch();
        
        /**
         * Get similar list
         */
        $arDate = \CTimeEx::getDateTimeFilter($arTime["SERVER_DATETIME"]);
        $dateStart = date("Y-m-d H:i:s");
        $arChannelsActive = ChannelTable::getActiveIdByCityByUser();
        $arFilter = array(
            array(
                "LOGIC" => "OR",
                array("UF_PROG.UF_EPG_ID" => $arProg["SID"]),
                array("UF_PROG.UF_CATEGORY" => $arProg["CATEGORY"]),
            ),
            //"=UF_PROG.UF_EPG_ID" => $arProg["SID"],
            "=UF_CHANNEL_ID" => $arChannelsActive,
            ">=UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'),
        );
        $arNav = array(
            "limit" => intval($arParams["limit"]), 
            "offset" => intval($arParams["offset"])
        );
        return self::getListModel($arFilter, $arNav);
    }
    
    public static function getListModel($arFilter, $arNav = array(), $arOrder = array(), $arGroup = array())
    {
        global $USER;
        
        /**
         * Get records statuses by user
         */
        $arRecordsStatuses = RecordTable::getListStatusesByUser();
        
        if(empty($arOrder))
        {
            $arOrder = array("UF_DATE_START" => "ASC");
        }
        
        /**
         * Nav
         */
        $limit = 10;
        if(intval($arNav["limit"])>0)
            $limit = intval($arNav["limit"]);
           
        $offset = 0;
        if(intval($arNav["offset"])>0)
            $offset = intval($arNav["offset"]); 
        
        $arSelect = array(
            "ID", "UF_DATE_START", "UF_DATE_END", "UF_DATE", "UF_CHANNEL_ID", "PROG_ID" => "UF_PROG_ID",
            "UF_PROG_CODE" => "UF_PROG.UF_CODE", "UF_TITLE" => "UF_PROG.UF_TITLE", 
            "UF_SUB_TITLE" => "UF_PROG.UF_SUB_TITLE", "UF_IMG_PATH" => "UF_PROG.UF_IMG.UF_PATH",
            "UF_CHANNEL_CODE" => "UF_CHANNEL.UF_BASE.UF_CODE", "RATING" => "UF_PROG.UF_RATING",
            "UF_CATEGORY" => "UF_PROG.UF_CATEGORY", "UF_IMAGES" => "UF_PROG.UF_IMG_LIST"
        );
        
        $arGetList = array(
            'filter' => $arFilter,
            'select' => $arSelect,
            'limit' => $limit,
            'offset' => $offset,
            'order' => $arOrder
        );
        
        if(count($arGroup)>0)
            $arGetList["group"] = $arGroup;
        
        $obCache = new \CPHPCache;
        if( $obCache->InitCache(600, serialize($arGetList), "/getListModel/"))
        {
        	$arResult["PROGS"] = $obCache->GetVars();
        }
        elseif($obCache->StartDataCache())
        {
            $result = self::getList($arGetList);
            while ($arSchedule = $result->fetch())
            {
                $arSchedule["UF_DATE_START"] = $arSchedule["DATE_START"] = \CTimeEx::dateOffset($arSchedule['UF_DATE_START']->toString());
                $arSchedule["UF_DATE_END"] = $arSchedule["DATE_END"] = \CTimeEx::dateOffset($arSchedule['UF_DATE_END']->toString());
                $arSchedule["UF_DATE"] = $arSchedule["DATE"] = substr($arSchedule["DATE_START"], 0, 10);
                $arSchedule["DETAIL_PAGE_URL"] = "/channels/".$arSchedule["UF_CHANNEL_CODE"]."/".$arSchedule["UF_PROG_CODE"]."/?event=".$arSchedule["ID"];
                $arResult["PROGS"][] = $arSchedule;
            }
            $obCache->EndDataCache($arResult["PROGS"]); 
        }
        
        $arResult["CATEGORIES"] = array();
        if($USER->IsAuthorized())
        {
            $arStat = CStat::getByUser($USER->GetID());
            foreach($arStat["CATS"] as $category => $id)
            {
                $str = \CDev::translit($category, "ru", array("replace_space"=>"-", "replace_other"=>"-"));
                $arResult["CATEGORIES"][$category] = $str; 
            }
        }else{
            foreach($arResult["PROGS"] as $key=>$arProg)
            {
                $category = $arProg["UF_CATEGORY"];
                $str = \CDev::translit($category, "ru", array("replace_space"=>"-", "replace_other"=>"-"));
                $arResult["CATEGORIES"][$category] = $str;
            }
        }
        
        foreach($arResult["PROGS"] as $arSchedule)
        {
            $time = substr($arSchedule['UF_DATE_START'], 11, 5);       
            $arStatus = CScheduleTemplate::status($arSchedule, $arRecordsStatuses);
            $status = $arStatus["status"];
            //$img_path = CFile::getCropedPath($arSchedule["UF_IMG_PATH"], array(288, 288));
            $img_path = \Hawkart\Megatv\CImage::getImageByClass($arSchedule["UF_IMAGES"], "one");
            if($status=="viewed")
            {
                $img_path = SITE_TEMPLATE_PATH."/ajax/img_grey.php?&path=".urlencode($_SERVER["DOCUMENT_ROOT"].$img_path);
            }
            
            $_arItem = array(
                "id" => $arSchedule["ID"],
                "prog_id" => $arSchedule["PROG_ID"],
                "channel_id" => $arSchedule["UF_CHANNEL_ID"],
                "time" => $time,
            	"date" => substr($arSchedule["DATE_START"], 0, 10),
                "date_start" => $arSchedule["DATE_START"],
                "date_end" => $arSchedule["DATE_END"],
            	"link" => $arSchedule["DETAIL_PAGE_URL"],
            	"name" => CScheduleTemplate::cutName(ProgTable::getName($arSchedule), 35),
            	"on_air" => $pointer,
                "image" => $img_path,
                "status" => "status-".$status,
                "category" => array(
                    "link" => $arResult["CATEGORIES"][$arSchedule["UF_CATEGORY"]],
                    "name" => $arSchedule["UF_CATEGORY"]
                ),
                "rating" => $arSchedule["RATING"],
                "is_clone" => false,
                "is_adv" => false               
            );
            
            $arItems[] = $_arItem;
            unset($_arItem);
        }
        
        unset($arResult["PROGS"]);
        
        $maxRecord = self::getList([
           'select' => [new \Bitrix\Main\Entity\ExpressionField('CNT', 'COUNT(*)')],
           'filter' => $arFilter,
        ])->fetch()['CNT'];
        
        return array(
            "items" => $arItems,
            "pageNum" => ceil($maxRecord/$limit)
        );
    }
    
    public static function deleteByEpgId($epg_id)
    {
        global $DB;
        $DB->Query("DELETE FROM ".self::getTableName()." WHERE UF_EPG_ID='".$epg_id."'");
    }
}