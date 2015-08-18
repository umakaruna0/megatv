<?
$arUrlRewrite = array(
	array(
		"CONDITION"	=>	"#^/services/#",
		"RULE"	=>	"",
		"ID"	=>	"bitrix:catalog",
		"PATH"	=>	"/services/index.php",
	),
    array(
		"CONDITION"	=>	"#^/channels/(.*)/(.*)/(.*)#",
		"RULE"	=>	"CHANNEL_CODE=\$1&SCHEDULE_CODE=\$2",
		"ID"	=>	"",
		"PATH"	=>	"/channels/schedule.php",
	),
    array(
		"CONDITION"	=>	"#^/channels/(.*)/(.*)/#",
		"RULE"	=>	"CHANNEL_CODE=\$1&SCHEDULE_CODE=\$2",
		"ID"	=>	"",
		"PATH"	=>	"/channels/schedule.php",
	),
    array(
		"CONDITION"	=>	"#^/channels/(.*)/(.*)#",
		"RULE"	=>	"CHANNEL_CODE=\$1",
		"ID"	=>	"bitrix:news.detail",
		"PATH"	=>	"/channels/detail.php",
	),
    array(
		"CONDITION"	=>	"#^/channels/(.*)/#",
		"RULE"	=>	"CHANNEL_CODE=\$1",
		"ID"	=>	"bitrix:news.detail",
		"PATH"	=>	"/channels/detail.php",
	),
    array(
		"CONDITION"	=>	"#^/channels/(.*)#",
		"RULE"	=>	"",
		"ID"	=>	"bitrix:news.list",
		"PATH"	=>	"/channels/index.php",
	),
);

?>