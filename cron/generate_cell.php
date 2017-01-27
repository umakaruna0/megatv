<?
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . '/../');
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Content-Type: text/html; charset=utf-8');
ini_set('mbstring.func_overload', '2');
ini_set('mbstring.internal_encoding', 'UTF-8');

global $USER, $APPLICATION;
if (!is_object($USER))
    $USER=new \CUser;

\CDev::deleteDirectory($_SERVER['DOCUMENT_ROOT'].'/bitrix/cache', 0);

\CDev::deleteDirectory($_SERVER["DOCUMENT_ROOT"]."/upload/cell/", 86400*2);

$arCities = \Hawkart\Megatv\CityTable::getLangCityList(15); //RU
foreach($arCities as $arCity)
{
    $fisrt_date = date('d.m.Y', strtotime(\CTimeEx::getCurDate()));
    for($day=0; $day<3; $day++)
    {
        $curDate = date('d.m.Y', strtotime("+".$day." day", strtotime($fisrt_date)));
        \Hawkart\Megatv\ScheduleCell::generate($curDate, $arCity["ID"]);
    }
}
die();
?>