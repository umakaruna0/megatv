<?if($APPLICATION->GetCurDir()!="/personal/records/"):?>
    <?/*<div class="calendar" data-module="calendar">
    	<script type="text/x-config">
    		{
    			"currentDate": "<?=CTimeEx::getCurDate()?>",
    			"minDate": 1,
    			"maxDate":<?=CTimeEx::getCalendarDays()?>
    		}
    	</script>
        <a href="#" data-type="calendar-trigger" class="calendar-trigger"><span><?=CTimeEx::dateToStr()?></span></a>
    	<div class="datepicker-holder"></div>
    </div>
    <div class="calendar-carousel" data-module="calendar-carousel">
    	<script type="text/x-config">
    		{
    			"currentDate": "<?=CTimeEx::getCurDate()?>",
    			"minDate": 1,
    			"maxDate":<?=CTimeEx::getCalendarDays()?>
    		}
    	</script>
    	<a href="#" class="prev-trigger disabled" data-type="prev-trigger"><span data-icon="icon-left-arrow-days"></span></a>
    	<div class="dates-holder" data-type="dates-carousel"></div>
    	<a href="#" class="next-trigger" data-type="next-trigger"><span data-icon="icon-right-arrow-days"></span></a>
    </div>*/?>
    
    <div class="calendar-carousel" data-module="calendar-carousel">
		<script type="text/x-config">
			{
				"currentDate": "<?=CTimeEx::getCurDate()?>",
                "minDate": 1,
                "maxDate":<?=CTimeEx::getCalendarDays()?>
			}
		</script>
		<a href="#" class="prev-trigger disabled" data-type="prev-trigger"><span data-icon="icon-left-arrow-days"></span></a>
		<div class="dates-holder" data-type="dates-carousel"></div>
		<a href="#" class="next-trigger" data-type="next-trigger"><span data-icon="icon-right-arrow-days"></span></a>
	</div>
    
<?else:?>
    <div class="fill-disk-space" data-type="fill-disk-space">
		<div class="progress-holder" data-progress="<?=$APPLICATION->ShowViewContent('user_filled_space_percent');?>"></div>
		<span class="label">Занято <strong><?=$APPLICATION->ShowViewContent('user_filled_space');?> ГБ</strong></span>
	</div>
    <?
    $CSubscribeEx = new CSubscribeEx("CHANNEL");
    $arChannels = $CSubscribeEx->getList(array("UF_ACTIVE"=>"Y", "UF_USER"=>$USER->GetID(), "!UF_CHANNEL"=>false), array("UF_CHANNEL"));
    ?>
    <a class="channels-menu-item" href="/personal/services/"><span>Каналов</span> <span class="badge"><?=count($arChannels)?></span></a>
<?endif;?>