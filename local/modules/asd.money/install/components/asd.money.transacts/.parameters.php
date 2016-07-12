<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"PARAMETERS" => array(
		"PATH_TO_ORDER" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SPT_PATH_TO_ORDER"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"PAGE_COUNT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SPT_PAGE_COUNT"),
			"TYPE" => "STRING",
			"DEFAULT" => "10",
		),
		"PAGER_TEMPLATE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SPT_PAGER_TEMPLATE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"SET_TITLE" => Array(),
	),
);
?>