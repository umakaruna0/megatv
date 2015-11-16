<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();
global $USER;

session_start();

LocalRedirect("/vendor/hybridauth/hybridauth/?provider=".$_REQUEST["provider"]);

die();
?>