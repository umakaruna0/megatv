<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class RecordTable extends Entity\DataManager
{
    protected static $cut_dir = "/upload/record_cut/";
    
    /**
     * Delete pic after delete record
     */
    public static function onAfterDelete(Entity\Event $event)
    {
        $id = $event->getParameter('id');
        
        $result = self::getList(array(
            'filter' => array(
                "!ID" => $id, 
            ),
            'select' => array(
                "UF_IMG_PATH" => "UF_PROG.UF_IMG.UF_PATH",
            ),
            'limit' => 1
        ));
        $arRecord = $result->fetch();

        if(!empty($arRecord["UF_IMG_PATH"]))
        {
            unlink(CFile::getCropedPath($arRecord["UF_IMG_PATH"], array(300, 300), true));
        }
    }
    
    
	/**
	 * Returns DB table name for entity
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'hw_record';
	}
    
    public static function getListStatusesByUser()
    {
        global $USER, $APPLICATION;
        if($USER->IsAuthorized())
        {
            $arTime =  \CTimeEx::getDatetime();
            $date_now = $arTime["SERVER_DATETIME_WITH_OFFSET"];
            $countRecorded = 0;
            $countInRec = 0;
            $count = 0;
            $arStatusRecording = array();   //записывается
            $arStatusRecorded = array();    //записана, можно просмотреть
            $arStatusViewed = array();    //просмотренна
            $result = self::getList(array(
                'filter' => array(
                    "=UF_USER_ID" => $USER->GetID(), 
                    "=UF_DELETED" => 0
                ),
                'select' => array("ID", "UF_URL", "UF_SCHEDULE_ID", "UF_WATCHED", "UF_PROG_ID",
                "DATE_START" => "UF_SCHEDULE.UF_DATE_START"),
            ));
            while ($arRecord = $result->fetch())
            {
                $shedule_id = $arRecord["UF_SCHEDULE_ID"];
                if(!empty($arRecord["DATE_START"]))
                {
                    $arRecord["DATE_START"] = \CTimeEx::dateOffset($arRecord["DATE_START"]->toString());
                    $minutes = intval(strtotime($date_now)-strtotime($arRecord["DATE_START"]))/60;
                }

                if(intval($shedule_id)>0)
                {
                    if((!\CTimeEx::dateDiff($arRecord["DATE_START"], $date_now) || $minutes<5) && !empty($arRecord["DATE_START"]) && !empty($arRecord["UF_URL"]))
                    {
                        $countInRec++;
                        $arStatusRecording[$shedule_id] = $arRecord;
                        $count++;
                        continue;
                    }
                    
                    if($arRecord["UF_WATCHED"]==1)
                    {
                        $countRecorded++;
                        $arStatusViewed[$shedule_id] = $arRecord;
                    }
                    else if(empty($arRecord["UF_URL"]))
                    {
                        $countInRec++;
                        $arStatusRecording[$shedule_id] = $arRecord;
                    }
                    else if(!empty($arRecord["UF_URL"]))
                    {
                        $countRecorded++;
                        $arStatusRecorded[$shedule_id] = $arRecord;
                    }
                }
                
                $count++;
            }
            $arRecordsStatuses = array(
                "RECORDING" => $arStatusRecording,
                "RECORDED"  => $arStatusRecorded,
                "VIEWED"    => $arStatusViewed
            );
            $APPLICATION->SetPageProperty("ar_record_status", json_encode($arRecordsStatuses));
            $APPLICATION->SetPageProperty("ar_record_in_rec", $countInRec);
            $APPLICATION->SetPageProperty("ar_record_recorded", $countRecorded);
            $APPLICATION->SetPageProperty("ar_record_total", $count);
        }
        
        return $arRecordsStatuses;
    }
    
    
    public static function create($arFields)
    {
        global $USER;
        
        if(!$USER_ID)
            $USER_ID = $USER->GetID();
            
        $start = new \Bitrix\Main\Type\DateTime(date('Y-m-d H:i:s', strtotime($arFields["UF_DATE_START"])), 'Y-m-d H:i:s');
        $end = new \Bitrix\Main\Type\DateTime(date('Y-m-d H:i:s', strtotime($arFields["UF_DATE_END"])), 'Y-m-d H:i:s');
        
        $data = array(
           'UF_USER_ID' => $USER_ID,
           'UF_DATE_START' => $start,
           'UF_DATE_END' => $end,
           'UF_EPG_ID' => $arFields["UF_EPG_ID"],
           'UF_CHANNEL_ID' => $arFields["UF_CHANNEL_ID"],
           'UF_SCHEDULE_ID' => $arFields["ID"],
           'UF_PROG_ID' => $arFields["UF_PROG_ID"],
           'UF_DATETIME_ADD' => new \Bitrix\Main\Type\DateTime(date('Y-m-d H:i:s'), 'Y-m-d H:i:s')
        );
        
        $result = self::add($data);
        if ($result->isSuccess()) 
        {   
            /**
             * Create image croped for record
             * \Hawkart\Megatv\CFile::getCropedPath($arFields["UF_IMG_PATH"], array(300, 300), true)
             */
            $path_from = $arFields["UF_IMG_PATH"];
            $path_parts = pathinfo($arFields["UF_IMG_PATH"]);
            $file_name = $path_parts["filename"];
            $path_to = self::$cut_dir. $file_name. "_300_300.jpg";
        
            CFile::add(array(
                "path_from" => $_SERVER["DOCUMENT_ROOT"]. $path_from,
                "path_to" => $_SERVER["DOCUMENT_ROOT"]. $path_to,
                "width" => 300,
                "height" =>  300
            ));
            
            return true;        
        }
        else
        { 
            return implode(', ', $result->getErrors());
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
            'UF_USER_ID' => array(
				'data_type' => 'integer',
                'required'  => true
			),
            'UF_PROG_ID' => array(
				'data_type' => 'integer',
                'required'  => true
			),
            'UF_PROG' => array(
				'data_type' => '\Hawkart\Megatv\ProgTable',
				'reference' => array('=this.UF_PROG_ID' => 'ref.ID'),
			),
            'UF_SCHEDULE_ID' => array(
				'data_type' => 'integer',
			),
            'UF_SCHEDULE' => array(
				'data_type' => '\Hawkart\Megatv\ScheduleTable',
				'reference' => array('=this.UF_SCHEDULE_ID' => 'ref.ID'),
			),
            'UF_CHANNEL_ID' => array(
				'data_type' => 'integer',
			),
            'UF_CHANNEL' => array(
				'data_type' => '\Hawkart\Megatv\ChannelTable',
				'reference' => array('=this.UF_CHANNEL_ID' => 'ref.ID'),
			),
            'UF_PROGRESS_PERS' => array(
				'data_type' => 'integer',
			),
            'UF_PROGRESS_SECS' => array(
				'data_type' => 'integer',
			),
            'UF_DATE_START' => array(
				'data_type' => 'datetime',
                'required'  => true
			),
            'UF_DATE_END' => array(
				'data_type' => 'datetime',
                'required'  => true
			),
            'UF_AFTER_NOTIFY' => array(
				'data_type' => 'boolean',
				'values'    => array(0, 1)
			),
            'UF_BEFORE_NOTIFY' => array(
				'data_type' => 'boolean',
				'values'    => array(0, 1)
			),
            'UF_WATCHED' => array(
				'data_type' => 'boolean',
				'values'    => array(0, 1)
			),
            'UF_DELETED' => array(
				'data_type' => 'boolean',
				'values'    => array(0, 1)
			),
            'UF_ERROR' => array(
				'data_type' => 'boolean',
				'values'    => array(0, 1)
			),
            'UF_URL' => array(
				'data_type' => 'string',
			),
            'UF_DATETIME_ADD' => array(
				'data_type' => 'datetime',
                'required'  => true
			),
            'UF_EPG_ID' => array(
				'data_type' => 'string',
			),
            
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
    
    /**
     * Get record by user with filter
     */
    public static function getListByUser($arParams)
    {
        global $USER;
        
        $limit = intval($arParams["limit"]);
        $arDatetime = \CTimeEx::getDatetime();
        $date_now = $arDatetime["SERVER_DATETIME_WITH_OFFSET"];
        
        if(intval($arParams['user_id'])>0)
        {
            $user_id = intval($arParams['user_id']);
        }else{
            $user_id = $USER->GetID();
        }
        
        $arFilter = array(
            "=UF_USER_ID" => $user_id, 
            "=UF_DELETED" => 0
        );
        
        if(!empty($arParams["category"]))
        {
            $arFilter["=UF_PROG.UF_CATEGORY"] = trim($arParams["category"]);
        }
        
        if(!empty($arParams["id"]))
        {
            unset($arFilter["=UF_DELETED"]);
            $arFilter["=ID"] = intval($arParams["id"]);
        }
        
        $arSelect = array(
            "ID", "UF_DATE_START", "UF_DATE_END", "UF_PROG_ID", "UF_WATCHED", "UF_PROGRESS_PERS", "UF_CHANNEL_ID",
            "UF_TITLE" => "UF_PROG.UF_TITLE", "UF_SUB_TITLE" => "UF_PROG.UF_SUB_TITLE", "UF_IMG_PATH" => "UF_PROG.UF_IMG.UF_PATH",
            "UF_CATEGORY" => "UF_PROG.UF_CATEGORY", "UF_URL", "UF_CHANNEL_CODE" => "UF_CHANNEL.UF_BASE.UF_CODE",
            "UF_PROG_CODE" => "UF_PROG.UF_CODE", "UF_EPG_ID"
        );
        $result = self::getList(array(
            'filter' => $arFilter,
            'select' => $arSelect,
            'order' => array("UF_DATE_END"=>"DESC"),
            'limit' => intval($arParams["limit"]),
            'offset' => intval($arParams["offset"]),
        ));
        while ($arRecord = $result->fetch())
        {
            $arRecord["UF_NAME"] = ProgTable::getName($arRecord);
            $arRecord["PICTURE"]["SRC"] = CFile::getCropedPath($arRecord["UF_IMG_PATH"], array(300, 300), true);
            $arRecord["DETAIL_PAGE_URL"] = "/channels/".$arRecord["UF_CHANNEL_CODE"]."/".$arRecord["UF_PROG_CODE"]."/";
            
            $arRecord["STATUS"] = "status-recording";
            if(!empty($arRecord["UF_DATE_START"]))
            {
                $arRecord["DATE_START"] = \CTimeEx::dateOffset($arRecord["UF_DATE_START"]->toString());
                $minutes = intval(strtotime($date_now)-strtotime($arRecord["UF_DATE_START"]))/60;
            }
            
            if(!empty($arRecord["UF_DATE_END"]))
                $arRecord["DATE_END"] = \CTimeEx::dateOffset($arRecord["UF_DATE_END"]->toString());
            
            if((!\CTimeEx::dateDiff($arRecord["DATE_START"], $date_now) || $minutes<5) && !empty($arRecord["DATE_START"]) && !empty($arRecord["UF_URL"]))
            {
                $arRecord["STATUS"] = "status-recording";
            }
            elseif(!empty($arRecord["UF_URL"]))
            {
                $arRecord["STATUS"] = "status-recorded";
            }
            
            if(\CTimeEx::dateDiff($arRecord["DATE_END"], $date_now) && empty($arRecord["UF_URL"]))
            {
                $arRecord["STATUS"] = "status-error";
            }
            
            $arResult["RECORDS"][] = $arRecord;
        }
        
        $maxRecord = self::getList([
           'select' => [new \Bitrix\Main\Entity\ExpressionField('CNT', 'COUNT(*)')],
           'filter' => $arFilter,
        ])->fetch()['CNT'];
        
        $arResult["CATEGORIES"] = array();
        $arStat = CStat::getByUser($USER->GetID());
        foreach($arStat["CATS"] as $category => $id)
        {
            $arParams = array("replace_space"=>"-", "replace_other"=>"-");
            $str = \CDev::translit($category, "ru", $arParams);
            $arResult["CATEGORIES"][$category] = $str; 
        }
        $arRecords = array();
        
        foreach($arResult["RECORDS"] as $arRecord)
        {
            $datetime = $arRecord['UF_DATE_START']->toString();
            $date = substr($datetime, 0, 10);
            $time = substr($datetime, 11, 5);
            if(strlen($arRecord["UF_NAME"])>25)
            {
                $arRecord["UF_NAME"] = substr($arRecord["UF_NAME"], 0, 25)."...";
            }
            
            if($arRecord["UF_WATCHED"])
            {
                $path = $_SERVER["DOCUMENT_ROOT"].$arRecord["PICTURE"]["SRC"];
                $path = SITE_TEMPLATE_PATH."/ajax/img_grey.php?path=".urlencode($path);
                $arRecord["STATUS"] = "status-viewed";
            }else{
                $path = $arRecord["PICTURE"]["SRC"];
            }
            
            $duration = strtotime($arRecord["DATE_END"])-strtotime($arRecord["DATE_START"]);
            
            $_arRecord = array(
                "id" => $arRecord["ID"],
                "time" => $time,
        		"date" => $date,
                "prog_id" => $arRecord["UF_PROG_ID"],
        		"link" => $arRecord["DETAIL_PAGE_URL"],
        		"name" => $arRecord["UF_NAME"],
        		"image" => $path,
                "channel_id" => $arRecord["UF_CHANNEL_ID"],
        		"category" => array(
                    "link" => $arResult["CATEGORIES"][$arRecord["UF_CATEGORY"]],
                    "name" => $arRecord["UF_CATEGORY"]
                ),
                "video_url" => str_replace(array("http://86.110.197.202", "https://86.110.197.202"), "https://dev.tvguru.com", $arRecord["UF_URL"]),
                "status" => $arRecord["STATUS"],
                "position" => $arRecord["UF_PROGRESS_PERS"],
                "duration" => $duration
            );
            
            $arProg = \Hawkart\Megatv\ProgTable::detailForRest($arRecord["UF_PROG_ID"]);
            unset($arProg["DATE_END"]);
            unset($arProg["DATE_START"]);
            unset($arProg["DURATION"]);
            unset($arProg["DATE"]);
            unset($arProg["UF_DATE_END"]);
            unset($arProg["UF_DATE_START"]);
            unset($arProg["UF_DATE"]);
            
            $_arRecord = array_merge($_arRecord, $arProg);
        
            $arRecords[] = $_arRecord;
            unset($_arRecord);
        }
            
        return array(
            "items" => $arRecords,
            "pageNum" => ceil($maxRecord/$limit)
        ); 
    }
}