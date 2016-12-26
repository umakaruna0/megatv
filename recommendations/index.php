<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Рекомендации");
?>

<?$APPLICATION->IncludeComponent("hawkart:megatv.recommendations", "", Array(
        "DATETIME" => CTimeEx::getDatetime(),
        "NEWS_COUNT" => 10
	),
	false
);?>

<?/*$APPLICATION->IncludeComponent("hawkart:recommendations", "index", Array("NOT_SHOW_CHANNEL"=>"Y", "TEMPLATE" => "MAIN_PAGE"),
	false
);*/?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>