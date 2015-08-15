<?
$arUrlRewrite = array(
	array(
		"CONDITION"	=>	"#^/services/#",
		"RULE"	=>	"",
		"ID"	=>	"bitrix:catalog",
		"PATH"	=>	"/services/index.php",
	),
    array(
		"CONDITION"	=>	"#^/(date-[0-9]{2}-[0-9]{2}-[0-9]{4})/(.*)#",
		"RULE"	=>	"DATE_CURRENT_SHOW=\$1",
		"ID"	=>	"",
		"PATH"	=>	"/date/index.php",
	),
    array(
		"CONDITION"	=>	"#^/(date-[0-9]{2}-[0-9]{2}-[0-9]{4})/#",
		"RULE"	=>	"DATE_CURRENT_SHOW=\$1",
		"ID"	=>	"",
		"PATH"	=>	"/date/index.php",
	),
);

?>