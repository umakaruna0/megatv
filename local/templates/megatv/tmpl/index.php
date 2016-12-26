<? 
    $css = [
        "css/main.css",
        "css/testcss.css"
    ];
    require("include/header.php");
?>
<style>
    .prev-icon{
        /*background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg+xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22+xmlns%3Axlink%3D%22http%3A%2F%2Fwww.w3.org%2F1999%2Fxlink%22+version%3D%221.1%22+viewBox%3D%220+0+129+129%22+enable-background%3D%22new+0+0+129+129%22+width%3D%22512px%22+height%3D%22512px%22%3E%3Cg%3E%3Cpath+d%3D%22m121.3%2C34.6c-1.6-1.6-4.2-1.6-5.8%2C0l-51%2C51.1-51.1-51.1c-1.6-1.6-4.2-1.6-5.8%2C0-1.6%2C1.6-1.6%2C4.2+0%2C5.8l53.9%2C53.9c0.8%2C0.8+1.8%2C1.2+2.9%2C1.2+1%2C0+2.1-0.4+2.9-1.2l53.9-53.9c1.7-1.6+1.7-4.2+0.1-5.8z%22+fill%3D%22%23FFFFFF%22%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E");*/
    }
    .next-icon{
        /*background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg+xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22+xmlns%3Axlink%3D%22http%3A%2F%2Fwww.w3.org%2F1999%2Fxlink%22+version%3D%221.1%22+viewBox%3D%220+0+129+129%22+enable-background%3D%22new+0+0+129+129%22+width%3D%22512px%22+height%3D%22512px%22%3E%3Cg%3E%3Cpath+d%3D%22m121.3%2C34.6c-1.6-1.6-4.2-1.6-5.8%2C0l-51%2C51.1-51.1-51.1c-1.6-1.6-4.2-1.6-5.8%2C0-1.6%2C1.6-1.6%2C4.2+0%2C5.8l53.9%2C53.9c0.8%2C0.8+1.8%2C1.2+2.9%2C1.2+1%2C0+2.1-0.4+2.9-1.2l53.9-53.9c1.7-1.6+1.7-4.2+0.1-5.8z%22+fill%3D%22%23FFFFFF%22%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E");*/
    }
</style>
<main class="site-content">

    <section class="section-h1 ">
        <h1>Программа телепередач на сегодня</h1>
    </section>
    
    <section class="broadcast-results" data-date="" data-module="broadcast-results">
        <div class="broadcasts-loader">
            <div class="broadcasts-loader__title"><p style="font-size:30px">Подождите,</p> <p>идёт загрузка элементов...</p></div>
            <div class="broadcasts-loader__divimg">
                <img src="/img/loader.gif" alt="" class="broadcasts-loader__img">
            </div>
        </div>
        <script type="text/x-config">
            { "recordingURL": "<?=SITE_TEMPLATE_PATH;?>/ajax/to_record.php", "fetchResultsURL" : "/", "page": "2", "ajaxType": {
                "start" : "CHANNELS",
                "next" : "nextChannels",
                "prev" : "prevChannels"
            }, "countChannels" : "10" }
        </script>

        <a href="#" class="prev-button" data-type="prev-button"><span class="sticky-wrapp">
			<span class="prev-date">Суббота. 30 июля</span>
            <span data-icon="icon-kinetic-arrow"></span>
            </span>
        </a>

        <div class="prev-channels">
            <div class="control-margin"></div>
            <div class="prev-btn control-btn">
                <div class="prev-icon">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 59.415 59.415" style="enable-background:new 0 0 59.415 59.415;" xml:space="preserve"><g><polygon points="58,45.268 29.708,16.975 1.414,45.268 0,43.854 29.708,14.147 59.415,43.854"/></g></svg>
                </div>
                <div class="prev-title">Предыдущие 10 каналов</div>
            </div>
        </div>

        <div class="slider-broadcasts">
            <div class="categories-logos"></div>
            
            <div class="categories-items kinetic-active swiper-container">
                <div class="canvas-wrap swiper-wrapper"></div>
                <div class="swiper-scrollbar"></div>
            </div>
            <!-- /.categories-items -->
        </div>

        <div class="next-channels">
            <div class="control-margin"></div>
            <div class="next-btn control-btn">
                <div class="next-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 129 129" enable-background="new 0 0 129 129"><g><path d="m121.3,34.6c-1.6-1.6-4.2-1.6-5.8,0l-51,51.1-51.1-51.1c-1.6-1.6-4.2-1.6-5.8,0-1.6,1.6-1.6,4.2 0,5.8l53.9,53.9c0.8,0.8 1.8,1.2 2.9,1.2 1,0 2.1-0.4 2.9-1.2l53.9-53.9c1.7-1.6 1.7-4.2 0.1-5.8z"></path></g></svg>
                </div>
                <div class="next-title">Следующие 10 каналов</div>
            </div>
        </div>

        <a href="#" class="next-button" data-type="next-button"><span class="sticky-wrapp">
			<span class="next-date">Воскресенье. 31 июля</span>
            	<span data-icon="icon-kinetic-arrow"></span>
            </span>
        </a>

    </section>

	<?require("include/content-bottom.php");?>

</main>

<script id="broadcastTmpl" type="text/template">
    <div class="broadcast status-recordable" data-type="broadcast" data-broadcast-id="<%- id %>">
        <div class="item-image-holder">
            <img class="lazy-img swiper-lazy" data-src="<%- blurImage %>" data-load="<%- image %>" alt="<%- title %>">
        </div>

        <% if(onAir){ %>
        <span class="badge" data-channel-id="115">в эфире</span>
        <% } %>

        <div class="broadcast__wrap-status">
            <span class="item-status-icon js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal">
                <span data-icon="icon-recordit"></span>
                <span class="status-desc">Записать</span>
            </span>
        </div>

        <div class="item-header">
            <time><%- time %></time>
            <a href="<%- link %>"><%- title %></a>
        </div>
    </div>
</script>

<script id="categoryLogoTmpl" type="text/template">
    <a class="category-logo" href="<%- link %>" data-channel-id="<%- id %>">
        <span data-icon="<%- icon %>"></span>
    </a>
</script>

<script id="swSlideTmpl" type="text/template">
    <div class="swiper-slide day-mark">
        <div class="day-mark__begin-day begin-day">
            <div class="begin-day__title"><%= current %></div>
        </div>
    </div>
    <div class="swiper-slide broadcasts-full-day broadcasts-column" data-br-day="<%= day %>"></div>
    <div class="swiper-slide day-mark">
        <div class="day-mark__end-day end-day">
            <div class="end-day__title"><%= current %></div>
        </div>
    </div>
</script>

<script id="bottomPreloaderTmpl" type="text/template">
    <div class="bottom-preloader" style="display: none;">
        <div class="bottom-preloader__wrap">
            <img src="/img/loader.gif" alt="Bottom Preloader" class="bottom-preloader__loader">
        </div>
    </div>
</script>

<script id="timelineTmpl" type="text/x-template">
    <div class="timeline">
        <div class="timeline__title"><%- today %></div>
        <div class="timeline__line"></div>
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

<script id="status-notifyTmpl" type="text/x-template">
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

<?
    $js = [
        "js/jquery.js",
        "js/swiper.jquery.js",
        "js/underscore.js",
        "js/json.js",
        "js/testscript.js"
    ];
    require("include/footer.php");
?>


<script>
if (!window.setImmediate) window.setImmediate = (function() {
  var head = { }, tail = head; // очередь вызовов, 1-связный список

  var ID = Math.random(); // уникальный идентификатор

  function onmessage(e) {
    if(e.data != ID) return; // не наше сообщение
    head = head.next;
    var func = head.func;
    delete head.func;
    func();
  }

  if(window.addEventListener) { // IE9+, другие браузеры
    window.addEventListener('message', onmessage);
  } else { // IE8
    window.attachEvent( 'onmessage', onmessage );
  }

  return function(func) {
    tail = tail.next = { func: func };
    window.postMessage(ID, "*");
  };
}());

!(function($){
    'use strict';
    var CONSTS = {
        // MAIN CONSTS
        DATE_START_TITLE : "date_start",
        DATE_END_TITLE : "date_end",
        NATIVE_DATE_START_TITLE : "native_date_start",
        NATIVE_DATE_END_TITLE : "native_date_end",
        DATE_DIFF_TITLE : "date_diff",
        ID_TITLE : "id",
        CHANNEL_ID_TITLE : "channel_id",
        DATES_TITLE : "DATES",
        COLUMN_TITLE : "column",
        CHANNEL_ID_TITLE : "channel_id",
        NAME_TITLE : "name",
        IMGS_TITLE : "images",
        IMG_TITLE : "image",
        IMG_BAD_PATH : "/local/templates/megatv/ajax/img_grey.php?quality=1&grey=false&path=/home/d/daotel/dev.megatv.su/public_html",
        LINK_TITLE : "link",
        TIME_TITLE : "time",
        ON_AIR_TITLE : "on_air",
        CHANNELS_TITLE : "CHANNELS",
        STATUS_TITLE : "status",
        AUTH_TITLE : "auth",
        DETAIL_PAGE_URL_TITLE : "DETAIL_PAGE_URL",
        ICON_TITLE : "ICON",
        WIDTH_BROADCAST : 288,
        HEIGHT_BROADCAST : 288,
        DOUBLE_ITEM_CLASS : "double-item",
        BROADCASTS_ROW_CLASS : "broadcasts-row",
        PAIR_CONTAINER_CLASS : "pair-container",
        PAIR_CONTAINER_ID_TMPL : "pairContainerTmpl",
        BROADCAST_ID_TMPL : "broadcastTmpl",
        SWSLIDE_ID_TMPL : "swSlideTmpl",
        CATEGORY_LOGO_ID_TMPL : "categoryLogoTmpl",
        BROADCASTS_FULL_DAY : "broadcasts-full-day",
        CHANNEL_LINK : "DETAIL_PAGE_URL",
        CHANNEL_ICON : "ICON",
        CHANNEL_ID : "ID",
        CATEGORIES_LOGOS_CLASS : ".categories-logos",
        CATEGORY_LOGO_CLASS : ".category-logo",
        BOTTOM_PRELOADER_ID_TMPL : "bottomPreloaderTmpl",
        BROADCAST_RESULTS_CLASS : ".broadcast-results",
        BPL_CLASS : "bottom-preloader-ph",
        WEEKDAYS : [
            "Понедельник",
            "Вторник",
            "Среда",
            "Четверг",
            "Пятница",
            "Суббота",
            "Воскресенье"
        ],
        PRELOADER_CLASS : "broadcasts-loader",
        PRELOADER_LOADED_CLASS : "broadcasts-loader--loaded",
        PRELOADER_LOADING_CLASS : "broadcasts-loader--loading",
        STATUS : {
            empty : "status-",
            recordable : "status-recordable",
            recording : "status-recording",
            viewed : "status-viewed",
            recorded : "status-recorded",
            notify : "status-notify",
        },

        // SWIPER-SLIDER
        swContainer : '.swiper-container',
        swWrapper : '.swiper-wrapper',
        swPrevButton : '.swiper-button-prev',
        swNextButton : '.swiper-button-next',
        swScrollbar : '.swiper-scrollbar',
        swSlide : '.swiper-slide',
        swLazy : '.swiper-lazy',
        swLazyLoading : '.swiper-lazy-loading',
        swLazyLoaded : '.swiper-lazy-loaded',
        swSlideActive : '.swiper-slide-active',
        swSlideNext : '.swiper-slide-next',
        swSlidePrev : '.swiper-slide-prev',
        
        PATTERN : "YYYY-MM-DD HH:mm:ss"
    };

    var Broadcasts = function (options) {
        var self = this;
        $.each(options, function(key, value){
          self[key] = value;
        });
        this.swiper = null;
        this.consts = $.extend(CONSTS, self.consts);
    }

    Broadcasts.prototype = {
        initialize: function(){
            var self = this;
            var CS = this.consts;
            this.time = this.json.time;
            var broadcasts = this.broadcasts = this.json.broadcasts;
            var channels = this.channels = this.json.channels;
            var wrapper = $(CS.swWrapper);
            var logos = $(CS.CATEGORIES_LOGOS_CLASS);

            var html = self.renderToHTML({
                broadcasts: broadcasts, 
                channels: channels
            });

            wrapper.html(self.minify(html[0]));
            logos.html(self.minify(html[1]));

            setImmediate(function(){
                $(CS.swWrapper + ' > div:first-child.day-mark').remove();
                setImmediate(function(){
                    self.swiper = self.initSwiper();
                    self.fixedTimeline();
                    self.loadImages();
                    broadcasts = null, channels = null, wrapper = null, logos = null, html = null;
                });
            });

            setInterval(function(){
                if($(CS.swLazy)[0]){
                    //self.loadImages();
                }
                sessionStorage.setItem('slide', self.getPositionUser("slide"));
                sessionStorage.setItem('top', self.getPositionUser("top"));
                sessionStorage.setItem('channels', self.getCurrentChannels());
                sessionStorage.setItem('dates', self.getCurrentDates());
            },1000);
        },

        addChannels: function(object){
            var self = this;
            var CS = this.consts;
            var broadcasts = object.broadcasts;
            var channels = object.channels;
            var logos = $(CS.CATEGORIES_LOGOS_CLASS);

            var html = self.renderToHTML({
                broadcasts: broadcasts, 
                channels: channels
            });

            if($("." + CS.BPL_CLASS).length <= 0) logos.append(html[1]);
            else $("." + CS.BPL_CLASS).before(html[1]);

            var wrapper = $("<div />").html(html[0]);
            wrapper.find("." + CS.BROADCASTS_ROW_CLASS).each(function(){
                var $bfd = $(this).parent();
                var $bfdChild = $(this);
                var tempDIV = $('<div>').append($bfdChild.clone());
                $('[data-br-day="' + $bfd.data("br-day") + '"]').append(tempDIV.html());
                setImmediate(function(){
                    tempDIV = null, $bfdChild = null, $bfd = null;
                });
            });

            setImmediate(function(){
                self = null, CS = null, broadcasts = null, wrapper = null, channels = null, html = null, logos = null;
            });
        },

        minify: function(buffer) {
            return (buffer.replace(/\n{1,}/gi,"")).replace(/([>])(\s){1,}([<])/gi, "><");
        },

        addDay: function(object){
            if(_.isEmpty(this.json)) {
                this.json = object;
                this.initialize();
                return;
            }
            var self = this;
            var CS = this.consts;
            var broadcasts = object.broadcasts;
            var channels = object.channels;
            var logos = $(CS.CATEGORIES_LOGOS_CLASS);

            var html = self.renderToHTML({
                broadcasts: broadcasts, 
                channels: channels
            });

            var wrapper = $("<div />").html(self.minify(html[0]));
            wrapper.children().each(function(){
                var $bfd = $(this);
                self.addSlides(self.minify($("<div />").html($bfd.clone()).html()));
                setImmediate(function(){
                    $bfd = null;
                });
            });

            setImmediate(function(){
                self = null, CS = null, broadcasts = null, wrapper = null, channels = null, html = null, logos = null;
            });
        },

        renderToHTML: function(object){
            var broadcasts = object.broadcasts;
            var channels = object.channels;
            var self = this;
            var CS = this.consts;
            var wrapper = $("<div />");
            var logos = $("<div />");
            var date = "", channelBroadcasts = null, channel = null, ch = null, item = null, $item = null, $channel = null;
                                                                                                                               
            broadcasts = self.renderBroadcasts(broadcasts);
            for (date in broadcasts) {
                item = broadcasts[date];
                if(wrapper.find('[data-br-day="' + date + '"]').length <= 0){
                    wrapper.append(self.tmpl("#" + CS.SWSLIDE_ID_TMPL, {
                        current: item.current,
                        day: date
                    }));
                }
                $item = wrapper.find('[data-br-day="' + date + '"]');
                for(ch in item.broadcasts){ 
                    channel = channels[ch];
                    if($item.find('[data-br-channel="' + ch + '"]').length <= 0){
                        $item.append('<div class="' + CS.BROADCASTS_ROW_CLASS + '" data-br-channel="' + ch + '"></div>');
                    }else break;

                    if(logos.find('[data-channel-id="' + ch + '"]').length <= 0){
                        logos.append(this.tmpl("#" + CS.CATEGORY_LOGO_ID_TMPL, {
                            id : channel[CS.CHANNEL_ID],
                            link : channel[CS.CHANNEL_LINK],
                            icon : channel[CS.CHANNEL_ICON]
                        }));  
                    }

                    $channel = $item.find('[data-br-channel="' + ch + '"]');
                    channelBroadcasts = item.broadcasts[ch];   
                    $channel.append(channelBroadcasts);
                }
            }

            self.enterWidth(wrapper.find("." + CS.BROADCASTS_FULL_DAY));

            var returnArr = [
                wrapper.html(),
                logos.html()
            ];

            setImmediate(function(){
                self = null, CS = null, wrapper = null, logos = null, date = null, channelBroadcasts = null, channel = null, ch = null, item = null, $item = null, $channel = null, broadcasts = null, channels = null, returnArr;
            });

            return returnArr;
        },

        enterWidth: function($broadcasts){
            var self = this;
            var CS = self.consts;
            var width = 0;
            $broadcasts.each(function(){
                var wRow = 0;
                $(this).find("." + CS.BROADCASTS_ROW_CLASS).each(function(){
                    var wBrs = 0;
                    $(this).children().each(function(){
                        wBrs += $(this).width();
                    });
                    $(this).width(wBrs);
                    if(wRow < wBrs) wRow = wBrs;
                });
                $(this).width(wRow);
                if(width < wRow) width = wRow;
            });
            return $broadcasts;
        },
            
        tmpl: function(selector, obj){
            var returnTmpl = _.template($(selector).html());
            return returnTmpl(obj);
        },

        renderBroadcasts: function(broadcasts){
            var self = this;
            var CS = self.consts;

            function getOutput(obj){
                var origin = self.origin;
                return {
                    id : obj[CS.ID_TITLE],
                    title : obj[CS.NAME_TITLE],
                    link : obj[CS.LINK_TITLE],
                    status : obj[CS.STATUS_TITLE],
                    time : obj[CS.DATE_START_TITLE].replace(/.*\s([0-9]{2}\:[0-9]{2}).*/,"$1"),
                    onAir : obj[CS.ON_AIR_TITLE],
                    auth : self.auth,
                    noAir : (!obj[CS.ON_AIR_TITLE]) ? true : false,
                    channel_id : obj[CS.CHANNEL_ID_TITLE],
                    image : origin + obj[CS.IMG_TITLE],
                    blurImage : origin + CS.IMG_BAD_PATH + obj[CS.IMG_TITLE]
                }
            }

            var channel = {}, item = {}, key = "", ch = 0, tp = {}, bs = 0, broadcastTmpl = "", $pairContainer = "", broadcastsTmpl = "", output = {}, $broadcast = null, $categoryRow = null, categoriesRows = {}, broadcastsReturn = {}, broadcastsRow = null;

            for(key in broadcasts){
                item = broadcasts[key];
                for (ch in item.channels) {
                    channel = item.channels[ch];
                    $categoryRow = $("<div class='" + CS.BROADCASTS_ROW_CLASS + "'></div>");
                    for (var y = 0; y < channel.length; y++) {
                        broadcastsRow = channel[y];
                        for (tp in broadcastsRow) {
                            bs = broadcastsRow[tp];
                            output = getOutput(bs[0]);
                            broadcastTmpl = self.tmpl("#" + CS.BROADCAST_ID_TMPL, output);
                            $broadcast = $(broadcastTmpl);

                            switch(tp){
                                case "one":
                                    $broadcast.css({
                                        width: (CS.WIDTH_BROADCAST)
                                    });
                                break;

                                case "double":
                                    $broadcast.css({
                                        width: (2 * CS.WIDTH_BROADCAST)
                                    }).addClass(CS.DOUBLE_ITEM_CLASS);
                                break;

                                case "half":
                                    broadcastsTmpl = "";
                                    for (var i = 0; i < bs.length; i++) {
                                        output = getOutput(bs[i]);
                                        broadcastsTmpl += self.tmpl("#" + CS.BROADCAST_ID_TMPL, output);
                                    }
                                    $broadcast = $("<div style='width: " + (CS.WIDTH_BROADCAST) + "px' class='" + CS.PAIR_CONTAINER_CLASS + "'></div>");
                                    $broadcast.html(broadcastsTmpl);
                                break;

                                default:
                                    var num = parseInt(tp);
                                    if(num <= 0) return false;
                                    $broadcast.css({
                                        width: (num * CS.WIDTH_BROADCAST)
                                    });
                                break;
                            }
                            $broadcast = self.addStatus($broadcast, output[CS.STATUS_TITLE]);
                            $categoryRow.append($broadcast);
                        }
                    }
                    categoriesRows[ch] = $categoryRow.html();
                }
                broadcastsReturn[key] = {};
                broadcastsReturn[key].broadcasts = categoriesRows;
                if("currentDay" in item) broadcastsReturn[key].current = item.currentDay;
            }
            
            setImmediate(function(){
                self = null, CS = null, channel = null, item = null, key = null, ch = null, tp = null, bs = null, broadcastTmpl = null, $pairContainer = null, broadcastsTmpl = null, output = null, $broadcast = null, $categoryRow = null, categoriesRows = null, broadcastsReturn = null, broadcastsRow = null;
            });

            return broadcastsReturn;
        },

        initSwiper: function (){
            var CS = this.consts;
            var swiper = new Swiper(CS.swContainer, {
                scrollbar: CS.swScrollbar,
                slidesPerView: "auto",
                scrollbarHide: true,
                keyboardControl: false,
                nextButton: CS.swNextButton,
                prevButton: CS.swPrevButton,
                spaceBetween: 0,
                hashnav: true,
                preloadImages: false,
                lazyLoading: true,
                lazyLoadingOnTransitionStart: true,
                grabCursor: false,
                freeMode: true,
                freeModeMomentum: false,
                freeModeMomentumBounce: false
            }); 
            
            setImmediate(function(){
                CS = null, swiper = null;
            });

            return swiper;
        },

        destroySwiper: function(){
            this.swiper.destroy();
            this.swiper = null;
            this.json = null;
        },

        getCurrentChannels: function(){
            var self = this;
            var CS = this.consts;
            var returnChannels = [];

            var logos = $(CS.CATEGORIES_LOGOS_CLASS + " " + CS.CATEGORY_LOGO_CLASS);
            logos.each(function(){
                var channel = $(this).data("channel-id");
                returnChannels.push(channel);
            });

            setImmediate(function(){
                self = null, CS = null, logos = null, returnChannels = null;
            });

            return returnChannels;
        },

        getCurrentDates: function(){
            var self = this;
            var CS = this.consts;
            var returnDates = [];

            var days = $("." + CS.BROADCASTS_FULL_DAY);
            days.each(function(){
                var date = $(this).data("br-day");
                returnDates.push(date);
            });

            setImmediate(function(){
                self = null, CS = null, days = null, returnDates = null;
            });

            return returnDates;
        },
        
        addSlides: function (slidesHTML, method){
            var self = this;
            if(!method) method = "append";
            var $div = $("<div />").html(slidesHTML);
            var slides = [];
            $div.children().each(function(){
                var $this = $(this);
                slides.push($this);
            });
            switch(method){
                case "append":
                    self.swiper.appendSlide(slides);
                break;

                case "prepend":
                    self.swiper.prependSlide(slides);
                break;
            }
            setImmediate(function(){
                slides = null;
                $div.empty().remove();
                $div = null;
            });
        },

        dateWithZero: function(date){
            return (date < 10) ? "0" + date : date;
        },

        getDateForTL: function(){
            var weekdays = this.consts.WEEKDAYS;
            var date = new Date();
            return weekdays[date.getDay()-1] + " " + this.dateWithZero(date.getHours()) + ":" + this.dateWithZero(date.getMinutes());
        },

        fixedTimeline: function (){
            var self = this;
            var broadcasts = this.broadcasts;
            var currentDate = self.getDateForTL();
            var CS = this.consts;
            var $wrapper = $(CS.swWrapper);
            var $timeline = $(self.tmpl("#timelineTmpl", {
                today: currentDate
            }));
            var $line = $timeline.find(".timeline__line");
            var $title = $timeline.find(".timeline__title");
            var $columns = $wrapper.find(".broadcast");
            var left = 0, onAir = 0, slide;
            for(var i = 0; i < $columns.length; i++){
                var $this = $($columns[i]);
                left += parseInt($this.width());
                if($this.find(".badge").length > 0){
                    onAir = i;
                    break;
                }
            };
            var sessionSlide = sessionStorage.getItem('slide');
            if (sessionSlide) {
                slide = sessionSlide;
                $("html,body").animate({ 
                    scrollTop: sessionStorage.getItem('top') 
                }, 1000);
            } else slide = onAir;
            self.swiper.slideTo(slide, 1000);
            $line.css("left", (left - 146));
            $title.css("left", (left - 238));
            $wrapper.prepend($timeline);
            setImmediate(function(){
                $wrapper = null, $title = null, $line = null, $columns = null, self = null, left = null, $this = null, $timeline = null;
            });
        },
        
        addStatus: function(selector, type, beforeAir){
            var self = this;
            var auth = this.auth;
            var CS = this.consts;
            var div = selector;
            var tmpl;
            var statusHTML;
            if(auth){
                switch(type){
                    case CS.STATUS.empty:
                    case CS.STATUS.recordable:
                        div.addClass(CS.STATUS.recordable);
                        tmpl = self.tmpl("#" + CS.STATUS.recordable + "Tmpl");
                    break;

                    case CS.STATUS.recording:
                        div.addClass(CS.STATUS.recording);
                        tmpl = self.tmpl("#" + CS.STATUS.recording + "Tmpl");
                    break;
                    
                    case CS.STATUS.viewed:
                        div.addClass(CS.STATUS.viewed);
                        tmpl = self.tmpl("#" + CS.STATUS.viewed + "Tmpl");
                    break;
                    
                    case CS.STATUS.recorded:
                        div.addClass(CS.STATUS.recorded);
                        tmpl = self.tmpl("#" + CS.STATUS.recorded + "Tmpl");
                    break;
                    
                    case CS.STATUS.notify:
                        div.addClass(CS.STATUS.notify);
                        tmpl = self.tmpl("#" + CS.STATUS.notify + "Tmpl");
                    break;
                }
            }else{
                tmpl = self.tmpl("#nonAuthTmpl");
            }
            if(tmpl !== ""){
                div.find(".broadcast__wrap-status").html(tmpl);
            }
            setImmediate(function(){
                self = null, auth = null, CS = null, div = null, tmpl = null, statusHTML = null;
            });
            return div;
        },

        loadImages: function(){
            function clearClass(input){
                return input.replace(/^\./,"");
            };
            var self = this;
            var CS = this.consts;
            var img = $(CS.swSlidePrev + " " + CS.swLazy + "," + CS.swSlideActive + " " + CS.swLazy + "," + CS.swSlideNext + " " + CS.swLazy + ", "+ CS.swSlideNext + " + " + CS.swSlide + "--end " + CS.swLazy);
            if (img.length === 0) return;
            var _img, src, siblings;
    
            img.each(function (iteration) {
                _img = $(this);
                src = _img.data("src");
                siblings = _img.siblings("img");
                _img.addClass(clearClass(CS.swLazyLoading));
                function imgLoaded(_img, src, siblings){
                    var clientHeight = document.documentElement.clientHeight;
                    var s_top = self.getPositionUser("top") + (clientHeight - 300);
                    var yes = _img.offset().top;
                    if(s_top > yes && (s_top - clientHeight) < yes){
                        if (src) {
                            _img = $(_img[0]);
                            var load = _img[0].dataset.load;
                            _img.attr("src", src);
                            _img.removeAttr('data-src');
                            if(!siblings[0]){
                                var $img = $(new window.Image());
                                $img.attr("src",load).hide();
                            }else{
                                $img = _img.siblings("img");
                            }
                            $img.on("load",function(){
                                load = _img[0].dataset.load;
                                _img.attr("src", load).addClass("fullLoaded").removeClass(clearClass(CS.swLazy));
                                siblings.remove();
                                setImmediate(function(){
                                    load = null;
                                    src = null;
                                    if(0 in siblings){
                                        siblings.empty();
                                        siblings = null;  
                                    }
                                    if(0 in $img){
                                        $img.remove();
                                        $img = null;
                                    }
                                });
                            });  
                        }
                        _img.addClass(clearClass(CS.swLazyLoaded)).removeClass(clearClass(CS.swLazyLoading));
                    }
                }
                imgLoaded(_img, src, siblings);
            });
            setImmediate(function(){
                img = null;
                _img = null;
                src = null;
                siblings = null;
            });
        },

        getPositionUser: function(type){
            var self = this;
            switch(type){
                case "top":
                    var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    return scrollTop;
                break;

                case "slide":
                    if(self.swiper !== null)
                        return self.swiper.activeIndex;
                    else return 0;
                break;
            }
        },

        openBottomPreloader: function(){
            var self = this;
            var CS = self.consts;
            var placeholder = $('<div class="category-logo bottom-preloader-ph"></div>');
            var preloader = $(self.tmpl("#" + CS.BOTTOM_PRELOADER_ID_TMPL));
            var logos = $(CS.CATEGORIES_LOGOS_CLASS);

            logos.append(placeholder);
            $(CS.BROADCAST_RESULTS_CLASS).prepend(preloader);
            setImmediate(function(){
                preloader.fadeIn();
            });

            this.el.on("closeBottomPreloader", function(){
                preloader.fadeOut(function(){
                    $(this).remove();
                    placeholder.remove();
                });
            });
            setImmediate(function(){
                self = null, placeholder = null, CS = null, logos = null, preloader = null;
            });
        },

        prevButton: function(){
            this.swiper.setWrapperTransition(500);
            var self = this;
            var CS = self.consts;
            var offset = this.offset;
            var translate = Math.round(self.swiper.translate);
            offset = translate + offset;
            if(offset > 0) offset = 0;
            this.swiper.setWrapperTranslate(offset);
        },

        nextButton: function(){
            this.swiper.setWrapperTransition(500);
            var self = this;
            var CS = self.consts;
            var offset = this.offset;
            var translate = Math.round(self.swiper.translate);
            offset = translate - offset;
            if(offset < swiper.maxTranslate()) offset = swiper.maxTranslate();
            this.swiper.setWrapperTranslate(offset);
        },

        arrowsChannels: function(type, method){
            var arrow = $("." + type + "-channels");
            if(method == true)
                arrow.removeClass("hidden-btn");
            else arrow.addClass("hidden-btn");
        },

        openPreloader: function(){
            var CS = this.consts;
            var $loader = $("." + CS.PRELOADER_CLASS);
            $loader.removeClass(CS.PRELOADER_LOADED_CLASS).addClass(CS.PRELOADER_LOADING_CLASS);
            setImmediate(function(){
                CS = null, $loader = null;
            });
        },

        closePreloader: function(){
            var CS = this.consts;
            var $loader = $("." + CS.PRELOADER_CLASS);
            $loader.removeClass(CS.PRELOADER_LOADING_CLASS).addClass(CS.PRELOADER_LOADED_CLASS);
            setImmediate(function(){
                CS = null, $loader = null;
            });
        }
    };

    $.fn.Broadcasts = function(method, options) {
      var options = $.extend({
          config: {},
          origin: location.origin,
          json: {},
          consts: {},
          time: "",
          offset: 288,
          preInit: function(){},
          postInit: function(){}
      }, options);

      var $this = $(this),
          bsInit = new Broadcasts($.extend({
            el: $this
          },options));
      if(method === undefined || method == "" || !method || typeof method === "object") {
        return bsInit;
      }else if(method in bsInit) {
        bsInit[method]();
        return bsInit;
      }else {
        console.error("Exception: Undefined method!");
        return;
      }

    };

})(jQuery);

    var broadcastObj = $(".categories-items").Broadcasts({
        auth: true,
        origin: "https://megatv.su"
    });

    var moduleEl = $(".broadcast-results");
    var swiper = null;
    var stopAjax = false;
    var offset = 0;

    function runSwiper(){
        swiper = broadcastObj.swiper;
        swiper.on('onSliderMove', function (getSwiper) {
            if(getSwiper.isEnd) {
                broadcastObj.openPreloader();
                date = getDay(date, "next");
                setTimeout(function(){
                    renderDay([date], channels.split(","));
                },1000);
            }
        });
    }

    function renderDay(days, channels, ajaxType, type){
        if(stopAjax) return;
        $.ajax({
            type: 'post',
            url: "http://megatv.local/js/json1.json",
            data: {
                AJAX: 'Y',
                AJAX_TYPE: "renderDay",
                date: days,
                channels: channels,
                offset: offset
            },
            beforeSend: function(){
                stopAjax = true;
            },
            dataType: "json",
            success: function (response) {
                if(!_.isEmpty(response)){
                    if("next_disable" in response) broadcastObj.arrowsChannels("next");
                    else broadcastObj.arrowsChannels("next", true);

                    if("prev_disable" in response) broadcastObj.arrowsChannels("prev");
                    else broadcastObj.arrowsChannels("prev", true);

                    broadcastObj.addDay(response);
                    moduleEl[0].dataset.date = days[days.length - 1];
                    setImmediate(function(){
                        if(swiper === null)
                        var swiperIntval = setInterval(function(){
                            if(broadcastObj.swiper !== null){
                                runSwiper();
                                clearInterval(swiperIntval);
                            }
                        });
                        stopAjax = false;
                        setTimeout(function(){
                            broadcastObj.closePreloader();
                        },1000);
                    });
                }else{
                    if("next" == type) {
                        broadcastObj.arrowsChannels("next");
                        broadcastObj.arrowsChannels("prev", true);
                    }else if("prev" == type) {
                        broadcastObj.arrowsChannels("prev");
                        broadcastObj.arrowsChannels("next", true);
                    }
                }
            },
            error: function () {
                console.warn('Ошибка загрузки дня');
            }
        });
    };

    function renderChannels(type){
        offset = parseInt(offset);
        swiper = null;
        if(type === "prev"){
            offset -= 10;
            sessionStorage.setItem("offsetChannels", offset == 0 ? 0 : offset);
        }else if(type === "next"){
            offset += 10;
            sessionStorage.setItem("offsetChannels", offset);
        }
        broadcastObj.openPreloader();
        broadcastObj.destroySwiper();
        var date = getDay();
        renderDay([date], [], type + "Channels");
    }
    $(".next-channels").on("click", function(){
        swiper.setWrapperTranslate(0);
        var type = "next";
        renderChannels(type);
    });
    $(".prev-channels").on("click", function(){
        swiper.setWrapperTranslate(0);
        var type = "prev";
        renderChannels(type);
    });

    $('[data-type="prev-button"]').on("click", function(){
        broadcastObj.prevButton();
        return false;
    });
    $('[data-type="next-button"]').on("click", function(){
        broadcastObj.nextButton();
        if(swiper.translate == swiper.maxTranslate()){
            var channels = sessionStorage.getItem("channels") ? sessionStorage.getItem("channels") : "";
            broadcastObj.openPreloader();
            renderDay(getDay(date, "next"), channels.split(","));
        }
        return false;
    });

    $("body").on("keydown", function(eventObject){
      if(eventObject.which === 37){
        broadcastObj.prevButton();
      }
      if(eventObject.which === 39){
        broadcastObj.nextButton();
        if(swiper.translate == swiper.maxTranslate()){
            var channels = sessionStorage.getItem("channels") ? sessionStorage.getItem("channels") : "";
            broadcastObj.openPreloader();
            renderDay(getDay(date, "next"), channels.split(","));
        }
      }
    });

    function getDay(currDate, type){
        var getDate = new Date();
        function cpDate(date){
            return (date < 10) ? "0" + date : date;
        }

        if(currDate !== undefined){
            if(currDate.match(/[0-9]{2}\./)){
                currDate = (currDate).replace(/([0-9]{2})\.([0-9]{2})\.([0-9]{4})\s(([0-9]{2})\:([0-9]{2})\:([0-9]{2}))/, "$3-$2-$1 $4");
            }else currDate;
            getDate = new Date(currDate);
        }
       
        if(type === "next") getDate.setDate(getDate.getDate() + 1);
        else if(type === "prev") getDate.setDate(getDate.getDate() - 1); 

        return cpDate(getDate.getDate()) + "." + cpDate(getDate.getMonth() + 1) + "." + getDate.getFullYear() + " " + cpDate(getDate.getHours()) + ":" + cpDate(getDate.getMinutes()) + ":" + cpDate(getDate.getSeconds());
    }

    // var broadcastObj = $(".categories-items").Broadcasts("initialize", {
    //     json: json,
    //     auth: authentication,
    //     origin: "https://megatv.su"
    // });

    // var countDays = 5;
    // var arrayDates = [];
    // arrayDates.push(date);
    // if(countDays > 1){
    //     for(var i = 0; i < (countDays - 1); i++){
    //         date = getDay(date, "next");
    //         arrayDates.push(date);
    //     }
    // }
    var date = getDay();
    var dates = sessionStorage.getItem("dates") ? sessionStorage.getItem("dates") : date;
    var channels = sessionStorage.getItem("channels") ? sessionStorage.getItem("channels") : "";
    offset = sessionStorage.getItem("offsetChannels") ? sessionStorage.getItem("offsetChannels") : 0;
    sessionStorage.setItem("offsetChannels", offset);

    renderDay(dates.split(","), channels.split(","), "", offset);
    // var iconLoaderService = Box.Application.getService('icon-loader');
    // setInterval(function(){
    //     if(document.querySelector("[data-icon]"))
    //         iconLoaderService.renderIcons();
    //     var btnModals = $('[data-module="modal"]');
    //     if(btnModals[0])
    //         btnModals.each(function(){
    //             var $this = $(this)[0];
    //             // Box.Application.start($this);
    //         });
    // },1000);
    
    // setTimeout(function(){
    //     broadcasts.openBottomPreloader();
    //     setTimeout(function(){
    //         broadcasts.addChannels(jsonParamsChannels);
    //         broadcasts.el.trigger("closeBottomPreloader");
    //     },2000);
    //     setTimeout(function(){
    //         broadcasts.addDay(jsonParamsDay);
    //         broadcasts.closePreloader();
    //         // broadcasts.openPreloader();
    //     },3000);
    // },1000);
$(document).ready(function(){
    
});
</script>