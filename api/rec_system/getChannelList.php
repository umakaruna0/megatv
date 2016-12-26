<?
/**
 * Get channel list with resource
 */
 
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . '/../../');

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER, $APPLICATION;
if (!is_object($USER))
    $USER=new CUser;


$arRecords = array();
$arFilter = array("=UF_BASE.UF_ACTIVE" => 1);

$arSelect = array(
    "ID", "TITLE" => "UF_BASE.UF_TITLE", "UF_EPG_ID", "STREAM" => "UF_BASE.UF_STREAM_URL"
);
$result = \Hawkart\Megatv\ChannelTable::getList(array(
    'filter' => $arFilter,
    'select' => $arSelect
));
while ($arRecord = $result->fetch())
{        
    $arItem = array(
        'ID' => $arRecord["UF_EPG_ID"],
        'TITLE' => $arRecord["TITLE"],
        'STREAM' => $arRecord["STREAM"]
    );
    
    $arRecords[] = $arItem;
}

echo json_encode($arRecords);

die();
?>