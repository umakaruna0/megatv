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

<section class="main-container" data-module="broadcast-results" data-date="<?=\CTimeEx::dateOffset(date("Y-m-d H:i:s"))?>">
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
    <?=$arResult["DATA"];?>
</script>


<script id="broadcastTmpl" type="text/x-template">
    <div class="bs-container__broadcast broadcast<% if(noAir && auth){ %> broadcast--no-air<% } %>" data-type="broadcast" data-broadcast-id="<%- id %>">
        <% if(onAir){ %>
        <span data-channel-id="<%- channel_id %>" class="badge broadcast__on-air">В эфире</span>
        <% } %>
        <img class="broadcast__image broadcast-image lazy-img swiper-lazy" data-src="<%- blurImage %>" data-load="<%- image %>">
        
        <div class="broadcast__wrap-status">
        <% if(!noAir){ %>
            <span class="broadcast__status item-status-icon">        
                <div data-icon="icon-recordit"></div>
                <span class="bs-status__title">Записать</span>
            </span>
        <% } %>
        </div>
        <div class="broadcast__info item-header">
            <div class="broadcast__time broadcast-time"><%- time %></div>
            <div class="broadcast__title broadcast-title">
                <a class="broadcast__link broadcast-link" href="<%- link %>"><%- title %></a>
            </div>
        </div>
    </div>
</script>

<script id="broadcastEmptyTmpl" type="text/x-template">
    <div class="bs-container__broadcast broadcast">
        <img class="broadcast__image broadcast-image" style="opacity:0.35;" height="350" src="<?=SITE_TEMPLATE_PATH?>/tmpl/img/emptyBroadcast.jpg">
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

<?/*
<section class="broadcast-results" data-module="broadcast-results">
    <script type="text/x-config">
    {
        "recordingURL": "<?=SITE_TEMPLATE_PATH?>/ajax/to_record.php",
        "fetchResultsURL" : "<?=$arParams["LIST_URL"]?>",
        "page": "<?=$curPage?>",
        "ajaxType": "CHANNELS",
        "dates" : [
            <?$key = 1;foreach($arResult["CONFIG_DATES"] as $date){?>
            {
                "dayReq": "<?=$date?>",
                "dayMark": "<?=\CTimeEx::dateToStrWithDay($date);?>"
            }<?if($key<count($arResult["CONFIG_DATES"])):?>,<?endif; $key++;}?>
        ]
    }
    </script>
    
	<div class="categories-logos">
        <?foreach($arResult["CHANNELS"] as $arItem):?>
        	<?
            if(!in_array($arItem['UF_CHANNEL_BASE_ID'], $arResult["CHANNELS_SHOW"]) && $USER->IsAuthorized()) continue;
            ?>
    		<a class="category-logo" href="<?=$arItem["DETAIL_PAGE_URL"]?>" data-channel-id="<?=$arItem['ID']?>">
    			<span data-icon="<?=$arItem["UF_ICON"]?>"></span>
                <?if(intval($arItem['UF_PRICE'])>0):?><span class="channel-to-pay"><i class="fa fa-rub"></i></span><?endif;?>
    		</a>
        <?endforeach?>
        <?foreach($arResult["SOCIAL_CHANNELS"] as $arItem):?>
            <a class="category-logo" href="#">
    			<span data-icon="<?=$arItem["UF_ICON"]?>"><?=$arItem["NAME"]?></span>
    		</a>
        <?endforeach?>
	</div>
    
    <a href="#" class="prev-button" data-type="prev-button">
		<span class="sticky-wrapp">
			<span class="prev-date"><?=\CTimeEx::dateToStrWithDay($arResult["FIRST_DATE"]);?></span>
			<span data-icon="icon-kinetic-arrow"></span>
		</span>
	</a>
    
	<div class="categories-items kinetic-active">
        <div class="canvas-wrap">
            <div class="left-days-placeholder"></div>
            <?
            $date_count = 1;
            foreach($arResult["DATES"] as $date => $arChannels)
            {
                ?>
                <div class="day">
                    <?
                    $first = false;
                    
                    foreach($arResult["CHANNELS"] as $arChannel)
                    {
                        $channel = $arChannel["ID"];
                        $arProgs = $arChannels[$channel];
                        
                        if(!in_array($arChannel["UF_CHANNEL_BASE_ID"], $arResult["CHANNELS_SHOW"]) && $USER->IsAuthorized())
                            continue;

                        ?>
                        <div class="category-row">
                            <?
                            if(!$first)
                            {
                                $arParams["NEED_POINTER"] = true;
                                $first = true;
                            }
                            $notShow = array();
                            foreach($arProgs as $key=>$arProg)
                            {
                                if(in_array($key, $notShow))
                                    continue;
                                    
                                if($arProg["CLASS"]=="one" || $arProg["CLASS"]=="double")
                                {
                                    echo \Hawkart\Megatv\CScheduleTemplate::getProgInfoIndex($arProg, $arParams);
                                }
        
                                if($arProg["CLASS"]=="half")
                                {
                                    $arProgNext = $arProgs[$key+1];
                                    ?>
                                    <div class="pair-container">
                                        <?=\Hawkart\Megatv\CScheduleTemplate::getProgInfoIndex($arProg, $arParams)?>
                                        <?=\Hawkart\Megatv\CScheduleTemplate::getProgInfoIndex($arProgNext, $arParams)?>
                    				</div>
                                    <?
                                    $notShow[]=$key+1;
                                }
                            }
                            unset($arParams["NEED_POINTER"]);
                            ?>
                        </div>
                        <?
                        
                        $next_date = date('d.m.Y', strtotime("+1 day", strtotime($date)));
                    }
                    
                    foreach($arResult["SOCIAL_CHANNELS"] as $arChannel)
                    {
                        $socialChannel = $arChannel["ID"];
                        $arProgs = $arChannels[$socialChannel];
                        ?>
                        <div class="category-row">
                            <?
                            if(!$first)
                            {
                                $arParams["NEED_POINTER"] = true;
                                $first = true;
                            }
                            $notShow = array();
                            foreach($arProgs as $key=>$arProg)
                            {
                                echo \Hawkart\Megatv\CScheduleTemplate::getSocialProgInfoIndex($arProg, $socialChannel);
                            }
                            unset($arParams["NEED_POINTER"]);
                            ?>
                        </div>
                        <?
                    }

                    //if($date_count<count($arResult["DATES"]))
                    //{
                        ?>
                        <div class="day-mark">
                            <span>
                                <span class="current-day"><?=\CTimeEx::dateToStrWithDay($date);?></span>
                                <span class="next-day"><?=\CTimeEx::dateToStrWithDay($next_date);?></span>
                            </span>
                        </div>
                        <?
                    //}
                    ?>
                </div>
                <?
                $date_count++;
            }
            ?>
            <div class="right-days-placeholder"></div>
            
        </div>
    </div><!-- /.categories-items -->
    
    <?
    $next_date = date('d.m.Y', strtotime("+1 day", strtotime($arResult["FIRST_DATE"])));
    ?>
    <a href="#" class="next-button" data-type="next-button">
		<span class="sticky-wrapp">
			<span class="next-date"><?=\CTimeEx::dateToStrWithDay($next_date);?></span>
			<span data-icon="icon-kinetic-arrow"></span>
		</span>
	</a>

</section>*/?>