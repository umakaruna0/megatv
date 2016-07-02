<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetDirProperty("h1-hide", "");
?>

<?$APPLICATION->IncludeComponent("hawkart:channel.detail", "", Array("ELEMENT_CODE" => $_REQUEST["CHANNEL_CODE"]), false);?>

<?/*$APPLICATION->IncludeComponent("hawkart:recommendations", "index", Array("NOT_SHOW_CHANNEL"=>"Y", "TEMPLATE" => "MAIN_PAGE"),
	false
);*/?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>