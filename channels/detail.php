<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>

<?$APPLICATION->IncludeComponent("hawkart:channel.detail", "", Array("ELEMENT_CODE" => $_REQUEST["CHANNEL_CODE"]), false);?>

<div class="fullsize-banner adv-styling-02">
	<div class="banner-content">
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/channel-banner.php"), false);?>
	</div>
</div>

<?$APPLICATION->IncludeComponent("hawkart:recommendations", "index", Array("NOT_SHOW_CHANNEL"=>"Y", "TEMPLATE" => "MAIN_PAGE"),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>