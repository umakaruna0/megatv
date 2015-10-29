<nav class="header-nav" data-module="user-navigation">
	<ul class="user-actions">
        <?
        if($USER->IsAuthorized())
        {           
            $countRecorded = 0;
            $countInRec = 0;
            $arStatusRecording = array();   //записывается
            $arStatusRecorded = array();    //записана, можно просмотреть
            $arStatusViewed = array();    //просмотренна
            $arFilter = array(
                "UF_USER" => $USER->GetID(),
                //возможно нужно добавить фильтр по дате между -2д и +11д по дате окончания
            );
            $arRecords = CRecordEx::getList($arFilter, array("UF_URL", "UF_SCHEDULE", "UF_WATCHED", "ID"));
            foreach($arRecords as $arRecord)
            {
                $shedule_id = $arRecord["UF_SCHEDULE"];
                
                if($arRecord["UF_WATCHED"]==1)
                {
                    $countRecorded++;
                    $arStatusViewed[$shedule_id] = $arRecord;
                    
                }
                else if(empty($arRecord["UF_URL"]))
                {
                    $countInRec++;
                    $arStatusRecording[$shedule_id] = $arRecord;
                }
                else if(!empty($arRecord["UF_URL"]))
                {
                    $countRecorded++;
                    $arStatusRecorded[$shedule_id] = $arRecord;
                }
            }
            //CDev::pre($arRecords);
            $arRecordStatus = array(
                "RECORDING" => $arStatusRecording,
                "RECORDED"  => $arStatusRecorded,
                "VIEWED"    => $arStatusViewed
            );
            $APPLICATION->SetPageProperty("ar_record_status", json_encode($arRecordStatus));
            
            
            $selectedChannels = array();
            $CSubscribeEx = new CSubscribeEx("CHANNEL");
            $arChannels = $CSubscribeEx->getList(array("UF_ACTIVE"=>"Y", "UF_USER"=>$USER->GetID()), array("UF_CHANNEL"));
            foreach($arChannels as $arChannel)
            {
                $selectedChannels[] = $arChannel["UF_CHANNEL"];
            }
            $APPLICATION->SetPageProperty("ar_subs_channels", json_encode($selectedChannels));
            
            
            $budget = floatval(CUserEx::getBudget());
            $arUser = CUserEx::updateAvatar($USER->GetID());
            
            if(floatval($arUser["UF_CAPACITY_BUSY"])==0 || floatval($arUser["UF_CAPACITY"])==0)
            {
                $filledPercent = 0;
            }else{
                $filledPercent = round(floatval($arUser["UF_CAPACITY_BUSY"])/floatval($arUser["UF_CAPACITY"]), 4);
            }
            
            $APPLICATION->AddViewContent('user_budget', number_format($budget, 0, "", " "));
            $APPLICATION->AddViewContent('user_filled_space', round($arUser["UF_CAPACITY_BUSY"], 2));
            $APPLICATION->AddViewContent('user_filled_space_percent', $filledPercent);  
            ?>
            <div class="user-card">
				<a href="/personal/" class="user-avatar<?if(!$arUser["PERSONAL_PHOTO"]):?> is-empty<?endif;?>" data-type="avatar-holder">
                    <?if($arUser["PERSONAL_PHOTO"]):?>
                        <img src="<?=CFile::GetPath($arUser["PERSONAL_PHOTO"])?>" alt="<?=$USER->GetFullName()?>" width="50" height="50">
                    <?endif;?>
                </a>
                <?
                if(strpos($APPLICATION->GetCurDir(), "personal")===false)
                {
                    $urlExit = $APPLICATION->GetCurPageParam("logout=yes", array("logout"));
                }else{
                    $urlExit = "/?logout=yes";
                }
                ?>
				<div class="info-panel">
					<a class="username" href="/personal/"><?=$USER->GetFullName()?></a>
					<a href="<?=$urlExit?>" class="signout-link">Выйти</a>
				</div>
			</div>
            
            <ul class="top-menu">
				<li><a href="/personal/records/"><span data-icon="icon-film-collection"></span> Мои записи</a></li>
				<li><a href="/personal/records/?type=recording"><span data-icon="icon-recording-small"></span> В записи <span class="badge" data-type="recording-count"><?=$countInRec?></span></a></li>
				<li><a href="/personal/records/?type=recorded"><span data-icon="icon-recorded-small"></span> Записанных <span class="badge"><?=$countRecorded?></span></a></li>
				<li><a href="/personal/services/"><span data-icon="icon-balance" data-size="small"></span> На счету: <?=number_format($budget, 0, "", " ")?> Р</a></li>
			</ul>
            <?
        }else{
            ?>
            <li><a href="#" class="signin-link" data-type="auth-screens-trigger" data-target="#singin-form">Войти</a></li>
            <li><a href="#" class="signup-link" data-type="auth-screens-trigger" data-target="#singup-form">Зарегистрироваться</a></li>
            <?
        }
        ?>
	</ul>
</nav>