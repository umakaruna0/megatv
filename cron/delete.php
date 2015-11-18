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

CProg::updateCache();
/*       
$arProgs = CProg::getList(array("PREVIEW_PICTURE"=>false, "!PROPERTY_PICTURE_DOUBLE"=>false), array(
    "ID", "NAME", "PREVIEW_TEXT", "PROPERTY_CHANNEL", "PROPERTY_SUB_TITLE", "PREVIEW_PICTURE",
    "PROPERTY_PICTURE_DOUBLE", "PROPERTY_PICTURE_HALF", "PROPERTY_PICTURE_VERTICAL", "PROPERTY_PICTURE_VERTICAL_DOUBLE"
));

foreach($arProgs as $arProg)
{
    echo "<pre>"; print_r($arProg); echo "</pre>";
    
    $arProps = array("PICTURE_DOUBLE", "PICTURE_HALF", "PICTURE_VERTICAL", "PICTURE_VERTICAL_DOUBLE");
    foreach($arProps as $code)
    {
        $value = $arProg["PROPERTY_".$code."_VALUE_ID"];
        CIBlockElement::SetPropertyValueCode($arProg["ID"], $code, Array (
            $value =>  array('del' => 'Y', 'tmp_name' => '') 
        ));
        CFile::Delete($arProg["PROPERTY_".$code."_VALUE"]);
    } 
}*/

die();
?>