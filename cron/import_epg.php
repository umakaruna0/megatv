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

/*
$arsFilter = array(
    ">=UF_DATE" => new \Bitrix\Main\Type\Date(date("Y-m-d"), 'Y-m-d'),
    "=UF_IS_PART" => 1,
);
$result = \Hawkart\Megatv\ScheduleTable::getList(array(
    'filter' => $arsFilter,
    'select' => array("ID")
));
while ($row = $result->fetch())
{
    \Hawkart\Megatv\ScheduleTable::delete($row["ID"]);
}
*/

mail("hawkart@rambler.ru", "Tvguru epg import success: ".$dstart, "start of import");

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

\Hawkart\Megatv\ScheduleCell::generateForWeek();

\CDev::deleteDirectory($_SERVER['DOCUMENT_ROOT'].'/bitrix/cache', 0);
echo $dfinish = date("H:i:s")."\r\n";

mail("hawkart@rambler.ru", "Tvguru epg import success: ".$dstart."-".$dfinish, "success");

echo " --finish loading--";
die();
?>