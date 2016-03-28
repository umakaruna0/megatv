<?php

namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class Epg
{
    private static $ftpUrl = 'xmldata.epgservice.ru';
    private static $ftpLogin = 'saturn';
    private static $ftpPasss = '7M3Z17cp';
    private static $ftpFile = 'TV_Pack.xml';
    
    public function __construct($dir = false)
    {
        if(!$dir)
            $dir = FULL_PATH_DOCUMENT_ROOT."/upload/";
            
        $this->dir = $dir;
        $this->file = $this->dir.self::$ftpFile;
    }
    
    /**
	 * Download file xml from EPG service to server
	 *
	 */
    public function download()
    {
        // установка соединения
        $conn = ftp_connect(self::$ftpUrl);
        
        // вход с именем пользователя и паролем
        $login_result = ftp_login($conn, self::$ftpLogin, self::$ftpPasss);
        
        if (ftp_get($conn, $this->file , self::$ftpFile, FTP_BINARY)) 
        {
            echo "Downloaded $this->file \r\n<br />";
        } else {
            echo "Error downloaded\r\n<br />";
        }
        
        // закрытие соединения
        ftp_close($conn);
    }
    
    /**
	 * Import categories from xml EPG
	 *
     * @return array
	 */
    public static function importCategory()
    {
        $file = "http://xmldata.epgservice.ru/EPGService/hs/xmldata/saturn/category_list";
        $xml = simplexml_load_file($file);
        
        $arList = array();
        $result = CategoryTable::getList(array(
            'filter' => array("!UF_EPG_ID" => false),
            'select' => array("UF_EPG_ID", "ID", "UF_TITLE")
        ));
        while ($row = $result->fetch())
        {
            $arList[$row["UF_EPG_ID"]] = $row;
        }
                
        foreach($xml->element as $arElement)
        {
            $json = json_encode($arElement);
            $arElement = json_decode($json, TRUE);
            
            $epg_id = (string)$arElement["@attributes"]["id"];
            $title = (string)$arElement["name"];
            
            if(!is_array($arList[$epg_id]))
            {
                $arFields = array(
                    "UF_EPG_ID" => $epg_id,
                    "UF_TITLE" => $title
                );
                $result = CategoryTable::add($arFields);
                if ($result->isSuccess())
                {
                    $id = $result->getId();
                    $arFields["ID"] = $id;
                    $arList[$epg_id] = $arFields;
                }else{
                    $errors = $result->getErrorMessages(); //print_r($errors);
                }
            }
            //\CDev::pre($arElement);
        }
        
        return $arList;
    }
    
    /**
	 * Import ganres from xml EPG
	 *
     * @return array
	 */
    public static function importGanre()
    {
        $file = "http://xmldata.epgservice.ru/EPGService/hs/xmldata/saturn/ganre_list";
        $xml = simplexml_load_file($file);
        
        $arList = array();
        $result = GanreTable::getList(array(
            'filter' => array("!UF_EPG_ID" => false),
            'select' => array("UF_EPG_ID", "ID", "UF_TITLE")
        ));
        while ($row = $result->fetch())
        {
            $arList[$row["UF_EPG_ID"]] = $row;
        }
                
        foreach($xml->element as $arElement)
        {
            $json = json_encode($arElement);
            $arElement = json_decode($json, TRUE);
            
            $epg_id = (string)$arElement["@attributes"]["id"];
            $title = (string)$arElement["name"];
            
            if(!is_array($arList[$epg_id]))
            {
                $arFields = array(
                    "UF_EPG_ID" => $epg_id,
                    "UF_TITLE" => $title
                );
                $result = GanreTable::add($arFields);
                if ($result->isSuccess())
                {
                    $id = $result->getId();
                    $arFields["ID"] = $id;
                    $arList[$epg_id] = $arFields;
                }else{
                    $errors = $result->getErrorMessages();
                }
            }
        }
        
        return $arList;
    }
    
    /**
	 * Import topics from xml EPG
	 *
     * @return array
	 */
    public static function importTopic()
    {
        $file = "http://xmldata.epgservice.ru/EPGService/hs/xmldata/saturn/topic_list";
        $xml = simplexml_load_file($file);
        
        $arList = array();
        $result = TopicTable::getList(array(
            'filter' => array("!UF_EPG_ID" => false),
            'select' => array("UF_EPG_ID", "ID", "UF_TITLE")
        ));
        while ($row = $result->fetch())
        {
            $arList[$row["UF_EPG_ID"]] = $row;
        }
                
        foreach($xml->element as $arElement)
        {
            $json = json_encode($arElement);
            $arElement = json_decode($json, TRUE);
            
            $epg_id = (string)$arElement["@attributes"]["id"];
            $title = (string)$arElement["name"];
            
            if(!is_array($arList[$epg_id]))
            {
                $arFields = array(
                    "UF_EPG_ID" => $epg_id,
                    "UF_TITLE" => $title
                );
                $result = TopicTable::add($arFields);
                if ($result->isSuccess())
                {
                    $id = $result->getId();
                    $arFields["ID"] = $id;
                    $arList[$epg_id] = $arFields;
                }else{
                    $errors = $result->getErrorMessages();
                }
            }
        }
        
        return $arCategories;
    }
    
    /**
	 * Import countries from xml EPG
	 *
     * @return array
	 */
    public static function importCountry()
    {
        $file = "http://xmldata.epgservice.ru/EPGService/hs/xmldata/saturn/country_list";
        $xml = simplexml_load_file($file);
        
        $arList = array();
        $result = CountryTable::getList(array(
            'filter' => array("!UF_EPG_ID" => false),
            'select' => array("UF_EPG_ID", "ID", "UF_TITLE")
        ));
        while ($row = $result->fetch())
        {
            $arList[$row["UF_EPG_ID"]] = $row;
        }
                
        foreach($xml->element as $arElement)
        {
            $json = json_encode($arElement);
            $arElement = json_decode($json, TRUE);
            
            $epg_id = (string)$arElement["@attributes"]["id"];
            $title = (string)$arElement["name"];
            $iso = (string)$arElement["@attributes"]["ISO"];
            
            if(!is_array($arList[$epg_id]))
            {
                $arFields = array(
                    "UF_EPG_ID" => $epg_id,
                    "UF_TITLE" => $title,
                    "UF_ISO" => $iso,
                    "UF_ACTIVE" => 1,
                    "UF_EXIST" => 1,
                );
                $result = CountryTable::add($arFields);
                if ($result->isSuccess())
                {
                    $id = $result->getId();
                    $arFields["ID"] = $id;
                    $arList[$epg_id] = $arFields;
                }else{
                    $errors = $result->getErrorMessages();
                }
            }
        }
        
        return $arCategories;
    }
    
    /**
	 * Import Productions from xml EPG
	 *
     * @return array
	 */
    public static function importProduction()
    {
        $file = "http://xmldata.epgservice.ru/EPGService/hs/xmldata/saturn/production_list";
        $xml = simplexml_load_file($file);
        
        $arList = array();
        $result = ProductionTable::getList(array(
            'filter' => array("!UF_EPG_ID" => false),
            'select' => array("UF_EPG_ID", "ID", "UF_TITLE")
        ));
        while ($row = $result->fetch())
        {
            $arList[$row["UF_EPG_ID"]] = $row;
        }
                
        foreach($xml->element as $arElement)
        {
            $json = json_encode($arElement);
            $arElement = json_decode($json, TRUE);
            
            $epg_id = (string)$arElement["@attributes"]["id"];
            $title = (string)$arElement["name"];

            if(!is_array($arList[$epg_id]))
            {
                $arFields = array(
                    "UF_EPG_ID" => $epg_id,
                    "UF_TITLE" => $title
                );
                $result = ProductionTable::add($arFields);
                if ($result->isSuccess())
                {
                    $id = $result->getId();
                    $arFields["ID"] = $id;
                    $arList[$epg_id] = $arFields;
                }else{
                    $errors = $result->getErrorMessages();
                }
            }
        }
        
        return $arList;
    }
    
    /**
	 * Import Roles from xml EPG
	 *
     * @return array
	 */
    public static function importRole()
    {
        $file = "http://xmldata.epgservice.ru/EPGService/hs/xmldata/saturn/role_list";
        $xml = simplexml_load_file($file);
        
        $arList = array();
        $result = RoleTable::getList(array(
            'filter' => array("!UF_EPG_ID" => false),
            'select' => array("UF_EPG_ID", "ID", "UF_TITLE")
        ));
        while ($row = $result->fetch())
        {
            $arList[$row["UF_EPG_ID"]] = $row;
        }
                
        foreach($xml->element as $arElement)
        {
            $json = json_encode($arElement);
            $arElement = json_decode($json, TRUE);
            
            $epg_id = (string)$arElement["@attributes"]["id"];
            $title = (string)$arElement["name"];

            if(!is_array($arList[$epg_id]))
            {
                $arFields = array(
                    "UF_EPG_ID" => $epg_id,
                    "UF_TITLE" => $title
                );
                $result = RoleTable::add($arFields);
                if ($result->isSuccess())
                {
                    $id = $result->getId();
                    $arFields["ID"] = $id;
                    $arList[$epg_id] = $arFields;
                }else{
                    $errors = $result->getErrorMessages();
                }
            }
        }
        
        return $arList;
    }
    
    /**
	 * Import People from xml EPG
	 *
     * @return array
	 */
    public static function importPeople($arRoles = false)
    {
        $file = "http://xmldata.epgservice.ru/EPGService/hs/xmldata/saturn/people_list";
        $xml = simplexml_load_file($file);
        
        $arList = array();
        $result = PeopleTable::getList(array(
            'filter' => array("!UF_EPG_ID" => false),
            'select' => array("UF_EPG_ID", "ID", "UF_TITLE")
        ));
        while ($row = $result->fetch())
        {
            $arList[$row["UF_EPG_ID"]] = $row;
        }
        
        if(!$arRoles)
            $arRoles = self::importRole();
                
        foreach($xml->element as $arElement)
        {
            $el_attr = $arElement->attributes();
            $epg_id = (string)$el_attr["id"];
            $title = (string)$arElement->name;
    
            $role_attr = $arElement->role->attributes();
            $role_epg_id = (string)$role_attr["id"];
            $role_id = $arRoles[$role_epg_id]["ID"];

            if(!is_array($arList[$epg_id]))
            {
                $arFields = array(
                    "UF_EPG_ID" => $epg_id,
                    "UF_TITLE" => $title,
                    "UF_ROLE_ID" => $role_id
                );
                $result = PeopleTable::add($arFields);
                if ($result->isSuccess())
                {
                    $id = $result->getId();
                    $arFields["ID"] = $id;
                    $arList[$epg_id] = $arFields;
                }else{
                    $errors = $result->getErrorMessages();
                    //\CDev::pre($arFields);\CDev::pre($errors);
                }
            }
        }
        
        unset($arRoles);
        
        return $arList;
    }
    
    /**
	 * Import Channel from xml EPG
	 *
     * @return array
	 */
    public static function importChannel($xmlChannel)
    {
        $arChannels = array();
        $result = ChannelTable::getList(array(
            'filter' => array("!UF_EPG_ID" => false),
            'select' => array("UF_EPG_ID", "ID", "UF_ACTIVE")
        ));
        while ($row = $result->fetch())
        {
            $arChannels[$row["UF_EPG_ID"]] = $row;
        }
        
        foreach($xmlChannel as $arChannel)
        {
            $epg_id = (string)\CXmlEx::getAttr($arChannel, "id");
            $name = (string)$arChannel->{'display-name'};
            $name = trim($name);
            
            $icon = (string)\CXmlEx::getAttr($arChannel->icon, "src");
            $icon = "http://". $icon;
            
            if(!is_array($arChannels[$epg_id]))
            {
                $arFields = array(
                    "UF_ACTIVE" => 1,
                    "UF_EPG_ID" => $epg_id,
                    "UF_TITLE" => $name
                );
                $result = ChannelTable::add($arFields);
                if ($result->isSuccess())
                {
                    $id = $result->getId();
                    $arFields["ID"] = $id;
                    $arChannels[$epg_id] = $arFields;
                }else{
                    $errors = $result->getErrorMessages(); //print_r($errors);
                }
            }
        }
        return $arChannels;
    }
    
    public function import()
    {
        if (file_exists($this->file))
            $xml = simplexml_load_file($this->file);
        
        $epgUrl = (string)\CXmlEx::getAttr($xml, "generator-info-url");
        $epgName = (string)\CXmlEx::getAttr($xml, "generator-info-name");
        
        $arScheduleIdsNotDelete = array();
        $arProgTimeDelete = array();        //Расписание, которое нужно удалить
        
        //echo date("i:s")."<br />";
        $arCategories = self::importCategory();
        $arGanres = self::importGanre();
        $arTopics = self::importTopic();
        $arCountries = self::importCountry();
        $arProductions = self::importProduction();
        $arRoles = self::importRole();
        $arPeople = self::importPeople($arRoles);
        $arChannels = self::importChannel($xml->channel);
        //echo date("i:s");
        
        /**
         * Get prog's list
         */
        $arProgs = array();
        $result = ProgTable::getList(array(
            'filter' => array("!UF_EPG_ID" => false),
            'select' => array("UF_EPG_ID", "ID")
        ));
        while ($row = $result->fetch())
        {
            $arProgs[$row["UF_EPG_ID"]] = $row;
        }
        
        /**
         * Get schedule's list
         */
        /*$arSchedules = array();
        $result = \Hawkart\Megatv\ScheduleTable::getList(array(
            'filter' => array(),
            'select' => array("UF_EPG_ID", "ID")
        ));
        while ($row = $result->fetch())
        {
            $arSchedules[$row["UF_EPG_ID"]] = $row["ID"];
        }*/
        
        
        /**
         * Update Progs
         */
        foreach($xml->programme as $arProg)
        {
            $_arProg = $arProg;
            $json = json_encode($arProg);
            $arProg = json_decode($json, TRUE);
            //$epg_id = (string)$arProg["@attributes"]["channel"];
            //$arChannel = $arChannels[$epg_id];
        
            if(/*intval($arChannel["ID"])==0 || */intval($arChannel["UF_ACTIVE"])!=1)
                continue;
            
            $arFields = array(
                "UF_ACTIVE" => 1,
                "UF_TITLE" => trim((string)$arProg["title"]),
                "UF_DESC" => (string)$arProg["desc"],
                "UF_YEAR_LIMIT" => (int)$arProg["rating"]["value"],
                "UF_YEAR" => (string)$arProg["year"]
            );
            
            if(isset($arProg["title"]))
            {
                $attr = $_arProg->title->attributes();
                $arFields["UF_EPG_ID"] = (string)$attr["id"];
                $prog_epg_id = (string)$attr["id"];
            }
        
            if (!array_key_exists($prog_epg_id, $arProgs))
            {
                if(isset($arProg["sub-title"]))
                {
                    $arFields["UF_SUB_TITLE"] = trim($arProg["sub-title"]);
                    $attr = $_arProg->{'sub-title'}->attributes();
                    $arFields["UF_EPG_SUB_ID"] = (string)$attr["id"];
                }
                
                if(!empty($arProg['episode-number']))
                    $arFields["UF_SERIA"] = (string)$arProg['episode-number'];
                
                if(!empty($arProg['season']))
                    $arFields["UF_SEASON"] = (string)$arProg['season'];
                
                if(!is_array($arProg["category"]))
                    $arProg["category"] = array($arProg["category"]);
                $arFields["UF_CATEGORY"] = implode(", ", $arProg["category"]);
                
                
                
                if(!is_array($arProg["topic"]))
                    $arProg["topic"] = array($arProg["topic"]);
                $arFields["UF_TOPIC"] = implode(", ", $arProg["topic"]);
                
                if(!is_array($arProg["ganre"]))
                    $arProg["ganre"] = array($arProg["ganre"]);
                $arFields["UF_GANRE"] = implode(", ", $arProg["ganre"]);
                
                if(!is_array($arProg["country"]))
                    $arProg["country"] = array($arProg["country"]);
                $arFields["UF_COUNTRY"] = implode(", ", $arProg["country"]);
        
                if(!is_array($arProg["credits"]["director"]))
                    $arProg["credits"]["director"] = array($arProg["credits"]["director"]);
                $arFields["UF_DIRECTOR"] = implode(", ", $arProg["credits"]["director"]);
                
                if(!is_array($arProg["credits"]["actor"]))
                    $arProg["credits"]["actor"] = array($arProg["credits"]["actor"]);
                $arFields["UF_ACTOR"] = implode(", ", $arProg["credits"]["actor"]);
                
                if(!is_array($arProg["credits"]["presenter"]))
                    $arProg["credits"]["presenter"] = array($arProg["credits"]["presenter"]);
                $arFields["UF_PRESENTER"] = implode(", ", $arProg["credits"]["presenter"]);
                
                $result = ProgTable::add($arFields);
                if ($result->isSuccess())
                {
                    $prog_id = $result->getId();
                    $arProgs[$prog_epg_id] = array(
                        "ID" => $prog_id
                    );
                }else{
                    $errors = $result->getErrorMessages();
                    /*print_r($errors);
                    \CDev::log(array(
                        "ERROR" => $errors,
                        "PROG" => $arFields,
                    ), false, $log_file);*/
                }
        
            }else{
                $prog_id = $arProgs[$prog_epg_id]["ID"];
            }
            
            
            
        }
    }
}