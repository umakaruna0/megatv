<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("MegaTV");
global $USER;

//CChannel::updateCache();
//CProg::updateCache();
//CProgTime::updateCache();
//$USER->Authorize(1);
?>

<?$APPLICATION->IncludeComponent("hawkart:channel.list", "", 
    Array(
        "IBLOCK_TYPE" => "directories",
		"IBLOCK_ID" => "6",
		"NEWS_COUNT" => "45",
        "DISPLAY_BOTTOM_PAGER" => "Y",
    ),
	false
);?>

<?/*$APPLICATION->IncludeComponent("hawkart:recommendations", "index", Array("NOT_SHOW_CHANNEL"=>"Y", "TEMPLATE" => "MAIN_PAGE"),
	false
);*/?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>