<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeTemplateLangFile(__FILE__);
?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="format-detection" content="telephone=no">
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<link rel="icon" href="favicon.ico" type="image/x-icon">
        <?
        //$APPLICATION->ShowHead();
        echo '<meta http-equiv="Content-Type" content="text/html; charset='.LANG_CHARSET.'"'.(true ? ' /':'').'>'."\n";
        $APPLICATION->ShowMeta("robots", false, true);
        $APPLICATION->ShowMeta("keywords", false, true);
        $APPLICATION->ShowMeta("description", false, true);
        $APPLICATION->ShowCSS(true, true);
        
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/main.css');
        $APPLICATION->ShowHeadStrings();
    	$APPLICATION->ShowHeadScripts();
        
        global $USER;
        session_start();
        ?>
		<title><?$APPLICATION->ShowTitle()?></title>
	</head>
	<body>
        <div id="panel"><?$APPLICATION->ShowPanel();?></div>
        
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/header-signin.php"), false);?>
        
		<div class="site-wrapper" data-module="page">
			<script type="text/x-config">{ "bannersHideTime": 1 }</script> <!-- конфиг для модуля page -->
			<div id="drop-area" class="drop-area">
				<div>
					<div class="dropzone">
						<span class="checkmark-holder"></span>
						<span class="dropzone-text">Чтобы добавить, тащи сюда</span>
					</div>
				</div>
			</div>
			<header class="site-header">
				<div class="fullsize-banner adv-styling-01" data-type="advertizing" id="header-adv">
					<div class="banner-content">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/header-banner.php"), false);?>
					</div>
					<a href="#" class="close-link" data-type="hide-banner-link">Скрыть</a>
				</div>
				<div class="top-panel">
					<span class="logo"></span>
                    
                    <?$APPLICATION->IncludeComponent(
                    	"bitrix:news.list",
                    	"cities",
                    	Array(
                    		"DISPLAY_DATE" => "Y",
                    		"DISPLAY_NAME" => "Y",
                    		"DISPLAY_PICTURE" => "Y",
                    		"DISPLAY_PREVIEW_TEXT" => "Y",
                    		"AJAX_MODE" => "N",
                    		"IBLOCK_TYPE" => "directories",
                    		"IBLOCK_ID" => "5",
                    		"NEWS_COUNT" => "100",
                    		"SORT_BY1" => "SORT",
                    		"SORT_ORDER1" => "ASC",
                    		"SORT_BY2" => "NAME",
                    		"SORT_ORDER2" => "ASC",
                    		"FILTER_NAME" => "",
                    		"FIELD_CODE" => array("NAME"),
                    		"PROPERTY_CODE" => array(),
                    		"CHECK_DATES" => "Y",
                    		"DETAIL_URL" => "",
                    		"PREVIEW_TRUNCATE_LEN" => "",
                    		"ACTIVE_DATE_FORMAT" => "d.m.Y",
                    		"SET_TITLE" => "Y",
                    		"SET_BROWSER_TITLE" => "Y",
                    		"SET_META_KEYWORDS" => "Y",
                    		"SET_META_DESCRIPTION" => "Y",
                    		"SET_STATUS_404" => "N",
                    		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
                    		"ADD_SECTIONS_CHAIN" => "Y",
                    		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
                    		"PARENT_SECTION" => "",
                    		"PARENT_SECTION_CODE" => "",
                    		"INCLUDE_SUBSECTIONS" => "Y",
                    		"CACHE_TYPE" => "A",
                    		"CACHE_TIME" => "36000000",
                    		"CACHE_FILTER" => "N",
                    		"CACHE_GROUPS" => "Y",
                    		"PAGER_TEMPLATE" => "",
                    		"DISPLAY_TOP_PAGER" => "N",
                    		"DISPLAY_BOTTOM_PAGER" => "Y",
                    		"PAGER_TITLE" => "Новости",
                    		"PAGER_SHOW_ALWAYS" => "N",
                    		"PAGER_DESC_NUMBERING" => "N",
                    		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    		"PAGER_SHOW_ALL" => "N",
                    		"AJAX_OPTION_JUMP" => "N",
                    		"AJAX_OPTION_STYLE" => "Y",
                    		"AJAX_OPTION_HISTORY" => "N",
                            "CITY_GEO" => \Olegpro\IpGeoBase\IpGeoBase::getInstance()->getRecord()
                    	),
                    false
                    );?>
                    
					<nav class="header-nav">
						<ul class="user-actions">
                            <?
                            if($USER->IsAuthorized())
                            {
                                ?>
                                <li><a href="<?=$APPLICATION->GetCurDir()?>?logout=yes" class="signin-link">Выйти</a></li>
                                <?
                            }else{
                                ?>
                                <li><a href="#" class="signin-link" data-type="auth-screens-trigger" data-target="#singin-form">Войти</a></li>
                                <li><a href="#" class="signup-link" data-type="auth-screens-trigger" data-target="#singup-form">Зарегистрироваться</a></li>
                                <?
                            }
                            ?>
							
						</ul>
					</nav>
				</div>
				<div class="bottom-panel">
					<div class="calendar" data-module="calendar">
						<a href="#" data-type="calendar-trigger" class="calendar-trigger"><span>19 сентября 2015</span></a>
						<div class="datepicker-holder"></div>
					</div>
					<div class="calendar-carousel" data-module="calendar-carousel">
						<script type="text/x-config">{ "currentDate": "19.09.2015" }</script>
						<a href="#" class="prev-trigger disabled" data-type="prev-trigger"><span data-icon="icon-left-arrow-days"></span></a>
						<div class="dates-holder" data-type="dates-carousel">
							<ul class="date-group">
								<li><a href="#" data-type="day-trigger"><span class="day-char">пн</span><span class="day-number">18</span></a></li>
								<li class="current" data-type="day-trigger"><a href="#"><span class="day-char">вт</span><span class="day-number">19</span></a></li>
								<li><a href="#" data-type="day-trigger"><span class="day-char">ср</span><span class="day-number">20</span></a></li>
								<li><a href="#" data-type="day-trigger"><span class="day-char">чт</span><span class="day-number">21</span></a></li>
								<li><a href="#" data-type="day-trigger"><span class="day-char">пт</span><span class="day-number">22</span></a></li>
								<li><a href="#" data-type="day-trigger"><span class="day-char">сб</span><span class="day-number">23</span></a></li>
								<li><a href="#" data-type="day-trigger"><span class="day-char">вс</span><span class="day-number">24</span></a></li>
							</ul>
							<ul class="date-group">
								<li><a href="#" data-type="day-trigger"><span class="day-char">пн</span><span class="day-number">25</span></a></li>
								<li><a href="#" data-type="day-trigger"><span class="day-char">вт</span><span class="day-number">26</span></a></li>
								<li><a href="#" data-type="day-trigger"><span class="day-char">ср</span><span class="day-number">27</span></a></li>
								<li><a href="#" data-type="day-trigger"><span class="day-char">чт</span><span class="day-number">28</span></a></li>
								<li><a href="#" data-type="day-trigger"><span class="day-char">пт</span><span class="day-number">29</span></a></li>
								<li><a href="#" data-type="day-trigger"><span class="day-char">сб</span><span class="day-number">30</span></a></li>
								<li><a href="#" data-type="day-trigger"><span class="day-char">вс</span><span class="day-number">1</span></a></li>
							</ul>
							<ul class="date-group">
								<li><a href="#" data-type="day-trigger"><span class="day-char">пн</span><span class="day-number">2</span></a></li>
								<li><a href="#" data-type="day-trigger"><span class="day-char">вт</span><span class="day-number">3</span></a></li>
								<li><a href="#" data-type="day-trigger"><span class="day-char">ср</span><span class="day-number">4</span></a></li>
								<li><a href="#" data-type="day-trigger"><span class="day-char">чт</span><span class="day-number">5</span></a></li>
								<li><a href="#" data-type="day-trigger"><span class="day-char">пт</span><span class="day-number">6</span></a></li>
								<li><a href="#" data-type="day-trigger"><span class="day-char">сб</span><span class="day-number">7</span></a></li>
								<li><a href="#" data-type="day-trigger"><span class="day-char">вт</span><span class="day-number">8</span></a></li>
							</ul>
						</div>
						<a href="#" class="next-trigger" data-type="next-trigger"><span data-icon="icon-right-arrow-days"></span></a>
					</div>
					<ul class="sections-menu">
						<li class="active"><a href="index.html"><span data-icon="icon-channels"></span><span>Каналы</span></a></li>
						<li><a href="themes.html"><span data-icon="icon-themes"></span><span>Тематики</span></a></li>
						<li><a href="recommendations.html"><span data-icon="icon-recommendations"></span><span>Рекомендации</span></a></li>
					</ul>
					<form action="#" class="search-form" data-module="search-form">
						<div class="form-group has-feedback">
							<label for="" class="sr-only">Название программы или сериала</label>
							<input type="text" data-type="search-field" name="" id="" class="form-control" placeholder="Название программы или сериала">
							<span data-icon="icon-search" class="form-control-feedback"></span>
						</div>
					</form>
				</div>
			</header>
			<main class="site-content">