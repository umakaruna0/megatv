<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><?
include(GetLangFileName(dirname(__FILE__)."/", "/payment.php"));

$psTitle = GetMessage("SPCP_DTITLE");
$psDescription = GetMessage("SPCP_DDESCR");
$arPSCorrespondence = array(
	"ORDER_ID" => array(
		"NAME" => GetMessage("ORDER_ID"),
		"DESCR" => GetMessage("ORDER_ID_DESCR"),
		"VALUE" => "ID",
		"TYPE" => "ORDER"
	),
	"SHOULD_PAY" => array(
			"NAME" => GetMessage("SHOULD_PAY"),
			"DESCR" => "",
			"VALUE" => "PRICE",
			"TYPE" => "ORDER"
		),
	);
?>