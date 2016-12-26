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
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/tmpl/css/recommendations.css');
?>

<div class="broadcasts-categories" data-module="broadcasts-categories">
    <script type="text/x-config">
        { "url": "/recommendations/" }
    </script>
    <div class="categories-broadcasts">
        <a href="#" class="category-broadcasts category-broadcasts--active" data-type="category-broadcasts" data-category="all">
    		Все
    	</a>
        <?
        foreach($arResult["CATEGORIES"] as $category => $translit)
        {
            ?>
            <a href="#<?=$translit?>" class="category-broadcasts" data-type="category-broadcasts" data-category="<?=$translit?>">
    			<?=$category?>
    		</a>
            <?
        }
        ?>
    </div>
    <div class="more" data-type="more">
        <span data-icon="icon-close"></span>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
    </div>
</div>


<section class="recommended-broadcasts" data-module="recomended-broadcasts" data-offset="0" data-date="<?=date("d.m.Y");?>">
     <script type="text/x-config">
        { 
        	"viewMoreUrl" : "/recommendations/?AJAX=Y",
        	"recordingURL": "/local/templates/megatv/ajax/to_record.php",
        	"countMax" : <?=intval($arParams["NEWS_COUNT"])?>,
            "lang":{
                "record_title": "Записать",
                "msg_out_of_space": "У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК",
                "btn_order_of_space": "Заказать дополнительную емкость"
            }
        }
    </script>
    
    <div class="broadcasts-loader broadcasts-loader--loaded"><div class="broadcasts-loader__title"><p style="font-size:30px">Подождите,</p> <p>идёт загрузка элементов...</p></div><div class="broadcasts-loader__divimg"><img src="/local/templates/megatv/img/loader.gif" alt="" class="broadcasts-loader__img"></div></div>
    
    <div class="broadcasts-list main-broadcasts__broadcasts broadcasts">
        <?
        $notShow = array();
        if(count($arResult["PROGS"])>0)
        {
            foreach($arResult["PROGS"] as $key=>$arProg)
            {
                $arProg["CAT_CODE"] = $arResult["CATEGORIES"][$arProg["UF_CATEGORY"]];
                echo \Hawkart\Megatv\CScheduleTemplate::getProgInfoRecommend($arProg);
            }
        }else{
            ?>
            <div class="empty-content">
	    		<h1 class="empty-content__title">Список рекомендаций пуст...</h1>
	    	</div>
            <?
        }
        ?>
    </div>
    <script type="text/x-template" id="broadcastFullTmpl">
        <div class="item broadcast <%- status %>" data-type="broadcast" data-broadcast-id="<%- id %>">
            <div class="inner">
                <div class="item-image-holder">
                    <img class="lazy-img swiper-lazy" src="<%- image %>" alt="<%- name %>">
                </div>

                <% if(onAir){ %>
                  <span class="badge" data-channel-id="115">в эфире</span>
                <% } %>

                <div class="broadcast__wrap-status">
                    <%=placeholder %> 
                </div>
                
                <div class="broadcast__alert bs-alert bs-alert--extend-drive"> 
                    <span data-icon="icon-storage"></span> 
                    <p class="g-mt-5">У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                    <p><a class="msg" href="/personal/services/">Заказать дополнительную емкость</a></p>
                </div>

                <div class="item-header"> 
                    <div class="meta"> 
                        <div class="time"><%- time %></div>
                        <div class="date"><%- date %></div>
                        <div class="category"><a href="<%- categoryLink %>" data-type="category"><%- categoryName %></a></div>
                    </div>
                    <div class="title"> 
                        <a href="<%- link %>"><%- name %></a>
                    </div>
                 </div>
            </div>
        </div>
    </script>
    <script id="nonAuthTmpl" type="text/x-template">
        <span class="broadcast__status" data-module="modal" data-modal="authURL" data-type="openModal">
            <span data-icon="icon-recordit"></span>
            <span class="bs-status__title">Записать</span>
        </span>
    </script>
    <script id="status-recordableTmpl" type="text/x-template">
        <span class="broadcast__status">
            <span data-icon="icon-recordit"></span>
            <span class="bs-status__title">Записать</span>
        </span>
    </script>
    <script id="status-viewedTmpl" type="text/x-template">
        <span class="broadcast__status">
            <span data-icon="icon-viewed"></span>
            <span class="bs-status__title">Просмотрено</span>
        </span>
    </script>
    <script id="status-recordedTmpl" type="text/x-template">
        <span class='broadcast__status'>
            <span data-icon='icon-recorded'></span>
            <span class='bs-status__title'>Смотреть</span>
        </span>
    </script>
    <script id="recording-notifyTmpl" type="text/x-template">
        <span class="broadcast__status">
            <div data-icon="icon-recording"></div>
            <span class="bs-status__title">Ваша любимая передача<br> поставлена на запись</span>
        </span>
    </script>
    <script id="status-recordingTmpl" type="text/x-template">
        <span class='broadcast__status'>
            <span data-icon='icon-recording'></span>
            <span class='bs-status__title'>В записи</span>
        </span>
    </script>
    <script>
        // var returnEl = $('<div class="item ' + item.status + '" data-type="broadcast" data-broadcast-id="' + item.id + '" data-category="' + item.category.link + '"> <div class="inner">' + item.button + '<div class="item-header"> <div class="meta"> <div class="time">' + item.time + '</div><div class="date">' + item.date + '</div><div class="category"><a href="#" data-type="category">' + item.category.name + '</a></div></div><div class="title"> <a href="' + item.link + '"> ' + item.name + ' </a> </div></div></div></div>');
    </script>
</section>