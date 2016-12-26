<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

if(strtolower(LANGUAGE_ID)=="ru"):
    $APPLICATION->SetTitle("Программа телепередач на сегодня - ТВ программа в Москве на МегаТВ, записи телепередач онлайн");
    $APPLICATION->SetDirProperty("h1", "Программа телепередач на сегодня");
    $APPLICATION->SetPageProperty("description", "Программа телепередач российских каналов на сайте TVguru. Эфиры ТНТ, СТС, Первого канала, России-1 и др. с описанием фильмов, сериалов, развлекательных шоу, аналитических, научно-популярных и других передач.");
endif;
$APPLICATION->SetDirProperty("h1-hide", "");
?>

<?$APPLICATION->IncludeComponent("hawkart:channel.cell", "new", 
    Array(
		"NEWS_COUNT" => "45",
        "DISPLAY_BOTTOM_PAGER" => "Y"
    ),
	false
);?>

<?/*$APPLICATION->IncludeComponent("hawkart:recommendations", "index", Array("NOT_SHOW_CHANNEL"=>"Y", "TEMPLATE" => "MAIN_PAGE"),
	false
);*/
?>

<?if(strtolower(LANGUAGE_ID)=="ru"):?>
<div class="channel-desc">
    <p>На сайте TVguru всегда опубликованы актуальные данные об эфирах на российском телевидении. У нас представлена программа телепередач на сегодня для популярных каналов. Вы можете прочитать о фильме или сериале, который собираетесь посмотреть на ТВ, узнать его актерский состав, тематику и жанровые особенности. Мы также публикуем необходимые сведения о развлекательных шоу, научно-популярных передачах, документальных фильмах, новостных выпусках и др.</p>
    <p>На TVguru вы найдете программу передач для Первого канала, «России-1», «НТВ», «ТНТ», «СТС». Мы публикуем расписание эфиров телеканалов «Звезда», «Карусель», «ТВ-3» и многих других. У нас есть функция записи телепередач, поэтому на нашем сайте вы можете посмотреть пропущенные эфиры в любое удобное время.</p>
    <p>Если вы хотите узнать, будут ли сегодня показывать ваш любимый фильм, введите его название в строке поиска на нашем сайте. С помощью поиска можно быстро узнать время эфира передачи или выпуска новостей. </p>
    <p>Если вам понравился конкретный фильм, вы можете найти похожие в ТВ программе. Для этого откройте страницу фильма и посмотрите рекомендации к нему. Точно так же можно сделать с понравившейся передачей.</p>
</div>
<?endif;?>

<div class="app-mobile hidden">
    <div class="app-mobile__wrap">
        <div class="app-mobile__left">
            <img src="/local/templates/megatv/img/iphone.png" alt="" class="app-mobile__image">
            <div class="app-mobile__titles">
                <div class="app-mobile__title1">
                    <ul class="app-mobile__crumbs">
                        <li class="crumbs__logo-foo g-list-none"><span data-icon="icon-logo-footer" class="crumbs__logo"></span></li>
                        <li class="crumbs__title">На iPhone</li>
                    </ul>
                </div>
                <div class="app-mobile__title2">Планируйте свою ТВ-программу</div>
            </div>
        </div>
        <div class="app-mobile__right">
            <button class="g-btn g-btn--info btn-appmob">
                <span data-icon="icon-iphone" class="g-icon icon-iphone btn-appmob__icon"></span>
                <span class="btn-appmob__titles">
                    <i class="btn-appmob__title-small">Скачать МЕГАТВ в</i>
                    <i class="btn-appmob__title-big">App Store</i>
                </span>
            </button>
        </div>
    </div>
</div>

<?
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>