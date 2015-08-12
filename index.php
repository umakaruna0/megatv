<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("MegaTv");
?><?
global $USER;
//$USER->Authorize(1);

$Epg = new CEpg();
//$Epg->download();
$Epg->import();


?>
<?/*$APPLICATION->IncludeComponent(
	"altasib:altasib.geoip",
	"",
	Array(
		"COMPONENT_TEMPLATE" => ".default"
	)
);*/?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>