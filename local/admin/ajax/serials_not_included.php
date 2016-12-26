<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();

global $USER;
if(!is_object($USER))
    $USER = new \CUser;
$youtube = new \Hawkart\Megatv\Social\YoutubeClient();
$youtube->importByExternalID($_REQUEST["external_id"], $_REQUEST["serial_id"]);
?>