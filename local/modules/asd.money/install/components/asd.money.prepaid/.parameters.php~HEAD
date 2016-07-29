<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$arCurrencyTypes = array("" => GetMessage("SPT_ALLOWED_CURRENCY_ALL"));
if(CModule::IncludeModule("currency"))
{
	$rsCurrency = CCurrency::GetList(($by="name"), ($order="desc"), LANGUAGE_ID);
	while ($arCurrency = $rsCurrency->Fetch())
		$arCurrencyTypes[$arCurrency["CURRENCY"]] = $arCurrency["FULL_NAME"];
}

$arPersonTypes = array();
if(CModule::IncludeModule('sale')) {
	$dbPerson = CSalePersonType::GetList(Array('SORT' => 'ASC', 'NAME' => 'ASC'), array('ACTIVE' => 'Y'));
	while($arPerson = $dbPerson->fetch()) {
		$arPersonTypes[$arPerson['ID']] = $arPerson['NAME'];
	}
}

$arComponentParameters = array(
	"PARAMETERS" => array(
		"ALLOWED_CURRENCY" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SPT_ALLOWED_CURRENCY"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arCurrencyTypes,
		),
		"DEFAULT_CURRENCY" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SPT_DEFAULT_CURRENCY"),
			"TYPE" => "LIST",
			"VALUES" => $arCurrencyTypes,
		),
		"COMISSION" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SPT_COMISSION"),
			"TYPE" => "STRING",
			"DEFAULT" => "0",
			"COLS" => "5",
		),
		"CART_PAGE" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SPT_CART_PAGE"),
			"TYPE" => "STRING",
		),
		"PAY_IMMED" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SPT_PAY_IMMED"),
			"TYPE" => "CHECKBOX",
			"REFRESH" => "Y"
		),
		"PERSON_TYPE" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SPT_PERSON_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arPersonTypes,
		),
		"SET_TITLE" => Array(),
	),
);

if ($arCurrentValues['PAY_IMMED'] == 'Y') {
	unset($arComponentParameters['PARAMETERS']['CART_PAGE']);
} else {
	unset($arComponentParameters['PARAMETERS']['PERSON_TYPE']);
}