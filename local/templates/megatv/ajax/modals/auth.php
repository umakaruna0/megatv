<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();
?>

<?
$APPLICATION->IncludeComponent("bitrix:system.auth.form", "", Array(
    "REGISTER_URL" => "register.php",
    "FORGOT_PASSWORD_URL" => "",
    "PROFILE_URL" => "/",
    "SHOW_ERRORS" => "Y" 
    )
);
?>

<script>
	jQuery(document).ready(function(){
		var form = $('[data-module="signin-overlay"]')[0];
		Box.Application.start(form);

		var btnModals = $('[data-module="modal"]');
		btnModals.each(function(){
			var $this = $(this)[0];
			Box.Application.start($this);
		});
	});
</script>