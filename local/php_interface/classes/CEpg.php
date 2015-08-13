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
            echo "Произведена запись в $this->file \n";
        } else {
            echo "Не удалось завершить операцию\n";
        }
        
        // закрытие соединения
        ftp_close($conn);
    }
    
    public function import()
    {
        if (file_exists($this->file))
            $xml = simplexml_load_file($this->file);
        
        $epgUrl = CXmlEx::getAttr($xml, "generator-info-url");
        $epgName = CXmlEx::getAttr($xml, "generator-info-name");
        
        //получим все каналы из кэша
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
                    echo $newID."<br />";
                }else{
                    echo "Добавлен канал  ".$newID."<br />";
                }
            }
        }
        
        //сбросим и обновим кэш после загрузки
        CChannel::updateCache();
        $arChannels = CChannel::getList();
        
        //список программ и время вещания из кэша
        $arProgs = CProg::getList();
        $arProgTimes = CProgTime::getList();
        
        foreach($xml->programme as $arProg)
        {
            $arChannel = $arChannels[(string)CXmlEx::getAttr($arProg, "channel")];
            $arFields = array(
                "FIELDS" => array(
                    "NAME" => (string)$arProg->title,
                    "PREVIEW_TEXT" => (string)$arProg->desc,
                    "DETAIL_TEXT" => (string)$arProg->desc,
                ),
                "PROPS" => array(
                    //"DATE_START" => (string)CXmlEx::getAttr($arProg, "start"),
                    //"DATE_END" => (string)CXmlEx::getAttr($arProg, "stop"),
                    //"DATE" =>  date("d.m.Y", strtotime((string)$arProg->date)),
                    "CHANNEL" => $arChannel["ID"],
                    "RATING" => (int)$arProg->rating->value,
                    "YEAR" => (string)$arProg->year
                )
            );
            
            if(isset($arProg->{'sub-title'}))
            {
                $arFields["PROPS"]["SUB_TITLE"] = (string)$arProg->{'sub-title'};
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
                if(isset($arProg->{'episode-num'}))
                {
                    $arFields["PROPS"]["SERIA"] = intval($arProg->{'episode-num'});
                }
                if(isset($arProg->{'season-num'}))
                {
                    $arFields["PROPS"]["SEASON"] = intval($arProg->{'season-num'});
                }
                
                $category = (array)$arProg->category;
                unset($category["@attributes"]);
                if(count($category)>0)
                {
                    $arFields["PROPS"]["CATEGORY"] = implode(", ", $category);
                }
                
                $topic = (array)$arProg->topic;
                unset($topic["@attributes"]);
                if(count($topic)>0)
                {
                    $arFields["PROPS"]["TOPIC"] = implode(", ", $topic);
                }
                
                $country = (array)$arProg->country;
                unset($country["@attributes"]);
                if(count($country)>0)
                {
                    $arFields["PROPS"]["COUNTRY"] = implode(", ", $country);
                }
                
                $director = (array)$arProg->credits->director;
                unset($director["@attributes"]);
                if(count($director)>0)
                {
                    $arFields["PROPS"]["DIRECTOR"] = implode(", ", $director);
                }
                
                $actor = (array)$arProg->credits->actor;
                unset($actor["@attributes"]);
                if(count($actor)>0)
                {
                    $arFields["PROPS"]["ACTOR"] = implode(", ", $actor);
                }
                
                $presenter = (array)$arProg->credits->presenter;
                unset($presenter["@attributes"]);
                if(count($presenter)>0)
                {
                    $arFields["PROPS"]["PRESENTER"] = implode(", ", $presenter);
                }
                
                $progID = CProg::add($arFields);
                if(intval($progID)==0)
                {
                    echo $progID."<br />";
                }else{
                    echo "Добавлена программа ".$progID."<br />";
                }
            }else{
                $progID = $arProgs[$unique]["ID"];
            }
            
            
            
            //Добавление расписания для программы
            $dateStart = (string)CXmlEx::getAttr($arProg, "start");
            
            $uniqueTimeID = CProgTime::generateUnique(array(
                "CHANNEL" => $arChannel["ID"],
                "DATE_START" => date("d.m.Y H:i:s", strtotime($dateStart))
            ));
            
            if(!isset($arProgTimes[$uniqueTimeID]))
            {
                $arFields = array(
                    "FIELDS" => array(
                        "NAME" => $progName,
                    ),
                    "PROPS" => array(
                        "DATE_START" => $dateStart,
                        "DATE_END" => (string)CXmlEx::getAttr($arProg, "stop"),
                        "DATE" =>  date("d.m.Y", strtotime((string)$arProg->date)),
                        "CHANNEL" => $arChannel["ID"],
                        "PROG" => $progID
                    )
                );
                $progTimeID = CProgTime::add($arFields);
                if(intval($progTimeID)==0)
                {
                    echo $progTimeID."<br />";
                }else{
                    echo "Добавлено время программы ".$progTimeID."<br />";
                }
            }
            
            //echo "<pre>"; print_r($arProg); echo "</pre>";
            //echo "<pre>"; print_r($arFields); echo "</pre>";
            //die();
        }
        CProg::updateCache();
        CProgTime::updateCache();
    }
}