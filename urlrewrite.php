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
    array(
		"CONDITION"	=>	"#^/channel/(.*)/(.*)/#",
		"RULE"	=>	"CHANNEL_CODE=\$1&SCHEDULE_CODE=\$2",
		"ID"	=>	"",
		"PATH"	=>	"/channel/schedule.php",
	),
    array(
		"CONDITION"	=>	"#^/channel/(.*)/#",
		"RULE"	=>	"CHANNEL_CODE=\$1",
		"ID"	=>	"bitrix:news.detail",
		"PATH"	=>	"/channel/index.php",
	),
);

?>