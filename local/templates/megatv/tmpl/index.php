<? 
    require("include/header.php");
?>

<main class="site-content">

    <section class="section-h1 main-page__title">
        <h1>Программа телепередач на сегодня</h1>
    </section>
    
    <section class="main-container" data-module="broadcast-results">
        <script type="text/x-config">
            {
                "weekdays" : {
                    "Mon" : "Понедельник",
                    "Tue" : "Вторник",
                    "Wed" : "Среда",
                    "Thu" : "Четверг",
                    "Fri" : "Пятница",
                    "Sat" : "Суббота",
                    "Sun" : "Воскресенье"
                }
            }
        </script>
        <div class="broadcasts-loader">
            <div class="broadcasts-loader__title"><p style="font-size:30px">Подождите,</p> <p>идёт загрузка элементов...</p></div>
            <div class="broadcasts-loader__divimg">
                <img src="<?=SITE_TEMPLATE_PATH?>/tmpl/img/loader.gif" alt="" class="broadcasts-loader__img">
            </div>
        </div>
        <div hidden class="broadcasts-json"></div>
        <div class="main-broadcasts main-broadcasts--slider">
            <div class="main-broadcasts__channels channels">
                <div class="channels__wrapper"></div>
            </div>

            <div class="main-broadcasts__broadcasts broadcasts">
                <!-- Swiper -->
                <div class="broadcasts__container bs-container swiper-container">
                    <!-- Add Scrollbar -->
                    <div class="bs-container__scrollbar swiper-scrollbar"></div>
                    <div class="bs-container__wrapper bs-wrapper swiper-wrapper"></div>
                    <!-- Add Arrows -->
                    <div class="broadcasts__wrapper-control">
                        <div class="broadcasts__btn-control bs-box-btn-next">
                            <button class="swiper-button-next"></button>
                        </div>
                        <div class="broadcasts__btn-control bs-box-btn-prev">
                            <button class="swiper-button-prev"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


<script id="paramsJson" type="text/json">
</script>
<script id="broadcastTmpl" type="text/x-template">
    <div class="bs-container__broadcast broadcast">
        <% if(onAir){ %>
        <span class="broadcast__on-air">В эфире</span>
        <% } %>
        <img class="broadcast__image broadcast-image lazy-img swiper-lazy" height="350" data-src="<%- blurImage %>" data-load="<%- image %>">
        <span class="broadcast__status <% var isAuth = isAuth || false; if(!isAuth){ %> js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal"<% }else{ %>" <% } %>>        
            <div data-icon="icon-recordit"></div>
            <span class="bs-status__title">Записать</span>
        </span>
        <div class="broadcast__info broadcast-info">
            <div class="broadcast__time broadcast-time"><%- time %></div>
            <div class="broadcast__title broadcast-title">
                <a class="broadcast__link broadcast-link" href="<%- link %>"><%- title %></a>
            </div>
        </div>
    </div>
</script>

<script id="broadcastEmptyTmpl" type="text/x-template">
    <div class="bs-container__broadcast broadcast">
        <img class="broadcast__image broadcast-image" style="opacity:0.35;" height="350" src="/img/emptyBroadcast.jpg">
        <div class="broadcast__info broadcast-info">
            <div class="broadcast__time broadcast-time"><%- time %></div>
            <div class="broadcast__title broadcast-title">
                <a class="broadcast__link broadcast-link" href="#">Профилактика на канале</a>
            </div>
        </div>
    </div>
</script>

<script id="channelTmpl" type="text/x-template">
    <div class="channels__channel channel">
        <a href="<%- link %>" class="channel__link" data-channel-id="<%- id %>">
            <span data-icon="<%- icon %>"></span>
        </a>
    </div>
</script>

<script id="timelineTmpl" type="text/x-template">
    <div class="timeline">
        <div class="timeline__title"><%- today %></div>
        <div class="timeline__line"></div>
    </div>
</script>

<script id="beginDayMarkTmpl" type="text/x-template">
    <div class="day-mark">
        <div class="day-mark__begin-day begin-day">
            <div class="begin-day__title"><%- beginDayTitle %></div>
        </div>
    </div>
</script>

<script id="endDayMarkTmpl" type="text/x-template">
    <div class="day-mark">
        <div class="day-mark__end-day end-day">
            <div class="end-day__title"><%- endDayTitle %></div>
        </div>
    </div>
</script>

	<?require("include/content-bottom.php");?>

</main>

<?
    $js = [
        "js/main.js"
    ];
    require("include/footer.php");
?>
