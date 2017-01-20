<?
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . '/../');
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Content-Type: text/html; charset=utf-8');
ini_set('mbstring.func_overload', '2');
ini_set('mbstring.internal_encoding', 'UTF-8');

//\Hawkart\Megatv\ProgTable::generateCodes(); die();

echo $dstart = date("H:i:s")."\r\n";

//Удаляем старые файлы лога
$path = $_SERVER['DOCUMENT_ROOT'].'/logs/sotal/';
\CDev::deleteOldFiles($path, 86400);

//Загружаем и импортируем данные из EPG
$epg = new \Hawkart\Megatv\CEpg();
$epg->importChannels(); //!!!
$epg->importChannelCity();
$epg->import();

\CDev::deleteDirectory($_SERVER['DOCUMENT_ROOT'].'/bitrix/cache', 0);

\Hawkart\Megatv\ScheduleTable::connectByTitle();

\CDev::deleteDirectory($_SERVER['DOCUMENT_ROOT'].'/bitrix/cache', 0);

\Hawkart\Megatv\ScheduleTable::slice(); 
\CDev::deleteDirectory($_SERVER['DOCUMENT_ROOT'].'/bitrix/cache', 0);

$fisrt_date = date('d.m.Y', strtotime(\CTimeEx::getCurDate()));
for($day=0; $day<3; $day++)
{
    $curDate = date('d.m.Y', strtotime("+".$day." day", strtotime($fisrt_date)));
    \Hawkart\Megatv\ScheduleCell::generate($curDate);
}
\CDev::deleteDirectory($_SERVER['DOCUMENT_ROOT'].'/bitrix/cache', 0);
echo $dfinish = date("H:i:s")."\r\n";

mail("hawkart@rambler.ru", "Tvguru epg import success: ".$dstart."-".$dfinish, "success");

echo " --finish loading--";
die();
?>