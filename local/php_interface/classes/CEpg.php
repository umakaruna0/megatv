<?
class CEpg
{
    private static $ftpUrl = 'xmldata.epgservice.ru';
    private static $ftpLogin = 'saturn';
    private static $ftpPasss = '7M3Z17cp';
    private static $ftpFile = 'TV_Pack.xml';
    
    public function __construct($dir = false)
    {
        if(!$dir)
            $dir = FULL_PATH_DOCUMENT_ROOT."/local/";
            
        $this->dir = $dir;
        $this->file = $this->dir.self::$ftpFile;
    }
    
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
    
    public function import()
    {
        if (file_exists($this->file))
            $xml = simplexml_load_file($this->file);
        
        $log_file = "/logs/import_".date("d_m_Y_H_i").".txt";
        
        $epgUrl = CXmlEx::getAttr($xml, "generator-info-url");
        $epgName = CXmlEx::getAttr($xml, "generator-info-name");
        
        //Расписание, которое нужно удалить
        $arProgTimeDelete = array();
        
        //получим все каналы из кэша
        CChannel::updateCache();
        $arChannels = CChannel::getList(false, array("ID", "PROPERTY_EPG_ID"));
        
        foreach($xml->channel as $arChannel)
        {
            $id = (string)CXmlEx::getAttr($arChannel, "id");
            $name = (string)$arChannel->{'display-name'};
            
            $icon = (string)CXmlEx::getAttr($arChannel->icon, "src");
            $icon = "http://". $icon;
            
            if(!isset($arChannels[$id]))
            {
                $arFields = array(
                    "EPG_ID" => $id,
                    "NAME" => $name,
                    "ICON_SRC" => $icon
                );
                $newID = CChannel::add($arFields);
                if(intval($newID)==0)
                {
                    echo $newID."\r\n<br />";
                }else{
                    echo "Added channel  ".$newID."\r\n<br />";
                }
            }
        }
        
        //сбросим и обновим кэш после загрузки
        CChannel::updateCache();
        $arChannels = CChannel::getList(false, array("ID", "PROPERTY_EPG_ID", "ACTIVE"));

        //список программ и время вещания из кэша
        CProg::updateCache();
        CProgTime::updateCache();        
        $arProgs = CProg::getList(false, array("ID", "NAME", "PREVIEW_TEXT", "PROPERTY_CHANNEL", "PROPERTY_SUB_TITLE"));
        $arProgTimes = CProgTime::getList(false, array("ID", "PROPERTY_CHANNEL", "PROPERTY_DATE_START"));
        
        //die();
        foreach($xml->programme as $arProg)
        {
            $json = json_encode($arProg);
            $arProg = json_decode($json, TRUE);
            
            $arChannel = $arChannels[$arProg["@attributes"]["channel"]];
            
            if(intval($arChannel["ID"])==0 || $arChannel["ACTIVE"]=="N")
                continue;
            
            $arFields = array(
                "FIELDS" => array(
                    "NAME" => trim($arProg["title"]),
                    "PREVIEW_TEXT" => $arProg["desc"],
                    "DETAIL_TEXT" => $arProg["desc"],
                ),
                "PROPS" => array(
                    "CHANNEL" => $arChannel["ID"],
                    "YEAR_LIMIT" => $arProg["rating"]["value"],
                    "YEAR" => $arProg["year"]
                )
            );
            
            if(isset($arProg["sub-title"]))
            {
                $arFields["PROPS"]["SUB_TITLE"] = trim($arProg["sub-title"]);
            }
            
            //генерируем идентификатор программы для проверки на существование
            if(!empty($arFields["PROPS"]["SUB_TITLE"]))
            {
                $progName = $arFields["FIELDS"]["NAME"]." (".trim($arFields["PROPS"]["SUB_TITLE"]).")";
            }else{
                $progName = $arFields["FIELDS"]["NAME"];
            }
            
            if(!is_array($arProg["credits"]["actor"]))
                $arProg["credits"]["actor"] = array($arProg["credits"]["actor"]);
            $arFields["PROPS"]["ACTOR"] = implode(", ", $arProg["credits"]["actor"]);
            
            if(!is_array($arProg["credits"]["presenter"]))
                $arProg["credits"]["presenter"] = array($arProg["credits"]["presenter"]);
            $arFields["PROPS"]["PRESENTER"] = implode(", ", $arProg["credits"]["presenter"]);
            
            $unique = CProg::generateUnique(array(
                "CHANNEL" => intval($arFields["PROPS"]["CHANNEL"]),
                "NAME" => $progName,
                "DESC" => $arFields["FIELDS"]["PREVIEW_TEXT"],
                "ACTOR" => $arFields["PROPS"]["ACTOR"],
                "PRESENTER" => $arFields["PROPS"]["PRESENTER"],
            ));
            
            if (!array_key_exists($unique, $arProgs))
            {
                if(isset($arProg['episode-num']))
                {
                    $arFields["PROPS"]["SERIA"] = intval($arProg['episode-num']);
                }
                if(isset($arProg['season-num']))
                {
                    $arFields["PROPS"]["SEASON"] = intval($arProg['season-num']);
                }
                
                if(!is_array($arProg["category"]))
                    $arProg["category"] = array($arProg["category"]);
                $arFields["PROPS"]["CATEGORY"] = implode(", ", $arProg["category"]);
                
                if(!is_array($arProg["topic"]))
                    $arProg["topic"] = array($arProg["topic"]);
                $arFields["PROPS"]["TOPIC"] = implode(", ", $arProg["topic"]);
                
                if(!is_array($arProg["country"]))
                    $arProg["country"] = array($arProg["country"]);
                $arFields["PROPS"]["COUNTRY"] = implode(", ", $arProg["country"]);

                if(!is_array($arProg["credits"]["director"]))
                    $arProg["credits"]["director"] = array($arProg["credits"]["director"]);
                $arFields["PROPS"]["DIRECTOR"] = implode(", ", $arProg["credits"]["director"]);
                
                $progID = CProg::add($arFields);
                if(intval($progID)==0)
                {
                    CDev::log(array(
                        "ERROR" => $progID,
                        "PROG" => $arFields,
                    ), false, $log_file);
                    
                    echo $progID."<br />";
                }else{
                    echo "Added prog ".$progID."<br />";
                    
                    //Добавляем в массив, чтобы дубляжа при подании одинаковой передачи
                    $arProgs[$unique] = array(
                        "ID" => $progID
                    );
                }
            }else{
                
                $progID = $arProgs[$unique]["ID"];
            }
            
            //Добавление расписания для программы
            $dateStart = $arProg["@attributes"]["start"];
            
            $uniqueTimeID = CProgTime::generateUnique(array(
                "CHANNEL" => $arChannel["ID"],
                "DATE_START" => date("d.m.Y H:i:s", strtotime($dateStart))
            ));
            
            //Если дата меньше сегодняшне - не грузим расписание
            if( CTimeEx::dateDiff( date("d.m.Y", strtotime($arProg["date"])), date("d.m.Y") ) )
                continue;
            
            if(!isset($arProgTimes[$uniqueTimeID]) && intval($progID)>0)  
                echo $uniqueTimeID."<br />";
            
            if(!isset($arProgTimes[$uniqueTimeID]) && intval($progID)>0)
            {
                $arFields = array(
                    "FIELDS" => array(
                        "NAME" => $progName,
                    ),
                    "PROPS" => array(
                        "DATE_START" => $dateStart,
                        "DATE_END" => $arProg["@attributes"]["stop"],
                        "DATE" =>  date("d.m.Y", strtotime($arProg["date"])),
                        "CHANNEL" => $arChannel["ID"],
                        "PROG" => $progID
                    )
                );
                $progTimeID = CProgTime::add($arFields);
                if(intval($progTimeID)==0)
                {
                    CDev::log(array(
                        "ERROR" => $progTimeID,
                        "PROG" => $arFields,
                    ), false, $log_file);
                    echo $progTimeID."<br />";
                }else{
                    $arProgTimes[$uniqueTimeID] = array(
                        "ID" => $progTimeID
                    );
                    echo "Added prog schedule ".$progTimeID."<br />";
                }
            }
        }
        CProg::updateCache();
        CProgTime::updateCache();
    }
}