<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();

\CModule::IncludeModule("sale");

global $USER;
if(!is_object($USER))
    $USER = new \CUser;
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:asd.money.prepaid", 
	"pay", 
	array(
		"COMPONENT_TEMPLATE" => "visual",
		"ALLOWED_CURRENCY" => array(
			0 => "RUB",
		),
		"DEFAULT_CURRENCY" => "RUB",
		"COMISSION" => "0",
		"CART_PAGE" => "",
		"PAY_IMMED" => "Y",
		"SET_TITLE" => "N",
		"PERSON_TYPE" => "1"
	),
	false
);?>