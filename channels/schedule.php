<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<?$APPLICATION->IncludeComponent("hawkart:schedule", "", Array("ELEMENT_CODE" => $_REQUEST["SCHEDULE_CODE"]), false);?>

<div class="fullsize-banner adv-styling-02">
	<div class="banner-content">
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/schedule-footer-banner.php"), false);?>
	</div>
</div>

<?$APPLICATION->IncludeComponent("hawkart:schedule.similar", "", Array("ELEMENT_CODE" => $_REQUEST["SCHEDULE_CODE"], "TITLE" => "Похожие передачи"), false);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>