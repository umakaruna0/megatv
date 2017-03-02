<?
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . '/../');
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Content-Type: text/html; charset=utf-8');
ini_set('mbstring.func_overload', '2');
ini_set('mbstring.internal_encoding', 'UTF-8');

echo date("H:i:s")."\r\n";
$rsUsers = CUser::GetList(($by="id"), ($order="asc"), Array("ACTIVE" => "Y"), array("FIELDS"=>array("ID")));
while($arUser = $rsUsers->GetNext())
{
    \Hawkart\Megatv\SerialTable::subscribeForUsers($arUser["ID"]);
}
echo date("H:i:s")."\r\n";
die();
?>