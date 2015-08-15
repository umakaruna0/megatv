<?
$_SERVER["DOCUMENT_ROOT"] = "/home/d/daotel/MEGATV/public_html";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


global $USER, $APPLICATION;
if (!is_object($USER))
    $USER=new CUser;

CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");

$str = "Интерны (193-я серия)";
$arParams = array("replace_space"=>"-", "replace_other"=>"-");
$translit = Cutil::translit($str, "ru", $arParams);

echo $translit;
die();
?>