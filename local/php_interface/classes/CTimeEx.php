<?
class CTimeEx
{
    public static function getDatetime()
    {
        global $APPLICATION;
        
        $arCity = CCityEx::getGeoCity();
        $offset = intval($arCity["PROPERTY_OFFSET_VALUE"]); //сдвиг относительно время сервера
        
        $arResult = array();
        $arResult["SERVER_DATETIME"] = date("d.m.Y H:i:s");     //серверная дата 
        $arResult["OFFSET"] = $offset;  //сдвиг относительно Москвы (берется из города)
        $arResult["SERVER_DATETIME_WITH_OFFSET"] = self::dateOffset($offset, $arResult["SERVER_DATETIME"]);
        $arResult["SELECTED_DATE"] = $_SESSION["DATE_CURRENT_SHOW"];
        $arResult["SELECTED_DATETIME"] = $_SESSION["DATE_CURRENT_SHOW"].date(" H:i:s");
        $arResult["SELECTED_DATETIME_WITH_OFFSET"] = self::dateOffset($offset, $arResult["SELECTED_DATETIME"]);
        return $arResult;
    }
    
    public static function datetimeForFilter($datetime, $add = false)
    {
        /*if($add)
        {
            return date("Y-m-d H:i:s", strtotime($add, strtotime($datetime)));
        }else{
            return date("Y-m-d H:i:s", strtotime($datetime));
        }*/
        return date("Y-m-d H:i:s", strtotime("-3 hour ".$add, strtotime($datetime))); 
    }
    
    public static function getCalendarDays()
    {
        $arDate = self::getDatetime();
        $datetime = $arDate["SERVER_DATETIME_WITH_OFFSET"];
        
        //$w = date("w");
        $w = date("w", strtotime($datetime));                
        $dateFullDownload = 6;
        
        //понедельник
        if($w==0) $w = 7;
        
        //Меньше дня выгрузки
        if($w<$dateFullDownload) $w+=7;
            
        $countDays = 10 - ($w-$dateFullDownload);
        $countDays--;
        return $countDays;
    }
    
    
    
    public static function getCurDate()
    {
        global $APPLICATION;
        if($APPLICATION->GetCurDir()=="/" && !isset($_GET["cur_date"]))
            $_SESSION["DATE_CURRENT_SHOW"] = date("d.m.Y");
        
        if(isset($_GET["cur_date"]) && !empty($_GET["cur_date"]))
        {
            $date = substr($_GET["cur_date"], 0, 10);
            $date = date("d.m.Y", strtotime($date));
        }else{
            if(!isset($_SESSION["DATE_CURRENT_SHOW"]) || empty($_SESSION["DATE_CURRENT_SHOW"]))
            {
                $date = date("d.m.Y");  //текущая дата сервера
            }else{
                $date = $_SESSION["DATE_CURRENT_SHOW"];
            }  
        }
        
        $_SESSION["DATE_CURRENT_SHOW"] = $date;
        
        return $date;
    }
    
    public static function dateOffset($offset, $datetime)
    {
        return date('d.m.Y H:i:s', strtotime($offset." hour", strtotime($datetime)));
    }
    
    //Высчитываем с учетом города сдвиг по времени относительно дня
    public static function getDateTimeOffset($offset = 0)
    {       
        if(!$offset)
        {
            $arCity = CCityEx::getGeoCity();
            $offset = intval($arCity["PROPERTY_OFFSET_VALUE"]); //сдвиг относительно время сервера
        }
        
        $date = self::getCurDate();
        $date = substr($date, 0, 10).date(" 00:00:00");
            
        $next_date = date('d.m.Y 00:00:00', strtotime("+1 day", strtotime($date)));
           
        $arDate = array(
            "DATE_FROM" => self::dateOffset($offset, $date),    //дата со смещением
            "DATE_TO" => self::dateOffset($offset, $next_date), //дата со смещением +1 день
            "OFFSET" => $offset,    //сдвиг
            "DATETIME_REAL" => date("d.m.Y H:i:s"), //дата настоящая без сдвигов и выборов
            "DATETIME_CURRENT" => substr($date, 0, 10).date(" H:i:s"),  //дата выбранная без сдвига
        );
        
        return $arDate;
    }
    
    public static function dateToStr($date = false)
    {
        if(!$date)
            $date = self::getCurDate();
            
        $arDATE = ParseDateTime($date, FORMAT_DATETIME);
        return $arDATE["DD"]." ".ToLower(GetMessage("MONTH_".intval($arDATE["MM"])."_S"))." ".$arDATE["YYYY"];
    }
    
    public static function dateDiff($date1, $date2)
    {
        return strtotime($date1)<strtotime($date2);
    }
    
    public static function secToTime($sec) 
    {
        if ($sec <= 0)
            return false;
            
        $hh = floor($sec/3600); 
        $min = floor(($sec-$hh*3600)/60); 
        $sec = $sec-$hh*3600-$min*60; 
    
        return array(
            "h" => $hh,
            "i" => $min,
            "s" => $sec
        );
    }
    
    public static function secToStr($sec)
    {
        $arTime = self::secToTime($sec);
        
        $str = "";
        if($arTime["h"])
        {
            $str.= $arTime["h"]." ".CDev::number_ending($arTime["h"], "часов", "час", "часа")." ";
        }
        if($arTime["i"])
        {
            $str.= $arTime["i"]." ".CDev::number_ending($arTime["i"], "минут", "минута", "минуты");
        }
        return $str;
    }
}