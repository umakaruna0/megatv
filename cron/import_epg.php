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

global $USER, $APPLICATION;
if (!is_object($USER))
    $USER=new CUser;

CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");


//DELETE ALL PROGS & SCHEDULE
/*CProg::updateCache();
CProgTime::updateCache();
$arProgs = CProg::getList(false, array("ID"));
$arProgTimes = CProgTime::getList(false, array("ID"));
foreach($arProgs as $arProg)
{
    CIBlockElement::Delete($arProg["ID"]);
}
foreach($arProgTimes as $arProg)
{
    CIBlockElement::Delete($arProg["ID"]);
}*/

//Удаляем устаревшие расписания программ
CProgTime::delete();

//Удаляем старые файлы лога
$path = $_SERVER['DOCUMENT_ROOT'].'/logs/sotal/';
CDev::deleteOldFiles($path, 86400);

CDev::deleteDirectory($_SERVER['DOCUMENT_ROOT'].'/upload/epg');

//Удаляем кэш
/*$pathes = array(
    //"/logs/sotal/",
    "/bitrix/cache/",
    "/upload/resize_cache/",
    "/upload/tmp/",
    "/bitrix/tmp/",
    "/bitrix/managed_cache/",
    "/bitrix/stack_cache/"
);
foreach($pathes as $path)
{
    $path = $_SERVER['DOCUMENT_ROOT'].$path;
    CDev::deleteOldFiles($path, 86400*2);
    CDev::deleteDirectory($path);
}*/

//Загружаем и импортируем данные из EPG
$Epg = new CEpg();
$Epg->download();
$Epg->import();

//Удаляем устаревшие программы
CProg::delete();

CDev::deleteDirectory($_SERVER['DOCUMENT_ROOT'].'/upload/resize_cache');

echo " --finish loading--";
die();
?>