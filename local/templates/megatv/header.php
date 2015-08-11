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
        ?>
		<title><?$APPLICATION->ShowTitle()?></title>
	</head>
	<body>
        <div id="panel"><?$APPLICATION->ShowPanel();?></div>
        
		<div class="modal fade" id="auth-screens">
			<div class="modal-dialog">
				<div class="modal-content">
					<ul class="modal-nav">
						<li class="active"><a href="#singin-form" data-toggle="tab">Войти</a></li>
						<li><a href="#singup-form" data-toggle="tab">Регистрация</a></li>
						<li><a href="#reset-form" data-toggle="tab">Восстановить пароль</a></li>
					</ul>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane fade in active" id="singin-form">
							<div class="modal-form-wrap signin-form-wrap">
								<a href="#" class="close-link" data-dismiss="modal"><span data-icon="icon-times"></span></a>
								<form action="#" class="signin-form">
									<div class="form-group">
										<label for="" class="sr-only">Эл. почта</label>
										<input type="text" name="" id="" class="form-control" placeholder="Эл. почта">
									</div>
									<div class="form-group">
										<label for="" class="sr-only">Пароль</label>
										<input type="text" name="" id="" class="form-control" placeholder="Пароль">
									</div>
									<span class="divider"><span>или</span></span>
									<ul class="social-singin-list">
										<li><a href="#"><span data-icon="icon-ya-social"></span></a></li>
										<li><a href="#"><span data-icon="icon-ok-social"></span></a></li>
										<li><a href="#"><span data-icon="icon-gp-social"></span></a></li>
										<li><a href="#"><span data-icon="icon-in-social"></span></a></li>
										<li><a href="#"><span data-icon="icon-vk-social"></span></a></li>
										<li><a href="#"><span data-icon="icon-tw-social"></span></a></li>
										<li><a href="#"><span data-icon="icon-im-social"></span></a></li>
										<li><a href="#"><span data-icon="icon-fb-social"></span></a></li>
									</ul>
									<button type="submit" class="btn btn-primary btn-block">Войти</button>
								</form>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="singup-form">
							<div class="modal-form-wrap singup-userdata-form-wrap">
								<a href="#" class="close-link" data-dismiss="modal"><span data-icon="icon-times"></span></a>
								<form action="#" class="singup-userdata-form">
									<label for="">Введите ваши данные и мы вышлем<br> вам пароль на электронную почту</label>
									<div class="form-group">
										<input type="text" name="" id="" class="form-control" placeholder="Имя">
									</div>
									<div class="form-group">
										<label for="" class="sr-only">Фамилия</label>
										<input type="text" name="" id="" class="form-control" placeholder="Фамилия">
									</div>
									<div class="form-group">
										<label for="" class="sr-only">Отчество</label>
										<input type="text" name="" id="" class="form-control" placeholder="Отчество">
									</div>
									<div class="form-group has-feedback">
										<label for="" class="sr-only">Дата рождения</label>
										<input type="text" name="" id="" class="form-control" placeholder="Дата рождения">
										<span class="form-control-feedback"><span data-icon="icon-calendar"></span></span>
									</div>
									<div class="form-group has-error">
										<label for="" class="sr-only">Эл. почта</label>
										<input type="text" name="" id="" class="form-control" placeholder="Эл. почта" value="rom$yandex.ru">
										<span class="form-control-message">Не верный формат данных</span>
									</div>
									<div class="checkbox">
										<label for="_id-singup-userdata-form--chackbox"><input type="checkbox" name="singup-userdata-form--chackbox" id="_id-singup-userdata-form--chackbox"><span>Я принимаю условия <a href="#">договора оферты</a></span></label>
									</div>
									<button type="submit" class="btn btn-primary btn-block">Зарегистрироваться</button>
								</form>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="reset-form">
							<div class="modal-form-wrap reset-form-wrap">
								<a href="#" class="close-link" data-dismiss="modal"><span data-icon="icon-times"></span></a>
								<form action="#" class="reset-form">
									<div class="form-group">
										<label for="" class="sr-only">Эл. почта</label>
										<input type="text" name="" id="" class="form-control" placeholder="Эл. почта">
									</div>
									<button type="submit" class="btn btn-primary btn-block">Восстановить пароль</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
        
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
					<div class="city-select" data-module="city-select">
						<select name="city-select" id="_id-city-select">
							<option value="0">Москва</option>
							<option value="1" selected>Санкт-Петербург</option>
							<option value="2">Томск</option>
							<option value="3">Омск</option>
							<option value="4">Ноябрьск</option>
						</select>
					</div>
					<nav class="header-nav">
						<ul class="user-actions">
							<li><a href="#" class="signin-link" data-type="auth-screens-trigger" data-target="#singin-form">Войти</a></li>
							<li><a href="#" class="signup-link" data-type="auth-screens-trigger" data-target="#singup-form">Зарегистрироваться</a></li>
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