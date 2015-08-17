<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("MegaTV");
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.detail",
	"schedule",
	Array(
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"USE_SHARE" => "N",
		"AJAX_MODE" => "N",
		"IBLOCK_TYPE" => "directories",
		"IBLOCK_ID" => "8",
		"ELEMENT_ID" => "",
		"ELEMENT_CODE" => $_REQUEST["SCHEDULE_CODE"],
		"CHECK_DATES" => "N",
		"FIELD_CODE" => array("ID", "CODE", "NAME"),
		"PROPERTY_CODE" => array(
            "PROG", "CHANNEL", "DATE_START", "DATE_END"
        ),
		"IBLOCK_URL" => "",
		"META_KEYWORDS" => "-",
		"META_DESCRIPTION" => "-",
		"BROWSER_TITLE" => "-",
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"USE_PERMISSIONS" => "N",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "3600",
		"CACHE_GROUPS" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "Страница",
		"PAGER_TEMPLATE" => "",
		"PAGER_SHOW_ALL" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
        "CURRENT_DATETIME" => CTimeEx::getDateTimeOffset(),
        "CITY" => CCityEx::getGeoCity()
	),
false
);?>

<div class="fullsize-banner adv-styling-02">
	<div class="banner-content">
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/schedule-footer-banner.php"), false);?>
	</div>
</div>

<?
$arTopics = array();
global $arRecommendFilter;
$arProgTime = CProgTime::getList(array("CODE"=>htmlspecialcharsbx($_REQUEST["SCHEDULE_CODE"])), array("ID", "PROPERTY_PROG"));
$arProgTime = array_shift($arProgTime);

//Темы программы
$arProg = CProg::getByID($arProgTime["PROPERTY_PROG_VALUE"], array("PROPERTY_TOPIC"));
$arTopicsExp = explode(",", $arProg["PROPERTY_TOPIC_VALUE"]);
foreach($arTopicsExp as $key=>$topic)
{
    if(!empty($topic))
        $arTopics[] = $topic;
}
unset($arTopicsExp);

$arTime = CTimeEx::getDateTimeOffset();

//активные каналы
$activeChannels = CChannel::getList(array("ACTIVE"=>"Y"), array("ID"));
$ids = array();
foreach($activeChannels as $activeChannel)
{
    $ids[] = $activeChannel["ID"];
}

$filterDateStart = date("Y-m-d H:i:s", strtotime("-3 hour", strtotime($arTime["DATETIME_CURRENT"])));
$filterDateEnd = date('Y-m-d H:i:s', strtotime("+1 day -3 hour", strtotime($arTime["DATETIME_CURRENT"])));

//Выберем все программы с такими же темами
$progIds = array();
$arProgs = CProg::getList(array("?PROPERTY_TOPIC"=>$arTopics, "!ID"=>$arProgTime["PROPERTY_PROG_VALUE"]), array("ID", "NAME", "PROPERTY_CHANNEL", "PROPERTY_SUB_TITLE"));
foreach($arProgs as $arProg)
{
    $progIds[] = $arProg["ID"];
}
$progIds = array_unique($progIds);

$arRecommendFilter[">=PROPERTY_DATE_START"] = $filterDateStart;
$arRecommendFilter["<PROPERTY_DATE_END"] = $filterDateEnd;
$arRecommendFilter["PROPERTY_PROG"] = $progIds;
$arRecommendFilter["PROPERTY_CHANNEL"] = $ids;
?>

<?$APPLICATION->IncludeComponent("bitrix:news.list", "similar", Array(
    "DISPLAY_DATE" => "Y",	// Выводить дату элемента
	"DISPLAY_NAME" => "Y",	// Выводить название элемента
	"DISPLAY_PICTURE" => "Y",	// Выводить изображение для анонса
	"DISPLAY_PREVIEW_TEXT" => "Y",	// Выводить текст анонса
	"AJAX_MODE" => "N",	// Включить режим AJAX
	"IBLOCK_TYPE" => "directories",	// Тип информационного блока (используется только для проверки)
	"IBLOCK_ID" => "8",	// Код информационного блока
	"NEWS_COUNT" => "10",	// Количество новостей на странице
	"SORT_BY1" => "PROPERTY_DATE_START",	// Поле для первой сортировки новостей
	"SORT_ORDER1" => "ASC",	// Направление для первой сортировки новостей
	"SORT_BY2" => "NAME",	// Поле для второй сортировки новостей
	"SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
	"FILTER_NAME" => "arRecommendFilter",	// Фильтр
	"FIELD_CODE" => array(	// Поля
		0 => "NAME",
		1 => "ID",
        2 => "CODE"
	),
	"PROPERTY_CODE" => array(	// Свойства
		0 => "DATE_START",
		1 => "DATE_END",
		2 => "CHANNEL",
        3 => "PROG"
	),
	"CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
	"DETAIL_URL" => "",	// URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
	"PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода (только для типа текст)
	"ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
	"SET_TITLE" => "N",	// Устанавливать заголовок страницы
	"SET_BROWSER_TITLE" => "N",	// Устанавливать заголовок окна браузера
	"SET_META_KEYWORDS" => "N",	// Устанавливать ключевые слова страницы
	"SET_META_DESCRIPTION" => "N",	// Устанавливать описание страницы
	"SET_STATUS_404" => "N",	// Устанавливать статус 404
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",	// Включать инфоблок в цепочку навигации
	"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
	"HIDE_LINK_WHEN_NO_DETAIL" => "N",	// Скрывать ссылку, если нет детального описания
	"PARENT_SECTION" => "",	// ID раздела
	"PARENT_SECTION_CODE" => "",	// Код раздела
	"INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
	"CACHE_TYPE" => "A",	// Тип кеширования
	"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
	"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
	"CACHE_GROUPS" => "Y",	// Учитывать права доступа
	"PAGER_TEMPLATE" => "",	// Шаблон постраничной навигации
	"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
	"DISPLAY_BOTTOM_PAGER" => "N",                                    //
	"PAGER_TITLE" => "Новости",	// Название категорий
	"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
	"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
	"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
	"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
	"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
	"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
	"COMPONENT_TEMPLATE" => ".default",
	"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
	"SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
	"PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
	"SHOW_404" => "N",	// Показ специальной страницы
	"MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
    "CURRENT_DATETIME" => $arTime,
    "CITY" => CCityEx::getGeoCity()
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>