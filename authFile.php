<?
require($_SERVER['DOCUMENT_ROOT']."/bitrix/header.php");
$USER->Authorize(1); // укажите ID вашего пользователя
LocalRedirect('/bitrix/admin/');
require($_SERVER['DOCUMENT_ROOT']."/bitrix/footer.php");
?>