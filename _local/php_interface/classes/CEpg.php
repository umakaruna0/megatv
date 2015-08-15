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
        
        define("LOG_FILENAME", "/logs/import_".date("d_m_Y_H_i").".php");
        
        $epgUrl = CXmlEx::getAttr($xml, "generator-info-url");
        $epgName = CXmlEx::getAttr($xml, "generator-info-name");
        
        //Расписание, которое нужно удалить
        $arProgTimeDelete = array();
        
        //получим все каналы из кэша
        CChannel::updateCache();
        $arChannels = CChannel::getList();
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
        $arChannels = CChannel::getList();

        //список программ и время вещания из кэша
        CProg::updateCache();
        //CProgTime::updateCache();        
        
        $arProgs = CProg::getList();
        $arProgTimes = CProgTime::getList();
        
        foreach($xml->programme as $arProg)
        {
            $json = json_encode($arProg);
            $arProg = json_decode($json, TRUE);
            
            $arChannel = $arChannels[$arProg["@attributes"]["channel"]];
            
            if(intval($arChannel["ID"])==0)
                continue; 
            
            $arFields = array(
                "FIELDS" => array(
                    "NAME" => $arProg["title"],
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
                $arFields["PROPS"]["SUB_TITLE"] = $arProg["sub-title"];
            }
            
            //генерируем идентификатор программы для проверки на существование
            if(!empty($arFields["PROPS"]["SUB_TITLE"]))
            {
                $progName = trim($arFields["FIELDS"]["NAME"]." (".$arFields["PROPS"]["SUB_TITLE"]).")";
            }
            $unique = CProg::generateUnique(array(
                "CHANNEL" => $arChannel["ID"],
                "NAME" => $progName,
                "DESC" => $arFields["FIELDS"]["PREVIEW_TEXT"]
            ));
            if(!isset($arProgs[$unique]))
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
                
                if(!is_array($arProg["credits"]["actor"]))
                    $arProg["credits"]["actor"] = array($arProg["credits"]["actor"]);
                $arFields["PROPS"]["ACTOR"] = implode(", ", $arProg["credits"]["actor"]);
                
                if(!is_array($arProg["credits"]["presenter"]))
                    $arProg["credits"]["presenter"] = array($arProg["credits"]["presenter"]);
                $arFields["PROPS"]["PRESENTER"] = implode(", ", $arProg["credits"]["presenter"]);                
                
                //echo "<pre>"; print_r($arFields); echo "</pre>"; die();

                $progID = CProg::add($arFields);
                if(intval($progID)==0)
                {
                    CDev::log(array(
                        "ERROR" => $progID,
                        "PROG" => $arFields,
                    ));
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
            if( CTimeEx::dateDiff($arProg["date"], CTimeEx::getCurDate()) )
                continue;
            
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
                    ));
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