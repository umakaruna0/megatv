<div class="header-user">
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
        
        <div class="fill-disk-space" data-type="fill-disk-space" onclick="window.location.href='/personal/services/';" style="cursor: pointer;">
    		<div class="progress-holder" data-progress="<?=$filledPercent?>"></div>
    		<span class="label"><?=GetMessage('BUSY')?> <strong><?=round($arUser["UF_CAPACITY_BUSY"], 2);?> <?=GetMessage('GB')?></strong></span>
    	</div>                

        <nav class="header-nav" data-module="user-navigation">
            <div class="user-card">
				<a href="/personal/" class="user-avatar<?if(!$arUser["PERSONAL_PHOTO"]):?> is-empty<?endif;?>" data-type="avatar-holder">
                    <?if($arUser["PERSONAL_PHOTO"]):?>
                        <img src="<?=\CFile::GetPath($arUser["PERSONAL_PHOTO"])?>" alt="<?=$USER->GetFullName()?>" width="50" height="50">
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
					<a href="<?=$urlExit?>" class="signout-link"><?=GetMessage('LOGOUT')?></a>
				</div>
			</div>
        </nav>
        <?
    }else{
        ?>
        <nav class="header-nav" data-module="user-navigation">
            <ul class="user-actions">
                <li><a href="#" class="signin-link" data-type="signin-overlay-toggle"><?=GetMessage('LOGIN')?></a></li>
    			<li><a href="#" class="signup-link" data-type="signup-overlay-toggle"><?=GetMessage('REGISTER')?></a></li>
            </ul>
        </nav>
        <?
    }
    ?>
    
    <?if(!$USER->IsAuthorized()):?>
        <?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "auth_ajax",Array(
             "REGISTER_URL" => "register.php",
             "FORGOT_PASSWORD_URL" => "",
             "PROFILE_URL" => "/",
             "SHOW_ERRORS" => "Y" 
             )
        );?>
        <?$APPLICATION->IncludeComponent("bitrix:system.auth.registration","",Array());?>
        <?$APPLICATION->IncludeComponent(
            "bitrix:system.auth.forgotpasswd",
            ".default",
            Array()
        );?>
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
</div>