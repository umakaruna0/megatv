<!DOCTYPE html>
<html lang="ru">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.png">
    <link rel="icon" href="/favicon.png">
    <title>Программа телепередач на сегодня - ТВ программа в Москве на МегаТВ, записи телепередач онлайн</title>
    <script id="globalConfig" type="text/x-config">
        { 
            "authURL" : "ajax/modals/auth.php", 
            "registerURL" : "ajax/modals/register.php", 
            "restorePassURL" : "ajax/modals/restore-password.php", 
            "haveCodeRestorePassURL" : "ajax/modals/have-code-for-restore-pass.php", 
            "sessid" : "adc9cde57e19cda2a39d08c0a39faa3d", 
            "ajax_key" : "76ce095374bbc723b7dde2bd46987d2c"
        }
    </script>
    <meta name="description" content="Программа телепередач российских каналов на сайте TVguru. Эфиры ТНТ, СТС, Первого канала, России-1 и др. с описанием фильмов, сериалов, развлекательных шоу, аналитических, научно-популярных и других передач." />
    <? 
    if(!isset($css)) 
    $css = [
        "css/main.css",
    ];
    foreach( $css as $val ){ ?>
        <link href="<?=$val;?>" type="text/css" rel="stylesheet" />
    <? } ?>
</head>

<body>

    <!-- SITE-WRAPPER -->
    <div class="site-wrapper" data-module="page">

        <script type="text/x-config">
            {
                "bannersHideTime": 1,
                "pathToSVGSprite": "img/sprites/svg_sprite.svg",
                "playerURL": "ajax/modals/player.php",
                "playerLastPositionURL": "ajax/player_last_position.php",
                "shareURL": "ajax/share.php",
                "authentication" : false
            }
        </script>

        <!-- HEADER -->
        <header class="site-wrapper__header header g-clearfix">

            <!-- BOX-LEFT -->
            <div class="header__box-left box-left">
                <div class="box-left__box-logo"> </div>
                <div class="city-select box-left__box-city" data-module="city-select">

                    <script type="text/x-config">
                        {  
                           "url":"/",
                           "cities":[  
                              { "id":22, "text":"Абакан"},
                              { "id":83, "text":"Анадырь"},
                              { "id":35, "text":"Архангельск"},
                              { "id":36, "text":"Астрахань"},
                              { "id":25, "text":"Барнаул" },
                              { "id":37, "text":"Белгород" },
                              { "id":80, "text":"Биробиджан" },
                              { "id":34, "text":"Благовещенск" },
                              { "id":38, "text":"Брянск" }
                           ],
                           "showCityRequestPopover":false
                        }
                    </script>

                    <form action="/" method="POST" id="city-select-form">
                        <select name="city-select" id="_id-city-select">
                            <option value="2" selected> Москва </option>
                        </select>
                        <input type="hidden" name="city-id" value="" id="city-select-value" />
                        <input type="hidden" name="sessid" id="sessid" value="adc9cde57e19cda2a39d08c0a39faa3d" /> 
                    </form>
                </div>

                <div class="lang-select box-left__box-lang box-lang" data-module="lang-select">

                    <script type="text/x-config">
                        {
                            "url": "/",
                            "languages":
                            [
                                { "id": 15, "text": "RU" },
                                { "id": 70, "text": "TR" }
                            ]
                        }
                    </script>

                    <form action="" id="lang-select-form" method="POST">
                        <input type="hidden" name="lang-id" value="" id="lang-select-value">
                        <select name="lang-select" id="lang-select">
                            <option value="15" selected> RU </option>
                        </select>
                        <input type="hidden" name="sessid" id="sessid_1" value="adc9cde57e19cda2a39d08c0a39faa3d" />
                    </form>
                </div>

            </div>
            <!-- END BOX-LEFT -->

            <!-- BOX-RIGHT -->
            <div class="header__box-right box-right">

               <!--  <div class="calendar-carousel" data-module="calendar-carousel">

                    <script type="text/x-config">
                        {
                            "currentDate": "30.07.2016",
                            "minDate": 1,
                            "maxDate": 9
                        }
                    </script>

                    <a href="#" class="prev-trigger disabled" data-type="prev-trigger">
                        <span data-icon="icon-left-arrow-days"></span>
                    </a>
                    <div class="dates-holder" data-type="dates-carousel"></div>
                    <a href="#" class="next-trigger" data-type="next-trigger">
                        <span data-icon="icon-right-arrow-days"></span>
                    </a> 
                </div> -->

                <div class="search" data-module="search">

                    <script type="text/x-config">
                        {
                         "url": "/local/components/hawkart/search/templates/.default/ajax.php?query=%QUERY",
                         "wildcard": "%QUERY"
                        }
                    </script>

                    <form action="#" class="search-form">
                        <div class="form-group has-feedback" data-type="search-group">
                            <label for="" class="sr-only">Название программы или сериала</label>
                            <input type="text" data-type="search-field" name="q" id="" class="form-control" placeholder="Название программы или сериала"><span data-icon="icon-search"></span>
                        </div>
                        <div class="search-close" data-type="close"> </div>
                    </form>
                    <div class="search-trigger" data-type="open"> <span data-icon="icon-search"></span> </div>

                </div>

                <div class="box-right__box-menu">
                    <a class="box-menu__link menu-link" href="channels.php">
                        <span data-icon="icon-channels" class="menu-link__icon g-icon"></span>
                        <span class="box-menu__title">Каналы</span>
                    </a>
                    <a class="box-menu__link menu-link" href="recommendations.php">
                        <span data-icon="icon-recommendations" class="menu-link__icon g-icon"></span>
                        <span class="box-menu__title">Рекомендации</span>
                    </a>
                    <a class="box-menu__link menu-link item-recording" href="personal-records.php">
                        <span data-icon="icon-film-collection" class="menu-link__icon g-icon"></span>
                        <span class="box-menu__title"><span class="item-recording__count">0 из 0</span> Мои записи</span>
                    </a> 
                </div>

                <? if(isset($auth)): ?>
                <div class="box-userbar__userbar userbar">

                    <div class="userbar__disk-space disk-space" data-type="fill-disk-space" onclick="location='personal-services.php'" style="cursor: pointer;">
                        <div class="disk-space__progress-holder progress-holder" data-progress="0"></div>
                        <span class="disk-space__label">Занято <strong class="disk-space__strong">0 ГБ</strong></span> 
                    </div>

                    <nav class="box-userbar__usernav usernav" data-module="user-navigation">
                        <div class="usernav__user-card">
                            <a href="/personal/" class="usernav__user-avatar" data-type="avatar-holder"> <img class="usernav__user-image" src="/upload/main/d57/d5712afa0c05eba0fb272f7ead73e3ce.jpg" alt="" width="50" height="50"> </a>
                            <div class="usernav__info-panel">
                                <a class="usernav__username" href="personal.php"></a> <a href="/?logout=yes" class="usernav__signout-link">Выйти</a> 
                            </div>
                        </div>
                    </nav>

                </div>
                <? endif; ?>

                <!-- <div class="box-right__box-menu"> 
                    <a class="box-menu__link" href="/channels/">
                        <span class="box-menu__icon g-icon">
                            <svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-channels"></use></svg>
                        </span>
                        <span class="box-menu__title">Каналы</span>
                    </a> 
                    <a class="box-menu__link" href="/recommendations/">
                        <span class="box-menu__icon g-icon">
                            <svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-recommendations"></use></svg>
                        </span>
                        <span class="box-menu__title">Рекомендации</span>
                    </a> 
                </div> -->
                <? if(!isset($auth)): ?>
                <!-- BOX-USERBAR -->
                <nav class="box-userbar box-right__box-userbar" data-module="user-navigation">
                    <a href="#" data-module="modal" data-modal="authURL" data-type="openModal" class="g-btn g-btn--primary box-userbar__btn-auth js-btnModalInit"><span>Войти</span></a>
                    <a href="#" data-module="modal" data-modal="registerURL" data-type="openModal" class="g-btn box-userbar__btn-register js-btnModalInit"><span>Зарегистрироваться</span></a>
                </nav>
                <!-- END BOX-USERBAR -->
                <? endif; ?>

            </div>
            <!-- END BOX-RIGHT -->

        </header>
        <!-- END HEADER -->

        <!-- MODAL -->
        <div class="ModalWindow js-ModalWindow">
            <div class="ModalWindow__overlay js-ModalOverlay">
                <div class="ModalWindow__content js-ModalContent">
                    <div class="ModalWindow__loader"></div>
                </div>
            </div>
            <div class="ModalWindow__blueBackground" data-type="closeModal"></div>
        </div>
        <!-- END MODAL -->