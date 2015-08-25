<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arCurrencyTypes = array("" => GetMessage("SPT_ALLOWED_CURRENCY_ALL"));
if(CModule::IncludeModule("currency"))
{
	$rsCurrency = CCurrency::GetList(($by="name"), ($order="desc"), LANGUAGE_ID);
	while ($arCurrency = $rsCurrency->Fetch())
		$arCurrencyTypes[$arCurrency["CURRENCY"]] = $arCurrency["FULL_NAME"];
}

$arComponentParameters = array(
	"PARAMETERS" => array(
		"ALLOWED_CURRENCY_FROM" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SPT_ALLOWED_CURRENCY_FROM"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arCurrencyTypes,
		),
		"ALLOWED_CURRENCY_TO" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SPT_ALLOWED_CURRENCY_TO"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arCurrencyTypes,
		),
		"COMISSION" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SPT_COMISSION"),
			"TYPE" => "STRING",
			"DEFAULT" => "0",
			"COLS" => "5",
		),
		"SET_TITLE" => Array(),
	),
);
?>