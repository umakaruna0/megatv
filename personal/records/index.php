<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Мои записи");
?>

<?$APPLICATION->IncludeComponent("hawkart:user.records", "", Array(
    "WATCHED" => "N",
    "NEWS_COUNT" => 6,
    "AJAX" => $_REQUEST["AJAX"],
    "LIST_URL" => $APPLICATION->GetCurPage()
), false);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>