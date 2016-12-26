<?
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . '/../../');

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER, $APPLICATION;
if (!is_object($USER))
    $USER=new CUser;

$arUsers = array();
$dbUsers = \CUser::GetList(($by="ID"), ($order="ASC"), Array(), array("SELECT" => array("UF_SOTAL_LOGIN", "UF_SOTAL_PASS"), "FIELDS" => array("ID")) );
while($arUser = $dbUsers->Fetch())
{
    if(empty($arUser["UF_SOTAL_LOGIN"]))
        $arUser = CUserEx::generateDataSotal($arUser["ID"]);
    
    $arItem = array(
        "ID" => $arUser["ID"],
        "ACCESS_LOGIN" => $arUser["UF_SOTAL_LOGIN"],
        "ACCESS_PASS" => $arUser["UF_SOTAL_PASS"]
    );
    $arUsers[] = $arItem;
}

echo json_encode($arUsers);

die()
?>