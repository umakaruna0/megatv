<?
class CTimeEx
{
    public static function getCalendarDays()
    {
        $w = date("w");
                
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
        if(isset($_GET["DATE_CURRENT_SHOW"]) && !empty($_GET["DATE_CURRENT_SHOW"]))
        {
            $date = str_replace("date-", "", $_GET["DATE_CURRENT_SHOW"]);
            $date = date("d.m.Y", strtotime($date));
        }else{
            $date = date("d.m.Y");  //текущая дата пользователя
        }
        
        return $date;
    }
    
    public static function dateOffset($offset, $datetime)
    {
        return date('d.m.Y H:i:s', strtotime($offset." hour", strtotime($datetime)));
    }
    
    //Высчитываем с учетом города сдвиг по времени
    public static function getCurDateTime($offset = 0)
    {       
        if(!$offset)
        {
            $arCity = CCityEx::getGeoCity();
            $offset = $arCity["PROPERTIES"]["OFFSET"]["VALUE"];
        }
        
        $date = self::getCurDate();
        if(strlen($date)==10)
            $date.=date(" H:i:s");
            
        $next_date = date('d.m.Y H:i:s', strtotime("+1 day", strtotime($date)));
           
        $arDate = array(
            "DATE_FROM" => self::dateOffset($offset, $date),
            "DATE_TO" => self::dateOffset($offset, $next_date),
            "OFFSET" => $offset,
            "DATE_REAL" => date('d.m.Y H:i:s')
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
}