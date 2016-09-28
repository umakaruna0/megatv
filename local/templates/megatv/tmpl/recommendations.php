<?
	$items = true;
	require("include/header.php");
?>

<main class="site-content">
    <section class="section-h1 hidden">
        <h1></h1></section>
    <div class="broadcasts-categories" data-module="broadcasts-categories">
        <script type="text/x-config">
            { "url": "/recommendations/" }
        </script>
        <div class="categories-broadcasts">
            <a href="#" class="category-broadcasts category-broadcasts--active" data-type="category-broadcasts" data-category="all">
			Все
		</a>
            <a href="#novosti" class="category-broadcasts" data-type="category-broadcasts" data-category="novosti">
    			Новости    		</a>
            <a href="#d-s" class="category-broadcasts" data-type="category-broadcasts" data-category="d-s">
    			Д/с    		</a>
            <a href="#sketch-shou" class="category-broadcasts" data-type="category-broadcasts" data-category="sketch-shou">
    			Скетч-шоу    		</a>
            <a href="#tok-shou" class="category-broadcasts" data-type="category-broadcasts" data-category="tok-shou">
    			Ток-шоу    		</a>
            <a href="#t-s" class="category-broadcasts" data-type="category-broadcasts" data-category="t-s">
    			Т/с    		</a>
        </div>
        <div class="more" data-type="more">
            <span data-icon="icon-close"></span>
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
        </div>
    </div>

    <!-- <link rel="stylesheet" href="css/recommendations.css"> -->

    <section class="main-broadcasts main-broadcasts--static" data-offset="0" data-date="<?=date("d\.m\.Y");?>" data-module="recomended-broadcasts"><!-- "viewMoreUrl" : "/recommendations/?AJAX=Y", -->
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
        <div class="broadcasts-list main-broadcasts__broadcasts broadcasts">
	    	<? if(!$items){ ?>
	    	<!-- ========================= Если рекоммендаций нет  ========================= -->
	    	<div class="empty-content">
	    		<h1 class="empty-content__title">Список рекомендаций пуст...</h1>
	    	</div>
	    	<!-- ======================= ! Если рекоммендаций нет !  ======================= -->
	    	<? }else{ ?>
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