<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
global $USER;
?>
<?//echo $arResult["DATA"];?>
<?

/*** В настройки компонента
** "AJAX" => $_REQUEST["AJAX"],
** "LIST_URL" => $APPLICATION->GetCurDir()
**/

// номер текущей страницы
$curPage = $arResult["NAV_RESULT"]->NavPageNomer;
// всего страниц - номер последней страницы
$totalPages = $arResult["NAV_RESULT"]->NavPageCount;
$curPage++;
?>
<section class="broadcast-results" data-module="broadcast-results" data-date="<?=\CTimeEx::dateOffset(date("Y-m-d H:i:s"))?>">
   <div class="broadcasts-loader">
        <div class="broadcasts-loader__title"><p style="font-size:30px">Подождите,</p> <p>идёт загрузка элементов...</p></div>
        <div class="broadcasts-loader__divimg">
            <img src="<?=SITE_TEMPLATE_PATH?>/img/loader.gif" alt="" class="broadcasts-loader__img">
        </div>
    </div>

    <?/*
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
            },
            "origin" : "https://megatv.su",
            "recordingURL": "<?=SITE_TEMPLATE_PATH?>/ajax/to_record.php",
            "fetchResultsURL" : "<?=$arParams["LIST_URL"]?>?AJAX_JSON=Y",
            "page": 2,
            "ajaxType": "CHANNELS"
        }
    </script>
    */?>
    
    <script type="text/x-config">
        { 
            "recordingURL": "<?=SITE_TEMPLATE_PATH;?>/ajax/to_record.php",
            "fetchResultsURL" : "<?=$arParams["LIST_URL"]?>?AJAX_JSON=Y", 
            "page": "2",
            "ajaxType": {
                "start" : "CHANNELS",
                "next" : "nextChannels",
                "prev" : "prevChannels"
            }, 
            "countChannels" : "10" 
        }
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

<script id="paramsJson" type="text/json">
    <?echo $arResult["DATA"];?>
</script>

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