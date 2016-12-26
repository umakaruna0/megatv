<?
namespace Hawkart\Megatv\Social;

class YoutubeClient
{
    protected static $dev_key;
    
    /**
     * Get dev_key from db
     */
    public function __construct()
    {
        \CModule::IncludeModule("iblock");
        
        $arrFilter = array(
            "IBLOCK_ID" => SOCIAL_CONFIG_IB,
            "PROPERTY_PROVIDER" => "Youtube",
            "PROPERTY_SOCIAL_ID" => $userProfile["identifier"]
        );
        $arSelect = array("PROPERTY_SECRET");
        $rsRes = \CIBlockElement::GetList( $arOrder, $arrFilter, false, false, $arSelect);
		if( $arItem = $rsRes->GetNext() )
        {
            self::$dev_key = $arItem["PROPERTY_SECRET_VALUE"];
		}
    }
    
    /**
     * Import all serials href from resource to db 
     */
    public static function importSerials()
    {
        $arToFile = array();
        $file_50_60 = $_SERVER['DOCUMENT_ROOT']."/upload/serials_50_60.txt";
        
        /**
         * Get all prog which are serials
         */
        $arSerials = array();
        $date_prevprev = date('Y-m-d', strtotime("-2 day"));
        $arFilter = array(
            ">=UF_DATE_START" => new \Bitrix\Main\Type\DateTime($date_prevprev." 00:00:00", 'Y-m-d H:i:s'),
            "<UF_DATE_START" => new \Bitrix\Main\Type\DateTime($date_prevprev." 23:59:59", 'Y-m-d H:i:s'),
            "!UF_PROG.UF_EPG_SUB_ID" => false
        );
        $arSelect = array(
            "ID", "SID" => "UF_PROG.UF_EPG_ID", "TITLE" => "UF_PROG.UF_TITLE", "SUB_TITLE" => "UF_PROG.UF_SUB_TITLE",
            "SERIA" => "UF_PROG.UF_SERIA", "SEASON" => "UF_PROG.UF_SEASON", "UF_DATE_START", "UF_DATE_END"
        );
        $result = \Hawkart\Megatv\ScheduleTable::getList(array(
            'filter' => $arFilter,
            'select' => $arSelect,
        ));
        while ($arSchedule = $result->fetch())
        {
            $arSchedule["UF_DATE_START"] = $arSchedule["DATE_START"] = $arSchedule['UF_DATE_START']->toString();
            $arSchedule["UF_DATE_END"] = $arSchedule["DATE_END"] = $arSchedule['UF_DATE_END']->toString();
            $arSerials[] = $arSchedule;
        }
        
        /**
         * Get all serials with epg_id
         */
        $arTableSerials = array();
        $result = \Hawkart\Megatv\SerialTable::getList(array(
            'filter' => array("!UF_EPG_ID" => false),
            'select' => array("ID", "UF_EPG_ID", "UF_CHANNEL_ID")
        ));
        while ($row = $result->fetch())
        {
            $arTableSerials[$row["UF_EPG_ID"]] = $row;
        }
        
        foreach($arSerials as $arProg)
        {
            $title = $arProg["TITLE"];
            if(!empty($arProg["SUB_TITLE"]))
            {
                $title = $arProg["SUB_TITLE"].". ".$title;
            }else{
                if(!empty($arProg["SEASON"]))
                    $title.= ": сезон ".$arProg["SEASON"];
                if(!empty($arProg["SERIA"]))
                    $title.= " серия ".$arProg["SERIA"];
            }
            
            $seria_secs = strtotime($arProg["DATE_END"]) - strtotime($arProg["DATE_START"]);
            
            $youtube = new \Hawkart\Megatv\Social\YoutubeClient();       
            $arVideos = $youtube->search($title);
            
            $arSerial = $arTableSerials[$arProg["SID"]];
            
            if(empty($arSerial))
                continue;
            
            //Progs in table by serial_id
            $arTableItems = array();
            $result = \Hawkart\Megatv\ProgExternalTable::getList(array(
                'filter' => array("=UF_SERIAL_ID" => $arSerial["ID"]),
                'select' => array("ID", "UF_SERIAL_ID",  "UF_EXTERNAL_ID")
            ));
            while ($row = $result->fetch())
            {
                $arTableItems[] = $row["UF_EXTERNAL_ID"];
            }    
            
            foreach($arVideos as $arItem)
            {
                //secs from youtube
                $time_parts = explode(":", $arItem["TIME"]);
                $secs = 60*$time_parts[count($time_parts)-2] + $time_parts[count($time_parts)-1];
                if(count($time_parts)==3)
                {
                    $secs = 60*intval($time_parts[0]) + intval($time_parts[1]);
                }else{
                    $secs = 3600*intval($time_parts[0]) + 60*intval($time_parts[1]) + intval($time_parts[2]);
                }
                $time_perc = $secs/$seria_secs*100;
                
                similar_text($title, $arItem["TITLE"], $perc);
                similar_text($arItem["TITLE"], $title, $perc_2);
                
                if( (floatval($perc)<60 && floatval($perc)>=40)/* || $time_perc<70*/)
                {
                    $arToFile[] = array(
                        "ORIGINAL_TITLE" => $title,
                        "SOCIAL_TITLE" => $arItem["TITLE"],
                        "PERCENT" => $perc,
                        "EXTERNAL_ID" => $arItem["ID"],
                        "TIME" => $arItem["TIME"],
                        "UF_SERIAL_ID" => $arSerial["ID"]
                    );
                }
                
                if(floatval($perc)>60 && floatval($perc_2)>40 && $time_perc>70)
                {
                    if(!in_array($arItem["ID"], $arTableItems))
                    {
                        $datetime = new \Bitrix\Main\Type\DateTime( date("Y-m-d H:i:s", strtotime($arItem["JSON"]["publishedAt"])), 'Y-m-d H:i:s'); 
                        $arFields = array(
                            "UF_TITLE" => $arItem["TITLE"],
                            "UF_EXTERNAL_ID" => $arItem["ID"],
                            "UF_SERIAL_ID" => $arSerial["ID"],
                            "UF_THUMBNAIL_URL" => $arItem["THUMBNAIL_URL"],
                            "UF_VIDEO_URL" => $arItem["VIDEO_URL"],
                            "UF_JSON" => $arItem["JSON"],
                            "UF_DATETIME" => $datetime
                        );
                        
                        $result = \Hawkart\Megatv\ProgExternalTable::add($arFields);
                        if ($result->isSuccess())
                        {
                            $arTableItems[] = $arItem["ID"];
                        }else{
                            $errors = $result->getErrorMessages();
                        }  
                    }
                    
                    break;
                }
            }
        }
        
        file_put_contents($file_50_60, json_encode($arToFile));
    }
    
    /**
     * Import all serials href from resource to db 
     */
    public static function importYoutubeSerials()
    {        
        /**
         * Get all serials with epg_id
         */
        $arTableSerials = array();
        $result = \Hawkart\Megatv\SerialTable::getList(array(
            'filter' => array("!UF_EPG_ID" => false),
            'select' => array("ID", "UF_EPG_ID", "UF_CHANNEL_ID", "UF_SOURCES")
        ));
        while ($row = $result->fetch())
        {
            $row["UF_SOURCES"] = explode(", ", $row["UF_SOURCES"]);
            $arTableSerials[$row["UF_EPG_ID"]] = $row;
        }
        
        foreach($arTableSerials as $arSerial)
        {
            $youtube = new \Hawkart\Megatv\Social\YoutubeClient();
            
            $playlist_link = false;
            $arVideos = false;
            foreach($arSerial["UF_SOURCES"] as $link)
            {
                $link = trim($link);
                $playlist_link = self::parseUrlToPlayList($link);
                
                echo $playlist_link;
                
                if($playlist_link)
                {
                    $videos = self::parseUrl("https://www.youtube.com".$playlist_link, 3, false);
                }else{
                    $videos = self::parseUrl($link, 3, false);
                }
                
                $arVideos = self::getVideosByIds($videos);
                
                //print_r($arVideos);
                
                $arTableItems = array();
                $result = \Hawkart\Megatv\ProgExternalTable::getList(array(
                    'filter' => array("=UF_SERIAL_ID" => $arSerial["ID"]),
                    'select' => array("ID", "UF_SERIAL_ID",  "UF_EXTERNAL_ID")
                ));
                while ($row = $result->fetch())
                {
                    $arTableItems[] = $row["UF_EXTERNAL_ID"];
                }    
                
                if(count($arVideos)==0)
                    continue;
                
                foreach($arVideos as $arItem)
                {
                    if(!in_array($arItem["ID"], $arTableItems))
                    {
                        $datetime = new \Bitrix\Main\Type\DateTime( date("Y-m-d H:i:s", strtotime($arItem["JSON"]["publishedAt"])), 'Y-m-d H:i:s'); 
                        $arFields = array(
                            "UF_TITLE" => $arItem["TITLE"],
                            "UF_EXTERNAL_ID" => $arItem["ID"],
                            "UF_SERIAL_ID" => $arSerial["ID"],
                            "UF_THUMBNAIL_URL" => $arItem["THUMBNAIL_URL"],
                            "UF_VIDEO_URL" => $arItem["VIDEO_URL"],
                            "UF_JSON" => $arItem["JSON"],
                            "UF_DATETIME" => $datetime
                        );
                        
                        $result = \Hawkart\Megatv\ProgExternalTable::add($arFields);
                        if ($result->isSuccess())
                        {
                            $arTableItems[] = $arItem["ID"];
                        }else{
                            $errors = $result->getErrorMessages();
                        }  
                    }
                }
            
            }
        }
    }
    
    public static function importByExternalID($id, $serial_id = false)
    {
        $arVideos = self::getVideosByIds(array(
            $id => " "
        ));
        
        $arItem = $arVideos[0];
        
        //print_r($arItem);
        
        if(!empty($arItem))
        {
            $datetime = new \Bitrix\Main\Type\DateTime( date("Y-m-d H:i:s", strtotime($arItem["JSON"]["publishedAt"])), 'Y-m-d H:i:s'); 
            $arFields = array(
                "UF_TITLE" => $arItem["TITLE"],
                "UF_EXTERNAL_ID" => $arItem["ID"],
                "UF_SERIAL_ID" => $serial_id,
                "UF_THUMBNAIL_URL" => $arItem["THUMBNAIL_URL"],
                "UF_VIDEO_URL" => $arItem["VIDEO_URL"],
                "UF_JSON" => $arItem["JSON"],
                "UF_DATETIME" => $datetime
            );
            
            $result = \Hawkart\Megatv\ProgExternalTable::add($arFields);
            if ($result->isSuccess())
            {
                $arTableItems[] = $arItem["ID"];
            }else{
                $errors = $result->getErrorMessages();
            }
            
            $arItems = array();
            $file = $_SERVER['DOCUMENT_ROOT']."/upload/serials_50_60.txt";
            $json = file_get_contents($file);
            $arProgs = json_decode($json, true);
            foreach($arProgs as $arProg)
            {
                if($arProg["EXTERNAL_ID"]!=$id)
                    $arItems[] = $arProg;
            }
            
            file_put_contents($file, json_encode($arItems));
        }
    }
    
    public static function search($title)
    {
        $url = "https://www.youtube.com/results?sp=EgIIAw%253D%253D&q=".urlencode($title);
        $arIds = self::parseUrl($url);
        $arVideos = self::getVideosByIds($arIds);
        
        if(!empty($arVideos))
        {
            return $arVideos;
        }
    }
    
    public static function parseUrlToPlayList($url, $timeout = 5)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $html = curl_exec($ch);
        curl_close($ch);
        
        $dom = new \DOMDocument();

        $videos = array();
        @$dom->loadHTML($html);
        
        $playlist_count = 0;
        $ids = array();
        foreach($dom->getElementsByTagName('a') as $link) 
        {
            $href = $link->getAttribute('href');
            
            if(strpos($href, "http://www.youtube.com")!==false)
                continue;
            
            if(strpos($href, "/playlist?list=")!==false)
            {
                if($playlist_count==1)
                {
                    return $href;
                }
                $playlist_count++;
            }
        }
        return false;
    }
    
    /**
     * Parse video urls from web page
     * 
     * @return array
     */
    public static function parseUrl($url, $timeout = 5, $need_time=true)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $html = curl_exec($ch);
        curl_close($ch);
        
        $dom = new \DOMDocument();

        $videos = array();
        @$dom->loadHTML($html);

        $ids = array();
        foreach($dom->getElementsByTagName('a') as $link) 
        {
            $href = $link->getAttribute('href');
            
            if(strpos($href, "/watch?v=")!==false && strpos($href, "https://www.youtube.com")===false)
            {
                $time = (string)trim($link->nodeValue);
                $vid = str_replace(array("/watch?v=", "https://www.youtube.com", "http://www.youtube.com"), "", $href);
                
                if(!$need_time && !array_key_exists($vid, $ids))
                {
                    $pos = strpos($vid, "&");
                    if($pos!==false)
                    {
                        $vid = substr($vid, 0, $pos);
                    }
                    $videos[$vid] = $vid;
                }
                else if(!array_key_exists($vid, $ids) && preg_match(' /^[0-9\:]+$/', $time))
                {
                    $videos[$vid] = $time; 
                }
                $ids[] = $vid;
            }
        }
        return $videos;
    }
    
    public function getVideosByIds($videos = array())
    { 
        $client = new \Google_Client();
        $client->setDeveloperKey(self::$dev_key);
        $youtube = new \Google_Service_YouTube($client);
        $ids = array();
        
        $videoIds = array();
        foreach($videos as $vid=>$time)
        {
            $videosResponse = $youtube->videos->listVideos('snippet, recordingDetails', array(
                'id' => $vid,
            ));

            foreach ($videosResponse['items'] as $videoResult) 
            {
                if(in_array($videoResult["id"], $ids))
                    continue;
                
                if(!empty($videoResult['snippet']['thumbnails']["high"]["url"]))
                    $img = $videoResult['snippet']['thumbnails']["high"]["url"];
                else
                    $img = $videoResult['snippet']['thumbnails']["standard"]["url"]; 
    
                $arVideo = array(
                    "TITLE" => $videoResult['snippet']['title'],
                    "ID" => $videoResult["id"],
                    "VIDEO_URL" => "https://www.youtube.com/watch?v=".$videoResult["id"],
                    "THUMBNAIL_URL" => $img,
                    "JSON" => $videoResult['snippet'],
                    "TIME" => $videos[$videoResult["id"]]
                );
        
                $ids[] = $videoResult["id"];
                $arVideos[] = $arVideo;
            }
        }
        
        return $arVideos;
    }
    
    /**
     * Get one item by id
     *
     * @array return
     */
    public static function getVideoById($video_id)
    {
        if(!$video_id)
            return false;
        
        $json = self::api('video/'.$video_id, array());
        
        $arVideo = array(
            "TITLE" => $json["title"],
            "ID" => $json["id"],
            "VIDEO_URL" => $json["embed_url"],
            "THUMBNAIL_URL" => $json["thumbnail_url"],
            "JSON" => $json
        );
        
        return $arVideo;
    }
}