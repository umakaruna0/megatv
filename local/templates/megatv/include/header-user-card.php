    <?
    if($USER->IsAuthorized())
    {           
        $budget = floatval(\CUserEx::getBudget());
        $arUser = \CUserEx::updateAvatar($USER->GetID());
        
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
        <div class="box-userbar__userbar userbar">
            <div class="userbar__disk-space disk-space" data-type="fill-disk-space" onclick="window.location.href='/personal/services/';" style="cursor: pointer;">
        		<div class="disk-space__progress-holder progress-holder" data-progress="<?=$filledPercent?>"></div>
        		<span class="disk-space__label"><?=GetMessage('BUSY')?> <strong class="disk-space__strong"><?=round($arUser["UF_CAPACITY_BUSY"], 2);?> <?=GetMessage('GB')?></strong></span>
        	</div>                

            <nav class="box-userbar__usernav usernav" data-module="user-navigation">
                <div class="usernav__user-card">
    				<a href="/personal/" class="usernav__user-avatar<?if(!$arUser["PERSONAL_PHOTO"]):?> usernav__user-avatar--empty<?endif;?>" data-type="avatar-holder">
                        <?if($arUser["PERSONAL_PHOTO"]):?>
                            <img class="usernav__user-image" src="<?=\CFile::GetPath($arUser["PERSONAL_PHOTO"])?>" alt="<?=$USER->GetFullName()?>" width="50" height="50">
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
    				<div class="usernav__info-panel">
    					<a class="usernav__username" href="/personal/"><?=$USER->GetFullName()?></a>
    					<a href="<?=$urlExit?>" class="usernav__signout-link"><?=GetMessage('LOGOUT')?></a>
    				</div>
    			</div>
            </nav>
        </div>
        <?
    }else{
        ?>
        <nav class="box-userbar box-right__box-userbar" data-module="user-navigation">
            <a href="#" data-module="modal" data-modal="authURL" data-type="openModal" class="g-btn g-btn--primary box-userbar__btn-auth js-btnModalInit"><span><?=GetMessage('LOGIN')?></span></a>
            <a href="#" data-module="modal" data-modal="registerURL" data-type="openModal" class="g-btn box-userbar__btn-register js-btnModalInit"><span><?=GetMessage('REGISTER')?></span></a>
        </nav>
        <?
    }
    ?>
    
    <?if(!$USER->IsAuthorized()):?>
        <?
        // $APPLICATION->IncludeComponent("bitrix:system.auth.form", "auth_ajax",Array(
        //      "REGISTER_URL" => "register.php",
        //      "FORGOT_PASSWORD_URL" => "",
        //      "PROFILE_URL" => "/",
        //      "SHOW_ERRORS" => "Y" 
        //      )
        // );
        ?>
        <?
        //$APPLICATION->IncludeComponent("bitrix:system.auth.registration","",Array());
        ?>
        <?
        // $APPLICATION->IncludeComponent(
        //     "bitrix:system.auth.forgotpasswd",
        //     ".default",
        //     Array()
        // );
        ?>
        <div class="authorize-overlay is-success-signup-overlay" data-module="success-signup-overlay">
    		<div class="overlay-content">
    			<h4 class="overlay-title"><?=GetMessage('CONGRATULATIONS')?></h4>
    			<p><?=GetMessage('CONGRATULATIONS_REGISTER_TEXT')?></p>
    			<a href="/" class="btn btn-primary btn-block"><?=GetMessage('START_USE_SERVICE')?></a>
    		</div>
    	</div>
    	<div class="authorize-overlay is-success-reset-overlay" data-module="success-reset-overlay">
    		<div class="overlay-content">
    			<h4 class="overlay-title"><?=GetMessage('PASSWORD_IS_CHANGED')?></h4>
    			<p><?=GetMessage('PASSWORD_IS_CHANGED_SUCCESS')?></p>
    			<a href="#" class="btn btn-primary btn-block" data-type="signin-handler-link"><?=GetMessage('AUTHORIZE')?></a>
    		</div>
    	</div>
    <?endif;?>