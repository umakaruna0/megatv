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

//Удаляем устаревшие программы
CProgTime::delete();

//Загружаем и импортируем данные из EPG
$Epg = new CEpg();
$Epg->download();
$Epg->import();

echo " --finish loading--";
die();
?>