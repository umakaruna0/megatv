<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();
?>

<?
$APPLICATION->IncludeComponent(
    "bitrix:system.auth.forgotpasswd",
    ".default",
    Array()
);
?>

<script>
	jQuery(document).ready(function(){
		var form = $('[data-module="reset-overlay"]')[0];
		Box.Application.start(form);
		
		var btnModals = $('[data-module="modal"]');
		btnModals.each(function(){
			var $this = $(this)[0];
			Box.Application.start($this);
		});
	});
</script>