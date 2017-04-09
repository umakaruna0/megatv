<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class ProgTable extends Entity\DataManager
{
    /**
     * array("UF_TITLE", "UF_SUB_TITLE")
     * 
     * @return string
     */
    public static function getName($arProg)
    {
        $str = $arProg["UF_TITLE"];
        if(!empty($arProg["UF_SUB_TITLE"]))
        {
            $str.= " | ".$arProg["UF_SUB_TITLE"];
        }
        
        return $str;
    }

	/**
	 * Returns DB table name for entity
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'hw_prog';
	}
    
    /**
     * Change data before adding
     * 
     * @return object 
     */
    /*public static function onBeforeAdd(Entity\Event $event)
    {
        $result = new Entity\EventResult;
        $data = $event->getParameter("fields");

        if (isset($data['UF_TITLE']))
        {
            $arParams = array("replace_space"=>"-", "replace_other"=>"-");
            $code = \CDev::translit(trim($data["UF_TITLE"]), "ru", $arParams);
            $result->modifyFields(array('UF_CODE' => $code));
        }

        return $result;
    }*/
    
    public static function generateCodes()
    {
        $arProgs = array();
        $codes = array();
        $result = self::getList(array(
            'filter' => array(/*"UF_CODE" => false*/),
            'select' => array("ID", "UF_TITLE", "UF_CODE", "UF_EPG_ID")
        ));
        while ($row = $result->fetch())
        {
            $arParams = array("replace_space"=>"-", "replace_other"=>"-");
            $code = \CDev::translit(trim($row["UF_TITLE"]), "ru", $arParams);
            
            if(!empty($row["UF_CODE"]))
            {
                $codes[$row["UF_TITLE"]] = $row;
                continue;
            }
            
            $arCode = $codes[$row["UF_TITLE"]];
            if(!empty($arCode))
            {
                if($row["UF_EPG_ID"]!=$arCode["UF_EPG_ID"])
                {
                    $code.= "-".$row["ID"];
                }else{
                    $code = $arCode["UF_CODE"];
                }
            }
            
            self::update($row["ID"], array(
                "UF_CODE" => $code
            ));
            
            $row["UF_CODE"] = $code;
            $codes[$row["UF_TITLE"]] = $row;
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
			'UF_ACTIVE' => array(
				'data_type' => 'boolean',
				'title'     => Localization\Loc::getMessage('prog_entity_active_field'),
				'values'    => array(0, 1),
				'required'  => true
			),
			'UF_TITLE' => array(
				'data_type' => 'string',
				'title'     => Localization\Loc::getMessage('prog_entity_title_field'),
                'required'  => true
			),
            'UF_SUB_TITLE' => array(
				'data_type' => 'string',
				'title'     => Localization\Loc::getMessage('prog_entity_sub_title_field')
			),
			'UF_DESC' => array(
				'data_type' => 'text',
				'title'     => Localization\Loc::getMessage('prog_entity_desc_field'),
			),
            'UF_SUB_DESC' => array(
				'data_type' => 'text',
				'title'     => Localization\Loc::getMessage('prog_entity_sub_desc_field'),
			),
            'UF_EPG_ID' => array(
				'data_type' => 'string',
				'title'     => Localization\Loc::getMessage('prog_entity_epg_id_field'),
                'required'  => true
			),
            'UF_EPG_SUB_ID' => array(
				'data_type' => 'string',
				'title'     => Localization\Loc::getMessage('prog_entity_epg_sub_id_field')
			),
            'UF_SEASON' => array(
				'data_type' => 'string',
				'title'     => Localization\Loc::getMessage('prog_entity_season_field'),
			),
            'UF_SERIA' => array(
				'data_type' => 'string',
				'title'     => Localization\Loc::getMessage('prog_entity_seria_field'),
			),
            'UF_CATEGORY' => array(
				'data_type' => 'string',
				'title'     => Localization\Loc::getMessage('prog_entity_category_field'),
			),
            'UF_DIRECTOR' => array(
				'data_type' => 'string',
				'title'     => Localization\Loc::getMessage('prog_entity_director_field'),
			),
            'UF_ACTOR' => array(
				'data_type' => 'string',
				'title'     => Localization\Loc::getMessage('prog_entity_actor_field'),
			),
            'UF_COUNTRY' => array(
				'data_type' => 'string',
				'title'     => Localization\Loc::getMessage('prog_entity_country_field'),
			),
            'UF_TOPIC' => array(
				'data_type' => 'string',
				'title'     => Localization\Loc::getMessage('prog_entity_topic_field'),
			),
            'UF_PRESENTER' => array(
				'data_type' => 'string',
				'title'     => Localization\Loc::getMessage('prog_entity_presenter_field'),
			),
            'UF_GANRE' => array(
				'data_type' => 'string',
				'title'     => Localization\Loc::getMessage('prog_entity_ganre_field'),
			),
            'UF_YEAR_LIMIT' => array(
				'data_type' => 'integer',
				'title'     => Localization\Loc::getMessage('prog_entity_year_limit_field'),
			),
            'UF_YEAR' => array(
				'data_type' => 'string',
				//'title'     => Localization\Loc::getMessage('prog_entity_year_field'),
			),
            'UF_RATING' => array(
				'data_type' => 'integer',
				'title'     => Localization\Loc::getMessage('prog_entity_rating_field'),
			),
            'UF_HD' => array(
				'data_type' => 'boolean',
				'title'     => Localization\Loc::getMessage('prog_entity_hd_field'),
				'values'    => array(0, 1),
			), 
            'UF_PREMIERE' => array(
				'data_type' => 'boolean',
				'values'    => array(0, 1),
			),             
            'UF_CODE' => array(
				'data_type' => 'string',
				'title'     => Localization\Loc::getMessage('prog_entity_code_field'),
                'required'  => false,
			),
            'UF_IMG_ID' => array(
				'data_type' => 'integer',
				'title'     => Localization\Loc::getMessage('prog_entity_img_id_field')
			),
            'UF_IMG' => array(
				'data_type' => '\Hawkart\Megatv\ImageTable',
				'reference' => array('=this.UF_IMG_ID' => 'ref.ID'),
			),
            'UF_SOCIAL_VIDEO' => array(
                'data_type' => 'string',
            ),
            'UF_EXTERNAL_ID' =>array(
				'data_type' => 'string',
			),
            'UF_EXTERNAL_URL' =>array(
				'data_type' => 'string',
			),
            'UF_EXTERNAL_RESOURCE' =>array(
				'data_type' => 'string',
			),
            'UF_IMG_LIST' =>array(
                'data_type' => 'text',
                'serialized' => true
			),
            'UF_EPG_IMG' => array(
                'data_type' => 'string',
            )
		);
	}
    
    /**
     * change rating for prog
     */
    public static function addRating($ID, $addRating)
    {
        $result = self::getList(array(
            'filter' => array("=ID" => intval($ID)),
            'select' => array("ID", "UF_RATING"),
        ));
        if ($arProg = $result->fetch())
        {
            //$rating = intval($arProg["UF_RATING"]) + intval($addRating);
            //self::update($arProg["ID"], array("UF_RATING"=>$rating));
        }
    }
    
    /**
     * change rating for all serial
     */
    public static function addByEpgRating($prog_epg_id, $addRating)
    {
        global $DB;
        
        $DB->Query("UPDATE ".self::getTableName()." SET UF_RATING = Coalesce(UF_RATING, 0) + ".intval($addRating).
        " WHERE UF_EPG_ID='".$prog_epg_id."'");
    }
    
    /**
     * delete rating
     */
    public static function clearRateAll()
    {
        global $DB;
        $DB->Query("UPDATE ".self::getTableName()." SET UF_RATING = 0");
    }
    
    
    public static function getProgsByRating()
    {
        $ids = array();
        $arProgs = array();
        $result = \Hawkart\Megatv\ProgTable::getList(array(
            'filter' => array(
                "=UF_ACTIVE" => 1,
            ),
            'select' => array(
                "ID", "UF_EPG_ID", "UF_GANRE", "UF_TOPIC"
            ),
            'order' => array("UF_RATING" => "DESC"),
        ));
        while ($arSchedule = $result->fetch())
        {
            if(in_array($arSchedule["UF_EPG_ID"], $ids))
                continue;
            
            $ids[] = $arSchedule["UF_EPG_ID"];
            
            $arSchedule["UF_GANRE"] = explode(",", $arSchedule["UF_GANRE"]);
            $arSchedule["UF_TOPIC"] = explode(",", $arSchedule["UF_TOPIC"]);
            $arProgs[] = $arSchedule;
        }
        
        unset($ids);
        
        return $arProgs;
    }
    
    public static function getCategoryAll()
    {
        $arCats = array();
        $result = self::getList(array(
            'filter' => array(
                "=UF_ACTIVE" => 1,
            ),
            'select' => array(
                "UF_CATEGORY",
            )
        ));
        while ($arSchedule = $result->fetch())
        {
            if(!empty($arSchedule["UF_CATEGORY"]))
                $arCats[] = $arSchedule["UF_CATEGORY"];
        }
        
        $arCats = array_unique($arCats);
        
        return $arCats;
    }
    
    public static function detailForRest($id)
    {
        $arResult = array();
        $arFilter = array("=ID" => $id);
        $arSelect = array(
            "ID", "UF_TITLE", "UF_SUB_TITLE", "UF_IMG_PATH" => "UF_IMG.UF_PATH",
            "UF_RATING", "UF_DESC", "UF_SUB_DESC", "UF_GANRE", "UF_YEAR_LIMIT", "UF_COUNTRY",
            "UF_YEAR", "UF_DIRECTOR", "UF_PRESENTER", "UF_ACTOR", "UF_CATEGORY"
        );
        $obCache = new \CPHPCache;
        if( $obCache->InitCache(86400, serialize($arFilter).serialize($arSelect), "/prog-detail/"))
        {
        	$arProg = $obCache->GetVars();
        }
        elseif($obCache->StartDataCache())
        {
            $result = \Hawkart\Megatv\ProgTable::getList(array(
                'filter' => $arFilter,
                'select' => $arSelect,
                'limit' => 1
            ));
            if ($arProg = $result->fetch())
            {
                $arProg["UF_PROG_ID"] = $arProg["ID"];
                $arProg["PICTURE"]["SRC"] = \Hawkart\Megatv\CFile::getCropedPath($arProg["UF_IMG_PATH"], array(600, 600));
                $arProg["KEYWORDS"] = array($arProg["UF_CATEGORY"], $arProg["UF_GANRE"]);
            }
            unset($arProg["ID"]);
            $obCache->EndDataCache($arProg);  
        }
        
        if(!empty($arProg))
            $arResult = array_merge($arResult, $arProg);
        
        $arDate = \CTimeEx::getDateTimeFilter($arParams["DATETIME"]["SERVER_DATETIME"]);
        $dateStart = date("Y-m-d H:i:s");
        $arFilter = array(
            "=UF_PROG_ID" => $id,
            ">=UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'),
        );
        $arSelect = array(
            "ID", "UF_DATE_START", "UF_DATE_END", "UF_DATE", "UF_CHANNEL_ID", "UF_ICON" => "UF_CHANNEL.UF_BASE.UF_ICON",
        );
        $obCache = new \CPHPCache;
        if( $obCache->InitCache(86400, serialize($arFilter).serialize($arSelect), "/shedule-detail/"))
        {
        	$arResult = $obCache->GetVars();
        }
        elseif($obCache->StartDataCache())
        {
            //get channel by code
            $result = \Hawkart\Megatv\ScheduleTable::getList(array(
                'filter' => $arFilter,
                'select' => $arSelect,
                'limit' => 1
            ));
            if ($arShedule = $result->fetch())
            {
                $arResult["ID"] = $arShedule["ID"];
                $arResult["UF_ICON"] = $arShedule["UF_ICON"];
                $arResult["UF_CHANNEL_ID"] = $arShedule["UF_CHANNEL_ID"];
                $arResult["UF_DATE_START"] = $arResult["DATE_START"] = \CTimeEx::dateOffset($arShedule['UF_DATE_START']->toString());
                $arResult["UF_DATE_END"] = $arResult["DATE_END"] = \CTimeEx::dateOffset($arShedule['UF_DATE_END']->toString());
                $arResult["UF_DATE"] = $arResult["DATE"] = substr($arShedule["DATE_START"], 0, 10);
                $sec = strtotime($arResult["DATE_END"]) - strtotime($arResult["DATE_START"]);
                $arResult["DURATION"] = \CTimeEx::secToStr($sec);
            }
            $obCache->EndDataCache($arResult); 
        }
        
        if(empty($arResult["DATE_START"]))
        {
            $arResult["IS_OLD"] = true;
        }else{
            $arResult["IS_OLD"] = false;
        }
        
        foreach(array("UF_DIRECTOR", "UF_PRESENTER", "UF_ACTOR") as $type)
        {
            $_arResult[$type] = array();
            $arPeoples = explode(",", $arResult[$type]);
        
            foreach($arPeoples as $actor)
            {
                $actor = trim($actor);
                if(!empty($actor))
                {
                    $link = \Hawkart\Megatv\PeopleTable::getKinopoiskLinkByName($actor);
                    $link = str_replace("//name", "/name", $link);
                    if(empty($link)) $link = "#";
                    $_arResult[$type][] = array(
                        "NAME" => $actor,
                        "LINK" => $link
                    );
                }
            }
            $arResult[$type] = $_arResult[$type];
            unset($_arResult[$type]);
        }
        
        return $arResult;
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
     * Delete empty images, bugfix
     */
    public static function updateOriginPicsEmpty()
    {
        $arFilter = [];
        $arSelect = [
            "ID", "IMG" => "UF_IMG.UF_PATH", "UF_IMG_ID"
        ];
        $result = self::getList(array(
            'filter' => $arFilter,
            'select' => $arSelect,
        ));
        while ($arProg = $result->fetch())
        {
            if(filesize($_SERVER["DOCUMENT_ROOT"].$arProg["IMG"])==0)
            {
                echo $_SERVER["DOCUMENT_ROOT"].$arProg["IMG"]."<br />";
                echo $arProg["UF_IMG_ID"]."<br />";
                
                ImageTable::delete($arProg["UF_IMG_ID"]);
                
                self::update($arProg["ID"], array(
                    "UF_IMG_ID" => 0
                ));
            }
        }
    }
    
    public static function getImages($prog_id)
    {
        $result = self::getList(array(
            'filter' => array("=ID" => $prog_id),
            'select' => array("UF_IMG_LIST"),
            'limit' => 1
        ));
        if ($arProg = $result->fetch())
        {
            return $arProg["UF_IMG_LIST"];
        }
        
        return array();
    }
    
    public static function addToImageList($prog_id, $img_to, $class="one")
    {
        $arImages = self::getImages($prog_id);
        $arImages[$class][] = $img_to;
        self::update($prog_id, array(
            "UF_IMG_LIST" => $arImages
        ));
    }
    
    public static function saveImageList($prog_id, $arImages)
    {
        self::update($prog_id, array(
            "UF_IMG_LIST" => $arImages
        ));
    }
}