<?
$_SERVER["DOCUMENT_ROOT"] = "/home/d/daotel/MEGATV/public_html"; //изменить на сервере
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Content-Type: text/html; charset=utf-8');
ini_set('mbstring.func_overload', '2');
ini_set('mbstring.internal_encoding', 'UTF-8');

echo date("H:i:s")."\r\n";

//Удаляем старые файлы лога
$path = $_SERVER['DOCUMENT_ROOT'].'/logs/sotal/';
\CDev::deleteOldFiles($path, 86400);

//Загружаем и импортируем данные из EPG
$epg = new \Hawkart\Megatv\CEpg();
$epg->download();
$epg->import();

\CDev::deleteDirectory($_SERVER['DOCUMENT_ROOT'].'/upload/resize_cache');

echo date("H:i:s")."\r\n";

echo " --finish loading--";
die();
?>