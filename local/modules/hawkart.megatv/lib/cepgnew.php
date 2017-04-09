<?php
namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class CEpgNew
{
    private static $ftpUrl = 'xmldata.epgservice.ru';
    private static $ftpLogin = 'saturn';
    private static $ftpPasss = '7M3Z17cp';
    private static $ftpFile = 'TV_Pack.xml';
    
    public function __construct($dir = false)
    {
        if(!$dir)
            $dir = $_SERVER["DOCUMENT_ROOT"]."/upload/";
            
        $this->dir = $dir;
        $this->file = $this->dir.self::$ftpFile;
        
        $file = 'http://xmldata.epgservice.ru/EPGService/hs/xmldata/saturnapi/index';
        $this->xml = simplexml_load_file($file);
    }
    
    public function importChannelCity()
    {
        $arChannelCity = array();
        $arFilter = array();
        $arSelect = array("ID", "UF_CHANNEL_ID", "UF_CITY_ID");
        $result = ChannelCityTable::getList(array(
            'filter' => $arFilter,
            'select' => $arSelect
        ));
        while ($arItem = $result->fetch())
        {
            $arChannelCity[$arItem["UF_CHANNEL_ID"]."-".$arItem["UF_CITY_ID"]] = $arItem["ID"];
        }
        
        $arCities = array();
        $arFilter = array(
            "UF_COUNTRY.UF_TITLE" => "Россия", 
            "UF_ACTIVE" => 1
        );
        $arSelect = array("ID", "UF_TITLE");
        $result = CityTable::getList(array(
            'filter' => $arFilter,
            'select' => $arSelect
        ));
        while ($arCity = $result->fetch())
        {
            $arCities[$arCity["UF_TITLE"]] = $arCity["ID"];
        }
        
        $arChannels = array();
        $arFilter = array();
        $arSelect = array("ID", "UF_EPG_ID");
        $result = ChannelTable::getList(array(
            'filter' => $arFilter,
            'select' => $arSelect
        ));
        while ($arChannel = $result->fetch())
        {
            $arChannels[$arChannel["UF_EPG_ID"]] = $arChannel["ID"];
        }
        
        foreach($this->xml->channel as $_arChannel)
        {
            $attr = $_arChannel->attributes();
            $channel_epg_id = trim((string)$attr["id"]);
            
            $city_xml_path = (string) $_arChannel->{'geo-data'};
            $city_xml = simplexml_load_file($city_xml_path);
            
            //foreach($_arChannel->{'broadcast-city'}->city as $_arCity)
            foreach($city_xml->channel->{'broadcast-city'}->city as $_arCity)
            {
                $json = json_encode($_arCity);
                $arCity = json_decode($json, TRUE);
                $city = trim((string)$arCity[0]);
                
                $city_id = $arCities[$city];
                $channel_id = $arChannels[$channel_epg_id];
                
                if(intval($arChannelCity[$channel_id."-".$city_id])==0)
                {
                    $arFields = array(
                        "UF_CITY_ID" => $city_id,
                        "UF_CHANNEL_ID" => $channel_id
                    );
                    $result = ChannelCityTable::add($arFields);
                    if ($result->isSuccess())
                    {
                        $id = $result->getId();
                        $arChannelCity[$channel_id."-".$city_id] = $id;
                    }
                }
            }
        }
    }
    
    public static function exportChannelCity()
    {
        $arRows = array();
        
        $fp = fopen($_SERVER["DOCUMENT_ROOT"]."/upload/channel_city.csv", 'w');
        $firstStr = array("Субъект федерации"."\t", "Город/Канал"."\t");
        
        $arChannelCity = array();
        $arSelect = array(
            "ID", "UF_CHANNEL_ID", "UF_CITY_ID",
            "UF_BASE_ID" => "UF_CHANNEL.UF_BASE_ID", 
            "UF_EPG_ID" => "UF_CHANNEL.UF_EPG_ID"
        );
        $result = ChannelCityTable::getList(array(
            'filter' => array(),
            'select' => $arSelect
        ));
        while ($arItem = $result->fetch())
        {
            $arChannelCity[$arItem["UF_CITY_ID"]][$arItem["UF_BASE_ID"]] = $arItem["UF_EPG_ID"];
        }
        
        $arCities = array();
        $arFilter = array(
            "UF_COUNTRY.UF_TITLE" => "Россия", 
            "UF_ACTIVE" => 1
        );
        $arSelect = array("ID", "UF_TITLE", "UF_REGION");
        $result = CityTable::getList(array(
            'filter' => $arFilter,
            'select' => $arSelect,
            'order' => array("UF_TITLE" => "ASC")
        ));
        while ($arCity = $result->fetch())
        {
            $arCities[$arCity["ID"]] = $arCity;
        }
        
        $arChannels = array();
        $arFilter = array("UF_ACTIVE" => 1);
        $arSelect = array("ID", "UF_TITLE");
        $result = ChannelBaseTable::getList(array(
            'filter' => $arFilter,
            'select' => $arSelect
        ));
        while ($arChannel = $result->fetch())
        {
            $arChannels[$arChannel["ID"]] = $arChannel["UF_TITLE"];
            $firstStr[] = $arChannel["UF_TITLE"]."\t";
        }
        
        $arRows[] = $firstStr;

        fputcsv($fp, $firstStr, ";");
        
        foreach($arCities as $city_id => $arCity)
        {
            $arStr = array($arCity["UF_REGION"], $arCity["UF_TITLE"]);
            
            foreach($arChannels as $channel_id => $channel_title)
            {
                $arStr[] = (string)$arChannelCity[$city_id][$channel_id]."\t";
            }
            
            $arRows[] = $arStr;
            
            fputcsv($fp, $arStr);
        }
        
        fclose($fp);
        
        $fileName = 'channel_city.csv';
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Content-Description: File Transfer');
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename={$fileName}");
        header("Expires: 0");
        header("Pragma: public");
        $fh = @fopen( 'php://output', 'w' );
        foreach ( $arRows as $data ) 
        {         
            fputcsv($fh, $data, ";");
        }
        fclose($fh);
        exit;
    }
    
    /**
	 * Download file xml from EPG service to server
	 *
	 */
    public function importChannels()
    {   
        $arHrefChannels = array();
        $arBaseChannels = array();
        $result = ChannelBaseTable::getList(array(
            'filter' => array(),
            'select' => array("UF_EPG_ID", "ID", "UF_ACTIVE", "UF_IMG_PATH"),
            'order' => array("ID" => "ASC")
        ));
        while ($row = $result->fetch())
        {
            if($arBaseChannels[$row["UF_EPG_ID"]]["ID"]>0)
            {
                ChannelBaseTable::delete($row["ID"]);   //if dublicate exist
            }else{
                $arBaseChannels[$row["UF_EPG_ID"]] = $row;
            }
        }
        
        $arChannels = array();
        $result = ChannelTable::getList(array(
            'filter' => array("!UF_EPG_ID" => false),
            'select' => array("UF_EPG_ID", "ID", "UF_BASE_ID")
        ));
        while ($row = $result->fetch())
        {
            $arChannels[$row["UF_EPG_ID"]] = $row;
        }
        
        $channel_dir = CImage::getChannelDir();
        
        foreach($this->xml->channel as $_arChannel)
        {
            $attr = $_arChannel->{'base-channel'}->attributes();
            $base_epg_id = trim((string)$attr["id"]);
            
            $json = json_encode($_arChannel);
            $arChannel = json_decode($json, TRUE);

            $epg_id = trim((string)$arChannel["@attributes"]["id"]);
            $name = trim((string)$arChannel["display-name"]);
            $base_title = trim((string)$arChannel["base-channel"]);
            $icon = (string)$arChannel["@attributes"]["src"];
            $href = trim((string)$arChannel["href"]);
            
            $arHrefChannels[$epg_id][] = $href;
            

            if(intval($arBaseChannels[$epg_id]["ID"])>0 && empty($arBaseChannels[$epg_id]["UF_IMG_PATH"]))
            {
                $attr = $_arChannel->{'icon'}->attributes();
                $img_url = trim((string)$attr["src"]);
                
                $path_parts = pathinfo($img_url);
                $file_name = $path_parts["filename"];
                $img_path_to = $channel_dir . $file_name . ".". $path_parts['extension'];
                
                file_put_contents($_SERVER['DOCUMENT_ROOT'].$img_path_to, file_get_contents($img_url));
                $image = new \Eventviva\ImageResize($_SERVER['DOCUMENT_ROOT'].$img_path_to);
                $image->resizeToWidth(135);
                $image->save($_SERVER['DOCUMENT_ROOT'].$img_path_to);
                
                $arBaseChannels[$epg_id]["UF_IMG_PATH"] = $img_path_to;
                
                ChannelBaseTable::update($arBaseChannels[$epg_id]["ID"], array(
                    "UF_IMG_PATH" => $img_path_to
                ));
                
                echo $epg_id." ".$img_url."\r\n";
            }

            //включим базовые каналы в админке
            /*if( (!empty($base_epg_id) && $base_epg_id==$epg_id) || empty($base_epg_id) )
            {
                //echo $name." BASE=".$base_epg_id." EPG_ID".$epg_id."<br />";
                
                if(is_array($arBaseChannels[$epg_id]) && intval($arBaseChannels[$epg_id]["UF_ACTIVE"])!=1)
                {
                    ChannelBaseTable::update($arBaseChannels[$epg_id]["ID"], array(
                        "UF_ACTIVE" => 1,
                        "UF_FORBID_REC" => 1
                    ));
                }
            }*/
                       
            
            if(!empty($base_epg_id) && $base_epg_id!=$epg_id)
            {
                //print_r($arBaseChannels[$epg_id]);
                if(!empty($epg_id) && intval($arBaseChannels[$epg_id]["ID"])>0)
                {
                    //echo $arBaseChannels[$epg_id]["ID"]."\r\n";
                    ChannelBaseTable::delete($arBaseChannels[$epg_id]["ID"]);
                    unset($arBaseChannels[$epg_id]);
                }
                
                if(!is_array($arBaseChannels[$base_epg_id]))
                {
                    $arFields = array(
                        "UF_ACTIVE" => 0,
                        "UF_EPG_ID" => $base_epg_id,
                        "UF_TITLE" => trim((string)$arChannel["base-channel"])
                    );
                    
                    $result = ChannelBaseTable::add($arFields);
                    if ($result->isSuccess())
                    {
                        $id = $result->getId();
                        $arFields["ID"] = $id;
                        $arBaseChannels[$base_epg_id] = $arFields;
                    }else{
                        $errors = $result->getErrorMessages();
                    }
                }
                
            }else{
                if(!is_array($arBaseChannels[$epg_id]))
                {
                    $arFields = array(
                        "UF_ACTIVE" => 0,
                        "UF_EPG_ID" => $epg_id,
                        "UF_TITLE" => $name
                    );
                    $result = ChannelBaseTable::add($arFields);
                    if ($result->isSuccess())
                    {
                        $id = $result->getId();
                        $arFields["ID"] = $id;
                        $arBaseChannels[$epg_id] = $arFields;
                    }else{
                        $errors = $result->getErrorMessages();
                    }
                }
            }

            if(!is_array($arChannels[$epg_id]))
            {
                if(empty($base_epg_id))
                    $base_epg_id = $epg_id;
                
                $arFields = array(
                    "UF_EPG_ID" => $epg_id,
                    "UF_BASE_ID" => $arBaseChannels[$base_epg_id]["ID"]
                );
                $result = ChannelTable::add($arFields);
                if ($result->isSuccess())
                {
                    $id = $result->getId();
                    $arFields["ID"] = $id;
                    $arChannels[$epg_id] = $arFields;
                }else{
                    $errors = $result->getErrorMessages();
                }
            }
            
            if(empty($base_epg_id)) $base_epg_id = $epg_id;    
            $arChannels[$epg_id]["UF_BASE_EPG_ID"] = $base_epg_id;
        }
        
        $this->base_channels = $arBaseChannels;
        $this->channels = $arChannels;
        $this->href_channels = $arHrefChannels;
        
        return $arChannels;
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
                    "UF_ACTIVE" => 0,
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
     * Import Epg file 
     */
    public function import()
    {
        $arImagesToCrop = array();   //prog id list to crop image
        $arScheduleIdsNotDelete = array();
        $arProgTimeDelete = array();            //Расписание, которое нужно удалить
        /*$arCategories = self::importCategory();
        $arGanres = self::importGanre();
        $arTopics = self::importTopic();
        $arCountries = self::importCountry();
        $arProductions = self::importProduction();*/
        $arRoles = self::importRole();
        $arPeople = self::importPeople($arRoles);
        $arBaseChannels = $this->base_channels;
        $arChannels = $this->channels;
        
        //Delete schedule before prev day
        ScheduleTable::deleteOld();
        
        /**
         * Get prog's list
         */
        $arProgs = array();
        $result = ProgTable::getList(array(
            'filter' => array("!UF_EPG_ID" => false),
            'select' => array("UF_EPG_ID", "UF_EPG_SUB_ID", "ID", "UF_EPG_IMG")
        ));
        while ($row = $result->fetch())
        {
            $arProgs[$row["UF_EPG_ID"].$row["UF_EPG_SUB_ID"]] = $row;
        }
        
        /**
         * Get schedule's list
         */
        $arSchedules = array();
        $result = ScheduleTable::getList(array(
            'filter' => array(),
            'select' => array("UF_EPG_ID", "ID")
        ));
        while ($row = $result->fetch())
        {
            $arSchedules[$row["UF_EPG_ID"]] = $row["ID"];
        }
        
        /**
         * Get schedule's list parted
         */
        $epg_parts = array();
        $result = ScheduleTable::getList(array(
            'filter' => array("=UF_IS_PART" => 1),
            'select' => array("UF_EPG_ID", "ID")
        ));
        while ($row = $result->fetch())
        {
            $epg_parts[] = $row["UF_EPG_ID"];
        }
        
        foreach($this->href_channels as $channel_epg_id => $xml_urls)
        {
            $arScheduleIdsNotDelete = array();
            $arChannel = $arChannels[$channel_epg_id];
            $active = intval($arBaseChannels[$arChannel["UF_BASE_EPG_ID"]]["UF_ACTIVE"]);
            
            if(intval($arChannel["ID"])<=0 || empty($arChannel["ID"])  || $active!=1 || empty($channel_epg_id))
                continue;
            
            foreach($xml_urls as $xml_url)
            {
                echo "loading ".$xml_url."\r\n";
                
                $xml = simplexml_load_file($xml_url);

                //$attr = $xml->channel->attributes();
                //$channel_epg_id = (string)$attr["id"];
                
                $prev_title = false;
                $first_id = false;
                $date_end = false;
                $prev_ids = array();
                
                /**
                 * Update Progs
                 */
                foreach($xml->programme as $arProg)
                {
                    $_arProg = $arProg;
                    $json = json_encode($arProg);
                    $arProg = json_decode($json, TRUE);
                    
                    $arFields = array(
                        "UF_ACTIVE" => 1,
                        "UF_TITLE" => trim((string)$arProg["title"]),
                        "UF_DESC" => (string)$_arProg->desc,
                        "UF_YEAR_LIMIT" => (int)$arProg["rating"]["value"],
                        "UF_YEAR" => (string)$arProg["year"]
                    );
                    
                    if(isset($arProg["title"]))
                    {
                        $attr = $_arProg->title->attributes();
                        $arFields["UF_EPG_ID"] = (string)$attr["id"];
                        $prog_epg_id = (string)$attr["id"];
                    }
 
                    if(isset($arProg["sub-title"]) && !empty($arProg["sub-title"]))
                    {
                        $arFields["UF_SUB_TITLE"] = trim($arProg["sub-title"]);
                        $attr = $_arProg->{'sub-title'}->attributes();
                        $arFields["UF_EPG_SUB_ID"] = (string)$attr["id"];
                        $prog_epg_id.= $arFields["UF_EPG_SUB_ID"];
                    }else{
                        
                        //get sub_epg_id from full id if no sub-title
                        $full_epg_id = (string) $arProg["@attributes"]["id"];
                        $pos = strripos($full_epg_id, $prog_epg_id) + strlen($prog_epg_id);
    
                        if($pos!=strlen($full_epg_id))
                        {
                            $epg_sub_id = substr($full_epg_id, $pos);
                            if(!empty($epg_sub_id))
                            {
                                $arFields["UF_EPG_SUB_ID"] = $epg_sub_id;
                                $prog_epg_id.= $arFields["UF_EPG_SUB_ID"];
                            }
                        } 
                    }
                                   
                    if (!array_key_exists($prog_epg_id, $arProgs))
                    {
                        if(!empty($_arProg->{"desc-sub-title"}))
                        {
                            $arFields["UF_SUB_DESC"] = (string) $_arProg->{"desc-sub-title"};
                        }
                        
                        if(!empty($_arProg->{"episode-number"}))
                            $arFields["UF_SERIA"] = (string) $_arProg->{"episode-number"};
                        
                        if(!empty($_arProg->season))
                            $arFields["UF_SEASON"] = (string) $_arProg->season;
                        
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
                        
                        /**
                         * Bugfix: Problem with sub-title id.
                         * If no sub-title id, but it's serial, update previous prog
                         */
                        if(array_key_exists($arFields["UF_EPG_ID"], $arProgs) && $prog_epg_id!=$arFields["UF_EPG_ID"])
                        {
                            $arProgs[$prog_epg_id] = $arProgs[$arFields["UF_EPG_ID"]];
                            unset($arProgs[$arFields["UF_EPG_ID"]]);
                            $prog_id = $arProgs[$prog_epg_id]["ID"];
                            
                            ProgTable::Update($prog_id, $arFields);
                        }else{
                            
                            $result = ProgTable::add($arFields);
                            if ($result->isSuccess())
                            {
                                $prog_id = $result->getId();
                                $arProgs[$prog_epg_id] = array(
                                    "ID" => $prog_id
                                );
                                                   
                            }else{
                                $errors = $result->getErrorMessages();
                            }
                        }
                        
                    }else{
                        $prog_id = $arProgs[$prog_epg_id]["ID"];
                    }
                    
                    //Get biggest picture if not exist for prog
                    if(empty($arProgs[$prog_epg_id]["UF_EPG_IMG"]))
                    {
                        $image_url = CImage::getBiggestByEpgIcons($arProg["icon"]);
                        ProgTable::Update($prog_id, ["UF_EPG_IMG" => $image_url]);
                        //Add to crop  list. After import crop images for progs.
                        $arImagesToCrop[$prog_id] = $image_url;
                    }
                    
                    
                    /**
                     * Adding schedules
                     */
                    $schedule_epg_id = trim((string) $arProg["@attributes"]["id"]);
                    
                    $dateStart = new \Bitrix\Main\Type\DateTime( date("Y-m-d H:i:s", strtotime($arProg["@attributes"]["start"])), 'Y-m-d H:i:s' );
                    $dateEnd = new \Bitrix\Main\Type\DateTime( date("Y-m-d H:i:s", strtotime($arProg["@attributes"]["stop"])), 'Y-m-d H:i:s' );
                    $date = new \Bitrix\Main\Type\Date(date("Y-m-d", strtotime($arProg["date"])), 'Y-m-d');
                    
                    if (!array_key_exists($schedule_epg_id, $arSchedules))
                    {
                        $title = (string)$arProg["title"];
                        if(!empty($arProg["sub-title"]))
                        {
                            $title.= " | ". trim($arProg["sub-title"]);
                        }
                        
                        $arFields = array(
                            "UF_ACTIVE" => 1,
                            "UF_DATE_START" => $dateStart,
                            "UF_DATE_END" => $dateEnd,
                            "UF_DATE" => $date,
                            "UF_CHANNEL_ID" => (int)$arChannel["ID"],
                            "UF_PROG_ID" => (int)$prog_id,
                            "UF_EPG_ID" => $schedule_epg_id,
                            "UF_CODE" => $title." - ". $arProg["@attributes"]["start"],
                            "UF_DATETIME_CREATE" => new \Bitrix\Main\Type\Date(date("Y-m-d H:i:s"), 'Y-m-d H:i:s'),
                            "UF_DATETIME_EDIT" => new \Bitrix\Main\Type\Date(date("Y-m-d H:i:s"), 'Y-m-d H:i:s')
                        );
                        
                        $result = ScheduleTable::add($arFields);
                        if ($result->isSuccess())
                        {
                            $schedule_id = $result->getId();
                            $arSchedules[$schedule_epg_id] = $schedule_id;
                        }else{
                            $errors = $result->getErrorMessages();
                        }
                    }else{
                        $schedule_id = (int) $arSchedules[$schedule_epg_id];
                        
                        $arFields = array(
                            "UF_DATE_START" => $dateStart,
                            "UF_DATE_END" => $dateEnd,
                            "UF_DATE" => $date,
                            "UF_DATETIME_EDIT" => new \Bitrix\Main\Type\Date(date("Y-m-d H:i:s"), 'Y-m-d H:i:s')
                        );
                        
                        //update only not parted
                        if(!in_array($schedule_epg_id, $epg_parts))
                            ScheduleTable::Update($schedule_id, $arFields);
                    }
                    $arScheduleIdsNotDelete[] = $schedule_id;
                }
            }
            
            /**
             * Delete not exist schedule
             */
            if(!empty($arScheduleIdsNotDelete))
            {
                $arsFilter = array(
                    ">=UF_DATE" => new \Bitrix\Main\Type\Date(date("Y-m-d"), 'Y-m-d'),
                    //"!UF_IS_PART" => 1,
                    "=UF_IS_PART" => 0,
                    "=UF_CHANNEL_ID" => (int)$arChannel["ID"]
                );
                $result = ScheduleTable::getList(array(
                    'filter' => $arsFilter,
                    'select' => array("ID", "UF_EPG_ID")
                ));
                while ($row = $result->fetch())
                {
                    if(!in_array($row["ID"], $arScheduleIdsNotDelete))
                    {
                        echo "delete ".$row["ID"]."\r\n";
                        //ScheduleTable::delete($row["ID"]);
                        ScheduleTable::deleteByEpgId($row["UF_EPG_ID"]);
                    }
                }
            }
        }
        
        //Make cut images for imported progs 
        CImage::cropForProgList($arImagesToCrop);
        
        unset($arScheduleIdsNotDelete);
        unset($arImagesToCrop);
        
        //self::guessSerials();
    }
    
    /**
     * Add new serials to the table Serials
     */
    public static function guessSerials()
    {
        /**
         * Get serials list
         */
        $arSerials = array();
        $result = SerialTable::getList(array(
            'filter' => array("!UF_EPG_ID" => false),
            'select' => array("UF_EPG_ID", "ID", "UF_CHANNEL_ID")
        ));
        while ($row = $result->fetch())
        {
            $arSerials[$row["UF_EPG_ID"]] = $row;
        }
        
        /**
         * Get prog's list
         */
        $arProgs = array();
        $result = ProgTable::getList(array(
            'filter' => array(),
            'select' => array("UF_EPG_SUB_ID", "ID", "UF_TITLE", "UF_EPG_ID")
        ));
        while ($row = $result->fetch())
        {
            if(!empty($row["UF_EPG_SUB_ID"]))
                $arProgs[$row["UF_EPG_ID"]] = $row["UF_TITLE"];
        }
        
        foreach($arProgs as $epg_id => $serial_title)
        {
            if(empty($arSerials[$epg_id]))
            {
                $arFields = array(
                    "UF_EPG_ID" => $epg_id,
                    "UF_TITLE" => $serial_title,
                );
                
                $result = SerialTable::add($arFields);
                if ($result->isSuccess())
                {
                    $serial_id = $result->getId();
                    $arSerials[$epg_id] = array(
                        "ID" => $serial_id
                    );
                }
            }
        }
    }
    
    /**
     * Clear tables & deleting images
     */
    public static function clear()
    {
        ProgTable::deleteAll();
        ScheduleTable::deleteAll();
        //ChannelTable::deleteAll();
        \CDev::deleteOldFiles($_SERVER["DOCUMENT_ROOT"]. self::$cut_dir, 0);
    }
}