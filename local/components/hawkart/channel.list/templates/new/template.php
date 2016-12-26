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
	</div>
    
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

                        //\CDev::pre($arProgs);
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
                                }else if($arProg["CLASS"]=="half")
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
    
</section>

<style>
    .broadcast-results .canvas-wrap{
        position: relative !important;
    }
    .broadcast-results .categories-items{
        overflow-x: scroll !important;
    }
</style>