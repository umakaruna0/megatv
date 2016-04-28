<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Мои записи");
?>

<?$APPLICATION->IncludeComponent("hawkart:user.records", "", Array("WATCHED"=>"N"), false);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>