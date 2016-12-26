<?
/**
 * Update links for records by POST
 */
 
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . '/../../');

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER, $APPLICATION, $DB;
if (!is_object($USER))
    $USER = new CUser;

$table = \Hawkart\Megatv\RecordTable::getTableName();
$post = file_get_contents('php://input');
$arJson = json_decode($post, true);

foreach($arJson as $json)
{
    if(!empty($json["ID"]) && !empty($json["LINK"]))
        $DB->Query("UPDATE ".$table." SET UF_URL='".$json["LINK"]."' WHERE UF_EPG_ID=".$json["ID"], false);
}

echo json_encode(array("result" => "true"));

die()
?>