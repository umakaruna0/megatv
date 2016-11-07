<?
    $css = [
        "css/main.css",
        "css/recommendations.css"
    ];
	$items = true;
	require("include/header.php");
?>

<main class="site-content">
    <section class="section-h1 hidden">
        <h1></h1></section>
    <div class="broadcasts-categories" data-module="broadcasts-categories">

        <script type="text/x-config">
            { "url": "personal-records.php" }
        </script>

        <div class="items">
            <a href="#" class="item active" data-type="item" data-category="all">Все</a>
            <a href="#d-s" class="item" data-type="item" data-category="d-s">Д/с</a>
            <a href="#novosti" class="item" data-type="item" data-category="novosti">Новости</a>
            <a href="#tok-shou" class="item" data-type="item" data-category="tok-shou">Ток-шоу</a>
            <a href="#t-s" class="item" data-type="item" data-category="t-s">Т/с</a>
            <a href="#poznavatelnoe" class="item" data-type="item" data-category="poznavatelnoe">Познавательное</a>
            <a href="#tv-shou" class="item" data-type="item" data-category="tv-shou">ТВ-шоу</a>
            <a href="#yumor" class="item" data-type="item" data-category="yumor">Юмор</a>
            <a href="#sport" class="item" data-type="item" data-category="sport">Спорт</a>
        </div>
        <div class="more" data-type="more">
            <span data-icon="icon-close"></span>
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
        </div>
    </div>

    <!-- <link rel="stylesheet" href="css/recommendations.css"> -->

    <section class="broadcasts" data-offset="0" data-date="<?=date("d\.m\.Y");?>" data-module="recomended-broadcasts"><!-- "viewMoreUrl" : "/recommendations/?AJAX=Y", -->
        <script type="text/x-config">
	        { 
	        	"viewMoreUrl" : "/local/templates/megatv/tmpl/ajax/load_recommendations.php",
	        	"recordingURL": "/local/templates/megatv/ajax/to_record.php",
	        	"countMax" : 9,
	            "lang":{
	                "record_title": "Записать",
	                "msg_out_of_space": "У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК",
	                "btn_order_of_space": "Заказать дополнительную емкость"
	            }
	        }
	   </script>
        <div class="broadcasts-list">
	    	<? if(!$items){ ?>
	    	<!-- ========================= Если рекоммендаций нет  ========================= -->
	    	<div class="empty-content">
	    		<h1 class="empty-content__title">Список рекомендаций пуст...</h1>
	    	</div>
	    	<!-- ======================= ! Если рекоммендаций нет !  ======================= -->
	    	<? }else{ ?>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="223341" data-category="novosti">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/53804_288_288.jpg)"></div>

                    <span class="item-status-icon">
                                    <div class="icon icon-recordit "><svg class="icon__cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-recordit"></use></svg></div>
                        <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <div class="icon icon-storage ">
                                <svg class="icon__cnt">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-storage"></use>
                                </svg>
                            </div>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">20:45</div>
                            <div class="date">14.10.2016</div>
                            <div class="category"><a href="#" data-type="category">Новости</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/rossiya-1/vesti/?event=223341">
                                    Вести                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="225074" data-category="kh-f">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/225404_288_288.jpg)"></div>

                    <span class="item-status-icon">
                                    <div class="icon icon-recordit "><svg class="icon__cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-recordit"></use></svg></div>
                        <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <div class="icon icon-storage ">
                                <svg class="icon__cnt">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-storage"></use>
                                </svg>
                            </div>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">20:29</div>
                            <div class="date">14.10.2016</div>
                            <div class="category"><a href="#" data-type="category">Х/ф</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/zvezda/gost-s-kubani/?event=225074">
                                    Гость с Кубани                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="225182" data-category="t-s">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/181749_288_288.jpg)"></div>

                    <span class="item-status-icon">
                                    <div class="icon icon-recordit "><svg class="icon__cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-recordit"></use></svg></div>
                        <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <div class="icon icon-storage ">
                                <svg class="icon__cnt">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-storage"></use>
                                </svg>
                            </div>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">16:00</div>
                            <div class="date">14.10.2016</div>
                            <div class="category"><a href="#" data-type="category">Т/с</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/sts/voroniny/?event=225182">
                                    Воронины                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="225224" data-category="kh-f">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/313525_288_288.jpg)"></div>

                    <span class="item-status-icon">
                                    <div class="icon icon-recordit "><svg class="icon__cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-recordit"></use></svg></div>
                        <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <div class="icon icon-storage ">
                                <svg class="icon__cnt">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-storage"></use>
                                </svg>
                            </div>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">10:45</div>
                            <div class="date">14.10.2016</div>
                            <div class="category"><a href="#" data-type="category">Х/ф</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/mir/o-schastlivchik/?event=225224">
                                    О, счастливчик!                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="225097" data-category="d-s">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/302340_288_288.jpg)"></div>

                    <span class="item-status-icon">
                                    <div class="icon icon-recordit "><svg class="icon__cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-recordit"></use></svg></div>
                        <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <div class="icon icon-storage ">
                                <svg class="icon__cnt">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-storage"></use>
                                </svg>
                            </div>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">17:00</div>
                            <div class="date">14.10.2016</div>
                            <div class="category"><a href="#" data-type="category">Д/с</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/tv3/gadalka/?event=225097">
                                    Гадалка | Концы в воду                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="225181" data-category="t-s">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/384304_288_288.jpg)"></div>

                    <span class="item-status-icon">
                                    <div class="icon icon-recordit "><svg class="icon__cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-recordit"></use></svg></div>
                        <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <div class="icon icon-storage ">
                                <svg class="icon__cnt">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-storage"></use>
                                </svg>
                            </div>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">13:30</div>
                            <div class="date">14.10.2016</div>
                            <div class="category"><a href="#" data-type="category">Т/с</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/sts/kukhnya/?event=225181">
                                    Кухня                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="223492" data-category="tok-shou">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/186334_288_288.jpg)"></div>

                    <span class="item-status-icon">
                                    <div class="icon icon-recordit "><svg class="icon__cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-recordit"></use></svg></div>
                        <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <div class="icon icon-storage ">
                                <svg class="icon__cnt">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-storage"></use>
                                </svg>
                            </div>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">15:15</div>
                            <div class="date">14.10.2016</div>
                            <div class="category"><a href="#" data-type="category">Ток-шоу</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/pervyy-kanal/vremya-pokazhet/?event=223492">
                                    Время покажет                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="230810" data-category="m-s">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/350082_288_288.jpg)"></div>

                    <span class="item-status-icon">
                                    <div class="icon icon-recordit "><svg class="icon__cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-recordit"></use></svg></div>
                        <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <div class="icon icon-storage ">
                                <svg class="icon__cnt">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-storage"></use>
                                </svg>
                            </div>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">19:17</div>
                            <div class="date">14.10.2016</div>
                            <div class="category"><a href="#" data-type="category">М/с</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/karusel/malenkiy-zoomagazin/?event=230810">
                                    Маленький зоомагазин | Хомячья брат...                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="223485" data-category="tok-shou">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/359459_288_288.jpg)"></div>

                    <span class="item-status-icon">
                                    <div class="icon icon-recordit "><svg class="icon__cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-recordit"></use></svg></div>
                        <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <div class="icon icon-storage ">
                                <svg class="icon__cnt">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-storage"></use>
                                </svg>
                            </div>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">10:55</div>
                            <div class="date">14.10.2016</div>
                            <div class="category"><a href="#" data-type="category">Ток-шоу</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/pervyy-kanal/modnyy-prigovor/?event=223485">
                                    Модный приговор                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="225208" data-category="novosti">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/195644_288_288.jpg)"></div>

                    <span class="item-status-icon">
                                    <div class="icon icon-recordit "><svg class="icon__cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-recordit"></use></svg></div>
                        <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <div class="icon icon-storage ">
                                <svg class="icon__cnt">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-storage"></use>
                                </svg>
                            </div>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">18:30</div>
                            <div class="date">14.10.2016</div>
                            <div class="category"><a href="#" data-type="category">Новости</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/pyatyy-kanal/seychas/?event=225208">
                                    Сейчас                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="223338" data-category="tok-shou">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/198277_288_288.jpg)"></div>

                    <span class="item-status-icon">
                                    <div class="icon icon-recordit "><svg class="icon__cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-recordit"></use></svg></div>
                        <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <div class="icon icon-storage ">
                                <svg class="icon__cnt">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-storage"></use>
                                </svg>
                            </div>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">17:45</div>
                            <div class="date">14.10.2016</div>
                            <div class="category"><a href="#" data-type="category">Ток-шоу</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/rossiya-1/pryamoy-efir/?event=223338">
                                    Прямой эфир                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="223483" data-category="poznavatelnoe">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/171147_288_288.jpg)"></div>

                    <span class="item-status-icon">
                                    <div class="icon icon-recordit "><svg class="icon__cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-recordit"></use></svg></div>
                        <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <div class="icon icon-storage ">
                                <svg class="icon__cnt">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-storage"></use>
                                </svg>
                            </div>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">09:20</div>
                            <div class="date">14.10.2016</div>
                            <div class="category"><a href="#" data-type="category">Познавательное</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/pervyy-kanal/kontrolnaya-zakupka/?event=223483">Контрольная закупка</a>
                        </div>
                    </div>
                </div>
            </div>








            <div class="broadcasts__broadcast broadcast broadcast--recordable" data-type="broadcast" data-broadcast-id="70186" data-category="sketch-shou">
                <img class="broadcast__image broadcast-image" height="350" src="img/324640.jpg">
                <div class="broadcast__wrap-status">
                    <span class="broadcast__status js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal">
                        <span data-icon="icon-recordit"></span>
                        <span class="bs-status__title">Записать</span>
                    </span>
                </div>
                <!--<div class="extend-drive-notify">
                    <div class="extend-drive-notify-text-wrap">
                        <span data-icon="icon-storage"></span>
                        <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                        <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                    </div>
                </div>-->
                <div class="broadcast__info broadcast__info--full broadcast-info">
                    <div class="g-flexbox">
                        <div class="broadcast__time broadcast-time">20:00</div>
                        <div class="broadcast__date broadcast-date">12.12.2016</div>
                        <div class="broadcast__title broadcast-title">
                            <a class="broadcast__category-link category-broadcasts-link" data-type="category" href="#">Первый</a>
                        </div>
                    </div>
                    <a class="broadcast__link broadcast-link" href="#">Мистер Олимпия 2016</a>
                </div>
            </div>
            <div class="broadcasts__broadcast broadcast broadcast--viewed" data-type="broadcast" data-broadcast-id="70186" data-category="sketch-shou">
                <img class="broadcast__image broadcast-image" height="350" src="img/324640.jpg">
                <div class="broadcast__wrap-status">
                    <span class='broadcast__status'>
                        <span data-icon='icon-viewed'></span>
                        <span class='bs-status__title'>Просмотрено</span>
                    </span>
                </div>
                <div class="broadcast__info broadcast__info--full broadcast-info">
                    <div class="g-flexbox">
                        <div class="broadcast__time broadcast-time">20:00</div>
                        <div class="broadcast__date broadcast-date">12.12.2016</div>
                        <div class="broadcast__title broadcast-title">
                            <a class="broadcast__category-link category-broadcasts-link" data-type="category" href="#">Первый</a>
                        </div>
                    </div>
                    <a class="broadcast__link broadcast-link" href="#">Мистер Олимпия 2016</a>
                </div>
            </div>
            <div class="broadcasts__broadcast broadcast broadcast--recorded" data-type="broadcast" data-broadcast-id="70186" data-category="sketch-shou">
                <img class="broadcast__image broadcast-image" height="350" src="img/324640.jpg">
                <div class="broadcast__wrap-status">
                    <span class='broadcast__status'>
                        <span data-icon='icon-recorded'></span>
                        <span class='bs-status__title'>Смотреть</span>
                    </span>
                </div>
                <div class="broadcast__info broadcast__info--full broadcast-info">
                    <div class="g-flexbox">
                        <div class="broadcast__time broadcast-time">20:00</div>
                        <div class="broadcast__date broadcast-date">12.12.2016</div>
                        <div class="broadcast__title broadcast-title">
                            <a class="broadcast__category-link category-broadcasts-link" data-type="category" href="#">Первый</a>
                        </div>
                    </div>
                    <a class="broadcast__link broadcast-link" href="#">Мистер Олимпия 2016</a>
                </div>
            </div>
            <div class="broadcasts__broadcast broadcast status-recording" data-type="broadcast" data-broadcast-id="70186" data-category="novosti">
                <img class="broadcast__image broadcast-image" height="350" src="img/324640.jpg">
                <div class="broadcast__wrap-status">
                    <span class='broadcast__status'>
                        <span data-icon='icon-recording'></span>
                        <span class='bs-status__title'>В записи</span>
                    </span>
                </div>
                <div class="broadcast__info broadcast__info--full broadcast-info">
                    <div class="g-flexbox">
                        <div class="broadcast__time broadcast-time">20:00</div>
                        <div class="broadcast__date broadcast-date">12.12.2016</div>
                        <div class="broadcast__title broadcast-title">
                            <a class="broadcast__category-link category-broadcasts-link" data-type="category" href="#">Первый</a>
                        </div>
                    </div>
                    <a class="broadcast__link broadcast-link" href="#">Мистер Олимпия 2016</a>
                </div>
            </div>
            <div class="broadcasts__broadcast broadcast status-notify" data-type="broadcast" data-broadcast-id="70186" data-category="novosti">
                <img class="broadcast__image broadcast-image" height="350" src="img/324640.jpg">
                <div class="broadcast__wrap-status">
                   <span class="broadcast__status item-status-icon">
                        <div data-icon="icon-recording"></div>
                        <span class="bs-status__title">Ваша любимая передача<br> поставлена на запись</span>
                    </span>
                </div>
                <div class="broadcast__info broadcast__info--full broadcast-info">
                    <div class="g-flexbox">
                        <div class="broadcast__time broadcast-time">20:00</div>
                        <div class="broadcast__date broadcast-date">12.12.2016</div>
                        <div class="broadcast__title broadcast-title">
                            <a class="broadcast__category-link category-broadcasts-link" data-type="category" href="#">Первый</a>
                        </div>
                    </div>
                    <a class="broadcast__link broadcast-link" href="#">Мистер Олимпия 2016</a>
                </div>
            </div>
            <div class="broadcasts__broadcast broadcast broadcast--recordable" data-type="broadcast" data-broadcast-id="70186" data-category="novosti">
                <img class="broadcast__image broadcast-image" height="350" src="img/324640.jpg">
                <div class="broadcast__wrap-status">
                    <span class="broadcast__status js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal">
                        <div data-icon="icon-recordit"></div>
                        <span class="bs-status__title">Записать</span>
                    </span>
                </div>
                <div class="broadcast__info broadcast__info--full broadcast-info">
                    <div class="g-flexbox">
                        <div class="broadcast__time broadcast-time">20:00</div>
                        <div class="broadcast__date broadcast-date">12.12.2016</div>
                        <div class="broadcast__title broadcast-title">
                            <a class="broadcast__category-link category-broadcasts-link" data-type="category" href="#">Первый</a>
                        </div>
                    </div>
                    <a class="broadcast__link broadcast-link" href="#">Мистер Олимпия 2016</a>
                </div>
            </div>
            <div class="broadcasts__broadcast broadcast broadcast--recordable" data-type="broadcast" data-broadcast-id="70186" data-category="novosti">
                <img class="broadcast__image broadcast-image" height="350" src="img/324640.jpg">
                <div class="broadcast__wrap-status">
                    <span class="broadcast__status js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal">
                        <div data-icon="icon-recordit"></div>
                        <span class="bs-status__title">Записать</span>
                    </span>
                </div>
                <div class="broadcast__info broadcast__info--full broadcast-info">
                    <div class="g-flexbox">
                        <div class="broadcast__time broadcast-time">20:00</div>
                        <div class="broadcast__date broadcast-date">12.12.2016</div>
                        <div class="broadcast__title broadcast-title">
                            <a class="broadcast__category-link category-broadcasts-link" data-type="category" href="#">Первый</a>
                        </div>
                    </div>
                    <a class="broadcast__link broadcast-link" href="#">Мистер Олимпия 2016</a>
                </div>
            </div>
            <div class="broadcasts__broadcast broadcast broadcast--recordable" data-type="broadcast" data-broadcast-id="70186" data-category="novosti">
                <img class="broadcast__image broadcast-image" height="350" src="img/324640.jpg">
                <div class="broadcast__wrap-status">
                    <span class="broadcast__status js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal"> 
                        <div data-icon="icon-recordit"></div>
                        <span class="bs-status__title">Записать</span>
                    </span>
                </div>
                <div class="broadcast__info broadcast__info--full broadcast-info">
                    <div class="g-flexbox">
                        <div class="broadcast__time broadcast-time">20:00</div>
                        <div class="broadcast__date broadcast-date">12.12.2016</div>
                        <div class="broadcast__title broadcast-title">
                            <a class="broadcast__category-link category-broadcasts-link" data-type="category" href="#">Первый</a>
                        </div>
                    </div>
                    <a class="broadcast__link broadcast-link" href="#">Мистер Олимпия 2016</a>
                </div>
            </div>
            <div class="broadcasts__broadcast broadcast broadcast--recordable" data-type="broadcast" data-broadcast-id="70186" data-category="novosti">
                <img class="broadcast__image broadcast-image" height="350" src="img/324640.jpg">
                <div class="broadcast__wrap-status">
                    <span class="broadcast__status js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal">
                        <div data-icon="icon-recordit"></div>
                        <span class="bs-status__title">Записать</span>
                    </span>
                </div>
                <div class="broadcast__info broadcast__info--full broadcast-info">
                    <div class="g-flexbox">
                        <div class="broadcast__time broadcast-time">20:00</div>
                        <div class="broadcast__date broadcast-date">12.12.2016</div>
                        <div class="broadcast__title broadcast-title">
                            <a class="broadcast__category-link category-broadcasts-link" data-type="category" href="#">Первый</a>
                        </div>
                    </div>
                    <a class="broadcast__link broadcast-link" href="#">Мистер Олимпия 2016</a>
                </div>
            </div>
            <div class="broadcasts__broadcast broadcast broadcast--recordable" data-type="broadcast" data-broadcast-id="70186" data-category="novosti">
                <img class="broadcast__image broadcast-image" height="350" src="img/324640.jpg">
                <div class="broadcast__wrap-status">
                    <span class="broadcast__status js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal">
                        <div data-icon="icon-recordit"></div>
                        <span class="bs-status__title">Записать</span>
                    </span>
                </div>
                <div class="broadcast__info broadcast__info--full broadcast-info">
                    <div class="g-flexbox">
                        <div class="broadcast__time broadcast-time">20:00</div>
                        <div class="broadcast__date broadcast-date">12.12.2016</div>
                        <div class="broadcast__title broadcast-title">
                            <a class="broadcast__category-link category-broadcasts-link" data-type="category" href="#">Первый</a>
                        </div>
                    </div>
                    <a class="broadcast__link broadcast-link" href="#">Мистер Олимпия 2016</a>
                </div>
            </div>
            <div class="broadcasts__broadcast broadcast broadcast--recordable" data-type="broadcast" data-broadcast-id="70186" data-category="novosti">
                <img class="broadcast__image broadcast-image" height="350" src="img/324640.jpg">
                <div class="broadcast__wrap-status">
                    <span class="broadcast__status js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal">
                        <div data-icon="icon-recordit"></div>
                        <span class="bs-status__title">Записать</span>
                    </span>
                </div>
                <div class="broadcast__info broadcast__info--full broadcast-info">
                    <div class="g-flexbox">
                        <div class="broadcast__time broadcast-time">20:00</div>
                        <div class="broadcast__date broadcast-date">12.12.2016</div>
                        <div class="broadcast__title broadcast-title">
                            <a class="broadcast__category-link category-broadcasts-link" data-type="category" href="#">Первый</a>
                        </div>
                    </div>
                    <a class="broadcast__link broadcast-link" href="#">Мистер Олимпия 2016</a>
                </div>
            </div>
            <? } ?>
        </div>

        <script type="text/x-template" id="broadcastFullTmpl">
            <div class="broadcasts__broadcast broadcast broadcast--full <%- status %>" data-type="broadcast" data-broadcast-id="<%- id %>" data-category="<%- categoryLink %>">
                <img class="broadcast__image broadcast-image" height="350" src="<%- image %>">
                <div class="broadcast__wrap-status">
                    <%= placeholder %>
                </div>
                <div class="broadcast__alert bs-alert bs-alert--extend-drive">
                    <span data-icon="icon-storage"></span>
                    <p class="g-mt-5">У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                    <p><a class="msg" href="/personal/services/">Заказать дополнительную емкость</a></p>
                </div>
                <div class="broadcast__info broadcast__info--full broadcast-info">
                    <div class="g-flexbox">
                        <div class="broadcast__time broadcast-time"><%- time %></div>
                        <div class="broadcast__date broadcast-date"><%- date %></div>
                        <div class="broadcast__title broadcast-title">
                            <a class="broadcast__category-link category-broadcasts-link" data-type="category" href="<%- categoryLink %>"><%- categoryName %></a>
                        </div>
                    </div>
                    <a class="broadcast__link broadcast-link" href="<%- link %>"><%- name %></a>
                </div>
            </div>
        </script>
        <script id="nonAuthTmpl" type="text/x-template">
            <span class="broadcast__status item-status-icon" data-module="modal" data-modal="authURL" data-type="openModal">
                <span data-icon="icon-recordit"></span>
                <span class="bs-status__title">Записать</span>
            </span>
        </script>
        <script id="status-recordableTmpl" type="text/x-template">
            <span class="broadcast__status item-status-icon">
                <span data-icon="icon-recordit"></span>
                <span class="bs-status__title">Записать</span>
            </span>
        </script>
        <script id="status-viewedTmpl" type="text/x-template">
            <span class="broadcast__status item-status-icon">
                <span data-icon="icon-viewed"></span>
                <span class="bs-status__title">Просмотрено</span>
            </span>
        </script>
        <script id="status-recordedTmpl" type="text/x-template">
            <span class='broadcast__status item-status-icon'>
                <span data-icon='icon-recorded'></span>
                <span class='bs-status__title'>Смотреть</span>
            </span>
        </script>
        <script id="recording-notifyTmpl" type="text/x-template">
            <span class="broadcast__status item-status-icon">
                <div data-icon="icon-recording"></div>
                <span class="bs-status__title">Ваша любимая передача<br> поставлена на запись</span>
            </span>
        </script>
        <script id="status-recordingTmpl" type="text/x-template">
            <span class='broadcast__status item-status-icon'>
                <span data-icon='icon-recording'></span>
                <span class='bs-status__title'>В записи</span>
            </span>
        </script>
        <script>
            // var returnEl = $('<div class="item ' + item.status + '" data-type="broadcast" data-broadcast-id="' + item.id + '" data-category="' + item.category.link + '"> <div class="inner">' + item.button + '<div class="item-header"> <div class="meta"> <div class="time">' + item.time + '</div><div class="date">' + item.date + '</div><div class="category"><a href="#" data-type="category">' + item.category.name + '</a></div></div><div class="title"> <a href="' + item.link + '"> ' + item.name + ' </a> </div></div></div></div>');
        </script>
    </section>
</main>
<?
    $js = [
        "js/main.js"
    ];
    require("include/footer.php");
?>